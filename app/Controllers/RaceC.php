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
        $this->rokZavodu = 0;
        }
    public function show($idZavod){

        $data = [
            'idZavod' => $idZavod,
            'year' => $this->rokZavodu,
            'zavod' => $this->race->where('id', $idZavod)->findAll()
        ];

        return view('race', $data);
    }

}
