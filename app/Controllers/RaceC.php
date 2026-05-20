<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RaceYear;
use App\Models\Race;
use CodeIgniter\HTTP\RequestInterface;
use Psr\Log\LoggerInterface;
use Override;
use Config\Config;

class RaceC extends BaseController
{
    protected $raceYear;
    protected $race;
    protected $Config;
    public $rokZavodu;

    #[Override]
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->raceYear = new RaceYear();

        $this->race = new Race();

        $this->Config = new Config();
    }

    public function show($idZavod)
    {
        $raceYearRow = $this->raceYear->find($idZavod);
        $rok = 0;
        if ($raceYearRow) {
            $rok = is_object($raceYearRow) ? $raceYearRow->year : $raceYearRow['year'];
        }

        $data = [
            'idZavod' => $idZavod,
            'year'    => $rok, 
            'zavod'   => $this->race->find($idZavod)
        ];

        return view('race', $data);
    }
}
