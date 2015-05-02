<?php 

use THL\Pinyin\Pinyin;

class BopomofoTest extends PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $source = '台灣華語羅馬拼音';
        $expect = 'ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ';

        $output = Pinyin::bpmf($source);
            
        $this->assertSame($expect, $output);
    }

    public function testEndWithSpaces()
    {
        $source = '台灣華語羅馬拼音  ';
        $expect = 'ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ  ';

        $output = Pinyin::bpmf($source);
            
        $this->assertSame($expect, $output);
    }

    public function testMixed()
    {
        $source = '台灣華語羅馬拼音 THL ';
        $expect = 'ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ THL ';

        $output = Pinyin::bpmf($source);
            
        $this->assertSame($expect, $output);
    } 
}
