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

    public function index($year)
    {
        $zavody = $this->raceYear->select('')->where('year', $year)->join('stage', 'race_year.id = stage.id_race_year')->groupBy('race_year.id')->findAll();

        $data = [
            "year" => $year,
            "zavody" => $zavody
        ];

        echo view('zavody', $data);
    }
}
