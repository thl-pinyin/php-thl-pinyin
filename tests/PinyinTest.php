<?php 

use THL\Pinyin\Pinyin;

class PinyinTest extends PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $source = '臺灣華語羅馬拼音';
        $expect = 'tai2 wan1 hua2 yu3 luo2 ma3 pin1 yin1';

        $output = Pinyin::pinyin($source);
            
        $this->assertSame($expect, $output);

        $source = '台湾华语罗马拼音';
        $expect = 'tai2 wan1 hua2 yu3 luo2 ma3 pin1 yin1';

        $output = Pinyin::pinyin($source);
            
        $this->assertSame($expect, $output);
    }

    public function testPhrase()
    {
        $source = '臺灣華語羅馬拼音';
        $expect = 'tai2wan1 hua2yu3 luo2ma3 pin1yin1';

        $output = Pinyin::pinyin($source, array('split' => 'phrase'));
            
        $this->assertSame($expect, $output);

        /* no phrase support for Hans so far */
        $source = '台湾华语罗马拼音';
        $expect = 'tai2 wan1 hua2 yu3 luo2 ma3 pin1yin1';

        $output = Pinyin::pinyin($source, array('split' => 'phrase'));
            
        $this->assertSame($expect, $output);
    }

    public function testEndWithSpaces()
    {
        $source = ' 台灣華語 羅馬拼音 ';
        $expect = ' tai2 wan1 hua2 yu3  luo2 ma3 pin1 yin1 ';

        $output = Pinyin::pinyin($source);
            
        $this->assertSame($expect, $output);

        $source = '  台灣華語  羅馬拼音  ';
        $expect = '  tai2 wan1 hua2 yu3  luo2 ma3 pin1 yin1  ';

        $output = Pinyin::pinyin($source);
            
        $this->assertSame($expect, $output);
    }

    public function testMixed()
    {
        $source = 'THL台灣華語THL羅馬拼音THL';
        $expect = 'THL tai2 wan1 hua2 yu3 THL luo2 ma3 pin1 yin1 THL';

        $output = Pinyin::pinyin($source);
            
        $this->assertSame($expect, $output);
    }

    public function testMixedWithSpaces()
    {
        $source = ' THL 台灣華語 THL 羅馬拼音 THL ';
        $expect = ' THL tai2 wan1 hua2 yu3 THL luo2 ma3 pin1 yin1 THL ';

        $output = Pinyin::pinyin($source);
            
        $this->assertSame($expect, $output);

        $source = '  THL 台灣華語  THL  羅馬拼音  THL  ';
        $expect = '  THL tai2 wan1 hua2 yu3  THL  luo2 ma3 pin1 yin1  THL  ';

        $output = Pinyin::pinyin($source);
            
        $this->assertSame($expect, $output);
    }

    public function testDefaultAlternatives()
    {
        $source = '補充字 - 崩棚猛奉撥坡摸佛';
        $expect = 'bu3 chong1 zih4 - bong1 pong2 mong3 fong4 bo1 po1 mo1 fo2';

        $output = Pinyin::pinyin($source);
            
        $this->assertSame($expect, $output);
    }

    public function testCustomAlternatives()
    {
        $source = '補充字 - 崩棚猛奉撥坡摸佛';
        $expect = 'bu3 chong1 zih4 - bong1 pong2 mong3 fong4 buo1 puo1 muo1 fo2';

        $output = Pinyin::pinyin($source, array("override" => array('ㄅㄛ' => 'buo', 'ㄆㄛ' => 'puo', 'ㄇㄛ' => 'muo')));
            
        $this->assertSame($expect, $output);
    }

    public function testToneless()
    {
        $source = '台灣華語羅馬拼音 THL ';
        $expect = 'tai wan hua yu luo ma pin yin THL ';

        $output = Pinyin::pinyin($source, array("tone" => "none", "split" => "word"));

        $this->assertSame($expect, $output);

        $source = '台灣華語羅馬拼音 THL ';
        $expect = 'taiwan huayu luoma pinyin THL ';

        $output = Pinyin::pinyin($source, array("tone" => "none", "split" => "phrase"));

        $this->assertSame($expect, $output);
    }

}
