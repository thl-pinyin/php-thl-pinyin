<?php 

namespace THL;

class Pinyin
{
    /**
     * Convert traditional chinese string to bopomofo with controll characters
     *
     * @param  string $source
     * @return string 
     */
    private static function phonetic($source)
    {       
        static $thl_pinyin_phrases; // cache

        if (!isset($thl_pinyin_phrases)) {
            $thl_pinyin_phrases = require __DIR__ . "/phrases.php";
        }

        return strtr($source, $thl_pinyin_phrases);
    }

    /**
     * Split by phrases
     *
     * @param  string $source
     * @return string 
     */
    private static function splitPhrase($source, $tonestyle, $splitter)
    {
        // $source = strtr($source, array("\035" => "5", "\036" => "6", "\037" => "7"));
        switch ($tonestyle) {
            case 'mark':
                $output = strtr($source, 
                    array("\037a"  => "{$splitter}a", "\037á"  => "{$splitter}á", "\037ǎ"  => "{$splitter}ǎ", "\037à"  => "{$splitter}à", "\037ạ"  => "{$splitter}ạ",
                          "\037e"  => "{$splitter}e", "\037é"  => "{$splitter}é", "\037ě"  => "{$splitter}ě", "\037è"  => "{$splitter}è", "\037ẹ"  => "{$splitter}ẹ",
                          "\037o"  => "{$splitter}o", "\037ó"  => "{$splitter}ó", "\037ǒ"  => "{$splitter}ǒ", "\037ò"  => "{$splitter}ò", "\037ọ"  => "{$splitter}ọ",
                          "\037ny" => "{$splitter}ny",
                    ));
                break;
            case 'none':
                $output = strtr($source,
                    array("\037a"  => "{$splitter}a",
                          "\037i"  => "{$splitter}i",
                          "\037e"  => "{$splitter}e",
                          "\037ê"  => "{$splitter}ê",
                          "\037o"  => "{$splitter}o",
                          "\037ny" => "{$splitter}ny",
                ));

                break;
            default: /* number */
                $output = $source;
                break;
        }

        $output = preg_replace("/^\035|\037|\036$/", '', $output);
        return preg_replace("/\036 \035|\036\035|\036 |\036| \035|\035/", ' ', $output);
    }

    /**
     * Split by words
     *
     * @param  string $source
     * @return string 
     */
    private static function splitWord($source)
    {
        // $output = strtr($source, array("\035" => "5", "\036" => "6", "\037" => "7"));

        $output = preg_replace("/^\035|\037\036$/", '', $source);
        return preg_replace("/\037\036 \035|\037\036\035|\037\036 |\037\036|\037| \035|\035/", ' ', $output);
    }

    /**
     * Convert traditional chinese string to pinyin (with tones)
     *
     * @param  string $source
     * @param  array  $options
     * @return string 
     */
    public static function pinyin($source, $options = array())
    {
        /* convert to bopomofo */
        $output = self::phonetic($source);

        if (isset($options['override'])) {
            $output = strtr($output, $options['override']);
        }

        $notation  = isset($options['notation'])? strtolower($options['notation']): 'thl';
        $tonestyle = isset($options['tone'])? strtolower($options['tone']): 'number';
        $split     = isset($options['split'])? strtolower($options['split']): 'word';
        $charset   = isset($options['charset'])? strtolower($options['charset']): 'unicode';

        if (! in_array($notation, array('thl', 'ty', 'hy', 'mps2', 'wg'))) {
            trigger_error("Unknown notation: $notation", E_USER_ERROR);
            return false;
        }

        if (! in_array($tonestyle, array('number', 'mark', 'none'))) {
            trigger_error("Unknown tonestyle: $tonestyle", E_USER_ERROR);
            return false;
        }

        if ($split != 'word' && $split != 'phrase') {
            trigger_error("Only 'word' or 'phrase' supported. $split", E_USER_ERROR);
            return false;
        }

        if ($tonestyle == 'mark') {
            if ($notation != 'thl') {
                trigger_error("Currently only thl has tone mark.", E_USER_ERROR);
                return false;
            }
            include __DIR__ . "/notations/{$notation}.php";
            // $pinyin = "thl_pinyin_{$notation}";
        } else {
            if ($charset == 'unicode') {
                include __DIR__ . "/notations/{$notation}-toneless.php";
                // $pinyin = "thl_pinyin_{$notation}_toneless";
            } else {
                include __DIR__ . "/notations/{$notation}-simple.php";
                // $pinyin = "thl_pinyin_{$notation}_simple";
            }
        }

        /* convert to pinyin */
        // global $$pinyin;
        
        // $output = strtr($output, $$pinyin);
        $output = strtr($output, $thl_pinyin_bpmf_to_pinyin);

        /* add tones */
        switch ($tonestyle) {
            case 'mark':
                /* handled by notaton array itself */
                break;
            case 'none':
                $tones = array(
                    "\037"  => "\037",
                    "ˊ\037" => "\037", 
                    "ˇ\037" => "\037", 
                    "ˋ\037" => "\037", 
                    "˙\037" => "\037");
                
                $output = strtr($output, $tones);

                break;

            default: /* number */
                $tones = array(
                    "\037"  => "1\037",
                    "ˊ\037" => "2\037", 
                    "ˇ\037" => "3\037", 
                    "ˋ\037" => "4\037", 
                    "˙\037" => "5\037");

                $output = strtr($output, $tones);

                break;
        }

        // $output = str_replace("\036", "\037", $output);

        if ($split == 'phrase') {
            $splitter = ($notation == 'thl' || $notation == 'hy') ? '\'' : '-';
            $output = self::splitPhrase($output, $tonestyle, $splitter);
        } else { /* word */
            $output = self::splitWord($output);
        }

        return $output;
    }

    /**
     * Convert traditional chinese string to bopomofo (jhuyin/zhuyin)
     *
     * @param  string $source
     * @return string 
     */
    public static function bpmf($source)
    {
        return self::splitWord(self::phonetic($source));
    }

    /**
     * Convert traditional chinese string to url friendly string
     *
     * @param  string $source
     * @param  array  $options
     * @return string 
     */
    public static function slug($source, $options = array())
    {
        $options['tone'] = isset($options['tone'])? $options['tone']: 'none';
        $options['charset'] = 'ascii';
        // $options['split'] = isset($options['split'])? $options['split']: 'phrase';

        if ($options['tone'] == 'mark') {
            trigger_error("Can not use tone mark in slug.", E_USER_ERROR);
            return false;
        }

        /* convert to pinyin */
        $output = self::pinyin($source, $options);

        /* based on discussion of
            http://stackoverflow.com/questions/3371697/replacing-accented-characters-php
        */

        // $table = array('ü' => 'a', ' '=>'-');

        $table = array(
            'ˊ'=>'','ˇ'=>'','ˋ'=>'','˙'=>'',
            'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
            'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'Ae',
            'Þ'=>'B',
            'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
            'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
            'Ğ'=>'G',
            'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
            'Ł'=>'L',
            'Ñ'=>'N', 'Ń'=>'N',
            'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
            'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
            'Ț'=>'T',
            'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'Ue',
            'Ý'=>'Y',
            'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
            'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'ae', 'æ'=>'ae', 'Ǽ'=>'ae', 'ǽ'=>'ae',
            'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
            'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'ch', 'ч'=>'ch',
            'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'D', 'ð'=>'d',
            'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
            'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
            'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
            'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
            'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'ij', 'Ĳ'=>'ij',
            'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'ja', 'Я'=>'ja', 'Э'=>'je', 'э'=>'je', 'ё'=>'jo', 'Ё'=>'jo', 'ю'=>'ju', 'Ю'=>'ju',
            'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
            'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
            'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
            'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
            'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'oe', 'Œ'=>'oe', 'ö'=>'oe',
            'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
            'ק'=>'q',
            'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
            'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'sch', 'щ'=>'sch', 'ш'=>'sh', 'Ш'=>'sh', 'ß'=>'ss',
            'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'tm',
            'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'ue',
            'в'=>'v', 'ו'=>'v', 'В'=>'v',
            'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
            'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
            'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'zh', 'ж'=>'zh',
            '\''=>'-',
        );
    
        $output = strtolower(strtr($output, $table));

        return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/u', '/[ -]+/', '/^-|-$/'), array('_', '-', ''), $output));

    }

}
