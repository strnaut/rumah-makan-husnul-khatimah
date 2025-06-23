<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{   
  protected $table = 'product';
  protected $primaryKey = 'product_id';
  protected $useAutoIncrement = true;
  protected $allowedFields = ['product_name', 'price', 'photo', 'stock'];
}