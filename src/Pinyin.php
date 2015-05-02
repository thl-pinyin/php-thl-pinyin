<?php namespace THL\Pinyin;

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
     * Convert traditional chinese string to pinyin (with tones)
     *
     * @param  string $source
     * @return string 
     */
    public static function pinyin($source, $options = array())
    {
        /* convert to bopomofo */
        $output = self::phonetic($source);

        $notation = "thl";
        $pinyin = "thl_pinyin_$notation";

        if(array_key_exists('override', $options)) {
            $output = strtr($output, $options['override']);
        }

        /* convert to pinyin */
        global $$pinyin;
        
        $output = strtr($output, $$pinyin);


        /* add tones */
        $tones  = array("\037"  => "1\037",
                        "ˊ\037" => "2\037", 
                        "ˇ\037" => "3\037", 
                        "ˋ\037" => "4\037", 
                        "˙\037" => "5\037");

        $output = str_replace("\036", "\037", $output);
        $output = strtr($output, $tones);

        $output = rtrim($output, "\037");
        $output = strtr($output, array("\037 " => " ", "\037" => " "));

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
        $output = self::phonetic($source);

        $output = rtrim($output, "\036");

        $spaces = array("\037" => " ", "\036 " => " ", "\036" => " ");

        $output = strtr($output, $spaces);

        return $output;
    }

    /**
     * Convert traditional chinese string to url friendly string
     *
     * @param  string $source
     * @return string 
     */
    public static function slug($source)
    {

    }
}