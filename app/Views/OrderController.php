<?php

namespace app\Controllers;

//inisialisasi model yang akan di gunakan
use App\Models\OrderModel;
use CodeIgniter\Controller;


class OrderController extends Controller
{

    public function index()
    {
        $orders = new OrderModel();
        $data['orders'] = $orders->getOrders(user_id());
        return view('orders', $data);
    }
}
