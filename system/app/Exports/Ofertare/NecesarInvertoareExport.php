<?php
namespace App\Exports\Ofertare;

use App\Models\AfmForm;
use App\Models\Componenta;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DB;

class NecesarInvertoareExport implements FromView, ShouldAutoSize, WithColumnWidths
{
    protected $section = 2021;

    public function __construct($section = 2021)
    {
        $this->section = $section;
    }

    public function view(): View
    {
        $info_table = AfmForm::setSection($this->section)->getInfoTableName();
        $puteri_invertoare = AfmForm::setSection($this->section)->withInfo()->select("{$info_table}.putere_invertor as putere_invertor")
            ->whereNotNull("{$info_table}.putere_invertor")
            ->whereNotNull('marca_invertor')
            ->where('marca_invertor', '<>', '""')
            // ->where('status', '6')
            ->whereIn('stare_montaj', ['sistem nemontat', 'sistem nemontat, pregatit pentru instalare'])
            ->groupBy("{$info_table}.putere_invertor")
            ->pluck('putere_invertor')->toArray();

        $invertoare = AfmForm::setSection($this->section)->withInfo()->select(
                DB::raw('count(*) as total'),'tipul_bransamentului',"{$info_table}.putere_invertor",'marca_invertor'
            )->whereNotNull("{$info_table}.putere_invertor")
            ->whereNotNull('marca_invertor')
            ->where('marca_invertor', '<>', '""')
            // ->where('status', '6')
            // ->where('stare_montaj', 'sistem nemontat')
            ->whereIn('stare_montaj', ['sistem nemontat', 'sistem nemontat, pregatit pentru instalare'])
            ->groupBy('tipul_bransamentului',"{$info_table}.putere_invertor",'marca_invertor')
            ->get()->groupBy([
                function ($item, $key) {
                    return $item['marca_invertor'];
                },
                function ($item, $key) {
                    return $item['tipul_bransamentului'];
                },
                function ($item, $key) {
                    return $item['putere_invertor'];
                }
            ])->sortKeys()->toArray();

        return view('exports.ofertare.necesar-invertoare', [
            'invertoare' => $invertoare,
            'puteri' => $puteri_invertoare,
        ]);
    }

    public function columnWidths(): array
    {
        $highestColumn = 'J';
        $columns = [];
        $highestColumn++;
        for ($column = 'C'; $column !== $highestColumn; $column++) {
            $columns[$column] = 10;
        }
        return $columns;
    }
}
