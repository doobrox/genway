<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Middleware\VerifyCsrfToken;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/private', [HomeController::class, 'private'])->name('private');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/send', [HomeController::class, 'sendMailContact'])->name('contact.send');
Route::post('/judete/{judet}/localitati', [HomeController::class, 'localitati'])->name('localitati');
Route::post('/localitati/{judet?}', [HomeController::class, 'localitati'])->name('localitati.all');
Route::post('/localitati/html/{judet?}', [HomeController::class, 'localitatiWithHTML'])->name('localitati.html');
Route::get('/assets/imagini/{path}', [HomeController::class, 'images'])->where('path', '(.*)')->name('images');
Route::get('/assets/fisiere-tehnice/{path}', [HomeController::class, 'techFiles'])
    ->where('path', '(.*)')
    ->name('files.tech');
// Route::get('/assets/{path}', [HomeController::class, 'files'])->where('path', '(.*)')->name('files');
Route::post('/newsletter', [HomeController::class, 'newsletter'])->name('newsletter');

Route::prefix('formular')->group(function() {
    Route::post('/transfer-dosare', [FormController::class, 'storeTransferDosar'])->name('form.transfer.dosar');
    Route::post('/casa-verde', [FormController::class, 'storeInscriereCasaVerde'])->name('form.casa.verde');
    Route::post('/electric-up', [FormController::class, 'storeInscriereElectricUp'])->name('form.electric.up');
    Route::post('/ofertare', [FormController::class, 'storeOfertare'])->name('form.ofertare');
});

Route::prefix('cos')->group(function() {
    Route::get('/', [CartController::class, 'index'])->name('cart');
    // Route::get('/procesare_plata', [CartController::class, 'procesare'])->name('cart.result');
    Route::post('/adauga/voucher', [CartController::class, 'voucher'])->name('cart.voucher');
    Route::post('/sterge/voucher', [CartController::class, 'removeVoucher'])->name('cart.voucher.remove');
    Route::post('/schimba/curier', [CartController::class, 'courier'])->name('cart.courier');
    Route::post('/schimba/metoda-de-plata', [CartController::class, 'payment'])->name('cart.payment');
    Route::post('/adauga/detalii-comanda', [CartController::class, 'message'])->name('cart.message');
    Route::post('/adauga/{produs}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/scade/{produs}', [CartController::class, 'subtract'])->name('cart.subtract');
    Route::post('/sterge/{produs}', [CartController::class, 'remove'])->name('cart.remove');

    Route::middleware(['auth'])->group(function() {
        Route::post('/procesare-plata', [CartController::class, 'checkout'])
            ->middleware(['auth'])
            ->name('cart.checkout');
        Route::post('/procesare-plata/confirmare', [CartController::class, 'confirm'])
            ->withoutMiddleware([VerifyCsrfToken::class])
            ->name('cart.confirm');
    });
});

Route::get('/comanda/{comanda}', [CheckoutController::class, 'order'])
    ->name('checkout.order');
Route::prefix('checkout')->group(function() {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/calculare/transport', [CheckoutController::class, 'calculateShipping'])
        ->name('calculate.shipping');
    Route::post('/procesare-plata', [CheckoutController::class, 'checkout'])
        ->name('checkout.post');
    Route::post('/procesare-plata/confirmare', [CheckoutController::class, 'confirm'])
        ->withoutMiddleware([VerifyCsrfToken::class])
        ->name('cart.confirm');
});

Route::middleware(['auth'])->group(function() {
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profil/schimba-parola', [ProfileController::class, 'password'])
        ->name('profile.password');
    Route::post('/profil/schimba-parola', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');
    Route::get('/profil/istoric-comenzi', [ProfileController::class, 'orders'])
        ->name('profile.orders');
    Route::get('/profil/istoric-comenzi/comanda/{comanda}', [ProfileController::class, 'order'])
        ->name('profile.orders.show');
    Route::get('/profil/istoric-comenzi/comanda/{comanda}/factura-proforma/{stream?}', [ProfileController::class, 'pdf'])
        ->name('profile.orders.invoice');

    Route::post('/review/{slug}-{produs}', [ProductController::class, 'review'])
        ->where('slug', '[A-Za-z0-9_\-]+')->where('produs', '[0-9]+')->name('product.review');
});

Route::middleware(['ci_auth'])->group(function() {

    Route::group([
        'as' => 'admin.',
        'prefix' => 'admin',
        'namespace' => 'App\Http\Controllers\Admin',
    ], function() {
        Route::group([
            'as' => 'pagini.',
            'prefix' => 'pagini',
            'controller' => PaginaController::class,
        ], function() {
            Route::get('{pagina}/edit', function($pagina) {
                return redirect()->route('admin.pagini.editor', $pagina);
            })->name('edit');
            Route::post('{pagina}/edit', function($pagina) {
                return redirect()->route('admin.pagini.editor', $pagina);
            })->withoutMiddleware(['csrf']);
            Route::get('{pagina}/editor', 'editor')->name('editor');
            // Route::post('{pagina}/actualizare', 'update')->name('update');
        });
    });

    Route::group([
        'as' => 'ofertare.',
        'prefix' => 'ofertare',
        'middleware' => ['ci_auth'],
        'namespace' => 'App\Http\Controllers\Ofertare',
    ], function() {
        Route::group(['controller' => OfertareController::class], function() {
            Route::get('/views/{path}', 'views')->where('path', '(.*)')->name('views');
            Route::get('/assets/{path}', 'assets')->where('path', '(.*)')->name('assets')
                ->withoutMiddleware(['ci_auth']);
            Route::post('json/tehnicieni', 'tehnicieni')->name('json.tehnicieni');
        });

        Route::group([
            'as' => 'oferte.',
            'prefix' => 'oferte',
            'middleware' => ['can:oferte.view'],
            'controller' => OfertaController::class,
        ], function() {
            Route::post('/{oferta}/sectiune/{section}/creaza', 'createAfm')->name('create.afm')
                ->withoutMiddleware(['csrf']);
            Route::get('/{oferta}/sectiune/{section:nume}/generate/{sablon}/afm', 'generate')->name('generate.afm');
        });

        Route::group([
            'as' => 'programari.',
            'prefix' => 'programari',
            'middleware' => ['can:programari.view'],
            'controller' => ProgramareController::class,
        ], function() {
            Route::get('/', 'index')->name('browse');
            Route::post('/', 'index')->withoutMiddleware(['csrf']);
            Route::get('{an}/creaza', 'create')->name('create');
            Route::post('{an}/creaza', 'store')->name('store');
            Route::get('{programare}/editeaza', 'edit')->name('edit');
            Route::post('{programare}/editeaza', 'edit')->withoutMiddleware(['csrf']);
            Route::patch('{programare}/editeaza', 'update')->name('update');
            Route::delete('{programare}/sterge', 'delete')->name('delete');

            Route::get('{programare}/fisier/{slug}/{fisier}', 'download')->name('file.download');

            Route::get('{an}/formular/{formular}/creaza', 'create')->name('create.form');
            Route::post('{an}/formular/{formular}/creaza', 'create')->withoutMiddleware(['csrf']);
            Route::post('{an}/formular/{formular}/salveaza', 'store')->name('store.form');

            Route::post('echipa/{echipa?}', 'echipa')->name('echipa');
            Route::post('{an}/formulare/afm', 'formulare')->name('formulare');
        });

        Route::group([
            'as' => 'montaje.',
            'prefix' => 'executii',
            'middleware' => ['can:executii.view'],
            'controller' => ExecutieController::class,
        ], function() {
            Route::get('/', 'index')->name('browse');
            Route::post('/', 'index')->withoutMiddleware(['csrf']);
            Route::get('programare/{programare}/completeaza', 'create')->name('create');
            Route::post('programare/{programare}/completeaza', 'store')->name('store');
            Route::get('{executie}/editeaza', 'edit')->name('edit');
            Route::patch('{montaj}/editeaza', 'update')->name('update');
            Route::delete('{executie}/sterge', 'delete')->name('delete');

            Route::get('{executie}/fisier/{fisier}', 'get')->name('file.get');

        });

        Route::group([
            'as' => 'afm.',
            'prefix' => 'afm_2',
            'middleware' => ['can:afm.2021.view'],
            'controller' => AfmController::class,
        ], function() {
            Route::get('/test_afm', 'index')->name('browse');
            Route::get('/explicatii-tabel', 'tableDescription')->name('description');
            Route::post('/explicatii-tabel/coloana/{column:nume}', 'tableDescriptionColumnUpdate')->name('description.update.column');
            // Route::get('/test_afm2', 'index')->name('browse');
            Route::get('/{section?}', 'table')->name('browse');
            Route::post('/{section?}', 'table')->name('browse')->withoutMiddleware(['csrf']);
            // Route::post('/', 'index')->withoutMiddleware(['csrf']);

            Route::get('{section}/creaza', 'create')->name('create');
            // Route::post('formular/{section}/creaza_nou', 'create')->name('create_new_post')->withoutMiddleware('csrf');
            Route::get('formular/{section}/creaza_nou', 'create')->name('create_new_get');
            Route::post('{section}/creaza', 'store')->name('store');
            Route::get('formular/{section}/{formular}/editeaza', 'edit')->name('edit');
            Route::patch('formular/{section}/{formular}/editeaza', 'update')->name('update');
            Route::get('formular/{section}/{formular}/coloana/{column:nume}', 'getColumn')->name('get.column');
            Route::post('formular/{section}/{formular}/coloana/{column:nume}', 'updateColumn')->name('update.column');
            // Route::post('formular/{section}/{formular}/coloana/{column:nume}', 'updateColumn')
            //     ->name('update.column')
            //     ->withoutMiddleware(['csrf']);

            Route::get('/formular/coloana/{column:nume}/options/{section?}/{formular?}', 'getColumnDbOptions')->name('get.column.db.options');
            Route::get('formular/{section}/{formular}/generare/document/{slug}/{key?}', 'generateDocument')->name('generate.document');

            Route::get('formular/{section}/{formular}/generare-pv-predare-primire', 'generatePvPredarePrimire')
                ->name('generate.pv.predare.primire');
            // Route::post('formular/{section}/{formular}/generare-pv-predare-primire', 'generatePvPredarePrimire')
            //     ->name('generate.pv.predare.primire')
            //     ->withoutMiddleware(['csrf']);

            Route::get('formular/{section}/{formular}/generare-notificare-racordare/{distribuitor}', 'generateNotificareRacordare')
                ->name('generate.notificare.racordare');
            // Route::post('formular/{section}/{formular}/generare-notificare-racordare/{distribuitor}', 'generateNotificareRacordare')
            //     ->name('generate.notificare.racordare')
            //     ->withoutMiddleware(['csrf']);

            Route::get('formular/{section}/{formular}/generare-monofilara', 'generateMonofilara')
                ->name('generate.monofilara');
            // Route::post('formular/{section}/{formular}/generare-monofilara', 'generateMonofilara')
            //     ->name('generate.monofilara')
            //     ->withoutMiddleware(['csrf']);

            Route::get('formular/{section}/{formular}/generare-proces-verbal-pif/{distribuitor}', 'generateProcesVerbalPIF')
                ->name('generate.proces.verbal.pif');
            // Route::post('formular/{section}/{formular}/generare-proces-verbal-pif/{distribuitor}', 'generateProcesVerbalPIF')
            //     ->name('generate.proces.verbal.pif')
            //     ->withoutMiddleware(['csrf']);

            Route::get('formular/{section}/{formular}/generare-dosar-reglaje-pram', 'generateDosarReglajePram')
                ->name('generate.dosar.reglaje.pram');
            // Route::post('formular/{section}/{formular}/generare-dosar-reglaje-pram', 'generateDosarReglajePram')
            //     ->name('generate.dosar.reglaje.pram')
            //     ->withoutMiddleware(['csrf']);

            Route::get('formular/{section}/{formular}/generare-fisa-vizita', 'generateFisaVizita')
                ->name('generate.fisa.vizita');
            // Route::post('formular/{section}/{formular}/generare-fisa-vizita', 'generateFisaVizita')
            //     ->name('generate.fisa.vizita')
            //     ->withoutMiddleware(['csrf']);


            // Route::get('formular/{section}/{formular}/generare-fisa-vizita', 'generateFisaVizita')->name('generate.fisa.vizita');

            // Route::get('formular/{section}/{formular}/generare-contract-instalare', 'generateContractInstalare')->name('generate.contract.instalare');
            Route::get('formular/{section}/{formular}/generare-act-aditional-contract-instalare', 'generateActAditionalContractInstalare')->name('generate.act.aditional.contract.instalare');

            Route::get('formular/{section}/{formular}/generare-act-aditional-upgrade', 'generateActAditionalUpgrade')->name('generate.act.aditional.upgrade');
            Route::get('formular/{section}/{formular}/generare-anexa-factura', 'generateAnexaFactura')->name('generate.anexa.factura');
            // Route::get('formular/{section}/{formular}/generare-pv-receptie', 'generatePvReceptie')->name('generate.pv.receptie');
            // Route::get('formular/{section}/{formular}/generare-act-aditional-cupon', 'generateActAditionalCupon')->name('generate.act.aditional.cupon');

            Route::get('formular/{section}/{formular}/mail-data-estimata-montaj', 'sendMailDataEstimataMontaj')->name('mail.data.estimata.montaj');
            Route::get('formular/{section}/{formular}/mail-contract-instalare', 'sendMailContractInstalare')->name('mail.contract.instalare');

            Route::get('formular/{section}/{formular}/{view}-qr-factura/', 'generateQrCode')->name('viewqrfactura');
            Route::post('formular/{section}/{formular}/generare-qr-factura', 'generateQrCode')->name('generateqrfactura')->withoutMiddleware(['csrf']);


            // Route::get('formular/{section}/export-afm-table', 'exportAfmTable')->name('export.afm.table');
            // Route::post('formular/{section}/export-afm-table', 'exportAfmTable')
            //     ->name('export.afm.table')
            //     ->middleware('forget.parameters:user_email,key')
            //     ->withoutMiddleware(['csrf']);


        });

        Route::group([
            'as' => 'exporturi.',
            'prefix' => 'exporturi',
            'middleware' => ['can:exporturi_afm.view'],
            'controller' => ExportAfmController::class,
        ], function() {
            Route::get('{section?}', 'index')->name('browse');
            Route::post('{section?}', 'index')->withoutMiddleware(['csrf']);
            Route::get('descarca/{export:nume}', 'download')->name('download');
            Route::delete('sterge/{export:nume}/sterge', 'delete')->name('delete');
        });

        Route::group([
            'as' => 'rapoarte.',
            'prefix' => 'rapoarte',
            'middleware' => ['can:rapoarte_afm.view'],
            'controller' => RaportController::class,
        ], function() {
            Route::get('{section}/{filter?}', 'index')->name('browse');
            Route::post('{section}/{filter?}', 'index')->withoutMiddleware(['csrf']);
            Route::get('{section}/{filter}/export', 'export')->name('export');
        });

        Route::group([
            'as' => 'roles.',
            'prefix' => 'roluri',
            'middleware' => ['can:roluri.view'],
            'controller' => RoleController::class,
        ], function() {
            Route::get('/', 'index')->name('browse');
            Route::post('/', 'index')->withoutMiddleware(['csrf']);
            Route::get('creaza', 'create')->name('create');
            Route::post('creaza', 'store')->name('store');
            Route::get('editeaza/{role}', 'edit')->name('edit');
            Route::patch('editeaza/{role}', 'update')->name('update');
            Route::delete('sterge/{role}', 'delete')->name('delete');
        });

        Route::group([
            'as' => 'sabloane_afm.',
            'prefix' => 'sabloane-afm',
            'middleware' => ['can:sabloane_afm.view'],
            'controller' => SablonController::class,
        ], function() {
            Route::get('/', 'index')->name('browse');
            Route::post('/', 'index')->withoutMiddleware(['csrf']);
            Route::get('creaza', 'create')->name('create');
            Route::post('creaza', 'store')->name('store');
            Route::get('copiaza', 'copy')->name('copy');
            Route::post('copiaza', 'duplicate')->name('duplicate');
            Route::post('user/{user?}', 'sabloaneUser')->name('user.get');
            Route::get('{sablon}/editeaza', 'edit')->name('edit');
            Route::patch('{sablon}/editeaza', 'update')->name('update');
            Route::delete('{sablon}/sterge', 'delete')->name('delete');

            Route::get('{sablon}/check', 'check')->name('check');
        });

        Route::group([
            'as' => 'sarcini.',
            'prefix' => 'sarcini',
            'middleware' => ['can:sarcini.view'],
            'controller' => SarcinaController::class,
        ], function() {

            Route::group(['middleware' => ['can:sarcini.edit']], function() {
                Route::get('{an?}/creaza', 'create')->name('create');
                Route::post('{an?}/creaza', 'store')->name('store');
                Route::get('{sarcina}/editeaza', 'edit')->name('edit');
                Route::patch('{sarcina}/editeaza', 'update')->name('update');
                Route::post('{sarcina}/mesaj', 'mesajStore')->name('mesaj.store');
                Route::get('{sarcina}/check', 'check')->name('check');
            });

            Route::delete('{sarcina}/sterge', 'delete')->name('delete')->middleware(['can:sarcini.delete']);

            Route::get('{sarcina}/vizualizare', 'show')->name('show');
            Route::post('/search-afm/{section}/{id?}', 'searchAfm')->name('searchAfm');
            Route::get('/get-users-ofertare', 'getUsersOfertare')->name('getUsersOfertare');

            Route::get('{an?}', 'index')->name('browse');
            Route::post('{an?}', 'index')->name('browse')->withoutMiddleware(['csrf']);
        });

        Route::group([
            'as' => 'sabloane_documente.',
            'prefix' => 'sabloane-documente',
            'middleware' => ['can:sabloane_documente.view'],
            'controller' => SablonDocumentController::class,
        ], function() {

            Route::get('/', 'index')->name('browse');
            Route::post('/', 'index')->name('browse')->withoutMiddleware(['csrf']);

            Route::group(['middleware' => ['can:sabloane_documente.edit']], function() {
                Route::get('creaza', 'create')->name('create');
                Route::post('creaza', 'store')->name('store');
                Route::get('{sablon}/editor', 'editor')->name('editor');
                Route::get('{sablon}/editeaza', 'edit')->name('edit');
                Route::patch('{sablon}/editeaza', 'update')->name('update');
            });

            Route::delete('{sablon}/sterge', 'delete')->name('delete')->middleware(['can:sabloane_documente.delete']);
            Route::get('{sablon}/vizualizare', 'show')->name('show');
        });

        Route::group([
            'as' => 'componente.',
            'prefix' => 'componente',
            'middleware' => ['can:componente.view'],
            'controller' => ComponentaController::class,
        ], function() {

            Route::get('/', 'index')->name('browse');
            Route::post('/', 'index')->name('browse')->withoutMiddleware(['csrf']);

            Route::get('/get', 'get')->withoutMiddleware(['can:componente.view'])->name('get');
            Route::get('/get/html', 'getWithHTML')->withoutMiddleware(['can:componente.view'])->name('get.html');

            Route::group(['middleware' => ['can:componente.edit']], function() {
                Route::get('creaza', 'create')->name('create');
                Route::post('creaza', 'store')->name('store');
                Route::get('{componenta}/editeaza', 'edit')->name('edit');
                Route::patch('{componenta}/editeaza', 'update')->name('update');
            });

            Route::delete('{componenta}/sterge', 'delete')->name('delete')->middleware(['can:componente.delete']);
            Route::get('{componenta}/vizualizare', 'show')->name('show');
        });

        Route::group([
            'as' => 'invertoare.',
            'prefix' => 'invertoare',
            'middleware' => ['can:invertoare.view'],
            'controller' => InvertorController::class,
        ], function() {

            Route::get('/', 'index')->name('browse');
            Route::post('/', 'index')->name('browse')->withoutMiddleware(['csrf']);

            Route::get('/get', 'get')->withoutMiddleware(['can:invertoare.view'])->name('get');
            Route::get('/get/html', 'getWithHTML')->withoutMiddleware(['can:invertoare.view'])->name('get.html');

            Route::group(['middleware' => ['can:invertoare.edit']], function() {
                Route::get('creaza', 'create')->name('create');
                Route::post('creaza', 'store')->name('store');
                Route::get('{invertor}/editeaza', 'edit')->name('edit');
                Route::patch('{invertor}/editeaza', 'update')->name('update');
            });

            Route::delete('{invertor}/sterge', 'delete')->name('delete')->middleware(['can:invertoare.delete']);
            Route::get('{invertor}/vizualizare', 'show')->name('show');
        });

        Route::group([
            'as' => 'panouri.',
            'prefix' => 'panouri',
            'middleware' => ['can:panouri.view'],
            'controller' => PanouController::class,
        ], function() {

            Route::get('/', 'index')->name('browse');
            Route::post('/', 'index')->name('browse')->withoutMiddleware(['csrf']);

            Route::get('/get', 'get')->withoutMiddleware(['can:panouri.view'])->name('get');
            Route::get('/get/html', 'getWithHTML')->withoutMiddleware(['can:panouri.view'])->name('get.html');

            Route::group(['middleware' => ['can:panouri.edit']], function() {
                Route::get('creaza', 'create')->name('create');
                Route::post('creaza', 'store')->name('store');
                Route::get('{panou}/editeaza', 'edit')->name('edit');
                Route::patch('{panou}/editeaza', 'update')->name('update');
            });

            Route::delete('{panou}/sterge', 'delete')->name('delete')->middleware(['can:panouri.delete']);
            Route::get('{panou}/vizualizare', 'show')->name('show');
        });

        Route::group([
            'as' => 'aws.',
            'prefix' => 'aws',
            'controller' => FileController::class,
        ], function() {
            Route::post('/', 'index')->name('store')->withoutMiddleware(['csrf']);
            Route::get('get/{path}', 'get')->where('path', '(.*)')->name('get')->withoutMiddleware(['ci_auth']);
            Route::delete('delete/{fisier}', 'delete')->name('delete')->withoutMiddleware(['csrf']);
        });
    });
});

// routes that redirect to old admin
Route::get('/admin', [HomeController::class, 'admin'])->name('admin');
Route::get('/afm', [HomeController::class, 'afm'])->name('admin.afm');
Route::get('/ofertare', [HomeController::class, 'ofertare'])->name('admin.ofertare');

// product specific routes
Route::get('/produse', [CategoryController::class, 'index'])->name('products');
Route::get('/producator/{producator:slug}', [CategoryController::class, 'producator'])->name('producer');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/info/{pagina:slug}', [HomeController::class, 'page'])->name('page');
Route::get('/{categorie:slug}', [CategoryController::class, 'category'])->name('category');
Route::get('/{categorie:slug}/{slug}-{produs}', [CategoryController::class, 'product'])
    // ->where('slug', '[A-Za-z0-9_\-]+')->where('produs', '[0-9]+')
    ->where('slug', '[^\/]+')->where('produs', '[0-9]+')
    ->name('product');

// redirect routes
Route::get('/{categorie:slug}/{slug}-{produs}/{path}', [CategoryController::class, 'redirectProduct'])
    ->where('slug', '[^\/]+')->where('produs', '[0-9]+')->where('path', '.+')
    // ->where('slug', '[A-Za-z0-9_\-]+')->where('produs', '[0-9]+')->where('path', '[A-Za-z0-9_\-\/]+')
    ->name('product.redirect');
Route::get('/{categorie:slug}/{path}', [CategoryController::class, 'redirectCategory'])
    ->where('path', '.+')
    // ->where('path', '[A-Za-z0-9_\-\/]+')
    ->name('category.redirect');
// Route::get('/{path}', [CategoryController::class, 'redirect'])
//     ->where('path', '.+')
//     // ->where('path', '[A-Za-z0-9_\-\/]+')
//     ->name('general.redirect');

//Route::post('/ofertare/afm_2/formular/{id}/generare-qr-factura', 'YourController@yourMethod')->name('generate.qr.invoice')->withoutMiddleware(['csrf']);;


//Route::post('formular/{section}/{formular}/generare-qr-factura', 'generateQrCode')->name('generate.qr.factura')->withoutMiddleware(['csrf']);
