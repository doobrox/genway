<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "index_page";
$route['404_override'] = '_404';

$route['admin(.*)'] = 'admin$1';

$route['contact(.*)'] = 'contact$1';
$route['login(.*)'] = 'login$1';
$route['cos(.*)'] = 'cos$1';
$route['profilul_meu(.*)'] = 'profilul_meu$1';
$route['recuperare_parola(.*)'] = 'recuperare_parola$1';
$route['istoric_comenzi(.*)'] = 'istoric_comenzi$1';
$route['noutati'] = 'categorii/index////2';
$route['promotii'] = 'categorii/index///1';
$route['index_page/ajax_get_localitati(.*)'] = 'index_page/ajax_get_localitati$1';
$route['index_page/ajax_get_taxa_expediere(.*)'] = 'index_page/ajax_get_taxa_expediere$1';
$route['index_page/ajax_get_total_cos(.*)'] = 'index_page/ajax_get_total_cos$1';
$route['index_page/ajax_get_produs_by_filtre(.*)'] = 'index_page/ajax_get_produs_by_filtre$1';
$route['index_page/ajax_verifica_stoc(.*)'] = 'index_page/ajax_verifica_stoc$1';
$route['index_page/ajax_popup_stoc_indisponibil(.*)'] = 'index_page/ajax_popup_stoc_indisponibil$1';
$route['index_page/ajax_popup_stoc_indisponibil_submit(.*)'] = 'index_page/ajax_popup_stoc_indisponibil_submit$1';
$route['index_page/ajax_get_discount_plata_op(.*)'] = 'index_page/ajax_get_discount_plata_op$1';

$route['info/([a-zA-Z0-9\-]+)'] = 'pagina/index/$1';
$route['pagina(.*)'] = 'pagina$1';


$route['.*/([a-zA-Z0-9\-]+)/.*-([0-9]+)/adauga-comentariu'] = 'produs/adauga_comentariu/$1/$2';
$route['([a-zA-Z0-9\-]+)/.*-([0-9]+)/adauga-comentariu'] = 'produs/adauga_comentariu/$1/$2';

$route['producator/([a-zA-Z\-]+)'] = 'categorii/index//$1';

$route['.*/([a-zA-Z0-9\-]+)/.*-([0-9]+)'] = 'produs/index/$1/$2';
$route['([a-zA-Z0-9\-]+)/.*-([0-9]+)'] = 'produs/index/$1/$2';

$route['([a-zA-Z0-9\-]+)'] = 'categorii/index/$1';
$route['.*/([a-zA-Z0-9\-]+)'] = 'categorii/index/$1';



/* End of file routes.php */
/* Location: ./application/config/routes.php */