<?php
/** 
 * Setările de bază pentru WordPress.
 *
 * Acest fișier conține următoarele detalii despre: setările MySQL, prefixul pentru tabele,
 * cheile secrete, localizarea WordPress și ABSPATH. Informații adăugătoare pot fi găsite
 * în pagina {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} din Codex. Setările MySQL pot fi obținute de la serviciul de găzduire.
 *
 * Acest fișier este folosit la crearea wp-config.php din timpul procesului de instalare.
 * Folosirea interfeței web nu e obligatorie, acest fișier poate fi copiat
 * sub numele de "wp-config.php", iar apoi populate toate detaliile.
 *
 * @package WordPress
 */

// ** Setările MySQL: aceste informații pot fi obținute de la serviciile de găzduire ** //
/** Numele bazei de date pentru WordPress */
define('DB_NAME', 'rroi3063_blog');

/** Numele de utilizator MySQL */
define('DB_USER', 'rroi3063_pam');

/** Parola utilizatorului MySQL */
define('DB_PASSWORD', ']#ap-^,4s-9V');

/** Adresa serverului MySQL */
define('DB_HOST', 'localhost');

/** Setul de caractere pentru tabelele din baza de date. */
define('DB_CHARSET', 'utf8');

/** Schema pentru unificare. Nu faceți modificări dacă nu sunteți siguri. */
define('DB_COLLATE', '');

/**#@+
 * Cheile unice pentru autentificare
 *
 * Modificați conținutul fiecărei chei pentru o frază unică.
 * Acestea pot fi generate folosind {@link http://api.wordpress.org/secret-key/1.1/salt/ serviciul pentru chei de pe WordPress.org}
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'QC?#K:):gv!Hd_Mhot$ppY]u7t3)AF<swHGx~/y}xnvS;`7kt.y.xFg9BIqyY[%1');
define('SECURE_AUTH_KEY',  'S9_U*OJ`c)n?pO7)-,8,c7ln{VX=f:,gRQ9NU-^L.@i:]<n-%0.)jTrRsb+I*uR?');
define('LOGGED_IN_KEY',    'hw:Qqnwjfwo-70WiTK+a9tyAP|O{A[@48(j~lL:-_YO9qQz#tR=(2+I !3FaT#Hh');
define('NONCE_KEY',        'l82vKM$c?t|+Y$TaKo8#{XCETgZG_D%cx$EA_~Bt3R!t FWoeco24ENr}6g|M~H|');
define('AUTH_SALT',        '81(wp$PImA)~vg0lt7{eKxdGH{)bOW~esBM3TKNh>DQLRmcD9qhR]AI]tJ?G||JM');
define('SECURE_AUTH_SALT', 'vZTgrV!QP3f#;}Xw,a^1+&]#LGQ5)eL i|wl9tfE=_K^@7m!+/c)A:]I}f^>Isv=');
define('LOGGED_IN_SALT',   '|k-B~jq|.?VLJ?2}8F7,xZq+?p^L<[)g}EX{d5.|GIVMY4ro5hm]OckpBRL>i[OK');
define('NONCE_SALT',       '@yjcY$HN2<4NU+Zau0b~|dnOj#U:vCY;;_q]ttL(1uWjXyseZ|sD~75uqtIm,V%}');

/**#@-*/

/**
 * Prefixul tabelelor din MySQL
 *
 * Acest lucru permite instalarea a câteva instanțe WordPress folosind aceeași bază de date
 * și prefix diferit. Sunt permise doar cifre, litere și caracterul liniuță de subliniere.
 */
$table_prefix  = 'wp_';

/**
 * Limba pentru localizarea WordPress, implicit vine cu Engleză
 *
 * Modificați acest parametru, pentru a folosi o altă localizare. Fișierul .mo
 * pentru localizarea respectivă trebuie plasat în directorul wp-content/languages.
 */
define ('WPLANG', 'ro_RO');

/**
 * Pentru dezvoltatori: WordPress în mod de depanare.
 *
 * Setează cu true pentru a permite afișarea notificărilor la dezvoltare.
 * Este mult recomadată folosirea modului WP_DEBUG la dezvoltarea modulelor și
 * a șabloanelor în mediile personale.
 */
define('WP_DEBUG', false);

/* Asta e tot, am terminat cu editarea. Spor! */

/** Calea absolută spre directorul WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sunt încărcați toți parametrii WordPress și conectat fișierul. */
require_once(ABSPATH . 'wp-settings.php');
?>
