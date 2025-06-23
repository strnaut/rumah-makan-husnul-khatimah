<?php

namespace App\Controllers;

//inisialisasi model yang akan di gunakan
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\OrderDetailModel;
use App\Models\ProductModel; // Tambahkan ProductModel
use CodeIgniter\Controller;


class CartController extends Controller
{
    //multifungsi variable
    private $cart;
    private $productModel; // Tambahkan properti productModel

    public function __construct()
    {
        //menggunakan model
        $this->cart = new CartModel();
        $this->productModel = new ProductModel(); // Inisialisasi ProductModel
        helper(['form']); // Tambahkan helper form untuk unggah file
    }

    public function index()
    {
        $data['carts'] = $this->cart->getCart(user_id());
        return view('carts', $data);
    }

    public function tambah_qty($id)
    {
        $cartItem = $this->cart->where('product_id', $id)->where('user_id', user_id())->first();
        $product = $this->productModel->find($id);

        if (!$cartItem || !$product) {
            return redirect()->to(base_url('cart'))->with('error', 'Item keranjang atau produk tidak ditemukan.');
        }

        $newQty = $cartItem['qty'] + 1;

        if ($product['stock'] < $newQty) {
            return redirect()->to(base_url('cart'))->with('error', 'Stok ' . esc($product['product_name']) . ' tidak cukup. Stok tersedia: ' . esc($product['stock']));
        }

        $this->cart->update($cartItem['id'], ['qty' => $newQty]);
        
        return redirect()->to(base_url('cart'));
    }

    public function kurang_qty($id)
    {
        $cart = $this->cart->where('product_id', $id)->where('user_id', user_id())->first();
        if ($cart && $cart['qty'] > 1) { // Periksa apakah item keranjang ada dan qty > 1
            $this->cart->update($cart['id'], ['qty' => $cart['qty'] - 1]);
        } else if ($cart && $cart['qty'] == 1) { // Jika qty adalah 1, hapus item
            $this->cart->delete($cart['id']);
        }
        return redirect()->to(base_url('cart'));
    }

    public function hapus($id)
    {
        $cart = $this->cart->where('product_id', $id)->where('user_id', user_id())->first();
        if ($cart) { // Periksa apakah item keranjang ada
            $this->cart->delete($cart['id']);
        }
        return redirect()->to(base_url('cart'));
    }

    public function checkout()
    {
        $data['carts'] = $this->cart->getCart(user_id());
        if (empty($data['carts'])) {
            return redirect()->to(base_url('cart'))->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Validasi stok sebelum menampilkan halaman checkout
        foreach ($data['carts'] as $cartItem) {
            $product = $this->productModel->find($cartItem['product_id']);
            if (!$product || $product['stock'] < $cartItem['qty']) {
                $productName = esc($product['product_name'] ?? 'Produk tidak dikenal');
                $availableStock = esc($product['stock'] ?? 0);
                return redirect()->to(base_url('cart'))
                                 ->with('error', "Stok '$productName' tidak mencukupi untuk jumlah yang Anda minta. Stok tersedia: $availableStock.");
            }
        }
        
        return view('checkout', $data);
    }

    public function checkout_process()
    {
        $order = new OrderModel();
        
        $carts = $this->cart->getCart(user_id());
        if (empty($carts)) {
            return redirect()->to(base_url('cart'))->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Validasi stok akhir sebelum proses checkout
        foreach ($carts as $cartItem) {
            $product = $this->productModel->find($cartItem['product_id']);
            if (!$product || $product['stock'] < $cartItem['qty']) {
                $productName = esc($product['product_name'] ?? 'Produk tidak dikenal');
                $availableStock = esc($product['stock'] ?? 0);
                return redirect()->to(base_url('cart'))
                                 ->with('error', "Stok '$productName' tidak mencukupi untuk jumlah yang Anda minta. Stok tersedia: $availableStock. Silakan sesuaikan keranjang Anda.");
            }
        }

        // Tangani unggahan bukti transfer
        $paymentProof = $this->request->getFile('payment_proof');
        $fileName = null;
        if ($paymentProof && $paymentProof->isValid() && ! $paymentProof->hasMoved()) {
            $fileName = $paymentProof->getRandomName();
            $paymentProof->move(FCPATH . 'uploads/payment_proof', $fileName);
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengunggah bukti transfer. Pastikan file valid.');
        }

        // Menyimpan data pesanan
        $order->insert([
            'user_id' => user_id(),
            'name' => $this->request->getPost('name'),
            'phone_number' => $this->request->getPost('phone_number'),
            'address' => $this->request->getPost('address'),
            'payment_proof' => $fileName,
            'verification_status' => 'menunggu verifikasi',
        ]);
        $last_id = $order->insertID();

        // Menyimpan detail pesanan dan mengurangi stok produk
        foreach ($carts as $cart) {
            $product = $this->productModel->find($cart['product_id']); // Ambil produk terbaru
            if ($product) {
                // Kurangi stok produk
                $newStock = $product['stock'] - $cart['qty'];
                $this->productModel->update($product['product_id'], ['stock' => $newStock]);
            }

            $cart['price'] = str_replace('.', '', $cart['price']);
            $order_detail = new OrderDetailModel();
            $order_detail->insert([
                'order_id' => $last_id,
                'product_id' => $cart['product_id'],
                'price' => $cart['price'],
                'qty' => $cart['qty'],
                'status' => 'menunggu konfirmasi',
            ]);
            $this->cart->delete($cart['id']);
        }

        return redirect()->to(site_url('orders'))->with('success', 'Checkout berhasil. Menunggu verifikasi pembayaran.');
    }
}