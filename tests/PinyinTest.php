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

    public function testEndWithSpaces()
    {
        $source = '台灣華語羅馬拼音  ';
        $expect = 'tai2 wan1 hua2 yu3 luo2 ma3 pin1 yin1  ';

        $output = Pinyin::pinyin($source);
            
        $this->assertSame($expect, $output);
    }

    public function testMixed()
    {
        $source = '台灣華語羅馬拼音 THL ';
        $expect = 'tai2 wan1 hua2 yu3 luo2 ma3 pin1 yin1 THL ';

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

    // public function testToneless()
    // {
    //     $source = '台灣華語羅馬拼音 THL ';
    //     $expect = 'taiwan huayu luoma pinyin THL ';

    //     $output = Pinyin::pinyin($source);
            
    //     $this->assertSame($expect, $output);
    // }

}
