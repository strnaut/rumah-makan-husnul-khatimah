<?php

namespace app\Controllers;

//inisialisasi model yang akan di gunakan
use App\Models\ProfileModel;
use CodeIgniter\Controller;


class ProfileController extends Controller
{
    private $ProfileModel;
    public $session;
    protected $config;
    protected $auth;

    public function __construct()
    {
        //menggunakan model
        $this->ProfileModel = new ProfileModel();
        $this->session = service('session');
        $this->config = config('Auth');
        $this->auth   = service('authentication');
    }

    function profile()
    {
        $id = $this->auth->user()->id;
        $data['user'] = $this->ProfileModel->findid($id);
        return view('profile', $data);
    }
}
