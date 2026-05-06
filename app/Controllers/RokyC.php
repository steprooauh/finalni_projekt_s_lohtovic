<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class RokyC extends BaseController
{
    var $Year;
    var $Location;
    var $data;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->Year = new \App\Models\RaceYear();
        $this->Location = new \App\Models\Location();

        $this->data = [

        ];
    }
    public function index()
    {
        $this->data +=[
        'Year' => $this->Year->findAll(),
        ];
        echo view('roky', $this->data);
    }

}