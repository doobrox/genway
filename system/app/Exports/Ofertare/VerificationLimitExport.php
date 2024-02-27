<?php
namespace App\Exports\Ofertare;

use App\Models\AfmForm;
use App\Models\Judet;
use App\Models\Componenta;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VerificationLimitExport implements FromView, ShouldAutoSize
{
    use Exportable;

    protected $section = 2021;

    public function __construct($section = 2021)
    {
        $this->section = $section;
    }

    public function view(): View
    {
        $judete = Judet::get(['id','nume']);
        $items = AfmForm::setSection($this->section)->withInfo()->visible()->select(
            \DB::raw('COUNT(*) as result'), 'judet_imobil',
            \DB::raw('CASE
                WHEN data_alegere_instalator > DATE(NOW()) THEN "in_termeni"
                WHEN DATE_ADD(data_alegere_instalator, INTERVAL 4 DAY) >= DATE(NOW()) THEN "0_4"
                WHEN DATE_ADD(data_alegere_instalator, INTERVAL 10 DAY) >= DATE(NOW()) THEN "5_10"
                WHEN DATE_ADD(data_alegere_instalator, INTERVAL 20 DAY) >= DATE(NOW()) THEN "11_20"
                WHEN DATE_ADD(data_alegere_instalator, INTERVAL 25 DAY) >= DATE(NOW()) THEN "21_25"
                ELSE "intarziate"
            END AS limita_verificare')
        )->where(function($query) {
            $query->where('status_aprobare_dosar', '')->orWhereNull('status_aprobare_dosar');
        })->where(function($query) {
            $query->where('data_alegere_instalator', '<>', '')->whereNotNull('data_alegere_instalator');
        })->whereDate('data_alegere_instalator', '<=', now()->format('Y-m-d'))
        ->groupBy('judet_imobil','limita_verificare')
        ->get()->groupBy([
            function ($item, $key) {
                return $item['judet_imobil'];
            },
            function ($item, $key) {
                return $item['limita_verificare'];
            }
        ])->mapWithKeys(function ($item, $key) {
            foreach ($item as $key2 => $value) {
                $newItem[$key2] = $value[0]['result'];
            }
            return [$key => $newItem];
        });

        return view('exports.ofertare.limita-verificare', [
            'judete' => Judet::get(['id','nume']),
            'items' => $items,
        ]);
    }
}
