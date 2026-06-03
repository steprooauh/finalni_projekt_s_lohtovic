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

        $builder = $this->raceYear->where('year', $year);

        $stage = $this->Stage->join('race_year', 'stage.id_race_year = race_year.id')
            ->join('race', 'race.id = race_year.id_race')
            ->where('race_year.year', $year)
            ->findAll();

        // OPRAVA FILTRU: Pokud je zaškrtnuto "Moje", filtrujeme hodnotu 1 v DB
        if ($jenMoje) {
            $builder->where('vytvoril_uzivatel_id', 1);
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

        $data = [
            'stage'          => $stage,
            'year'           => $year,
            'zavody'         => $builder->paginate($this->Config->strankovani),
            'vsechny_zavody' => $this->raceYear->where('year', $year)->findAll(),
            'pager'          => $this->raceYear->pager,
            'jenMoje'        => $jenMoje,
            'uci_moznosti'   => $uci_moznosti
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

            $insertData = [
                'real_name'            => $this->request->getPost('nazev'),
                'year'                 => $this->request->getPost('rok'),
                'id_uci_tour'          => $this->request->getPost('id_uci_tour'),
                'id_rocniku'           => $this->request->getPost('id_rocniku'),
                'logo'                 => '',
                'vytvoril_uzivatel_id' => 1, // Automaticky dáváme 1 (vytvořeno uživatelem)
            ];

            if ($this->raceYear->insert($insertData)) {
                $newId = $this->raceYear->getInsertID();
                $img = $this->request->getFile('logo');

                if ($img && $img->isValid() && ! $img->hasMoved()) {
                    $extension = $img->getClientExtension();
                    $logoName = 'logo-' . $newId . '.' . $extension;
                    $uploadPath = FCPATH . 'img/logos/';

                    if ($img->move($uploadPath, $logoName)) {
                        $this->raceYear->update($newId, ['logo' => $logoName]);
                    }
                }

                $rok = $this->request->getPost('rok');
                return redirect()->to(base_url("index.php/zavody/{$rok}"))->with('success', 'Závod byl úspěšně přidán.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Nepodařilo se uložit závod.');
            }
        }

        return redirect()->to(base_url());
    }

    public function change()
    {
        if (! $this->request->is('post')) {
            return redirect()->back()->with('error', 'Neoprávněný přístup.');
        }

        $id = $this->request->getPost('zavod_id');

        if (empty($id)) {
            return redirect()->back()->with('error', 'Nebyl vybrán žádný závod k úpravě.');
        }

        $zavod = $this->raceYear->find($id);
        if (!$zavod) {
            return redirect()->back()->with('error', 'Závod nebyl nalezen v databázi.');
        }

        $updateData = [
            'real_name'   => $this->request->getPost('nazev'),
            'id_uci_tour' => $this->request->getPost('uci_tour'),
        ];

        $file = $this->request->getFile('logo');

        // Opravená podmínka a validace pro nahrávání souborů v CI4
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $rules = [
                'logo' => 'max_size[logo,2048]|ext_in[logo,jpg,jpeg,png]'
            ];

            if ($this->validate($rules)) {
                $extension = $file->getClientExtension();
                $newName = 'logo-' . $id . '.' . $extension;
                $uploadPath = FCPATH . 'img/logos/';

                if (!empty($zavod->logo) && $zavod->logo !== $newName && file_exists($uploadPath . $zavod->logo)) {
                    unlink($uploadPath . $zavod->logo);
                }

                if ($file->move($uploadPath, $newName)) {
                    $updateData['logo'] = $newName;
                }
            } else {
                return redirect()->back()->with('error', 'Obrázek nesplňuje podmínky (max 2MB, formát JPG/PNG).');
            }
        }

        if ($this->raceYear->update($id, $updateData)) {
            return redirect()->back()->with('success', 'Závod a logo byly úspěšně upraveny.');
        }

        return redirect()->back()->with('error', 'Chyba při ukládání dat.');
    }

    public function delete()
    {
        if (! $this->request->is('post')) {
            return redirect()->back()->with('error', 'Neoprávněný přístup.');
        }

        $id = $this->request->getPost('id');

        if (empty($id)) {
            return redirect()->back()->with('error', 'Nebylo zadáno ID závodu ke smazání.');
        }

        // POZOR: Tady máš model 'ZavodModel', ujisti se, že pod tímto názvem je v DB soft-delete, 
        // jinak použij přímo $this->raceYear->delete($id);
        $zavodyModel = model('ZavodModel');
        $zavod = $zavodyModel->find($id);

        if (!$zavod) {
            return redirect()->back()->with('error', 'Závod nebyl nalezen.');
        }

        if ($zavodyModel->delete($id)) {
            return redirect()->back()->with('success', 'Závod byl úspěšně skryt z přehledu.');
        } else {
            return redirect()->back()->with('error', 'Závod se nepodařilo odstranit.');
        }
    }
}
