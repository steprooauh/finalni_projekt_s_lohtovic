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
use App\Models\Stage;

class ZavodyC extends BaseController
{
    protected $raceYear;
    protected $race;
    protected $Config;
    public $rokZavodu;
    protected $Stage;

    #[Override]
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->raceYear = new RaceYear();
        $this->Stage = new Stage();
        $this->race = new Race();

        $this->Config = new Config();
        $this->rokZavodu = 0;
    }

    public function index($year)
    {
        $jenMoje = $this->request->getGet('moje') === '1';

        $this->rokZavodu = $year;

        $builder = $this->raceYear
            ->where('year', $year);
        
        $stage = $this->Stage->join('race_year', 'stage.id_race_year = race_year.id')->join('race', 'race.id = race_year.id_race')->where('race_year.year', $year)->findAll();

        if ($jenMoje && session()->get('user_id')) {
            $builder->where('vytvoril_uzivatel_id', session()->get('user_id'));
        }

        $data = [
            'stage'   => $stage,
            'year'    => $year,
            'zavody'  => $builder->paginate($this->Config->strankovani),
            'pager'   => $this->raceYear->pager,
            'jenMoje' => $jenMoje
        ];

        return view('zavody', $data);
    }

    public function add()
    {
        if ($this->request->is('post')) {

            $rules = [
                'nazev'       => 'required|min_length[3]|max_length[255]',
                'rok'         => 'required|numeric',
                'id_uci_tour' => 'required|numeric',
                'logo'        => 'uploaded[logo]|max_size[logo,2048]|ext_in[logo,jpg,jpeg,png]',
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $img = $this->request->getFile('logo');
            $logoName = '';

            if ($img->isValid() && ! $img->hasMoved()) {
                $logoName = $img->getRandomName();
                $img->move(ROOTPATH . 'public/uploads/logos', $logoName);
            }

            $insertData = [
                'nazev'                 => $this->request->getPost('nazev'),
                'year'                  => $this->request->getPost('rok'),
                'id_uci_tour'           => $this->request->getPost('id_uci_tour'),
                'id_rocniku'            => $this->request->getPost('id_rocniku'),
                'logo'                  => $logoName,
                'vytvoril_uzivatel_id'  => session()->get('user_id'),
            ];

            if ($this->raceYear->insert($insertData)) {
                $rok = $this->request->getPost('rok');
                return redirect()->to(base_url("zavody/{$rok}"))->with('success', 'Závod byl úspěšně přidán.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Nepodařilo se uložit závod.');
            }
        }

        return redirect()->to(base_url());
    }
}
