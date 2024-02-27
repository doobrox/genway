<?php

namespace App\Http\Controllers;

use App\Events\SendMails;
use App\Mail\TemplateEmail;
use App\Models\Banner;
use App\Models\Categorie;
use App\Models\Localitate;
use App\Models\Judet;
use App\Models\Pagina;
use App\Models\Produs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\SitemapGenerator;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'banners' => Banner::where('activ', 1)
                ->where('zona', 'HEADER')
                ->orderByDesc('data_adaugare')
                ->get(),
            'categorii' => Categorie::where('display_home', '1')
                ->with('subcategorii')->get(),
            'produseNoi' => Produs::produseNoi(),
            'produseRecomandate' => Produs::produsePromovate(),
        ]);
    }
    public function private()
    {

        // return view('private', [
        //     'pagina' => Pagina::find(65),
        // ]);
    }

    public function page(Pagina $pagina)
    {
        return view('template', [
            'pagina' => $pagina,
            'title' => $pagina->seo_title,
            'meta_description' => $pagina->meta_description,
            'keywords' => $pagina->meta_keywords,
        ]);
    }

    public function blog()
    {
        return view('blog', [
            'items' => Pagina::select('*')
                ->join('pagini_categorii', Pagina::getTableName().'.id', '=', 'pagini_categorii.id_pagina')
                ->where('id_categorie', 12) // blog
                ->paginate(20),
        ]);
    }

    public function contact()
    {
        $setari = setari([
            'EMAIL_CONTACT_PUBLIC',
            'FACTURARE_NUME_FIRMA',
            'FACTURARE_CUI',
            'FACTURARE_CAPITAL_SOCIAL',
        ]);
        return view('contact', [
            'contact_mails' => explode(';', $setari['EMAIL_CONTACT_PUBLIC']),
            'nume_firma' => $setari['FACTURARE_NUME_FIRMA'],
            'capital_social' => $setari['FACTURARE_CAPITAL_SOCIAL'],
            'cod_fiscal' => $setari['FACTURARE_CUI'],
        ]);
    }

    public function sendMailContact(Request $request)
    {
        $inputs = Validator::make($request->all(), [
            'nume' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefon' => ['nullable', 'string', 'max:20'],
            'subiect' => ['required', 'string', 'max:255'],
            'mesaj' => ['required', 'string', 'max:1000'],
            'botcheck' => ['nullable','in:null'],
            'termenii_de_prelucrare' => ['required', 'accepted']
        ],[
            'botcheck.*' => __('Mesajul nu a putut fi trimis, incercati mai tarziu.')
        ])->validate();

        try {
            $details = setari(['TITLU_NUME_SITE','TITLU_NUME_SITE_SCURT','EMAIL_CONTACT']);
            Mail::to($details['EMAIL_CONTACT'])
                ->send(new TemplateEmail([
                    'from' => ['email' => $details['EMAIL_CONTACT'], 'name' => $details['TITLU_NUME_SITE']],
                    'subject' => __('Formular de contact :site', ['site' => $details['TITLU_NUME_SITE_SCURT']]),
                    'body' => __('Nume: :nume<br>Email: :email<br>Telefon: :telefon<br>Subiect: :subiect<br><br>', [
                        'nume' => strip_tags($inputs['nume']),
                        'email' => strip_tags($inputs['email']),
                        'telefon' => strip_tags($inputs['telefon']),
                        'subiect' => strip_tags($inputs['subiect']),
                    ]).strip_tags(nl2br($inputs['mesaj'])),
                ])
            );
        } catch(\Exception $e) { \Log::info($e); }
        return back()->with(['status' => __('Emailul a fost trimis.')], 200);
    }

    public function localitati(Judet $judet)
    {
        return Localitate::where('id_judet', $judet->id)->get();
    }

    public function localitatiWithHTML(Judet $judet = null)
    {
        if($judet) {
            $options = '';
            foreach($this->localitati($judet) as $localitate) {
                $options .= '<option value="'.$localitate->id.'">'.$localitate->nume.'</option>';
            }
        } else {
            $options = '<option value="">'.__('Selecteaza localitate').'</option>';
        }
        return $options;
    }

    public function newsletter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nume' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'termenii_de_prelucrare' => ['required', 'accepted']
        ]);

        if($validator->fails()) {
            return response(['alert' => 'error', 'message' => $validator->messages()->first()], 200);
        }
        $inputs = $validator->validate();
        $inputs['activ'] = 1;
        $inputs['ip'] = $request->ip();
        unset($inputs['termenii_de_prelucrare']);

        $abonat = DB::table('newsletters_abonati')->where('email', $inputs['email'])->first();
        if($abonat && $abonat->activ == '0') {
            DB::table('newsletters_abonati')->where('email', $inputs['email'])->update(['activ' => 1]);
        } elseif($abonat == null) {
            DB::table('newsletters_abonati')->insert($inputs);

            try {
                // prepare data for emails
                $details = setari(['TITLU_NUME_SITE','TITLU_NUME_SITE_SCURT','EMAIL_CONTACT']);
                // send email
                SendMails::dispatch($inputs, $details, [4]);

            } catch(\Exception $e) { \Log::info($e); }
        } else {
            return response(['alert' => 'success', 'message' => __('Emailul este deja abonat.')], 200);
        }
        return response(['alert' => 'success', 'message' => __('Ati fost abonat cu succes la newsletter.')], 200);
    }

    public function images($path, $stream = 1)
    {
        return $this->files('images/'.$path);
    }

    public function techFiles($path, $stream = false)
    {
        return $this->files('images/produse/fisiere_tehnice/'.$path);
    }

    protected function files($path, $stream = 1)
    {
        if (Storage::disk('old_site')->exists($path)) {
            return $stream
                ? $stream == 1 ? Storage::disk('old_site')->response($path) : Storage::disk('old_site')->get($path)
                : Storage::disk('old_site')->download($path);
        }
        return $stream ? abort('404') : redirect()->back()->withErrors([
            'error' => __('Fișierul nu există'),
        ]);
    }

    public function admin()
    {
        return redirect((env('OLD_SITE_NAME').'admin'));
    }

    public function ofertare()
    {
        return redirect((env('OLD_SITE_NAME').'ofertare'));
    }

    public function afm()
    {
        return redirect((env('OLD_SITE_NAME').'afm'));
    }
}
