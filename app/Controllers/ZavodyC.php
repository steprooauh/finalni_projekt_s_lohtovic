<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\RaceYear;
use CodeIgniter\HTTP\RequestInterface;
use Psr\Log\LoggerInterface;
use Override;

class ZavodyC extends BaseController
{
    protected $raceYear;

    #[Override]
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->raceYear = new RaceYear();
    }

    public function index($num)
    {
        $zavody = $this->raceYear->where('year', $num)->findAll();

        $data = [
            "zavody" => $zavody
        ];

        echo view('zavody', $data);
    }
}
