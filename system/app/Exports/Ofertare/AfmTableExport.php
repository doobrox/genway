<?php
namespace App\Exports\Ofertare;

use App\Models\AfmForm;
use App\Models\Componenta;
use App\Models\Fisier;
use App\Models\QueuedExport;
use App\Models\Ofertare\ColoanaTabelAFM;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Style;
// use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AfmTableExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithCustomChunkSize, WithCustomQuerySize, WithDefaultStyles
{
    use Exportable;

    protected $section = 2021;
    protected $export_id = null;
    protected $query = [];
    protected $coloane = [];
    protected $coloane_db = [];
    protected $coloane_necesare = [];
    protected $scopes = [];
    protected $joins = [];

    public function __construct($coloane, $query, $section = 2021, $export_id = null)
    {
        $this->query = $query;
        $this->section = $section;
        $this->export_id = $export_id;

        $data = AfmForm::prepareQueryData($coloane, $this->query, $this->section);

        $this->coloane = $data['coloane'];
        $this->scopes = $data['scopes'];
        $this->joins = $data['joins'];
        $this->coloane_db = $data['coloane_db'];
        $this->coloane_necesare = $data['coloane_necesare'];

        // $table = AfmForm::setSection($this->section)->getTableName();
        // $info_table = AfmForm::setSection($this->section)->getInfoTableName();
        // // $permisiuni = $this->session->userdata('permisiuni');
        // $permisiuni = array_flip(auth()->user()->getAllPermissions()->pluck('name','id')->all());
        // $coloane = \Arr::wrap($coloane);

        // // if it doesn't have permission to access all table columns
        // if(!isset($permisiuni['afm.2021.*'])) {
        //     $coloane_permise = null;
        //     preg_match_all(
        //         '/afm\.[^\.]*\.((?!generare[^\.]*|buton[^\.]*|mail[^\.]*|view|\*)[^\.]*)(?:\.view);?/',
        //         implode(';',array_keys($permisiuni)).';',
        //         $coloane_permise
        //     );

        //     $coloane_permise = $coloane_permise[1] ?? [];
        //     $coloane = array_intersect($coloane, $coloane_permise);
        // }

        // $coloane = ColoanaTabelAFM::whereIn('nume', $coloane)
        //     ->orderByRaw('FIELD(nume, "'.implode('", "', $coloane).'")')
        //     ->get();

        // $this->coloane = $coloane;

        // // for columns that are not found in database
        // $coloane_db = $coloane->where('tip', '<>', '9')->pluck('nume')->toArray();

        // // for columns that are not saved in database
        // // but are composed from 'n' diferent columns
        // $coloane_necesare = [];
        // foreach ($coloane as $coloana) {
        //     if(isset($coloana['rules']['callback']['columns'])) {
        //         $coloane_necesare += array_flip($coloana['rules']['callback']['columns']);
        //     }
        // }
        // // add conditions columns if they are not selected
        // $coloane_necesare = is_array($coloane_necesare) && is_array($this->query)
        //     ? $coloane_necesare + $this->query
        //     : $coloane_necesare;
        // if($coloane_necesare != []) {
        //     $coloane_db = array_keys(array_flip($coloane_db) + $coloane_necesare);
        // }

        // // add scopes and joins for query
        // $coloane_necesare = ColoanaTabelAFM::whereIn('nume', $coloane_db)
        //     ->orderByRaw('FIELD(nume, "'.implode('", "', $coloane_db).'")')
        //     ->get();

        // // reset db columns in case a custom condition is added that is not a column name
        // $coloane_db = [];
        // $scopes = [];
        // $joins = [];
        // foreach($coloane_necesare as $index => $coloana) {
        //     // add table name to columns
        //     $coloane_db[$index] = $coloana->getColumnTable($this->section).'.'.$coloana->nume;

        //     // remove columns that are retrieved from scopes
        //     if(isset($coloana['rules']['scope'])) {
        //         $scopes[] = $coloana['rules']['scope'] === true
        //             ? Str::camel('with_'.$coloana['nume'])
        //             : $coloana['rules']['scope'];
        //         unset($coloane_db[$index]);
        //     }

        //     if(isset($coloana['rules']['db']['table'])) {
        //         $joins[] = [
        //             'tabel' => $coloana['rules']['db']['table'],
        //             'join_tabel' => $coloana->getColumnTable($this->section),
        //             'coloane_concat' => $coloana['rules']['db']['cols'] ?? null,
        //             'coloana' => $coloana['nume'],
        //         ];
        //     }
        //     if($coloana['tip'] == '7') {
        //         $joins[] = [
        //             'tabel' => (new Fisier)->getTable(),
        //             'join_tabel' => $coloana->getColumnTable($this->section),
        //             'coloane_concat' => ['path','name'],
        //             'separator' => '',
        //             'coloana' => $coloana['nume'],
        //         ];
        //     }
        // }
        // $this->scopes = $scopes;
        // $this->joins = $joins;

        // // add table to the 'id' column
        // // or add the column if it is not in the array of columns
        // $temp = array_flip($coloane_db);
        // if(!isset($temp[$table.'.id'])) {
        //     $coloane_db[] = $table.'.id';
        // }

        // $this->coloane_db = $coloane_db;
        // $this->coloane_necesare = $coloane_necesare;
        // dd($coloane_necesare);
    }

    public function query()
    {
        return AfmForm::getQueryFromData([
            'coloane_db' => $this->coloane_db,
            'coloane_necesare' => $this->coloane_necesare,
            'scopes' => $this->scopes,
            'joins' => $this->joins,
        ], $this->query, $this->section);

        // $table = AfmForm::setSection($this->section)->getTableName();
        // // $query = AfmForm::setSection($this->section)->select(...$this->coloane_db);
        // // visible scope includes arhivat
        // $query = AfmForm::setSection($this->section)->distinct()->visible()->select(...$this->coloane_db);
        // if($this->coloane_necesare->where('tabel', 'info')->count() > 0) {
        //     $query->withInfo();
        // }
        // if($this->coloane_necesare->where('tabel', 'copro')->count() > 0) {
        //     $query->withCopro();
        // }
        // foreach($this->scopes as $scope) {
        //     $query->{$scope}();
        // }
        // foreach($this->joins as $join) {
        //     $query->customJoin($join['tabel'], $join['coloana'], [
        //         'retrieved_columns' => $join['coloane_concat'] ?? null,
        //         'join_table' => $join['join_tabel'] ?? null,
        //         'separator' => $join['separator'] ?? null,
        //     ]);
        // }

        // // $query->where('arhivat', 0);
        // $conditions = $this->query;
        // if($conditions != [] && is_array($conditions)) {
        //     if(isset($conditions['formular'])) {
        //         $query->whereIn($table.'.id', \Arr::wrap($conditions['formular']));
        //         unset($conditions['formular']);
        //     }
        //     foreach ($conditions as $coloana => $val) {
        //         if($coloana_tabel = $this->coloane_necesare->where('nume', $coloana)->first()) {
        //             // don't add table name if the column is created from a scope/join
        //             $coloana_cu_tabel = isset($coloana_tabel['rules']['scope'])
        //                 ? $coloana
        //                 : $coloana_tabel->getColumnTable($this->section).'.'.$coloana;
        //             if(isset($coloana_tabel->rules['relationship']['name'])) {
        //                 $rel = $coloana_tabel->rules['relationship'];
        //                 $query->whereHas($rel['name'], function ($query) use ($val, $rel) {
        //                     $query->whereIn($rel['table'].'.id', \Arr::wrap($val));
        //                 });
        //             } elseif($coloana_tabel->tip == '3') { // date conditions
        //                 if(is_array($val)) {
        //                     $query->where(function($subquery) use($coloana_cu_tabel, $val) {
        //                         $func = 'where';
        //                         if(isset($val['empty'])) {
        //                             $subquery->whereNull($coloana_cu_tabel)->orWhere($coloana_cu_tabel, '');
        //                             $func = 'orWhere';
        //                         }
        //                         if(isset($val['not_empty'])) {
        //                             $subquery->{$func}($coloana_cu_tabel, '<>', '')->whereNotNull($coloana_cu_tabel);
        //                             $func = 'where';
        //                         }
        //                         if(isset($val['start']) && $val['start']) {
        //                             $subquery->{$func}($coloana_cu_tabel, '>=', $val['start']);
        //                             $func = 'where';
        //                         }
        //                         if(isset($val['end']) && $val['end']) {
        //                             $subquery->{$func}($coloana_cu_tabel, '<=', $val['end']);
        //                         }
        //                     });
        //                 } elseif(isset($conditions[$coloana.'_sign'])){
        //                     $query->where($coloana_cu_tabel, $conditions[$coloana.'_sign'], $val);
        //                 } else {
        //                     $query->where($coloana_cu_tabel, 'like', $val.'%');
        //                 }
        //             } else { // text, textarea, number, select conditions
        //                 if(is_array($val)) {
        //                     $flipped_val = array_flip($val);
        //                     $query->where(function($subquery) use($coloana_cu_tabel, $val, $flipped_val) {
        //                         $func = 'whereIn';
        //                         if(isset($flipped_val['']) || isset($val['empty'])) {
        //                             $subquery->whereNull($coloana_cu_tabel)->orWhere($coloana_cu_tabel, '');
        //                             $func = 'orWhereIn';
        //                             unset($val['empty']);
        //                         }
        //                         if(isset($val['not_empty'])) {
        //                             $subquery->whereNotNull($coloana_cu_tabel)->orWhere($coloana_cu_tabel, '<>', '');
        //                             $func = 'orWhereIn';
        //                             unset($val['not_empty']);
        //                         }
        //                         if(count($val) > 0) {
        //                             $subquery->{$func}($coloana_cu_tabel, $val);
        //                         }
        //                     });
        //                 } else {
        //                     $func = isset($coloana_tabel->rules['scope']) ? 'having' : 'where';
        //                     if(in_array($coloana_tabel->tip, ['2', '5', '6', '7'])) {
        //                         $query->{$func}($coloana_cu_tabel, $val);
        //                     } else {
        //                         $query->{$func}($coloana_cu_tabel, 'like', '%'.$val.'%');
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        // // if(auth()->id() == '413') {    
        // //     \Log::info($query->getBindings());
        // //     \Log::info(\Str::replaceArray('?', $query->getBindings(), $query->toSql()));
        // //     dd(\Str::replaceArray('?', $query->getBindings(), $query->toSql()));
        // // }

        // return $query;
    }

    public function headings(): array
    {
        $coloane = [];
        foreach($this->coloane->pluck('titlu') as $nume) {
            $coloane[] = $nume;
        }
        return $coloane;
    }

    public function map($formular): array
    {
        $array = [];
        foreach($this->coloane as $coloana) {
            $nume = $coloana->nume;
            $values = $coloana->default_values ?? [];
            // for columns with type file
            if($coloana->tip == '7' && isset($formular['nume_'.$nume]) && $formular['nume_'.$nume]) {
                $formular['nume_'.$nume] = '=HYPERLINK("'.route('ofertare.aws.get', $formular['nume_'.$nume]).'", "Link")';
            }
            if($coloana->tip == '9') {
                $formular['nume_'.$nume] = $formular->getCustomColumnTypeValues($coloana, 2);
            }
            $array[] = $formular['nume_'.$nume] 
                ?? $values[is_array($formular[$nume]) ? json_encode($formular[$nume]) : $formular[$nume]] 
                ?? $formular[$nume] ?? '';
        }
        return $array;
    }

    // public function failed(\Throwable $exception): void
    // {
    //     if($this->export_id) {
    //         QueuedExport::where('id', $this->export_id)->update(['status' => '-1']);
    //     }
    // }

    // public function registerEvents(): array
    // {
    //     return [
    //         BeforeExport::class => function (BeforeExport $event) {
    //             // throw new \Exception('Export failed');
    //         },
    //         // AfterSheet::class => function (AfterSheet $event) {
    //         //     if($this->export_id) {
    //         //         QueuedExport::where('id', $this->export_id)->update(['status' => '2']);
    //         //     }
    //         // },
    //     ];
    // }

    public function defaultStyles(Style $defaultStyle)
    {
        // return the styles array
        // with default values
        return [
            'alignment' => [
                // allow newline in cell
                'wrapText'   => true,
            ],
        ];
    }

    public function querySize(): int
    {
        return $this->query()->count();
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
