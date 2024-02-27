<?php
use App\Models\Galerie;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
//use App\EmailTemplate;

if (! function_exists('setare')) {
    function setare($name) {
        $setare = DB::table('setari')->where('camp', $name)->first();
        return ($setare) ? $setare->valoare : '';
    }
}

if (! function_exists('acronym')) {
    function acronym($string) {
        return preg_replace('/(?:\b|_)(\w)|./', '$1', $string);
    }
}

if (! function_exists('full_sql')) {
    function full_sql($query) {
        return \Str::replaceArray('?', $query->getBindings(), $query->toSql());
    }
}

if (! function_exists('setari')) {
    function setari($array) {
        $names = is_array($array) ? $array : [$array];
        return DB::table('setari')->whereIn('camp', $names)->get()->mapWithKeys(function ($item) {
                return [$item->camp => $item->valoare];
            })->toArray() + map($names);
    }

    if (! function_exists('map')) {
        function map($array) {
            foreach($array as $value) {
                $temp[$value] = '';
            }
            return $temp ?? [];
        }
    }
}

if (!function_exists('input_name_to_dot')) {
    function input_name_to_dot($string, $glue = '.')
    {
        return str_replace(['[]','[',']'],[$glue.'*',$glue,''], $string);
    }
}

if (! function_exists('remove_empty_p_tags')) {
    function remove_empty_p_tags($string) {
        return preg_replace('/<p[^>]*>([\s]|&nbsp;)*<\/p>/', '', $string);
    }
}

if (! function_exists('affix_array_keys')) {
    function affix_array_keys(array $array, $suffixWith = '', $prefixWith = '') {
        foreach ($array as $key => $value) {
            if($suffixWith != '' && strpos($key, $suffixWith) === false) {
                $new_key = $key.$suffixWith;
            }
            if($prefixWith != '' && strpos($key, $prefixWith) === false) {
                $new_key = isset($new_key) ? $prefixWith.$new_key : $prefixWith.$key;
            }
            if(isset($new_key)) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
            $new_key = null;
        }
        return $array;
        // return collect($array)->mapWithKeys(function ($item, $key) use ($prefixWith, $suffixWith) {
        //     return [$prefixWith.$key.$suffixWith => $item];
        // })->all();
    }
}

if (! function_exists('replace_shortcodes')) {
    function replace_shortcodes($string) {
        $string = html_entity_decode($string);
        $shortcodes = [
            'galerie' => '\[galerie_([1-9][0-9]*)\]',       // 'Galeria salvata in baza de date',
            'galerie_slider' => '\[galerie_slider_([1-9][0-9]*)\]',       // 'Galeria salvata in baza de date',
            'formular' => '\[formular_([1-9][0-9]*)\s*(?:sesiune="([A-Za-z0-9_\-]+)"\s*)?\]',      // 'Formulare facute manual'
        ];
        foreach($shortcodes as $name => $code) {
            preg_match_all("/".$code."/", $string, $matches, PREG_SET_ORDER);
            switch ($name) {
                case 'galerie':
                    foreach($matches as $item) { // $item[0] - full pattern match / $item[1] - group match
                        $info = Galerie::find($item[1]);
                        $info = $info ? $info->imagini : null;
                        if($info) {
                            // inlocuim shortcode-ul cu html (galeria)
                            $replace_with = Blade::render('<x-slider
                                :popup="$popup"
                                :gallery="$gallery"
                                :folder="$folder"
                                />', [
                                'popup' => $item[1],
                                'gallery' => $info,
                                'folder' => 'pagini_galerii/',
                            ]);
                            $string = preg_replace("/".preg_quote($item[0])."/", $replace_with, $string);
                        }
                    }
                    break;
                case 'galerie_slider':
                    foreach($matches as $item) { // $item[0] - full pattern match / $item[1] - group match
                        $info = Galerie::find($item[1]);
                        $info = $info ? $info->imagini : null;
                        if($info) {
                            // inlocuim shortcode-ul cu html (galeria)
                            $replace_with = Blade::render('<x-slider
                                :gallery="$gallery"
                                :folder="$folder"
                                :static="false"
                                />', [
                                'gallery' => $info,
                                'folder' => 'pagini_galerii/',
                            ]);
                            $string = preg_replace("/".preg_quote($item[0])."/", $replace_with, $string);
                        }
                    }
                    break;
                case 'formular':
                    foreach($matches as $item) { // $item[0] - full pattern match / $item[1] - group match
                        // if($info) {
                            // inlocuim shortcode-ul cu html (formularul)
                            switch ($item[1]) {
                                case '1':
                                    $replace_with = Blade::render('<x-formular-transfer-dosar
                                        :id="$id"
                                        />', [
                                        'id' => $item[1],
                                    ]);
                                    break;
                                case '2':
                                    $replace_with = Blade::render('<x-formular-electric-up
                                        :id="$id"
                                        />', [
                                        'id' => $item[1],
                                    ]);
                                    break;
                                case '3':
                                    $replace_with = Blade::render('<x-formular-casa-verde
                                        :id="$id"
                                        />', [
                                        'id' => $item[1],
                                    ]);
                                    break;
                                case '4':
                                    $replace_with = Blade::render('<x-formular-casa-verde
                                        :id="$id"
                                        :partener="$partener"
                                        />', [
                                        'id' => $item[1],
                                        'partener' => 1,
                                    ]);
                                    break;
                                case '5':
                                    $replace_with = Blade::render('<x-formular-ofertare
                                        :id="$id"
                                        :section="$section"
                                        />', [
                                        'id' => $item[1],
                                        'section' => $item[2] ?? null,
                                    ]);
                                    break;
                                case '6':
                                    $replace_with = Blade::render('<x-formular-ofertare
                                        :id="$id"
                                        :section="$section"
                                        tip="1"
                                        />', [
                                        'id' => $item[1],
                                        'section' => $item[2] ?? null,
                                    ]);
                                    break;
                                default:
                                    $replace_with = '';
                                    break;
                            }
                            $string = preg_replace("/".preg_quote($item[0])."/", $replace_with, $string);
                        // }
                    }
                    break;
                default:
                    // code...
                    break;
            }

        }
        return preg_replace('/<p[^>]*>([\s]|&nbsp;)*<\/p>/', '', $string);
    }
}

if (!function_exists('email_template')) {
    function email_template($id, $field, $info = array(), $tag = '__')
    {
        $template = DB::table('email_template')->where('id', $id)->first();
        $result = $template ? $template->$field : FALSE;

        if( is_array( $info ) && !empty( $info ) ) {
            $search = array();
            $replace = array();
            foreach( $info as $key=>$val ) {
                $search[] = $key;
                $replace[] = $val;
            }
            $pattern = '/('.$tag.'(?:[A-Z\d]+_?)+'.$tag.')/';
            $result = preg_replace($pattern, '', str_replace( $search, $replace, $result ?? ''));
            // $result = str_replace( $search, $replace, $result );
        }
        return $result;
    }
}

if (!function_exists('full_email_template')) {
    function full_email_template($id, $info = array(), $tag = '__')
    {
        $template = DB::table('email_template')->where('id', $id)->first();
        $result = $template ?: FALSE;

        if( $result && is_array( $info ) && !empty( $info ) ) {
            $search = array();
            $replace = array();
            foreach( $info as $key=>$val ) {
                $search[] = $key;
                $replace[] = $val;
            }
            $pattern = '/('.$tag.'(?:[A-Z\d]+_?)+'.$tag.')/';
            $subject = preg_replace($pattern, '', str_replace( $search, $replace, $result->subiect ?? ''));
            $body = preg_replace($pattern, '', str_replace( $search, $replace, $result->continut ?? ''));
            // $subject = str_replace( $search, $replace, $result->subiect ?? '');
            // $body = str_replace( $search, $replace, $result->continut ?? '');
        }
        return [
            'subiect' => $subject,
            'continut' => $body,
        ];
    }
}

if(!function_exists('format_placeholder_data')) {
    function format_placeholder_data($info = array(), $tag = '__')
    {
        foreach($info as $key => $value) {
            $info[$tag.strtoupper($key).$tag] = is_array($value) ? ($value['id'] ?? '') : $value;
            unset($info[$key]);
        }
        return $info;
    }
}

if (!function_exists('document_template')) {
    function document_template($id, $field = 'continut', $info = array(), $tag = '__')
    {
        $template = \DB::table('sabloane_documente')->where('id', $id)->first();
        $result = $template ? $template->$field : FALSE;

        if( is_array( $info ) && !empty( $info ) ) {
            $search = array();
            $replace = array();
            foreach( $info as $key=>$val ) {
                $search[] = $key;
                $replace[] = $val;
            }
            $pattern = '/('.$tag.'(?:[A-Z\d]+_?)+'.$tag.')/';
            $result = preg_replace($pattern, '', str_replace( $search, $replace, $result ));
        }
        return $result;
    }
}

if (!function_exists('full_document_template')) {
    function full_document_template($id, $info = array(), $tag = '__')
    {
        // $template = \DB::table('sabloane_documente')->where('id', $id)->first();
        $template = App\Models\SablonDocument::where('id', $id)->first();
        $result = $template ?: FALSE;

        if( $result && is_array( $info ) && !empty( $info ) ) {
            $search = array();
            $replace = array();
            foreach( $info as $key=>$val ) {
                $search[] = $key;
                $replace[] = $val;
            }
            // remove unknown tags
            $pattern = '/('.$tag.'(?:[A-Z\d]+_?)+'.$tag.')/';
            $subject = preg_replace($pattern, '', str_replace( $search, $replace, $result->subiect ?? ''));
            $body = preg_replace($pattern, '', str_replace( $search, $replace, 
                $result->pagebuilder ? ($result->html ?? '') : ($result->continut ?? ''))
            );
        }
        return [
            'subiect' => $subject,
            'continut' => $body,
            'styles' => $result->stilizari.($result->pagebuilder ? ($result->css ?? '') : ''),
            'scripts' => $result->scripts,
        ];
    }
}

if (! function_exists('format_time')) {
    function format_time($time, $format = 'd/m/Y') {
        return Carbon::parse($time)->format($format);
    }
}
if (! function_exists('remove_accents')) {
    function remove_accents( $string, $locale = '' ) {
        if ( ! preg_match( '/[\x80-\xff]/', $string ) ) {
            return $string;
        }

        if ( seems_utf8( $string ) ) {
            $chars = array(
                // Decompositions for Latin-1 Supplement.
                'ª' => 'a','º' => 'o','À' => 'A','Á' => 'A','Â' => 'A','Ã' => 'A','Ä' => 'A','Å' => 'A','Æ' => 'AE',
                'Ç' => 'C','È' => 'E','É' => 'E','Ê' => 'E','Ë' => 'E','Ì' => 'I','Í' => 'I','Î' => 'I','Ï' => 'I',
                'Ð' => 'D','Ñ' => 'N','Ò' => 'O','Ó' => 'O','Ô' => 'O','Õ' => 'O','Ö' => 'O','Ù' => 'U','Ú' => 'U',
                'Û' => 'U','Ü' => 'U','Ý' => 'Y','Þ' => 'TH','ß' => 's','à' => 'a','á' => 'a','â' => 'a','ã' => 'a',
                'ä' => 'a','å' => 'a','æ' => 'ae','ç' => 'c','è' => 'e','é' => 'e','ê' => 'e','ë' => 'e','ì' => 'i',
                'í' => 'i','î' => 'i','ï' => 'i','ð' => 'd','ñ' => 'n','ò' => 'o','ó' => 'o','ô' => 'o','õ' => 'o',
                'ö' => 'o','ø' => 'o','ù' => 'u','ú' => 'u','û' => 'u','ü' => 'u','ý' => 'y','þ' => 'th','ÿ' => 'y',
                'Ø' => 'O',
                // Decompositions for Latin Extended-A.
                'Ā' => 'A','ā' => 'a','Ă' => 'A','ă' => 'a','Ą' => 'A','ą' => 'a','Ć' => 'C','ć' => 'c','Ĉ' => 'C',
                'ĉ' => 'c','Ċ' => 'C','ċ' => 'c','Č' => 'C','č' => 'c','Ď' => 'D','ď' => 'd','Đ' => 'D','đ' => 'd',
                'Ē' => 'E','ē' => 'e','Ĕ' => 'E','ĕ' => 'e','Ė' => 'E','ė' => 'e','Ę' => 'E','ę' => 'e','Ě' => 'E',
                'ě' => 'e','Ĝ' => 'G','ĝ' => 'g','Ğ' => 'G','ğ' => 'g','Ġ' => 'G','ġ' => 'g','Ģ' => 'G','ģ' => 'g',
                'Ĥ' => 'H','ĥ' => 'h','Ħ' => 'H','ħ' => 'h','Ĩ' => 'I','ĩ' => 'i','Ī' => 'I','ī' => 'i','Ĭ' => 'I',
                'ĭ' => 'i','Į' => 'I','į' => 'i','İ' => 'I','ı' => 'i','Ĳ' => 'IJ','ĳ' => 'ij','Ĵ' => 'J','ĵ' => 'j',
                'Ķ' => 'K','ķ' => 'k','ĸ' => 'k','Ĺ' => 'L','ĺ' => 'l','Ļ' => 'L','ļ' => 'l','Ľ' => 'L','ľ' => 'l',
                'Ŀ' => 'L','ŀ' => 'l','Ł' => 'L','ł' => 'l','Ń' => 'N','ń' => 'n','Ņ' => 'N','ņ' => 'n','Ň' => 'N',
                'ň' => 'n','ŉ' => 'n','Ŋ' => 'N','ŋ' => 'n','Ō' => 'O','ō' => 'o','Ŏ' => 'O','ŏ' => 'o','Ő' => 'O',
                'ő' => 'o','Œ' => 'OE','œ' => 'oe','Ŕ' => 'R','ŕ' => 'r','Ŗ' => 'R','ŗ' => 'r','Ř' => 'R','ř' => 'r',
                'Ś' => 'S','ś' => 's','Ŝ' => 'S','ŝ' => 's','Ş' => 'S','ş' => 's','Š' => 'S','š' => 's','Ţ' => 'T',
                'ţ' => 't','Ť' => 'T','ť' => 't','Ŧ' => 'T','ŧ' => 't','Ũ' => 'U','ũ' => 'u','Ū' => 'U','ū' => 'u',
                'Ŭ' => 'U','ŭ' => 'u','Ů' => 'U','ů' => 'u','Ű' => 'U','ű' => 'u','Ų' => 'U','ų' => 'u','Ŵ' => 'W',
                'ŵ' => 'w','Ŷ' => 'Y','ŷ' => 'y','Ÿ' => 'Y','Ź' => 'Z','ź' => 'z','Ż' => 'Z','ż' => 'z','Ž' => 'Z',
                'ž' => 'z','ſ' => 's',
                // Decompositions for Latin Extended-B.
                'Ș' => 'S','ș' => 's','Ț' => 'T','ț' => 't',
                // Euro sign.
                '€' => 'E',
                // GBP (Pound) sign.
                '£' => '',
                // Vowels with diacritic (Vietnamese).
                // Unmarked.
                'Ơ' => 'O','ơ' => 'o','Ư' => 'U','ư' => 'u',
                // Grave accent.
                'Ầ' => 'A','ầ' => 'a','Ằ' => 'A','ằ' => 'a','Ề' => 'E','ề' => 'e','Ồ' => 'O','ồ' => 'o','Ờ' => 'O',
                'ờ' => 'o','Ừ' => 'U','ừ' => 'u','Ỳ' => 'Y','ỳ' => 'y',
                // Hook.
                'Ả' => 'A','ả' => 'a','Ẩ' => 'A','ẩ' => 'a','Ẳ' => 'A','ẳ' => 'a','Ẻ' => 'E','ẻ' => 'e','Ể' => 'E',
                'ể' => 'e','Ỉ' => 'I','ỉ' => 'i','Ỏ' => 'O','ỏ' => 'o','Ổ' => 'O','ổ' => 'o','Ở' => 'O','ở' => 'o',
                'Ủ' => 'U','ủ' => 'u','Ử' => 'U','ử' => 'u','Ỷ' => 'Y','ỷ' => 'y',
                // Tilde.
                'Ẫ' => 'A','ẫ' => 'a','Ẵ' => 'A','ẵ' => 'a','Ẽ' => 'E','ẽ' => 'e','Ễ' => 'E','ễ' => 'e','Ỗ' => 'O',
                'ỗ' => 'o','Ỡ' => 'O','ỡ' => 'o','Ữ' => 'U','ữ' => 'u','Ỹ' => 'Y','ỹ' => 'y',
                // Acute accent.
                'Ấ' => 'A','ấ' => 'a','Ắ' => 'A','ắ' => 'a','Ế' => 'E','ế' => 'e','Ố' => 'O','ố' => 'o','Ớ' => 'O',
                'ớ' => 'o','Ứ' => 'U','ứ' => 'u',
                // Dot below.
                'Ạ' => 'A','ạ' => 'a','Ậ' => 'A','ậ' => 'a','Ặ' => 'A','ặ' => 'a','Ẹ' => 'E','ẹ' => 'e','Ệ' => 'E',
                'ệ' => 'e','Ị' => 'I','ị' => 'i','Ọ' => 'O','ọ' => 'o','Ộ' => 'O','ộ' => 'o','Ợ' => 'O','ợ' => 'o',
                'Ụ' => 'U','ụ' => 'u','Ự' => 'U','ự' => 'u','Ỵ' => 'Y','ỵ' => 'y',
                // Vowels with diacritic (Chinese, Hanyu Pinyin).
                'ɑ' => 'a',
                // Macron.
                'Ǖ' => 'U', 'ǖ' => 'u',
                // Acute accent.
                'Ǘ' => 'U', 'ǘ' => 'u',
                // Caron.
                'Ǎ' => 'A','ǎ' => 'a','Ǐ' => 'I','ǐ' => 'i','Ǒ' => 'O','ǒ' => 'o','Ǔ' => 'U','ǔ' => 'u','Ǚ' => 'U',
                'ǚ' => 'u',
                // Grave accent.
                'Ǜ' => 'U','ǜ' => 'u',
            );

            // Used for locale-specific rules.
            if ( empty( $locale ) ) {
                $locale = app()->currentLocale();
            }

            /*
             * German has various locales (de_DE, de_CH, de_AT, ...) with formal and informal variants.
             * There is no 3-letter locale like 'def', so checking for 'de' instead of 'de_' is safe,
             * since 'de' itself would be a valid locale too.
             */
            if ( str_starts_with( $locale, 'de' ) ) {
                $chars['Ä'] = 'Ae';
                $chars['ä'] = 'ae';
                $chars['Ö'] = 'Oe';
                $chars['ö'] = 'oe';
                $chars['Ü'] = 'Ue';
                $chars['ü'] = 'ue';
                $chars['ß'] = 'ss';
            } elseif ( 'da_DK' === $locale ) {
                $chars['Æ'] = 'Ae';
                $chars['æ'] = 'ae';
                $chars['Ø'] = 'Oe';
                $chars['ø'] = 'oe';
                $chars['Å'] = 'Aa';
                $chars['å'] = 'aa';
            } elseif ( 'ca' === $locale ) {
                $chars['l·l'] = 'll';
            } elseif ( 'sr_RS' === $locale || 'bs_BA' === $locale ) {
                $chars['Đ'] = 'DJ';
                $chars['đ'] = 'dj';
            }

            $string = strtr( $string, $chars );
        } else {
            $chars = array();
            // Assume ISO-8859-1 if not UTF-8.
            $chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
                . "\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
                . "\xc3\xc4\xc5\xc7\xc8\xc9\xca"
                . "\xcb\xcc\xcd\xce\xcf\xd1\xd2"
                . "\xd3\xd4\xd5\xd6\xd8\xd9\xda"
                . "\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
                . "\xe4\xe5\xe7\xe8\xe9\xea\xeb"
                . "\xec\xed\xee\xef\xf1\xf2\xf3"
                . "\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
                . "\xfc\xfd\xff";

            $chars['out'] = 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy';

            $string              = strtr( $string, $chars['in'], $chars['out'] );
            $double_chars        = array();
            $double_chars['in']  = array( "\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe" );
            $double_chars['out'] = array( 'OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th' );
            $string              = str_replace( $double_chars['in'], $double_chars['out'], $string );
        }

        return $string;
    }
}
if (! function_exists('seems_utf8')) {
    function seems_utf8( $str ) {
        mbstring_binary_safe_encoding();
        $length = strlen( $str );
        reset_mbstring_encoding();
        for ( $i = 0; $i < $length; $i++ ) {
            $c = ord( $str[ $i ] );
            if ( $c < 0x80 ) {
                $n = 0; // 0bbbbbbb
            } elseif ( ( $c & 0xE0 ) == 0xC0 ) {
                $n = 1; // 110bbbbb
            } elseif ( ( $c & 0xF0 ) == 0xE0 ) {
                $n = 2; // 1110bbbb
            } elseif ( ( $c & 0xF8 ) == 0xF0 ) {
                $n = 3; // 11110bbb
            } elseif ( ( $c & 0xFC ) == 0xF8 ) {
                $n = 4; // 111110bb
            } elseif ( ( $c & 0xFE ) == 0xFC ) {
                $n = 5; // 1111110b
            } else {
                return false; // Does not match any model.
            }
            for ( $j = 0; $j < $n; $j++ ) { // n bytes matching 10bbbbbb follow ?
                if ( ( ++$i == $length ) || ( ( ord( $str[ $i ] ) & 0xC0 ) != 0x80 ) ) {
                    return false;
                }
            }
        }
        return true;
    }
}
if(! function_exists('mbstring_binary_safe_encoding')) {
    function mbstring_binary_safe_encoding( $reset = false ) {
        static $encodings  = array();
        static $overloaded = null;

        if ( is_null( $overloaded ) ) {
            if ( function_exists( 'mb_internal_encoding' )
                && ( (int) ini_get( 'mbstring.func_overload' ) & 2 ) // phpcs:ignore PHPCompatibility.IniDirectives.RemovedIniDirectives.mbstring_func_overloadDeprecated
            ) {
                $overloaded = true;
            } else {
                $overloaded = false;
            }
        }

        if ( false === $overloaded ) {
            return;
        }

        if ( ! $reset ) {
            $encoding = mb_internal_encoding();
            array_push( $encodings, $encoding );
            mb_internal_encoding( 'ISO-8859-1' );
        }

        if ( $reset && $encodings ) {
            $encoding = array_pop( $encodings );
            mb_internal_encoding( $encoding );
        }
    }
}
if(! function_exists('reset_mbstring_encoding')) {
    function reset_mbstring_encoding() {
        mbstring_binary_safe_encoding( true );
    }
}

if(! function_exists('old_enc_dec')) {
    function old_enc_dec($string, $action = 1)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'MZiGy7ycYOLPGW4GTWVxyn0OeuMK'; // user define private key
        $secret_iv = 'C0n227lFdwL'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
        if ($action == 1) { // encrypt
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 2) { // decrypt
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}
?>
