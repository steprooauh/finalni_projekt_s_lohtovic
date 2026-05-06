<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\RaceYear;

class RokyC extends BaseController
{
    protected $raceYear;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->raceYear = new RaceYear();
    }

    public function index()
    {
        $roky = $this->raceYear->orderBy('year', 'asc')->findAll();

         $data = [
            "raceYear" => $roky
         ];

         echo view('roky', $data);
    }
}
