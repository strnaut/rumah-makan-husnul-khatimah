<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderDetailModel;
use App\Models\ProfileModel;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Models\GroupModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminController extends Controller
{
    protected $orderDetailModel;
    protected $orderModel;
    protected $profileModel;
    protected $userModel;
    protected $groupModel;
    protected $session;

    public function __construct()
    {
        helper(['url', 'form', 'auth']);
        $this->orderDetailModel = new OrderDetailModel();
        $this->orderModel = new OrderModel();
        $this->profileModel = new ProfileModel();
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
        $this->session = session();

        if (!logged_in() || !in_groups('admin')) {
            return redirect()->to('/login');
        }
    }

    public function index()
    {
        // --- Data untuk Kartu Dashboard ---
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        // Pemasukan Hari Ini
        $dailyRevenueResult = $this->orderModel
            ->select('SUM(CAST(order_details.price AS DECIMAL(10,2)) * order_details.qty) AS total_revenue')
            ->join('order_details', 'order_details.order_id = orders.id')
            ->where('DATE(orders.order_date)', $today)
            ->where('orders.verification_status', 'terverifikasi')
            ->get()
            ->getRow();
        $dailyRevenue = $dailyRevenueResult->total_revenue ?? 0;

        // Transaksi Hari Ini
        $dailyTransactions = $this->orderModel
            ->where('DATE(order_date)', $today)
            ->where('verification_status', 'terverifikasi')
            ->countAllResults();

        // Pemasukan Bulan Ini
        $monthlyRevenueResult = $this->orderModel
            ->select('SUM(CAST(order_details.price AS DECIMAL(10,2)) * order_details.qty) AS total_revenue')
            ->join('order_details', 'order_details.order_id = orders.id')
            ->where('DATE_FORMAT(orders.order_date, "%Y-%m")', $thisMonth)
            ->where('orders.verification_status', 'terverifikasi')
            ->get()
            ->getRow();
        $monthlyRevenue = $monthlyRevenueResult->total_revenue ?? 0;

        // Transaksi Bulan Ini
        $monthlyTransactions = $this->orderModel
            ->where('DATE_FORMAT(order_date, "%Y-%m")', $thisMonth)
            ->where('verification_status', 'terverifikasi')
            ->countAllResults();

        // --- Data untuk Grafik Pendapatan Bulanan ---
        $chartLabels = [];
        $chartData = [];

        // Ambil data untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthName = date('F Y', strtotime("-$i months"));

            $revenueThisMonth = $this->orderModel
                ->select('SUM(CAST(order_details.price AS DECIMAL(10,2)) * order_details.qty) AS total_revenue')
                ->join('order_details', 'order_details.order_id = orders.id')
                ->where('DATE_FORMAT(orders.order_date, "%Y-%m")', $month)
                ->where('orders.verification_status', 'terverifikasi')
                ->get()
                ->getRow();

            $chartLabels[] = $monthName;
            $chartData[] = $revenueThisMonth->total_revenue ?? 0;
        }

        $data = [
            'dailyRevenue' => $dailyRevenue,
            'dailyTransactions' => $dailyTransactions,
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyTransactions' => $monthlyTransactions,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ];

        return view('admin/dashboard', $data);
    }

    public function orderlist()
    {
        $statusFilter = $this->request->getGet('status');
        $data['orders'] = $this->orderModel->getAllOrdersWithDetails($statusFilter);
        return view('admin/orders_list', $data);
    }

    public function updateStatus($orderId)
    {
        $status = $this->request->getPost('status');
        $rejectionReason = $this->request->getPost('rejection_reason');

        $validStatus = ['menunggu konfirmasi', 'diproses', 'dalam perjalanan', 'selesai', 'ditolak'];

        if (!in_array($status, $validStatus)) {
            $this->session->setFlashdata('error', 'Status tidak valid');
            return redirect()->back();
        }

        $orderDetails = $this->orderDetailModel->where('order_id', $orderId)->findAll();

        $dataToUpdate = ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')];
        if ($status === 'ditolak' && !empty($rejectionReason)) {
            $dataToUpdate['rejection_reason'] = $rejectionReason;
        } else {
             $dataToUpdate['rejection_reason'] = null;
        }

        foreach ($orderDetails as $detail) {
            $this->orderDetailModel->update($detail['id'], $dataToUpdate);
        }

        $this->session->setFlashdata('success', 'Status pesanan berhasil diubah untuk Order ID: ' . $orderId);
        return redirect()->to('admin/orders_list');
    }

    public function verifyPayment($orderId)
    {
        $verificationStatus = $this->request->getPost('verification_status');
        $rejectionReason = $this->request->getPost('rejection_reason');

        $order = $this->orderModel->find($orderId);
        if (!$order) {
            $this->session->setFlashdata('error', 'Pesanan tidak ditemukan.');
            return redirect()->back();
        }

        $dataToUpdate = [
            'verification_status' => $verificationStatus,
            'rejection_reason' => null
        ];

        if ($verificationStatus === 'ditolak' && !empty($rejectionReason)) {
            $dataToUpdate['rejection_reason'] = $rejectionReason;
        }

        $this->orderModel->update($orderId, $dataToUpdate);

        if ($verificationStatus === 'ditolak') {
            $orderDetails = $this->orderDetailModel->where('order_id', $orderId)->findAll();
            foreach ($orderDetails as $detail) {
                $this->orderDetailModel->update($detail['id'], [
                    'status' => 'ditolak',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'rejection_reason' => $rejectionReason
                ]);
            }
        }
        else if ($verificationStatus === 'terverifikasi') {
            $orderDetails = $this->orderDetailModel->where('order_id', $orderId)->findAll();
            foreach ($orderDetails as $detail) {
                $this->orderDetailModel->update($detail['id'], [
                    'status' => 'diproses',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'rejection_reason' => null
                ]);
            }
        }

        $this->session->setFlashdata('success', 'Status verifikasi pembayaran berhasil diubah untuk Order ID: ' . $orderId);
        return redirect()->to('admin/orders_list');
    }

    public function users()
    {
        $data['users'] = $this->profileModel->getUsersWithGroups();
        return view('admin/users', $data);
    }

    public function editUser($id)
    {
        $data['user'] = $this->profileModel->getUserWithGroup($id);
        $data['groups'] = $this->groupModel->findAll();

        if (empty($data['user'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan: ' . $id);
        }
        return view('admin/user_detail', $data);
    }

    public function updateUser($id)
    {
        $user = $this->profileModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username,id,' . $id . ']',
            'email'    => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'phone_number' => 'permit_empty|numeric|max_length[15]',
            'group' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
        ];

        $this->userModel->update($id, $updateData);

        $newGroupId = $this->request->getPost('group');
        $this->groupModel->removeUserFromAllGroups($id);
        $this->groupModel->addUserToGroup($id, $newGroupId);

        return redirect()->to('admin/users')->with('success', 'Detail user berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        if (user_id() == $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = $this->profileModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $this->userModel->delete($id);

        return redirect()->to('admin/users')->with('success', 'User berhasil dihapus.');
    }

    public function exportReport()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        if (empty($startDate) || empty($endDate)) {
            $this->session->setFlashdata('error', 'Tanggal mulai dan tanggal akhir harus diisi untuk ekspor laporan.');
            return redirect()->to(base_url('admin'));
        }

        $orders = $this->orderModel->getVerifiedOrdersByDateRange($startDate, $endDate);

        if (empty($orders)) {
            $this->session->setFlashdata('error', 'Tidak ada data pesanan terverifikasi dalam rentang tanggal tersebut.');
            return redirect()->to(base_url('admin'));
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penjualan');

        // Header kolom
        $columns = ['ID Pesanan', 'Tanggal Pesanan', 'Nama Pelanggan', 'No. Telepon', 'Alamat', 'Nama Produk', 'Harga Satuan', 'Kuantitas', 'Total Item'];
        $sheet->fromArray($columns, NULL, 'A1');

        $row = 2;
        $grandTotal = 0;

        foreach ($orders as $order) {
            foreach ($order['items'] as $item) {
                $itemPrice = (float)str_replace('.', '', $item['price']);
                $itemTotal = $itemPrice * $item['qty'];
                $grandTotal += $itemTotal;

                $sheet->setCellValue('A' . $row, $order['order_id']);
                $sheet->setCellValue('B' . $row, date('d F Y H:i', strtotime($order['order_date'])));
                $sheet->setCellValue('C' . $row, $order['customer_name']);
                $sheet->setCellValue('D' . $row, $order['phone_number']);
                $sheet->setCellValue('E' . $row, $order['address']);
                $sheet->setCellValue('F' . $row, $item['product_name']);
                $sheet->setCellValue('G' . $row, $itemPrice);
                $sheet->setCellValue('H' . $row, $item['qty']);
                $sheet->setCellValue('I' . $row, $itemTotal);
                $row++;
            }
        }

        // Tambahkan Grand Total di bagian bawah
        $sheet->setCellValue('H' . $row, 'Grand Total');
        $sheet->setCellValue('I' . $row, $grandTotal);
        $sheet->getStyle('H' . $row . ':I' . $row)->getFont()->setBold(true);


        // Atur lebar kolom otomatis
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan_Penjualan_' . $startDate . '_to_' . $endDate . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}