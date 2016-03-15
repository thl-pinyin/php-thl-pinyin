<?php 

use THL\Pinyin;

class SlugTest extends PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        set_error_handler(function($no, $str, $file, $line, $context) {});

        $this->assertFalse(Pinyin::slug('THL', array('tone' => 'mark')), 'thl - mark');
        $this->assertFalse(Pinyin::slug('THL', array('notation' => 'ty', 'tone' => 'mark')), 'ty - mark');

        $output = Pinyin::slug('ĂÞĆÈĞİŁÑØŞȚÙÝŹ');

        $this->assertSame($output, 'abcegilnostuyz');

    }

    public function testWord()
    {
        $notations = array(
            'thl' => array(
                '臺灣華語羅馬拼音' => array(
                    'number' => 'tai2-wan1-hua2-yu3-luo2-ma3-pin1-yin1',
                    'none'   => 'tai-wan-hua-yu-luo-ma-pin-yin',
                ),
                '台湾华语罗马拼音' => array(
                    'number' => 'tai2-wan1-hua2-yu3-luo2-ma3-pin1-yin1',
                    'none'   => 'tai-wan-hua-yu-luo-ma-pin-yin',
                ),
                '全心全意追求卓越' => array(
                    'number' => 'chyuan2-sin1-chyuan2-yi4-jhuei1-chiou2-jhuo2-yueh4',
                    'none'   => 'chyuan-sin-chyuan-yi-jhuei-chiou-jhuo-yueh',
                ),
            ),
            'hy' => array(
                '全心全意追求卓越' => array(
                    'number' => 'quan2-xin1-quan2-yi4-zhui1-qiu2-zhuo2-yue4',
                    'none'   => 'quan-xin-quan-yi-zhui-qiu-zhuo-yue',
                ),
            ),
            'ty' => array(
                '全心全意追求卓越' => array(
                    'number' => 'cyuan2-sin1-cyuan2-yi4-jhuei1-ciou2-jhuo2-yue4',
                    'none'   => 'cyuan-sin-cyuan-yi-jhuei-ciou-jhuo-yue',
                ),
            ),
            'mps2' => array(
                '全心全意追求卓越' => array(
                    'number' => 'chiuan2-shin1-chiuan2-yi4-juei1-chiou2-juo2-yue4',
                    'none'   => 'chiuan-shin-chiuan-yi-juei-chiou-juo-yue',
                ),
            ),
            'wg' => array(
                '全心全意追求卓越' => array(
                    'number' => 'chuan2-hsin1-chuan2-i4-chui1-chiu2-chuo2-yueh4',
                    'none'   => 'chuan-hsin-chuan-i-chui-chiu-chuo-yueh',
                ),
            ),
        );
        
        foreach ($notations as $notation => $tests) {
            foreach ($tests as $source => $expect) {
                foreach (array_keys($expect) as $tone) {
                    $output = Pinyin::slug($source, array('notation' => $notation, 'tone' => $tone));
                    $this->assertSame($expect[$tone], $output, "$notation - $source - $tone");

                    $output = Pinyin::slug($source, array('notation' => $notation, 'split' => 'word', 'tone' => $tone));
                    $this->assertSame($expect[$tone], $output, "$notation - $source - $tone");

                    if ($notation == 'thl') {
                        $output = Pinyin::slug($source, array('tone' => $tone));
                        $this->assertSame($expect[$tone], $output, "$source - $tone");

                        $output = Pinyin::slug($source, array('split' => 'word', 'tone' => $tone));
                        $this->assertSame($expect[$tone], $output, "$source - $tone");
                    }

                }
            }
        }
    }

    public function testPharse()
    {
        $notations = array(
            'thl' => array(
                '臺灣華語羅馬拼音' => array(
                    'number' => 'tai2wan1-hua2yu3-luo2ma3-pin1yin1',
                    'none'   => 'taiwan-huayu-luoma-pinyin',
                ),
                '台湾华语罗马拼音' => array(
                    'number' => 'tai2-wan1-hua2-yu3-luo2-ma3-pin1yin1',
                    'none'   => 'tai-wan-hua-yu-luo-ma-pinyin',
                ),
                '仁愛白鵝海鷗長女' => array(
                    'number' => 'ren2ai4-bai2e2-hai3ou1-jhang3nyu3',
                    'none'   => 'ren-ai-bai-e-hai-ou-jhang-nyu',
                ),
            ),
            'hy' => array(
                '仁愛白鵝海鷗長女' => array(
                    'number' => 'ren2ai4-bai2e2-hai3ou1-zhang3nu3',
                    'none'   => 'ren-ai-bai-e-hai-ou-zhangnu',
                ),
            ),
            'ty' => array(
                '仁愛白鵝海鷗長女' => array(
                    'number' => 'ren2ai4-bai2e2-hai3ou1-jhang3nyu3',
                    'none'   => 'ren-ai-bai-e-hai-ou-jhang-nyu',
                ),
            ),
            'mps2' => array(
                '仁愛白鵝海鷗長女' => array(
                    'number' => 'ren2ai4-bai2e2-hai3ou1-jang3niu3',
                    'none'   => 'ren-ai-bai-e-hai-ou-jangniu',
                ),
            ),
            'wg' => array(
                '仁愛白鵝海鷗長女' => array(
                    'number' => 'jen2ai4-pai2e2-hai3ou1-chang3nu3',
                    'none'   => 'jen-ai-pai-e-hai-ou-changnu',
                ),
            ),
        );
        
        foreach ($notations as $notation => $tests) {
            foreach ($tests as $source => $expect) {
                foreach (array_keys($expect) as $tone) {
                    $output = Pinyin::slug($source, array('notation' => $notation, 'split' => 'phrase', 'tone' => $tone));
                    $this->assertSame($expect[$tone], $output, "$notation - $source - $tone");

                    if ($notation == 'thl') {
                        $output = Pinyin::slug($source, array('split' => 'phrase', 'tone' => $tone));
                        $this->assertSame($expect[$tone], $output, "$source - $tone");
                    }
                }
            }
        }
    }

    public function testWordWithSpaces()
    {
        $tests = array(
            ' 台灣華語羅馬拼音 ' => array(
                'number' => 'tai2-wan1-hua2-yu3-luo2-ma3-pin1-yin1',
                'none'   => 'tai-wan-hua-yu-luo-ma-pin-yin',
            ),
            ' 台灣華語 羅馬拼音 ' => array(
                'number' => 'tai2-wan1-hua2-yu3-luo2-ma3-pin1-yin1',
                'none'   => 'tai-wan-hua-yu-luo-ma-pin-yin',
            ),
            '  台灣華語  羅馬拼音  ' => array(
                'number' => 'tai2-wan1-hua2-yu3-luo2-ma3-pin1-yin1',
                'none'   => 'tai-wan-hua-yu-luo-ma-pin-yin',
            ),
            '   台灣華語   羅馬拼音   ' => array(
                'number' => 'tai2-wan1-hua2-yu3-luo2-ma3-pin1-yin1',
                'none'   => 'tai-wan-hua-yu-luo-ma-pin-yin',
            ),
        );
        
        foreach ($tests as $source => $expect) {
            foreach (array_keys($expect) as $tone) {
                $output = Pinyin::slug($source, array('tone' => $tone));
                $this->assertSame($expect[$tone], $output, "$source - $tone");
            }
        }
    }

    public function testPhraseWithSpaces()
    {
        $tests = array(
            ' 台灣華語羅馬拼音 ' => array(
                'number' => 'tai2wan1-hua2yu3-luo2ma3-pin1yin1',
                'none'   => 'taiwan-huayu-luoma-pinyin',
            ),
            ' 台灣華語 羅馬拼音 ' => array(
                'number' => 'tai2wan1-hua2yu3-luo2ma3-pin1yin1',
                'none'   => 'taiwan-huayu-luoma-pinyin',
            ),
            '  台灣華語  羅馬拼音  ' => array(
                'number' => 'tai2wan1-hua2yu3-luo2ma3-pin1yin1',
                'none'   => 'taiwan-huayu-luoma-pinyin',
            ),
            '  台灣華語   羅馬拼音  ' => array(
                'number' => 'tai2wan1-hua2yu3-luo2ma3-pin1yin1',
                'none'   => 'taiwan-huayu-luoma-pinyin',
            ),
            ' ①台灣②華語③羅馬④拼音⑤ ' => array(
                'number' => '_-tai2wan1-_-hua2yu3-_-luo2ma3-_-pin1yin1-_',
                'none'   => '_-taiwan-_-huayu-_-luoma-_-pinyin-_',
            ),

        );
        
        foreach ($tests as $source => $expect) {
            foreach (array_keys($expect) as $tone) {
                $output = Pinyin::slug($source, array('split' => 'phrase', 'tone' => $tone));
                $this->assertSame($expect[$tone], $output, "$source - $tone");
            }
        }
    }

    public function testWordMixed()
    {
        $tests = array(
            'THL台灣の華語THL羅馬拼音THL' => array(
                'number' => 'thl-tai2-wan1-_-hua2-yu3-thl-luo2-ma3-pin1-yin1-thl',
                'none'   => 'thl-tai-wan-_-hua-yu-thl-luo-ma-pin-yin-thl',
            ),
            ' THL 台灣 の 華語 THL 羅馬拼音 THL ' => array(
                'number' => 'thl-tai2-wan1-_-hua2-yu3-thl-luo2-ma3-pin1-yin1-thl',
                'none'   => 'thl-tai-wan-_-hua-yu-thl-luo-ma-pin-yin-thl',
            ),
            '  THL 台灣  の  華語  THL  羅馬拼音 THL  ' => array(
                'number' => 'thl-tai2-wan1-_-hua2-yu3-thl-luo2-ma3-pin1-yin1-thl',
                'none'   => 'thl-tai-wan-_-hua-yu-thl-luo-ma-pin-yin-thl',                
            ),
            '   THL  台灣   の   華語   THL   羅馬拼音  THL   ' => array(
                'number' => 'thl-tai2-wan1-_-hua2-yu3-thl-luo2-ma3-pin1-yin1-thl',
                'none'   => 'thl-tai-wan-_-hua-yu-thl-luo-ma-pin-yin-thl',
            ),
            ' ①台灣②華語③羅馬④拼音⑤ ' => array(
                'number' => '_-tai2-wan1-_-hua2-yu3-_-luo2-ma3-_-pin1-yin1-_',
                'none'   => '_-tai-wan-_-hua-yu-_-luo-ma-_-pin-yin-_',
            ),
        );
        
        foreach ($tests as $source => $expect) {
            foreach (array_keys($expect) as $tone) {
                $output = Pinyin::slug($source, array('tone' => $tone));
                $this->assertSame($expect[$tone], $output, "$source - $tone");
            }
        }
    }

    public function testPhraseMixed()
    {
        $tests = array(
            'THL台灣の華語THL羅馬拼音THL' => array(
                'number' => 'thl-tai2wan1-_-hua2yu3-thl-luo2ma3-pin1yin1-thl',
                'none'   => 'thl-taiwan-_-huayu-thl-luoma-pinyin-thl',
            ),
            ' THL 台灣 の 華語 THL 羅馬拼音 THL ' => array(
                'number' => 'thl-tai2wan1-_-hua2yu3-thl-luo2ma3-pin1yin1-thl',
                'none'   => 'thl-taiwan-_-huayu-thl-luoma-pinyin-thl',
            ),
            '  THL 台灣  の  華語  THL  羅馬拼音 THL  ' => array(
                'number' => 'thl-tai2wan1-_-hua2yu3-thl-luo2ma3-pin1yin1-thl',
                'none'   => 'thl-taiwan-_-huayu-thl-luoma-pinyin-thl',                
            ),
            '   THL  台灣   の   華語   THL   羅馬拼音  THL   ' => array(
                'number' => 'thl-tai2wan1-_-hua2yu3-thl-luo2ma3-pin1yin1-thl',
                'none'   => 'thl-taiwan-_-huayu-thl-luoma-pinyin-thl',
            ),
        );
        
        foreach ($tests as $source => $expect) {
            foreach (array_keys($expect) as $tone) {
                $output = Pinyin::slug($source, array('split' => 'phrase', 'tone' => $tone));
                $this->assertSame($expect[$tone], $output, "$source - $tone");
            }
        }
    }

    public function testSpecialCharacters()
    {
        $source = '特殊字元0~1!2@3#4$5%6^7&8*9(0)1_2+3{4}5|6:7"8<9>0?1-2=3[4]5\\6;7\'8,9.0/';
        $expect = 'te-shu-zih-yuan-0_1_2_3_4_5_6_7_8_9_0_1_2_3_4_5_6_7_8_9_0_1-2_3_4_5_6_7-8_9_0_';


        $output = Pinyin::slug($source);
            
        $this->assertSame($expect, $output);
    }

}
