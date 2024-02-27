<?php
namespace App\Exports\Ofertare;

use App\Models\AfmForm;
use App\Models\Ofertare\Invertor;
use App\Models\Ofertare\Panou;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use DB;

class NecesarEchipaExport implements FromView, ShouldAutoSize
{
    public $produse = [
        'FRONIUS PRIMO GEN24 3.0 Plus',
        'FRONIUS PRIMO GEN24 4.0 Plus', // nu exista
        'FRONIUS PRIMO GEN24 5.0 Plus',
        'FRONIUS PRIMO GEN24 6.0 Plus',
        'FRONIUS SYMO GEN24 3.0 Plus',
        'FRONIUS SYMO GEN24 4.0 Plus', // nu exista
        'FRONIUS SYMO GEN24 5.0 Plus',
        'FRONIUS SYMO GEN24 6.0 Plus',
        'FRONIUS SYMO GEN24 8.2 Plus', // facut din 8.0 => 8.2
        'FRONIUS SYMO GEN24 10.0 Plus',
        'BYD Battery Box Premium HVS 5.1',
        'BYD Battery Box Premium HVM 11',
        'FRONIUS PRIMO 3.0-1', // adaugat la toate fronius primo "-1" la sfarsit sa se potriveasca cu cele din db
        'FRONIUS PRIMO 4.0-1',
        'FRONIUS PRIMO 5.0-1',
        'FRONIUS PRIMO 6.0-1',
        'FRONIUS PRIMO 8.2-1',
        'FRONIUS SYMO 3.0-3-M', // adaugat la toate fronius symo "-3-M" la sfarsit sa se potriveasca cu cele din db
        'FRONIUS SYMO 5.0-3-M',
        'FRONIUS SYMO 6.0-3-M',
        'FRONIUS SYMO 8.2-3-M',
        'FRONIUS SYMO 10.0-3-M',
        'FRONIUS SYMO 15.0-3-M', // adaugate din fisier
        'FRONIUS SYMO 20.0-3-M', // adaugate din fisier
        'FRONIUS SYMO 27.0-3-M', // adaugate din fisier // nu exista in platforma
        'HUAWEI SUN2000-3KTL-L1',
        'HUAWEI SUN2000-5KTL-L1',
        'HUAWEI SUN2000-6KTL-L1',
        'HUAWEI SUN2000-3KTL-M1',
        'HUAWEI SUN2000-5KTL-M1',
        'HUAWEI SUN2000-6KTL-M1',
        'HUAWEI SUN2000-8KTL-M1', // nu exista in fisier
        'HUAWEI SUN2000-10KTL-M1', // nu exista in fisier
        'Panouri fotovoltaice 545W',
        'Panouri fotovoltaice 460W',
        'Panouri fotovoltaice 430W',
        'Panouri fotovoltaice 425W',
        'Panouri fotovoltaice 410W',
        'Panouri fotovoltaice 405W',
        'Panouri fotovoltaice 400W',
        'Panouri fotovoltaice 385W',
        'SM-FRONIUS MONO-TS 100A-1',
        'SM-FRONIUS TRIF-TS 65A-3',
        'SM-FRONIUS TRIF-TS TS 5kA-3',
        'DISJUNCTOARE/BOBINE',
        'SM-HUAWEI-DDSU666-H 3PHASE', // in fisiere nu este numele complet (lipsea "3phase")
        'SM-HUAWEI-DDSU666-H 1PHASE', // in fisiere nu este numele complet (lipsea "1phase")
        'Cablu 1x4 CS.01 negru',
        'Cablu 1x4 CS.01 rosu',
        'Cablu 1x6 CS.02 negru',
        'Cablu 1x6 CS.02 rosu',
        'Cablu 1x10 CS.03 negru',
        'Cablu 1x10 CS.03 rosu',
        'Mufe SY-MC4-2',
        'SP.01',
        'SP.02',
        'SP.03',
        'SP.04',
        'Sina R1 2.1',
        'Sina R1 3.1',
        'Clema capat EC-01',
        'Clema de mijloc MC-01',
        'Sina de imbinare RS',
        // 'TRH-01A - TIGLA',
        'TIGLA: TRH-01A',
        'TIGLA: TRH-01B',
        'TIGLA: TRH-01C',
        // 'MRH-01  - TABLA',
        'TABLA: MRH-06A',
        'TABLA: MRH-15',
        // 'MRH12-300(PANOU SANDWICH)',
        // 'MRH03-400(PANOU SANDWICH)',
        'SANDWICH: MRH-12-200',
        'SANDWICH: MRH-12-300',
        'SANDWICH: MRH-03',
        'MRH13(TABLA FALTUITA)',
        'RC.03(SUPORT TABLA FALTUITA)',
        'GC.02-Lamela impam.pt.MC-01',
        'GL-Clema Impamantare sina',
        'TABLOU electric',
        'SIGURANTA MONO 20A',
        'SIGURANTA MONO 25A',
        'SIGURANTA MONO 32A',
        'SIGURANTA MONO 40A',
        'SIGURANTA MONO 50A',
        'SIGURANTA TRIF 16A',
        'SIGURANTA TRIF 20A',
        'SIGURANTA TRIF 25A',
        'SIGURANTA TRIF 32A',
        'SIGURANTA TRIF 40A',
        'SIGURANTA TRIF 50A',
        'SIGURANTA TRIF 63A',
        'Cablu Alimentare 3x2.5',
        'Cablu Alimentare 3x4',
        'Cablu Alimentare 3x6',
        'Cablu Alimentare 5X2.5',
        'Cablu Alimentare 5X4',
        'Cablu Alimentare 5X6',
        'Cablu Alimentare 5X10',
        'Copex metalic cu manta de pvc 25mm',
        'Cablu Utp',
        'CANALET PERFORAT',
    ];

    protected $formulare = [];
    protected $section = [];

    public function __construct($formulare, $section = 2021) {
        $this->formulare = is_array($formulare) ? $formulare : [];
        $this->section = $section;
    }

    public function view(): View
    {
        $afm_table = AfmForm::setSection($this->section)->getTableName();
        $formulare = AfmForm::setSection($this->section)->withInfo()->select("{$afm_table}.id as id", 'id_formular', 'nume', 'prenume',
            'siruri_panouri', 'stare_montaj', 'tipul_invelitorii', 'tipul_bransamentului',
            'numar_panouri', 'putere_panouri', 'putere_invertor', 'marca_invertor',
            'numar_sp-uri', 'cod', 'contor'
        )->leftJoin(Invertor::getTableName(), function($join) {
            $join->on('marca_invertor', 'marca')
                ->on('putere', 'putere_invertor')
                ->on('tip', 'tipul_bransamentului');
        })->whereIn("{$afm_table}.id", $this->formulare)
        ->whereNotNull('tipul_invelitorii')
        ->whereNotNull('marca_invertor')
        ->where('marca_invertor', '<>', '""')
        // ->where('stare_montaj', 'sistem nemontat')
        ->get();

        return view('exports.ofertare.necesar-echipa', [
            'produse' => $this->produse,
            'formulare' => $formulare,
            'highestColumn' => Coordinate::stringFromColumnIndex(2 + (count($formulare) * 2)), // starting from C on position 3
        ]);
    }
}
