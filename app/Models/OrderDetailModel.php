<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderDetailModel extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['order_id', 'product_id', 'price', 'qty', 'status', 'updated_at', 'rejection_reason'];
    protected $returnType = 'array';

    // Update status dan updated_at
    public function updateStatus($id, $status)
    {
        return $this->update($id, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Ambil order detail beserta relasi order dan product untuk admin
    // Fungsi ini tidak akan digunakan lagi untuk list utama admin, tapi bisa tetap ada jika diperlukan di tempat lain.
    public function getOrderDetailsWithRelations()
    {
        return $this->select('order_details.*, orders.name as customer_name, orders.address, orders.order_date, orders.payment_proof, orders.verification_status, orders.rejection_reason, product.product_name')
                    ->join('orders', 'orders.id = order_details.order_id')
                    ->join('product', 'product.product_id = order_details.product_id')
                    ->orderBy('order_details.status', 'ASC')
                    ->orderBy('order_details.updated_at', 'DESC')
                    ->findAll();
    }
}