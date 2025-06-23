<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['user_id', 'product_id', 'qty'];
    protected $returnType = 'array';

    public function getCart($user_id)
    {
        return $this->db->table($this->table)
            ->select('carts.id, carts.qty, product.product_id, product.product_name, product.price, product.photo, product.stock') // Tambahkan 'product.stock'
            ->join('product', 'product.product_id = carts.product_id')
            ->where('carts.user_id', $user_id)
            ->get()
            ->getResultArray();
    }
}