<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['user_id', 'name', 'address', 'phone_number', 'payment_proof', 'verification_status', 'rejection_reason'];
    protected $returnType = 'array';

    public function getOrders($user_id)
    {
        $builder = $this->db->table($this->table);
        $builder->select('orders.id as order_id, orders.name, orders.address, orders.phone_number, orders.order_date, orders.payment_proof, orders.verification_status, orders.rejection_reason, order_details.qty, order_details.status, order_details.id as order_detail_id, product.product_name, product.price, product.photo');
        $builder->join('order_details', 'order_details.order_id = orders.id');
        $builder->join('product', 'product.product_id = order_details.product_id');
        $builder->where('orders.user_id', $user_id);
        $builder->orderBy('orders.order_date', 'DESC');
        $builder->orderBy('orders.id', 'DESC');
        $results = $builder->get()->getResultArray();

        $groupedOrders = [];
        foreach ($results as $row) {
            $orderId = $row['order_id'];
            if (!isset($groupedOrders[$orderId])) {
                $groupedOrders[$orderId] = [
                    'order_id' => $row['order_id'],
                    'name' => $row['name'],
                    'address' => $row['address'],
                    'phone_number' => $row['phone_number'],
                    'order_date' => $row['order_date'],
                    'payment_proof' => $row['payment_proof'],
                    'verification_status' => $row['verification_status'],
                    'rejection_reason' => $row['rejection_reason'],
                    'items' => [],
                    'total_price' => 0
                ];
            }
            $itemPrice = (float)str_replace('.', '', $row['price']);
            $itemTotal = $itemPrice * $row['qty'];

            $groupedOrders[$orderId]['items'][] = [
                'order_detail_id' => $row['order_detail_id'],
                'product_name' => $row['product_name'],
                'price' => $itemPrice,
                'qty' => $row['qty'],
                'status' => $row['status']
            ];
            $groupedOrders[$orderId]['total_price'] += $itemTotal;
        }

        krsort($groupedOrders);

        return array_values($groupedOrders);
    }

    public function getAllOrdersWithDetails($statusFilter = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('orders.id as order_id, orders.name as customer_name, orders.address, orders.phone_number, orders.order_date, orders.payment_proof, orders.verification_status, orders.rejection_reason, order_details.qty, order_details.status, order_details.id as order_detail_id, product.product_name, product.price');
        $builder->join('order_details', 'order_details.order_id = orders.id');
        $builder->join('product', 'product.product_id = order_details.product_id');
        
        if ($statusFilter) {
            $builder->where('orders.verification_status', $statusFilter);
        }

        $builder->orderBy('orders.order_date', 'DESC');
        $builder->orderBy('orders.id', 'DESC');
        $results = $builder->get()->getResultArray();

        $groupedOrders = [];
        foreach ($results as $row) {
            $orderId = $row['order_id'];
            if (!isset($groupedOrders[$orderId])) {
                $groupedOrders[$orderId] = [
                    'order_id' => $row['order_id'],
                    'customer_name' => $row['customer_name'],
                    'address' => $row['address'],
                    'phone_number' => $row['phone_number'],
                    'order_date' => $row['order_date'],
                    'payment_proof' => $row['payment_proof'],
                    'verification_status' => $row['verification_status'],
                    'rejection_reason' => $row['rejection_reason'],
                    'items' => [],
                    'total_price' => 0
                ];
            }
            $itemPrice = (float)str_replace('.', '', $row['price']);
            $itemTotal = $itemPrice * $row['qty'];

            $groupedOrders[$orderId]['items'][] = [
                'order_detail_id' => $row['order_detail_id'],
                'product_name' => $row['product_name'],
                'price' => $itemPrice,
                'qty' => $row['qty'],
                'status' => $row['status']
            ];
            $groupedOrders[$orderId]['total_price'] += $itemTotal;
        }

        krsort($groupedOrders);
        return array_values($groupedOrders);
    }

    // Fungsi baru untuk mendapatkan pesanan yang terverifikasi dalam rentang tanggal
    public function getVerifiedOrdersByDateRange($startDate, $endDate)
    {
        $builder = $this->db->table($this->table);
        $builder->select('orders.id as order_id, orders.name as customer_name, orders.address, orders.phone_number, orders.order_date, orders.payment_proof, orders.verification_status, orders.rejection_reason, order_details.qty, order_details.status, order_details.id as order_detail_id, product.product_name, product.price');
        $builder->join('order_details', 'order_details.order_id = orders.id');
        $builder->join('product', 'product.product_id = order_details.product_id');
        $builder->where('orders.verification_status', 'terverifikasi');
        $builder->where('DATE(orders.order_date) >=', $startDate);
        $builder->where('DATE(orders.order_date) <=', $endDate);
        $builder->orderBy('orders.order_date', 'ASC');
        $builder->orderBy('orders.id', 'ASC');
        $results = $builder->get()->getResultArray();

        $groupedOrders = [];
        foreach ($results as $row) {
            $orderId = $row['order_id'];
            if (!isset($groupedOrders[$orderId])) {
                $groupedOrders[$orderId] = [
                    'order_id' => $row['order_id'],
                    'customer_name' => $row['customer_name'],
                    'address' => $row['address'],
                    'phone_number' => $row['phone_number'],
                    'order_date' => $row['order_date'],
                    'payment_proof' => $row['payment_proof'],
                    'verification_status' => $row['verification_status'],
                    'rejection_reason' => $row['rejection_reason'],
                    'items' => [],
                    'total_price' => 0
                ];
            }
            $itemPrice = (float)str_replace('.', '', $row['price']);
            $itemTotal = $itemPrice * $row['qty'];

            $groupedOrders[$orderId]['items'][] = [
                'order_detail_id' => $row['order_detail_id'],
                'product_name' => $row['product_name'],
                'price' => $itemPrice,
                'qty' => $row['qty'],
                'status' => $row['status']
            ];
            $groupedOrders[$orderId]['total_price'] += $itemTotal;
        }
        return array_values($groupedOrders);
    }
}