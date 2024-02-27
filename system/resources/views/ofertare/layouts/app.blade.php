<!DOCTYPE html>
<html lang="en"><!--<![endif]--><!-- BEGIN HEAD --><head>
	<head>
        <meta charset="utf-8">
        <title>{{ $sectiune }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <meta content="#1 selling multi-purpose bootstrap admin theme sold in themeforest marketplace packed with angularjs, material design, rtl support with over thausands of templates and ui elements and plugins to power any type of web applications including saas and admin dashboards. Preview page of Theme #1 for blank page layout" name="description">
        <meta content="" name="author">
    	<meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css">
        {{-- <link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"> --}}
        @yield('pre-styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" integrity="sha512-ZV9KawG2Legkwp3nAlxLIVFudTauWuBpC10uEafMHYL0Sarrz5A7G79kXh5+5+woxQ5HM559XX2UZjMJ36Wplg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.min.css" integrity="sha512-QKC1UZ/ZHNgFzVKSAhV5v5j73eeL9EEN289eKAEFaAjgAiobVAnVv/AGuPbXsKl1dNoel3kNr6PYnSiTzVVBCw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css">
        <link href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="https://www.old.genway.ro/application/views/ofertare/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css">
        <link href="https://www.old.genway.ro/application/views/ofertare/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css">
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="https://www.old.genway.ro/application/views/ofertare/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css">
        <link href="https://www.old.genway.ro/application/views/ofertare/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color">
        <link href="https://www.old.genway.ro/application/views/ofertare/assets/layouts/layout/css/custom.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" type="text/css">
		<link rel="stylesheet" href="https://www.old.genway.ro/application/views/ofertare/assets/global/css/custom.css" type="text/css">
        <!-- END THEME LAYOUT STYLES -->
        @yield('styles')
        <link rel="shortcut icon" href="https://www.old.genway.ro/application/views/favicon.ico">
	</head>
    <!-- END HEAD -->
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
					<!-- BEGIN LOGO -->
					<div class="page-logo">
						<a href="https://www.old.genway.ro/admin" class="logo-default">
							Administrare
						</a>
						<div class="menu-toggler sidebar-toggler">
							<span></span>
						</div>
					</div>
					<!-- END LOGO -->
					<!-- BEGIN RESPONSIVE MENU TOGGLER -->
					<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
						<span></span>
					</a>
					<!-- END RESPONSIVE MENU TOGGLER -->
					<!-- BEGIN TOP NAVIGATION MENU -->
					<div class="top-menu">
						<ul class="nav navbar-nav pull-right">
							<!-- BEGIN NOTIFICATION DROPDOWN -->
							<!-- BEGIN USER LOGIN DROPDOWN -->
							<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
							<li class="dropdown dropdown-user">
								<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
									<span class="username username-hide-on-mobile"> {{ auth()->user()->nume }} {{ auth()->user()->prenume }} </span>
									<i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu dropdown-menu-default">
									<li>
										<a href="https://www.old.genway.ro/profilul_meu">
											<i class="icon-user"></i> Profilul meu </a>
									</li>
									<li class="divider"> </li>
									<li>
										<a href="https://www.old.genway.ro/ofertare/utilizatori/logout">
											<i class="icon-key"></i> Deconectare </a>
									</li>
								</ul>
							</li>
							<!-- END USER LOGIN DROPDOWN -->
						</ul>
					</div>
					<!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
				<div class="page-sidebar-wrapper">
					<!-- BEGIN SIDEBAR -->
					<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
					<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
					<div class="page-sidebar navbar-collapse collapse">
						<!-- BEGIN SIDEBAR MENU -->
						<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
						<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
						<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
						<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
						<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
						<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
						<ul class="page-sidebar-menu page-header-fixed " data-keep-expanded="false" data-auto-scroll="false" data-slide-speed="200" style="padding-top: 20px">
							<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
							{{-- <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
							<li class="sidebar-toggler-wrapper hide">
								<div class="sidebar-toggler">
									<span></span>
								</div>
							</li>
							<!-- END SIDEBAR TOGGLER BUTTON --> --}}
							@if(auth()->user()->can('oferte.view'))
								<li class="nav-item ">
									<a href="https://www.old.genway.ro/ofertare/oferte" class="nav-link nav-toggle">
										<i class="fa fa-list"></i>
										<span class="title">Oferte</span>
									</a>
								</li>
							@endif
							@if(auth()->user()->can('contracte.*'))
								<li class="nav-item ">
									<a href="https://www.old.genway.ro/ofertare/contracte" class="nav-link nav-toggle">
										<i class="fa fa-edit"></i>
										<span class="title">Contracte</span>
									</a>
								</li>
							@endif
							@if(auth()->user()->can('avarii.*'))
								<li class="nav-item ">
									<a href="https://www.old.genway.ro/ofertare/avarii" class="nav-link nav-toggle">
										<i class="fa fa-wrench"></i>
										<span class="title">Avarii</span>
									</a>
								</li>
							@endif
							@if(auth()->user()->can('personal_teren.*'))
								<li class="nav-item ">
									<a href="https://www.old.genway.ro/ofertare/personal_teren" class="nav-link nav-toggle">
										<i class="fa fa-briefcase"></i>
										<span class="title">Personal teren</span>
									</a>
								</li>
							@endif
							@if(auth()->user()->can('clienti.*'))
								<li class="nav-item ">
									<a href="https://www.old.genway.ro/ofertare/clienti" class="nav-link nav-toggle">
										<i class="fa fa-users"></i>
										<span class="title">Clienti</span>
									</a>
								</li>
							@endif
							@if(auth()->user()->can('furnizori.*'))
								<li class="nav-item ">
									<a href="https://www.old.genway.ro/ofertare/firme" class="nav-link nav-toggle">
										<i class="fa fa-building-o"></i>
										<span class="title">Furnizori</span>
									</a>
								</li>
							@endif
							@if(auth()->user()->can('compartimente.*'))
								<li class="nav-item ">
									<a href="https://www.old.genway.ro/ofertare/compartimente" class="nav-link nav-toggle">
										<i class="fa fa-building-o"></i>
										<span class="title">Compartimente</span>
									</a>
								</li>
							@endif
							@if(auth()->user()->can('utilizatori.*'))
								<li class="nav-item ">
									<a href="https://www.old.genway.ro/ofertare/utilizatori" class="nav-link nav-toggle">
										<i class="fa fa-user"></i>
										<span class="title">Utilizatori</span>
									</a>
								</li>
							@endif
							@if(auth()->user()->can('roluri.*'))
								<li class="nav-item {{ Request::is('ofertare/roluri*') ? 'active open' : '' }}">
									<a href="{{ route('ofertare.roles.browse') }}" class="nav-link nav-toggle">
										<i class="fa fa-users"></i>
										<span class="title">Roluri</span>
									</a>
								</li>
							@endif
							@if(auth()->user()->can('tehnicieni.*'))
				                <li class="nav-item ">
				                    <a href="https://www.old.genway.ro/ofertare/tehnicieni" class="nav-link nav-toggle">
				                        <i class="fa fa-user"></i>
				                        <span class="title">Tehnicieni</span>
				                    </a>
				                </li>
							@endif
							@if(auth()->user()->can('salarizare.*'))
					            <li class="nav-item ">
					                <a href="https://www.old.genway.ro/ofertare/salarizare" class="nav-link nav-toggle">
					                    <i class="fa fa-user"></i>
					                    <span class="title">Salarizare</span>
					                </a>
					            </li>
							@endif
							@if(auth()->user()->can('statistici.*'))
					            <li class="nav-item ">
					                <a href="https://www.old.genway.ro/ofertare/statistici" class="nav-link nav-toggle">
					                    <i class="fa fa-bar-chart-o"></i>
					                    <span class="title">Statistici</span>
					                </a>
					            </li>
							@endif
							@if(auth()->user()->can('programari.*'))
					            <li class="nav-item {{ Request::is('ofertare/programari*') ? 'active open' : '' }}">
					                <a href="{{ route('ofertare.programari.browse') }}" class="nav-link nav-toggle">
					                    <i class="fa fa-calendar"></i>
					                    <span class="title">Programari</span>
					                </a>
					            </li>
							@endif
							@if(auth()->user()->can('executii.*'))
					            <li class="nav-item {{ Request::is('ofertare/executii*') ? 'active open' : '' }}">
					                <a href="{{ route('ofertare.montaje.browse') }}" class="nav-link nav-toggle">
					                    <i class="fa fa-briefcase"></i>
					                    <span class="title">Executii</span>
					                </a>
					            </li>
							@endif
							@if(auth()->user()->can('afm.2021.view'))
					            <li class="nav-item {{ Request::is('ofertare/afm_2*') ? 'active open' : '' }}">
					                <a href="javascript:void(0)" class="nav-link nav-toggle">
					                    <i class="fa fa-file-text-o"></i>
					                    <span class="title">AFM</span>
					                    <span class="arrow {{ Request::is('ofertare/afm_2*') ? 'open' : '' }}"></span>
					                </a>
					                <ul class="sub-menu">
                                        {{-- <li class="{{ Request::is('ofertare/afm_2/2021') ? 'active' : '' }}">
                                            <a href="{{ route('ofertare.afm.browse', 2021) }}?status=6"><span class="nav-list-link">2021</span></a>
                                        </li> --}}
                                        @foreach([
					                		'2021' => '2021',
					                		'2023' => '2023',
					                		'fonduri-proprii' => 'Fonduri proprii'
					                	] as $section => $title)
                                            <li class="{{
					                        	Request::is('ofertare/afm_2') && $section == '2023'
					                        	|| Request::is('ofertare/afm_2/'.$section)
					                        	? 'active' : ''
					                        }}">
					                        	<a href="{{ route('ofertare.afm.browse', $section) }}"><span class="nav-list-link">{{ $title }}</span></a>
					                        </li>
					                    @endforeach
				                        {{-- <li class="{{ Request::is('ofertare/afm_2/formular/2021*') ? 'active' : '' }}">
				                        	<a href="https://www.old.genway.ro/ofertare/afm?dosar_aprobat=1">
							                    <span class="nav-list-link">2021</span>
							                </a>
				                        </li>
				                        <li class="">
				                        	<a href="https://www.old.genway.ro/ofertare/afm/index/2023">
							                    <span class="nav-list-link">2023</span>
							                </a>
				                        </li>
				                        <li class="">
				                        	<a href="https://www.old.genway.ro/ofertare/afm/index/fonduri-proprii">
							                    <span class="nav-list-link">Fonduri proprii</span>
							                </a>
				                        </li> --}}
				                    </ul>
					            </li>
							@endif
							@if(auth()->user()->can('exporturi_afm.view'))
					            <li class="nav-item {{ Request::is('ofertare/exporturi*') ? 'active open' : '' }}">
					                <a href="javascript:void(0)" class="nav-link nav-toggle">
					                    <i class="fa fa-file-excel-o"></i>
					                    <span class="title">Exporturi AFM</span>
					                    <span class="arrow {{ Request::is('ofertare/exporturi*') ? 'open' : '' }}"></span>
					                </a>
					                <ul class="sub-menu">
					                	@foreach([
					                		'2021' => '2021',
					                		'2023' => '2023',
					                		'fonduri-proprii' => 'Fonduri proprii'
					                	] as $section => $title)
					                        <li class="{{
					                        	Request::is('ofertare/exporturi') && $section === '2021'
					                        	|| Request::is('ofertare/exporturi/'.$section)
					                        	? 'active' : ''
					                        }}">
					                        	<a href="{{ route('ofertare.exporturi.browse', $section) }}"><span class="nav-list-link">{{ $title }}</span></a>
					                        </li>
					                    @endforeach
				                    </ul>
					            </li>
							@endif
							@if(auth()->user()->can('rapoarte_afm.view'))
					            <li class="nav-item {{ Request::is('ofertare/rapoarte*') ? 'active open' : '' }}">
					                <a href="javascript:void(0)" class="nav-link nav-toggle">
					                    <i class="fa fa-map"></i>
					                    <span class="title">Rapoarte AFM</span>
					                    <span class="arrow {{ Request::is('ofertare/rapoarte*') ? 'open' : '' }}"></span>
					                </a>
					                <ul class="sub-menu">
					                	@foreach([
					                		'2021' => '2021',
					                		'2023' => '2023',
					                		'fonduri-proprii' => 'Fonduri proprii'
					                	] as $section => $title)
					                		<li class="nav-item {{ Request::is('ofertare/rapoarte/'.$section.'*') ? 'active open' : '' }}">
					                        	<a href="javascript:void(0)" class="nav-link nav-toggle">
						                        	<span class="nav-list-link title">{{ $title }}</span>
							                    	<span class="arrow {{ Request::is('ofertare/rapoarte/'.$section.'*') ? 'open' : '' }}"></span>
						                    	</a>

					                        	<ul class="sub-menu">
					                        		@foreach([
								                		'evenimente' => 'per evenimente',
								                		'stare-montaj' => 'per stare montaj',
								                		'situatie-dosar' => 'per situatie dosar',
								                		'data-alegere-instalator' => 'per data alegere instalator',
								                		'status-aprobare-dosar' => 'per aprobare dosar',
								                		'contract-instalare' => 'per contract instalare',
								                		'inginer-vizita' => 'per inginer vizita',
								                		'schita-amplasare-panouri' => 'per schita realizata',
								                		'verificare-schita-panouri' => 'per schita verificata',
								                		'programare-montaj' => ['per programare montaj', 'only' => ['2023']],
								                	] as $filter => $name)
								                		@if(!is_array($name) || (in_array($section, $name['only']) && $name = $name[0]))
									                		<li class="{{ Request::is('ofertare/rapoarte/'.$section.'') || Request::is('ofertare/rapoarte/'.$section.'/'.$filter) ? 'active' : '' }}">
									                        	<a href="{{ route('ofertare.rapoarte.browse', ['section' => $section, 'filter' => $filter]) }}"><span class="nav-list-link">{{ $name }}</span></a>
									                        </li>
									                    @endif
								                    @endforeach
							                        {{-- <li class="{{ Request::is('ofertare/rapoarte/'.$section.'') || Request::is('ofertare/rapoarte/'.$section.'/stare-montaj') ? 'active' : '' }}">
							                        	<a href="{{ route('ofertare.rapoarte.browse', ['section' => $section, 'filter' => 'stare-montaj']) }}"><span class="nav-list-link">per stare montaj</span></a>
							                        </li>
							                        <li class="{{ Request::is('ofertare/rapoarte/'.$section.'/situatie-dosar') ? 'active' : '' }}">
							                        	<a href="{{ route('ofertare.rapoarte.browse', ['section' => $section, 'filter' => 'situatie-dosar']) }}"><span class="nav-list-link">per situatie dosar</span></a>
							                        </li>
							                        <li class="{{ Request::is('ofertare/rapoarte/'.$section.'/evenimente') ? 'active' : '' }}">
							                        	<a href="{{ route('ofertare.rapoarte.browse', ['section' => $section, 'filter' => 'evenimente']) }}"><span class="nav-list-link">per evenimente</span></a>
							                        </li>
							                        <li class="{{ Request::is('ofertare/rapoarte/'.$section.'/data-alegere-instalator') ? 'active' : '' }}">
							                        	<a href="{{ route('ofertare.rapoarte.browse', ['section' => $section, 'filter' => 'data-alegere-instalator']) }}"><span class="nav-list-link">per data alegere instalator</span></a>
							                        </li>
							                        <li class="{{ Request::is('ofertare/rapoarte/'.$section.'/status-aprobare-dosar') ? 'active' : '' }}">
							                        	<a href="{{ route('ofertare.rapoarte.browse', ['section' => $section, 'filter' => 'status-aprobare-dosar']) }}"><span class="nav-list-link">per aprobare dosar</span></a>
							                        </li>
							                        <li class="{{ Request::is('ofertare/rapoarte/'.$section.'/contract-instalare') ? 'active' : '' }}">
							                        	<a href="{{ route('ofertare.rapoarte.browse', ['section' => $section, 'filter' => 'contract-instalare']) }}"><span class="nav-list-link">per contract instalare</span></a>
							                        </li>
							                        <li class="{{ Request::is('ofertare/rapoarte/'.$section.'/inginer-vizita') ? 'active' : '' }}">
							                        	<a href="{{ route('ofertare.rapoarte.browse', ['section' => $section, 'filter' => 'inginer-vizita']) }}"><span class="nav-list-link">per inginer vizita</span></a>
							                        </li>
							                        <li class="{{ Request::is('ofertare/rapoarte/'.$section.'/schita-amplasare-panouri') ? 'active' : '' }}">
							                        	<a href="{{ route('ofertare.rapoarte.browse', ['section' => $section, 'filter' => 'schita-amplasare-panouri']) }}"><span class="nav-list-link">per schita realizata</span></a>
							                        </li> --}}
							                    </ul>
					                        </li>
					                	@endforeach
				                    </ul>
					            </li>
							@endif
							@if(auth()->user()->can('sarcini.view'))
					            <li class="nav-item {{ Request::is('ofertare/sarcini*') ? 'active open' : '' }}">
					                <a href="javascript:void(0)" class="nav-link nav-toggle">
					                    <i class="fa fa-list-ul"></i>
					                    <span class="title">Sarcini</span>
					                    <span class="arrow {{ Request::is('ofertare/sarcini*') ? 'open' : '' }}"></span>
					                </a>
					                <ul class="sub-menu">
					                	@foreach([
					                		'2021' => '2021',
					                		'2023' => '2023',
					                		'fonduri-proprii' => 'Fonduri proprii'
					                	] as $section => $title)
					                		<li class="nav-item {{ Request::is('ofertare/sarcini/'.$section.'*') ? 'active open' : '' }}">
					                        	<a href="javascript:void(0)" class="nav-link nav-toggle">
						                        	<span class="nav-list-link title">{{ $title }}</span>
							                    	<span class="arrow {{ Request::is('ofertare/sarcini/'.$section.'*') ? 'open' : '' }}"></span>
						                    	</a>
					                        	<ul class="sub-menu">
					                        		<li class="{{ Request::is('ofertare/sarcini/'.$section) && request()->query('status') == 0 ? 'active' : '' }}">
							                        	<a href="{{ route('ofertare.sarcini.browse', $section) }}?status=0"><span class="nav-list-link">In lucru</span></a>
							                        </li>
							                        <li class="{{ Request::is('ofertare/sarcini/'.$section) && request()->query('status') == 1 ? 'active' : '' }}">
							                        	<a href="{{ route('ofertare.sarcini.browse', $section) }}?status=1"><span class="nav-list-link">Finalizat</span></a>
							                        </li>
				                    			</ul>
							                </li>
							            @endforeach
				                    </ul>
					            </li>
							@endif
							@if(auth()->user()->canAny(['sabloane_afm.view','sabloane_documente.view']))
								@php $main = Request::is('ofertare/sabloane*') || Request::is('ofertare/sabloane-documente*'); @endphp
								<li class="nav-item {{ $main ? 'active open' : '' }}">
					                <a href="javascript:void(0)" class="nav-link nav-toggle">
					                    <i class="fa fa-list-alt"></i>
					                    <span class="title">Sabloane</span>
					                    <span class="arrow {{ $main ? 'open' : '' }}"></span>
					                </a>
					                <ul class="sub-menu">
                                        @foreach([
					                		'sabloane_afm' => 'Tabel AFM',
					                		'sabloane_documente' => 'Documente',
					                	] as $section => $title)
											@if(auth()->user()->can($section.'.view'))
	                                            <li class="{{
						                        	Request::is('ofertare/'.str_replace('_', '-', $section).'*') ? 'active' : ''
						                        }}">
						                        	<a href="{{ route('ofertare.'.$section.'.browse') }}"><span class="nav-list-link">{{ $title }}</span></a>
						                        </li>
											@endif
					                    @endforeach
				                    </ul>
					            </li>
							@endif
							@if(auth()->user()->canAny(['invertoare.view','componente.view','panouri.view']))
								@php $main = Request::is('ofertare/invertoare*') || Request::is('ofertare/componente*') || Request::is('ofertare/panouri*'); @endphp
								<li class="nav-item {{ $main ? 'active open' : '' }}">
					                <a href="javascript:void(0)" class="nav-link nav-toggle">
					                    <i class="fa fa-television"></i>
					                    <span class="title">Echipamente</span>
					                    <span class="arrow {{ $main ? 'open' : '' }}"></span>
					                </a>
					                <ul class="sub-menu">
                                        @foreach([
					                		'invertoare' => 'Invertoare',
					                		'componente' => 'Componente',
					                		'panouri' => 'Panouri'
					                	] as $section => $title)
											@if(auth()->user()->can($section.'.view'))
	                                            <li class="{{
						                        	Request::is('ofertare/'.$section.'*') ? 'active' : ''
						                        }}">
						                        	<a href="{{ route('ofertare.'.$section.'.browse') }}"><span class="nav-list-link">{{ $title }}</span></a>
						                        </li>
											@endif
					                    @endforeach
				                    </ul>
					            </li>
							@endif
						</ul>
						<!-- END SIDEBAR MENU -->
					</div>
					<!-- END SIDEBAR -->
				</div>
				<!-- END SIDEBAR -->
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content" style="min-height: 886px;">
                        {{-- <!-- BEGIN PAGE HEADER-->
						<div class="page-bar">
						    <ul class="page-breadcrumb">
								<li>
									<span>{{ $sectiune }}</span>
								</li>
						    </ul>
						</div>
						<!-- BEGIN BREADCRUMBS --> --}}
						<div class="breadcrumbs">
							<h1>{{ $sectiune }}</h1>
							@isset($form)
								<ol class="breadcrumb">
									<li>
										<a href="{{ $browse_route ?? 'javascript:void(0)' }}">{{ $sectiune }}</a>
									</li>
									<li class="active">{{ isset($item) ? __('Editeaza') : __('Adauga') }}</li>
								</ol>
							@elseif(isset($add_route))
								<div class="pull-right">
									@yield('buttons-section')
									<a href="{{ $add_route }}" class="btn red-sunglo">
										<i class="fa fa-plus"></i> {{  __('Adauga') }}
									</a>
								</div>
							@endisset
						</div>
						<!-- END BREADCRUMBS -->
						<hr>
						@yield('content')
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
            </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner"> {{ date('Y') }} ©  - Toate drepturile rezervate</div>
                <div class="scroll-to-top" style="display: none;">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
        <!--[if lt IE 9]>
		<script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/respond.min.js"></script>
		<script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/excanvas.min.js"></script>
		<script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/ie8.fix.min.js"></script>
		<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
		<script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
		@isset($can_delete)
			<script type="text/javascript">
			    function confirmDelete(el, message = "Sigur doresti stergerea?") {
			    	let data = el.dataset;
			        if (data.url && confirm(message)) {
            			let access_token = document.querySelector('meta[name="csrf-token"]').content;
			        	$.post({
				            url: data.url,
				            headers: {'X-CSRF-TOKEN': access_token},
				            data: {'_method': 'DELETE', '_csrf': access_token},
				            success: (result) => {
				            	if(result.refresh) {
				            		location.reload();
				            	} else {
				                	el.closest("tr").remove();
				            	}
				            }
						});
			        }
			    }
			</script>
		@endisset
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="https://www.old.genway.ro/application/views/ofertare/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="https://www.old.genway.ro/application/views/ofertare/assets/layouts/layout/scripts/layout.min.js"></script>
        <script src="https://www.old.genway.ro/application/views/ofertare/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="https://www.old.genway.ro/application/views/ofertare/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="https://www.old.genway.ro/application/views/ofertare/assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
		<script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
		<script src="https://www.old.genway.ro/application/views/ofertare/assets/global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ro.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
		<script>
			let site_url = "https://www.old.genway.ro/";
		</script>
		@yield('scripts')
		@stack('scripts')
	</body>
</html>
