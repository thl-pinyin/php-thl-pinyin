<?php namespace THL;

include __DIR__ . "/phrases.php";
include __DIR__ . "/notations.php";

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
        global $thl_pinyin_phrases;

        return strtr($source, $thl_pinyin_phrases);
    }

    /**
     * Split by phrases
     *
     * @param  string $source
     * @return string 
     */
    private static function splitPhrase($source)
    {
        // $source = strtr($source, array("\035" => "5", "\036" => "6", "\037" => "7"));
        $output = preg_replace("/(^\035|\037|\036$)/", '', $source);
        return preg_replace("/(\036\035|\036 |\036| \035|\035)/", ' ', $output);
    }

    /**
     * Split by words
     *
     * @param  string $source
     * @return string 
     */
    private static function splitWord($source)
    {
        // $source = strtr($source, array("\035" => "5", "\036" => "6", "\037" => "7"));
        $output = preg_replace("/(^\035|\037\036$)/", '', $source);
        return preg_replace("/(\037\036\035|\037\036 |\037\036|\037| \035|\035)/", ' ', $output);
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

        $notation = "thl";
        $pinyin = "thl_pinyin_$notation";

        if(isset($options['override'])) {
            $output = strtr($output, $options['override']);
        }

        /* convert to pinyin */
        global $$pinyin;
        
        $output = strtr($output, $$pinyin);

        $tonestyle = isset($options['tone'])? strtolower($options['tone']): 'number';
        $split     = isset($options['split'])? strtolower($options['split']): 'word';  

        /* add tones */
        switch ($tonestyle) {
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
            $output = self::splitPhrase($output);
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
        $options['split'] = isset($options['split'])? $options['tone']: 'phrase';

        /* convert to pinyin */
        $output = self::pinyin($source, $options);

        $table = array(
            'ˊ' => '', 'ˇ' => '', 'ˋ' => '', '˙' => '', 
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'z', 'ž'=>'z', 
            'Č'=>'C', 'č'=>'c', 'Ć'=>'C',  'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A',  'Ã'=>'A', 'Ä'=>'A', 'Å'=>'a', 'Æ'=>'A', 'Ç'=>'C',
            'È'=>'E', 'É'=>'E', 'Ê'=>'E',  'Ë'=>'E', 'Ì'=>'I', 'Í'=>'i', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 
            'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',  'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 
            'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U',  'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a',  'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 
            'è'=>'e', 'é'=>'e', 'ê'=>'e',  'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 
            'ð'=>'o', 'ñ'=>'n', 'ò'=>'o',  'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 
            'ù'=>'u', 'ú'=>'u', 'û'=>'u',  'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'r', 'ŕ'=>'r',  '/' => '-', ' ' => '-',
        );

        /* remove spaces */
        $output = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $output);
        
        $output = strtolower(strtr($output, $table));

        return $output;

    }
}
