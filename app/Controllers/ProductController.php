<?php

namespace App\Controllers; // Mengubah namespace menjadi 'App\Controllers' untuk konsistensi

//inisialisasi model yang akan di gunakan
use App\Models\ProductModel;
use App\Models\CartModel;
use CodeIgniter\Controller;


class ProductController extends Controller
{
    //multifungsi variable
    private $ProductModel;
    private $cart;

    public function __construct()
    {
        //menggunakan model
        $this->ProductModel = new ProductModel();
        $this->cart = new CartModel();
        helper(['form']); // Tambahkan helper form untuk unggah file
    }

    public function index()
    {
        $data['product'] = $this->ProductModel->findAll();
        return view('product', $data);
    }

    public function tambah_ke_keranjang($id)
    {
        $product = $this->ProductModel->find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $currentCartItem = $this->cart->where('product_id', $id)->where('user_id', user_id())->first();
        $requestedQty = ($currentCartItem ? $currentCartItem['qty'] : 0) + 1;

        if ($product['stock'] < $requestedQty) {
            return redirect()->to(base_url('product'))->with('error', 'Stok ' . esc($product['product_name']) . ' tidak cukup. Stok tersedia: ' . esc($product['stock']));
        }

        if ($currentCartItem) {
            $this->cart->update($currentCartItem['id'], ['qty' => $requestedQty]);
        } else {
            $data = [
                'user_id' => user_id(),
                'product_id' => $id,
                'qty' => 1
            ];
            $this->cart->insert($data);
        }
        return redirect()->to(base_url('product'))->with('success', 'Menu berhasil ditambahkan ke keranjang');
    }

    public function tambah_menu() {
        return view('tambah_menu');
    }

    public function store() {
        $data = $this->request->getPost();

        // Validasi input stok
        $rules = [
            'product_name' => 'required',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock' => 'required|numeric|greater_than_equal_to[0]', // Tambahkan validasi stok
            'photo' => 'uploaded[photo]|max_size[photo,1024]|is_image[photo]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Tangani unggahan foto
        $photo = $this->request->getFile('photo');
        $fileName = null;

        if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
            $fileName = $photo->getRandomName(); // Dapatkan nama unik untuk file
            $photo->move(FCPATH . 'uploads/product_photos', $fileName);
        }

        // Tambahkan nama file foto ke data yang akan disimpan ke database
        $data['photo'] = 'uploads/product_photos/' . $fileName; // Simpan path relatif
        
        $dataToSave = [
            'product_name' => $data['product_name'],
            'price' => $data['price'],
            'stock' => $data['stock'], // Simpan stok
            'photo' => $data['photo']
        ];

        $this->ProductModel->save($dataToSave);
        return redirect()->to(base_url('product'))->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit_menu($id) {
        $data['product'] = $this->ProductModel->find($id);
        if (empty($data['product'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Menu tidak ditemukan.');
        }
        return view('edit_menu', $data);
    }

    public function update_menu($id) {
        $data = $this->request->getPost();
        
        $rules = [
            'product_name' => 'required',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock' => 'required|numeric|greater_than_equal_to[0]', // Tambahkan validasi stok
        ];

        if ($this->request->getFile('photo')->isValid()) {
            $rules['photo'] = 'uploaded[photo]|max_size[photo,1024]|is_image[photo]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $product = $this->ProductModel->find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan.');
        }

        $updateData = [
            'product_name' => $data['product_name'],
            'price' => $data['price'],
            'stock' => $data['stock'], // Update stok
        ];

        // Tangani unggahan foto baru jika ada
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
            // Hapus foto lama jika ada
            if (!empty($product['photo']) && file_exists(FCPATH . $product['photo'])) {
                unlink(FCPATH . $product['photo']);
            }
            $fileName = $photo->getRandomName();
            $photo->move(FCPATH . 'uploads/product_photos', $fileName);
            $updateData['photo'] = 'uploads/product_photos/' . $fileName;
        }

        $this->ProductModel->update($id, $updateData);
        return redirect()->to(base_url('product'))->with('success', 'Menu berhasil diedit');
    }

    public function delete_menu($id) {
        $product = $this->ProductModel->find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan.');
        }

        // Hapus file foto terkait jika ada
        if (!empty($product['photo']) && file_exists(FCPATH . $product['photo'])) {
            unlink(FCPATH . $product['photo']);
        }

        $this->ProductModel->delete($id);
        return redirect()->to(base_url('product'))->with('success', 'Menu berhasil dihapus');
    }

}