<?php

namespace App\Models;

use CodeIgniter\Model;
use Myth\Auth\Models\UserModel as MythAuthUserModel; 

class ProfileModel extends MythAuthUserModel 
{   
  
  // protected $table      = 'users';
  // protected $primaryKey = 'id';
  // protected $useAutoIncrement = true;
  // protected $allowedFields = ['username', 'email', 'phone_number'];
  // protected $returnType    = 'App\Entities\User'; // Jika Anda ingin mengembalikan objek User

    // Metode tambahan untuk mengambil user dengan grupnya
    public function getUsersWithGroups()
    {
        return $this->select('users.*, auth_groups.name as group_name')
                    ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
                    ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
                    ->findAll();
    }

    public function getUserWithGroup(int $id)
    {
        return $this->select('users.*, auth_groups.name as group_name, auth_groups.id as group_id')
                    ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
                    ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
                    ->where('users.id', $id)
                    ->first();
    }
}