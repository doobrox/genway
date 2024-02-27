<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Comentariu;
use App\Models\Localitate;
use App\Models\Judet;
use App\Models\Producator;
use App\Models\Produs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Blade;

class CategoryController extends Controller
{
    public function index(Request $request, Categorie $categorie = null, $item = null)
    {
        $produse = $categorie
            ? $categorie->produse()->image()
            : Produs::image();

        $produse = $this->search($request->input('search'), $produse, $categorie);

        if($request->has('only_prod')) { return $this->produseWithHtml($produse);}

        return view('categorie', [
            'route' => $request->url(),
            'min' => $produse->min(Produs::campPret()),
            'max' => $produse->max(Produs::campPret()),
            'pagination' => $this->paginationArray(),
            'sort' => $this->sortArray(),
            'search' => $request->input('search'),
            'produse' => $produse,
        ] + ($categorie ? [
            'categorie' => $categorie,
            'subcategorii' => $categorie->subcategorii,
        ] : []) + $this->metaInfo($item ?? $categorie, $categorie, $produse));
    }

    public function category(Request $request, $categorie)
    {
        $slug = $categorie;
        $categorie = Categorie::firstWhere('slug', $categorie);
        // search for product if category doesn't exists
        if(!$categorie) {
            preg_match('/([A-Za-z0-9_\-]+)\-([0-9]+)/', $slug, $segments);
            $product = Produs::find($segments[2] ?? null);
            // return $product ? $this->product(null, $segments[1], $product) : redirect()->route('products', null, 301);
            return $product ? $this->product(null, $segments[1], $product) : abort(404);
        }
        // if($categorie->id == 138) {
        //     return redirect()->route('products', null, 301);
        // }
        return $this->index($request, $categorie);
    }

    public function producator(Request $request, $producator)
    {
        // get producer if it is not already an object of class Producator
        $producator = $producator instanceof Producator ? $producator : Producator::firstWhere('slug', $producator);
        // if a producer is not found redirect to products page
        if(!$producator) {
            return $this->redirect($request);
        }

        // merge the default provider tot the search request
        $input = request()->all();
        $input['search']['provider'] = array_unique(
            array_merge([(string)$producator->id], $request->input('search.provider', []))
        );
        $request->merge($input);
        
        return $this->index($request, null);
    }

    public function product($categorie = null, $slug, $produs = null)
    {
        // get product if it is not already an object of class Produs
        $produs = $produs instanceof Produs ? $produs : Produs::find($produs);
        // keep category parameter for later check
        $categorie_slug = $categorie;
        // get category if it is not null or an object of class Categorie
        $categorie = $categorie 
            ? ($categorie instanceof Categorie ? $categorie : Categorie::firstWhere('slug', $categorie)) 
            : null;

        // if a product is not found redirect to the category
        // or to products page if a category is not found
        if(!$produs) {
            return $categorie ? redirect()->route('category', $categorie, 301) : redirect()->route('products', null, 301);
        }
        // if a category was passed as parameter and is not active or was not found
        if($categorie === null && $categorie_slug !== null) {
            return redirect()->route('category', $produs->slug.'-'.$produs->id, 301);
        }
        // if a category is found but the product is not part of that category
        // redirect to the category page
        if($categorie && !$categorie->produse()->where($produs->getTable().'.id', $produs->id)->exists()) {
            return redirect()->route('category', $categorie, 301);
        }

        $categorie = $categorie ?: $produs->categorie;
        return $this->checkProductSlug($produs, $slug) ? view('produs', [
            'breadcrumbs' => $categorie ? $categorie->breadcrumbs($produs) : Categorie::breadcrumb($produs),
            'produs' => $produs,
            'producator' => $produs->producator,
            'galerie' => $produs->galerie,
            'fisiere' => $produs->fisiere_tehnice,
            'comentarii' => $produs->comentarii,
            'recomandate' => $produs->recomandate()->limit(5)->get(),
            'asemanatoare' => $categorie ? $categorie->produse()->whereNot('produse.id', $produs->id)->image()->limit(5)->get() : collect([]),
        ] + $this->metaInfo($produs, $categorie)) : ($categorie 
            ? redirect()->route('category', $categorie, 301) 
            : redirect()->route('products', null, 301)
        );
    }

    public function checkProductSlug(Produs $produs, string $slug)
    {
        return \Str::slug($produs->nume, '-', 'en', []) === \Str::slug($slug, '-', 'en', []);
        // return \Str::slug($produs->nume) === $slug;
    }

    public function review(Request $request, $slug, Produs $produs)
    {
        $inputs = Validator::make($request->all() + ['ip' => $request->ip()],[
            'evaluare' => ['required','integer','min:1','max:5'],
            'comentariu' => ['required','string','max:1500'],
            'ip' => ['required', 'ip', function ($attribute, $value, $fail) use ($produs) {

                if (
                    Comentariu::where('id_produs', $produs->id)
                        ->where('ip', $value)
                        ->where('created_at', '>=', Carbon::parse('-24 hours'))
                        ->exists()
                ) {
                    $fail(__('Puteti adauga doar un comentariu / produs o data la 24 de ore.'));
                }
            }],
        ],[
            'ip.required' => __('S-a intamplat ceva neasteptat, va rog incercati mai tarziu.'),
            'ip.ip' => __('S-a intamplat ceva neasteptat, va rog incercati mai tarziu.'),
        ])->validateWithBag('review');

        Comentariu::create([
            'id_user' => auth()->id(),
            'id_produs' => $produs->id,
            'nota' => $inputs['evaluare'],
            'comentarii' => $inputs['comentariu'],
            'ip' => $inputs['ip'],
            'data_adaugare' => now(),
            'activ' => 1,
        ]);

        return back()->with([
            'review' => __('Multumim pentru review. Va mai asteptam.'),
        ]);
    }

    public function redirect(Request $request, $path = null)
    {
        return redirect()->route('products', null, 301);
    }

    public function redirectCategory(Request $request, $categorie, $path = null)
    {
        return redirect()->route('category', $categorie, 301);
    }

    public function redirectProduct(Request $request, $categorie, $slug, $produs, $path = null)
    {
        return redirect()->route('product', [$categorie, $slug, $produs], 301);
    }

    protected function produseWithHtml($produse)
    {
        $html = '';
        if(count($produse) > 0) {
            foreach ($produse as $produs) {
                $html .= Blade::render('<x-product class="col-xl-3 col-md-4 col-sm-6 col-12"
                    :product="$product" />', [
                    'product' => $produs,
                ]);
            }
            $html .= '<nav aria-label="Page navigation" class="mt-3">'.$produse->links().'</nav>';
        } else {
            $html = '<p>'.__('Nici un produs gasit. Mariti aria de cautare.').'</p>';
        }
        return $html;
    }

    protected function search($filters, $query, $categorie)
    {
        if(isset($filters['c'])) {
            $query->where(function($subquery) use ($filters) {
                $subquery->where('produse.nume', 'like', '%'.$filters['c'].'%')
                    ->orWhere('produse.descriere', 'like', '%'.$filters['c'].'%')
                    ->orWhere('produse.cod_ean13', 'like', '%'.$filters['c'].'%');
            });
        }
        if(isset($filters['provider'])) {
            $query->whereIn('produse.id_producator', $filters['provider']);
        }
        if(isset($filters['price'])) {
            $pret = explode('-', $filters['price']);
            $camp = Produs::campPret();
            $query->whereRaw('(1.19 * produse.'.$camp.') >= '. $pret[0]);
            if(isset($pret[1])) {
                $query->whereRaw('(1.19 * produse.'.$camp.') <= '. $pret[1]);
            }
        }
        if(isset($filters['rating'])) {
            $query->withAvg('comentarii', 'nota')
                ->having('comentarii_avg_nota', '>=', $filters['rating']);
            if($categorie) {
                $query->withoutGlobalScope('all');
            }
        }
        if(isset($filters['discount'])) {
            $query->where('produse.reducere_tip', '>', '0');
        }
        if(isset($filters['sort']) && $order = $this->sortArray($filters['sort'])) {
            $query->orderBy($order[0], $order[1]);
        } elseif($order = $this->sortArray('0')) {
            $query->orderBy($order[0], $order[1]);
        }

        return $query->paginate($filters['pag'] ?? 20)->appends(request()->except('only_prod'));
    }

    protected function sortArray($index = null)
    {
        // frontend value => [table column, 'order', 'frontend text'],
        $array = [
            0 => ['produse.ordonare_popular', 'asc', __('Popularitate')],
            1 => ['produse.nume', 'asc', __('Denumire A->Z')],
            2 => ['produse.nume', 'desc', __('Denumire Z->A')],
            3 => ['produse.data_adaugare', 'asc', __('Cele mai noi')],
            4 => ['produse.data_adaugare', 'desc', __('Cele mai vechi')],
            5 => ['produse.'.Produs::campPret(), 'asc', __('Pret crescator')],
            6 => ['produse.'.Produs::campPret(), 'desc', __('Pret descrescator')],
        ];
        return $index != null ? $array[is_int((int)$index) ? $index : 0] : $array;
    }

    protected function paginationArray($index = null)
    {
        return [8,20,40,80,100];
    }

    protected function metaInfo($item = null, $parinte = null, $items = null)
    {
        return [
            'meta_title' => $item ? $this->getMetaTitle($item, $parinte) : null,
            'meta_description' => $item ? $this->getMetaDescription($item, $parinte, $items) : null,
            'meta_keywords' => $item->meta_keywords ?? null
        ];
    }

    protected function getMetaTitle($item, $parinte = null)
    {
        return \Str::limit($item->seo_title ?: implode(' - ', array_filter([$item->nume, $parinte->nume ?: null])) ?: null, 60, '');
    }

    protected function getMetaDescription($item, $parinte = null, $items = null)
    {
        return \Str::limit(
            $item->meta_description ?: implode(', ', array_filter([
                $item->nume, 
                $item->descriere ?: null, 
                ...($items ? $items->pluck('nume')->toArray() : [])
            ])), 160, '');
    }
}
