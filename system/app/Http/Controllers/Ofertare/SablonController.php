<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ofertare\SablonAFM;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class SablonController extends Controller
{
    public function index()
    {
        return view('ofertare.sabloane.index', [
            'sectiune' => __('Sablone AFM'),
            'items' => auth()->user()->sabloaneAFM,
            'add_route' => route('ofertare.sabloane_afm.create'),
            'can_delete' => true
        ]);
    }

    public function create()
    {
        return view('ofertare.sabloane.create', $this->commonParameters());
    }

    public function store(Request $request)
    {
        $input = $request->validate($this->rules(),[],$this->names());

        if(isset($input['implicit'])) {
            $input['implicit'] = 1;
            auth()->user()->sabloaneAFM()->update(['implicit' => null]);
        }
        // order only selected columns
        $input['ordine_coloane'] = array_filter($input['ordine_coloane'], function($key) use ($input) {
            return in_array($key, $input['coloane']);
        }, ARRAY_FILTER_USE_KEY);

        // reorder columns input
        $coloane = $input['ordine_coloane'];
        asort($coloane);
        $input['coloane'] = array_keys($coloane);

        $sablon = SablonAFM::create($input + [
            'user_id' => auth()->id()
        ]);

        return redirect()->route('ofertare.sabloane_afm.edit', $sablon)->with([
            'status' => __('Sablonul AFM :name a fost creat.', ['name' => $sablon->nume])
        ], 200);
    }

    public function edit(SablonAFM $sablon)
    {
        return view('ofertare.sabloane.create', $this->commonParameters() + [
            'item' => $sablon,
        ]);
    }

    public function update(Request $request, SablonAFM $sablon)
    {
        $input = $request->validate($this->rules($sablon->id),[],$this->names());

        if(isset($input['implicit'])) {
            $input['implicit'] = 1;
            auth()->user()->sabloaneAFM()->whereNot('id', $sablon->id)->update(['implicit' => null]);
        }

        // order only selected columns
        $input['ordine_coloane'] = array_filter($input['ordine_coloane'], function($key) use ($input) {
            return in_array($key, $input['coloane']);
        }, ARRAY_FILTER_USE_KEY);

        // reorder columns input
        $coloane = $input['ordine_coloane'];
        asort($coloane);
        $input['coloane'] = array_keys($coloane);
        $sablon->update($input);

        return back()->with(['status' => __('Sablonul AFM :name a fost actualizat.', ['name' => $sablon->nume])], 200);
    }

    public function copy()
    {
        return view('ofertare.sabloane.copy', $this->commonParameters() + [
            'users' => User::whereIn('id', SablonAFM::select('user_id')->groupBy('user_id'))->get(),
            'sabloane' => SablonAFM::where('user_id', old('user'))->get() ?? []
        ]);
    }

    public function duplicate(Request $request)
    {
        $input = $request->validate(['sablon' => ['required', 'integer', 'min:1', Rule::exists(SablonAFM::class, 'id')]]);

        $sablon = SablonAFM::find($input['sablon'])->replicate();
        $sablon->user_id = auth()->id();
        $sablon->implicit = null;

        $coloane_permise = SablonAFM::allColumns();
        // get only available columns
        $sablon->ordine_coloane = array_filter($sablon->ordine_coloane ?? [], function($key) use ($coloane_permise) {
            return in_array($key, $coloane_permise);
        }, ARRAY_FILTER_USE_KEY);

        // get columns array
        $sablon->coloane = array_keys($sablon->ordine_coloane);
        $sablon->save();

        return redirect()->route('ofertare.sabloane_afm.edit', $sablon)->with([
            'status' => __('Sablonul AFM :name a fost copiat.', ['name' => $sablon->nume])
        ], 200);
    }

    public function sabloaneUser(User $user)
    {
        if($user) {
            $options = '';
            foreach($user->sabloaneAFM as $sablon) {
                $options .= '<option value="'.$sablon->id.'">'.$sablon->nume.'</option>';
            }
        } else {
            $options = '<option value="">'.__('Nici un sablon gasit').'</option>';
        }
        return $options;
    }

    public function check(Request $request, SablonAFM $sablon)
    {
        auth()->user()->sabloaneAFM()->where('implicit', 1)->update(['implicit' => null]);
        $sablon->update(['implicit' => 1]);
        return back()->with(['status' => __('Sablonul AFM :name a fost setat ca implicit.', ['name' => $sablon->nume])], 200);
    }

    public function delete(Request $request, SablonAFM $sablon)
    {
        $sablon->delete();
        if($sablon->implicit) {
            auth()->user()->sabloaneAFM()->limit(1)->update(['implicit' => 1]);
        }
        return back()->with(['status' => __('Sablonul AFM :name a fost sters.', ['name' => $sablon->nume])], 200);
    }

    protected function rules($id = null)
    {
        return [
            'nume' => ['required', 'max:255', 
                Rule::unique(SablonAFM::class, 'nume')
                    ->where(fn ($query) => $query->where('user_id', auth()->id()))
                    ->ignore($id)
            ],
            'coloane' => ['required', 'array', 'min:1'],
            'coloane.*' => ['required', 'string', 'max:50', Rule::in(SablonAFM::allColumns())],
            'ordine_coloane' => ['nullable', 'array', 'min:1'],
            'ordine_coloane.*' => ['nullable', 'numeric', 'max:999'],
            'implicit' => ['nullable', 'integer', 'in:1'],
        ];
    }

    protected function names()
    {
        return [
            'nume' => 'nume',
            'coloane' => 'coloane',
            'coloane.*' => 'coloana',
            'ordine_coloane' => 'ordine coloane',
            'ordine_coloane.*' => 'ordine coloana',
            'implicit' => 'implicit',
        ];
    }

    protected function commonParameters()
    {
        return [
            'sectiune' => __('Sablone AFM'),
            'browse_route' => route('ofertare.sabloane_afm.browse'),
            'form' => true,
            'columns' => SablonAFM::allColumns(),
        ];
    }
}
