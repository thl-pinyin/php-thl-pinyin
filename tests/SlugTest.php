<?php 

use THL\Pinyin\Pinyin;

class SlugTest extends PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $source = '台灣華語羅馬拼音';
        $expect = 'taiwan-huayu-luoma-pinyin';

        $output = Pinyin::slug($source);
            
        $this->assertSame($expect, $output);
    }

    public function testWord()
    {
        $source = '台灣華語羅馬拼音';
        $expect = 'tai-wan-hua-yu-luo-ma-pin-yin';

        $output = Pinyin::slug($source, array('split' => 'word'));
            
        $this->assertSame($expect, $output);
    }

    public function testMixed()
    {
        $source = 'THL台灣華語THL羅馬拼音THL';
        $expect = 'thl-taiwan-huayu-thl-luoma-pinyin-thl';

        $output = Pinyin::slug($source);
            
        $this->assertSame($expect, $output);
    }

    public function testMixedWithSpaces()
    {
        $source = ' THL 台灣華語 THL 羅馬拼音 THL ';
        $expect = '-thl-tai-wan-hua-yu-thl-luo-ma-pin-yin-thl-';

        $output = Pinyin::slug($source, array('split' => 'word'));
            
        $this->assertSame($expect, $output);

        $source = ' THL 台灣華語 THL 羅馬拼音 THL ';
        $expect = '-thl-taiwan-huayu-thl-luoma-pinyin-thl-';

        $output = Pinyin::slug($source);
            
        $this->assertSame($expect, $output);

        $source = '  THL  台灣華語  THL  羅馬拼音  THL  ';
        $expect = '-thl-taiwan-huayu-thl-luoma-pinyin-thl-';

        $output = Pinyin::slug($source);
            
        $this->assertSame($expect, $output);
    }

}
