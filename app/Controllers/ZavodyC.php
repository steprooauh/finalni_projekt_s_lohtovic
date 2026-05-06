<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ZavodyC extends BaseController
{
    public function index($num)
    {
        echo view('zavody');
    }
}
