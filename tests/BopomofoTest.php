<?php

use THL\Pinyin;

class BopomofoTest extends \PHPUnit\Framework\TestCase
{
    public function testBasic()
    {
        $source = '臺灣華語羅馬拼音';
        $expect = 'ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ';

        $output = Pinyin::bpmf($source);

        $this->assertSame($expect, $output);

        $source = '台湾華語羅馬拼音';
        $expect = 'ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ';

        $output = Pinyin::bpmf($source);

        $this->assertSame($expect, $output);

        $source = '台湾华语罗马拼音';
        $expect = 'ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ';

        $output = Pinyin::bpmf($source);

        $this->assertSame($expect, $output);

    }

    public function testWithSpaces()
    {
        $source = ' 台灣華語 羅馬拼音 ';
        $expect = ' ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ ';

        $output = Pinyin::bpmf($source);

        $this->assertSame($expect, $output);

        $source = '  台灣華語  羅馬拼音  ';
        $expect = '  ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ  ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ  ';

        $output = Pinyin::bpmf($source);

        $this->assertSame($expect, $output);
    }

    public function testMixed()
    {
        $source = 'THL台灣華語THL羅馬拼音THL';
        $expect = 'THL ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ THL ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ THL';

        $output = Pinyin::bpmf($source);

        $this->assertSame($expect, $output);
    }

    public function testMixedWithSpaces()
    {
        $source = ' THL 台灣華語 THL 羅馬拼音 THL ';
        $expect = ' THL ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ THL ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ THL ';

        $output = Pinyin::bpmf($source);

        $this->assertSame($expect, $output);

        $source = '  THL  台灣華語  THL  羅馬拼音  THL  ';
        $expect = '  THL  ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ  THL  ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ  THL  ';

        $output = Pinyin::bpmf($source);

        $this->assertSame($expect, $output);
    }
}
