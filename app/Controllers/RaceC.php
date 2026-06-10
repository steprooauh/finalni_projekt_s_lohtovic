<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RaceYear;
use App\Models\Race;
use App\Models\Stage;
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
    public $stageModel;

    #[Override]
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->raceYear = new RaceYear();
        $this->race = new Race();
        $this->Config = new Config();
        $this->stageModel = new Stage();
    }

    public function show($idZavod)
    {
        $raceYearRow = $this->raceYear->find($idZavod);

        $rok = is_object($raceYearRow) ? $raceYearRow->year : $raceYearRow['year'];
        $id = is_object($raceYearRow) ? $raceYearRow->id : $raceYearRow['id'];


        if (empty($raceYearRow->total_distance) || $raceYearRow->total_distance == 0) {
            $distanceResult = $this->stageModel->where('id_race_year', $id)->selectSum('distance')->get()->getRow();
            $raceYearRow->total_distance = $distanceResult ? ($distanceResult->distance ?? 0) : 0;
        }

        if (empty($raceYearRow->total_elevation) || $raceYearRow->total_elevation == 0) {
            $elevationResult = $this->stageModel->where('id_race_year', $id)->selectSum('vertical_meters', 'elevation')->get()->getRow();
            $raceYearRow->total_elevation = $elevationResult ? ($elevationResult->elevation ?? 0) : 0;
        }

        $uci_moznosti = [
            '0'  => 'Není v UCI',
            '1'  => 'UCI Worldtour',
            '2'  => 'UCI World Championships',
            '3'  => 'Africa Tour',
            '4'  => 'Asia Tour',
            '5'  => 'Europe Tour',
            '6'  => 'Men Junior',
            '7'  => 'Women Elite',
            '8'  => 'Women Junior',
            '9'  => 'America Tour',
            '10' => 'Nations Cup',
            '11' => 'National Championship',
            '12' => 'WWT',
            '13' => 'UCI Pro Series',
            '14' => 'Oceania Tour'
        ];

        $uci_klic = is_object($raceYearRow) ? $raceYearRow->uci_tour : $raceYearRow['uci_tour'];

        $raceYearRow->uci_tour_text = $uci_moznosti[$uci_klic] ?? 'Neznámo';

        $data = [
            'idZavod' => $idZavod,
            'zavod'   => $raceYearRow,
            'year'    => $rok
        ];

        return view('race', $data);
    }
}
