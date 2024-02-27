<?php

namespace App\Http\Controllers\Ofertare;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
	public function index(Request $request)
    {
        return view($this->pages('browse'), $this->commonParameters() + [
            'items' => $this->search($request)->paginate(25),
            'columns' => $this->viewColumns(),
            'add_route' => route($this->pages('create', 1)),
            'edit_route_text' => $this->pages('edit', 1),
            'delete_route_text' => $this->pages('delete', 2),
            'can_edit' => $this->permissions('edit'),
            'can_delete' => $this->permissions('delete')
        ]);
    }

    public function search(Request $request)
    {
        $query = $this->model()->query();
        $input = $request->input();
        foreach ($this->searchColumns() as $column) {
            if(!empty($input[$column])) {
                if(in_array('string', $this->rules()[$column] ?? [])) {
                    $query->where($column, 'like', '%'.$input[$column].'%');
                } else {
                    $query->where($column, $input[$column]);
                }
            }
        }
        return $query->orderByDesc('id');
    }

    public function get(Request $request)
    {
        return $this->search($request)->get();
    }

    public function getWithHTML(Request $request)
    {
        if($request) {
            $options = '';
            foreach($this->get($request) as $item) {
                $options .= '<option value="'.$item->id.'">'.$this->optionText($item).'</option>';
            }
        } else {
            $options = '<option value="">'.$this->defaultOptionText().'</option>';
        }
        return $request->ajax() ? response($options, 200) : $options;
    }

    public function create()
    {
        return view($this->pages('create'), $this->commonParameters() + [
            'save_route' => route($this->pages('create', 2)),
        ]);
    }

    public function store(Request $request)
    {
        return $this->save($request);
    }

    public function edit($item)
    {
        return view($this->pages('edit'), $this->commonParameters() + [
            'item' => $this->item($item),
            'save_route' => route($this->pages('edit', 2), $item),
        ]);
    }

    public function update(Request $request, $item)
    {
        return $this->save($request, $this->item($item));
    }

    public function save(Request $request, $item = null)
    {
        $input = $request->validate($this->rules($item->id ?? null), $this->messages(), $this->names());
        $model = $item ?? new ($this->model());
        $model->forceFill($input)->save();

        return redirect()->back()->with([
            'status' => $item ? $this->updateMessage($model) : $this->createMessage($model),
        ]);
    }

    public function delete(Request $request, $item)
    {
        $item = $this->item($item);
        $item->delete();

        return redirect()->back()->with(['status' => $this->deleteMessage($item)]);
    }


    protected function model()
    {
        return app(\App\Models\AfmForm::class);
    }

    protected function item($item)
    {
        $item = $this->model()->find($item);
        return $item ?? back()->withErrors(['not_found' => $this->notFoundMessage()])->throwResponse();
    }

    protected function section()
    {
        return '';
    }

    protected function basePath()
    {
        return 'ofertare.';
    }

    protected function viewPath($path = '')
    {
        return $this->basePath().$path;
    }

    protected function routePath($path = '')
    {
        return $this->basePath().$path;
    }

    protected function searchColumns()
    {
        return [];
    }

    protected function viewColumns()
    {
        $columns = [];
        $names = is_array($this->names()) ? $this->names() : [];
        foreach($this->searchColumns() as $column) {
            $columns[$column] = ucfirst($names[$column] ?? \Str::headline($column));
        }
        return $columns;
    }

    protected function createMessage($item)
    {
        return __('Creat cu succes.');
    }

    protected function updateMessage($item)
    {
        return __('Actualizat cu succes.');
    }

    protected function deleteMessage($item)
    {
        return __('Sters cu succes.');
    }

    protected function notFoundMessage()
    {
        return __('Entitatea nu a putut fi gasita.');
    }

    protected function defaultOptionText()
    {
        return __('Alege');
    }

    protected function optionText($item)
    {
        return $item->id ?? __('Alege');
    }

    protected function rules($id = null)
    {
        return [];
    }

    protected function messages()
    {
        return [];
    }

    protected function names()
    {
        return [];
    }

    protected function pages($index = null, $scope = 0)
    {
        // scopes: 0: view path, 1: view route name, 2: save route name
        $pages = [
            'browse' => [$this->viewPath('index'), $this->routePath('browse')],
            'create' => [$this->viewPath('create'), $this->routePath('create'), $this->routePath('store')], 
            'edit' => [$this->viewPath('create'), $this->routePath('edit'), $this->routePath('update')],
            'delete' => [null, null, $this->routePath('delete')],
            'show' => [$this->viewPath('show'), $this->routePath('show')]
        ];
        return $pages[$index][$scope] ?? $pages[$index] ?? $pages;
    }

    protected function permissions($index = null)
    {
    	$permissions = [
            'browse' => true,
            'create' => true, 
            'edit' => true,
            'delete' => true,
            'show' => true
        ];
        return $permissions[$index] ?? $permissions;
    }

    protected function commonParameters()
    {
        return [
            'sectiune' => $this->section(),
            'browse_route' => route($this->pages('browse', 1)),
        ];
    }
}