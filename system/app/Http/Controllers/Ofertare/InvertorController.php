<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller;
use App\Models\Ofertare\Invertor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class InvertorController extends Controller
{
    public function index(Request $request)
    {
        return view('ofertare.invertoare.index', [
            'sectiune' => __('Invertoare'),
            'items' => $this->search($request)->paginate(25),
            'add_route' => route('ofertare.invertoare.create'),
            'can_delete' => true
        ]);
    }

    public function search(Request $request)
    {
        $query = Invertor::query();
        $input = $request->input();
        if(!empty($input['cod'])) {
            $query->where('cod', 'like', '%'.$input['cod'].'%');
        }
        if(!empty($input['putere'])) {
            $query->where('putere', $input['putere']);
        }
        if(!empty($input['marca'])) {
            $query->where('marca', 'like', '%'.$input['marca'].'%');
        }
        if(!empty($input['tip'])) {
            $query->where('tip', $input['tip']);
        }
        if(!empty($input['grid'])) {
            $query->where('grid', $input['grid']);
        }
        if(!empty($input['contor'])) {
            $query->where('contor', 'like', '%'.$input['contor'].'%');
        }
        if(!empty($input['descriere'])) {
            $query->where('descriere', 'like', '%'.$input['descriere'].'%');
        }
        return $query;
    }

    public function get(Request $request)
    {
        return $this->search($request)->get();
    }

    public function getWithHTML(Request $request)
    {
        if($request) {
            $options = '';
            foreach($this->get($request) as $invertor) {
                $options .= '<option value="'.$invertor->id.'">'.$invertor->cod.' ('.$invertor->tip.')</option>';
            }
        } else {
            $options = '<option value="">'.__('Alege model invertor').'</option>';
        }
        return $request->ajax() ? response($options, 200) : $options;
    }

    public function create()
    {
        return view('ofertare.invertoare.create', $this->commonParameters() + [
            'save_route' => route('ofertare.invertoare.store'),
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->validate($this->rules(), [], $this->names());

        $item = Invertor::create([
            'cod' => $input['cod'],
            'putere' => $input['putere'],
            'marca' => $input['marca'],
            'tip' => $input['tip'],
            'grid' => $input['grid'],
            'contor' => $input['contor'],
            'descriere' => $input['descriere'] ?? null,
        ]);

        return redirect()->route('ofertare.invertoare.create')->with([
            'status' => __('Invertorul :cod a fost creat.', ['cod' => $item->cod])
        ]);
    }

    public function edit(Invertor $invertor)
    {
        return view('ofertare.invertoare.create', $this->commonParameters() + [
            'item' => $invertor,
            'save_route' => route('ofertare.invertoare.update', $invertor),
        ]);
    }

    public function update(Request $request, Invertor $invertor)
    {
        $input = $request->validate($this->rules($invertor->id), [], $this->names());

        $invertor->update([
            'cod' => $input['cod'],
            'putere' => $input['putere'],
            'marca' => $input['marca'],
            'tip' => $input['tip'],
            'grid' => $input['grid'],
            'contor' => $input['contor'],
            'descriere' => $input['descriere'] ?? null,
        ]);

        return redirect()->route('ofertare.invertoare.update', $invertor->id)->with([
            'status' => __('Invertorul :cod a fost actualizat.', ['cod' => $invertor->cod])
        ]);
    }

    public function delete(Request $request, Invertor $invertor)
    {
        $invertor->delete();

        session()->flash('status', __('Invertorul :cod a fost sters.', ['cod' => $invertor->cod]));

        if($request->has('redirect_url')) {
            return redirect($request['redirect_url']);
        }
        return ['refresh' => true];
    }

    protected function rules($id = null)
    {
        return [
            'cod' => ['required', 'max:255'],
            'putere' => ['numeric', 'min:1'],
            'marca' => ['required', 'max:255'],
            'tip' => ['required', 'max:255'],
            'grid' => ['required', 'max:255'],
            'contor' => ['required', 'max:255'],
            'descriere' => ['nullable'],
        ];
    }

    protected function names()
    {
        return [
            'cod' => __('cod'),
            'putere' => __('putere'),
            'marca' => __('marca'),
            'tip' => __('tip'),
            'grid' => __('grid'),
            'contor' => __('contor'),
            'descriere' => __('descriere'),
        ];
    }

    protected function commonParameters()
    {
        return [
            'sectiune' => __('Invertoare'),
            'browse_route' => route('ofertare.invertoare.browse'),
        ];
    }
}
