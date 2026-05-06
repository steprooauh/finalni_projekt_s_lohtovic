<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RaceYear;
use CodeIgniter\HTTP\RequestInterface;
use Psr\Log\LoggerInterface;
use Override;
use Config\Config;

class ZavodyC extends BaseController
{
    protected $raceYear;
    protected $Config;

    #[Override]
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    
        $this->raceYear = new RaceYear();
        $this->Config = new Config();
    }

    public function index($year)
    {
        $zavody = $this->raceYear->where('year', $year)->join('stage', 'race_year.id = stage.id_race_year')->paginate($this->Config->strankovani);

        $data = [
            "year" => $year,
            "pager" => $this->raceYear->pager,
            "zavody" => $zavody
        ];

        echo view('zavody', $data);
    }
}
