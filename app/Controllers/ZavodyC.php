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
        // Zjistíme z URL/GET požadavku, zda je aktivní filtr "jen moje"
        $jenMoje = $this->request->getGet('moje') === '1';

        // Základ dotazu
        $query = $this->raceYear->where('year', $year)
            ->join('stage', 'race_year.id = stage.id_race_year');

        // Pokud je zaškrtnuto "jen moje" a uživatel je přihlášený
        if ($jenMoje && session()->has('user_id')) {
            $query->where('race_year.vytvoril_uzivatel_id', session()->get('user_id'));
        }

        $zavody = $query->paginate($this->Config->strankovani);

        $data = [
            "year"    => $year,
            "pager"   => $this->raceYear->pager,
            "zavody"  => $zavody,
            "jenMoje" => $jenMoje // Předáme stav filtru do View
        ];

        echo view('zavody', $data);
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
