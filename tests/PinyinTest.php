<?php 

use THL\Pinyin;

class PinyinTest extends PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        set_error_handler(function($no, $str, $file, $line, $context) {});

        $source ='臺灣華語羅馬拼音';

        $tests = array(
            'notation' => array(
                'THL'  => true,
                'Thl'  => true,
                'thl'  => true,
                'hy'   => true,
                'ty'   => true,
                'mps2' => true,
                'wg'   => true,
                'null' => false,
                ''     => false,
            ),
            'tone' => array(
                'NUMBER' => true,
                'Number' => true,
                'number' => true,
                'mark'   => true,
                'none'   => true,
                'null'   => false,
                ''       => false,
            ),
            'split' => array(
                'WORD'   => true,
                'Word'   => true,
                'word'   => true,
                'phrase' => true,
                'null'   => false,
                ''       => false,
            )
        );

        foreach ($tests as $option => $test) {
            foreach ($test as $key => $value) {
                    $output = Pinyin::pinyin("THL", array($option => $key));
                if ($value == true) {
                    $this->assertSame($output, "THL", "$option - $key");
                } else {
                    $this->assertFalse($output, "$option - $key");
                }
            }
        }

        $this->assertSame(Pinyin::pinyin('THL', array('notation' => 'thl', 'tone' => 'mark')), 'THL', 'thl - mark');
        $this->assertFalse(Pinyin::pinyin('THL', array('notation' => 'ty', 'tone' => 'mark')), 'THL', 'ty - mark');


    }

    public function testWord() 
    {
        $notations = array(
            'thl' => array(
                '臺灣華語羅馬拼音' => array(
                    'number' => 'tai2 wan1 hua2 yu3 luo2 ma3 pin1 yin1',
                    'mark'   => 'taí wan huá yǔ luó mǎ pin yin',
                    'none'   => 'tai wan hua yu luo ma pin yin',
                ),
                '台湾华语罗马拼音' => array(
                    'number' => 'tai2 wan1 hua2 yu3 luo2 ma3 pin1 yin1',
                    'mark'   => 'taí wan huá yǔ luó mǎ pin yin',
                    'none'   => 'tai wan hua yu luo ma pin yin',
                ),
                '全心全意追求卓越' => array(
                    'number' => 'chyuan2 sin1 chyuan2 yi4 jhuei1 chiou2 jhuo2 yueh4',
                    'mark'   => 'chyuán sin chyuán yì jhuei chioú jhuó yuèh',
                    'none'   => 'chyuan sin chyuan yi jhuei chiou jhuo yueh',
                ),
            ),
            'hy' => array(
                '全心全意追求卓越' => array(
                    'number' => 'quan2 xin1 quan2 yi4 zhui1 qiu2 zhuo2 yue4',
                    'none'   => 'quan xin quan yi zhui qiu zhuo yue',
                ),
            ),
            'ty' => array(
                '全心全意追求卓越' => array(
                    'number' => 'cyuan2 sin1 cyuan2 yi4 jhuei1 ciou2 jhuo2 yue4',
                    'none'   => 'cyuan sin cyuan yi jhuei ciou jhuo yue',
                ),
            ),
            'mps2' => array(
                '全心全意追求卓越' => array(
                    'number' => 'chiuan2 shin1 chiuan2 yi4 juei1 chiou2 juo2 yue4',
                    'none'   => 'chiuan shin chiuan yi juei chiou juo yue',
                ),
            ),
            'wg' => array(
                '全心全意追求卓越' => array(
                    'number' => 'ch\'üan2 hsin1 ch\'üan2 i4 chui1 ch\'iu2 chuo2 yüeh4',
                    'none'   => 'ch\'üan hsin ch\'üan i chui ch\'iu chuo yüeh',
                ),
            ),
        );
        
        foreach ($notations as $notation => $tests) {
            foreach ($tests as $source => $expect) {
                foreach (array_keys($expect) as $tone) {
                    $output = Pinyin::pinyin($source, array('notation' => $notation, 'tone' => $tone));
                    $this->assertSame($expect[$tone], $output, "$notation - $source - $tone");

                    $output = Pinyin::pinyin($source, array('notation' => $notation, 'split' => 'word', 'tone' => $tone));
                    $this->assertSame($expect[$tone], $output, "$notation - $source - $tone");

                    if ($notation == 'thl') {
                        $output = Pinyin::pinyin($source, array('tone' => $tone));
                        $this->assertSame($expect[$tone], $output, "$source - $tone");

                        $output = Pinyin::pinyin($source, array('split' => 'word', 'tone' => $tone));
                        $this->assertSame($expect[$tone], $output, "$source - $tone");
                    }

                }
            }
        }

    }

    public function testPhrase()
    {
        $notations = array(
            'thl' => array(
                '臺灣華語羅馬拼音' => array(
                    'number' => 'tai2wan1 hua2yu3 luo2ma3 pin1yin1',
                    'mark'   => 'taíwan huáyǔ luómǎ pinyin',
                    'none'   => 'taiwan huayu luoma pinyin',
                ),
                '台湾华语罗马拼音' => array(
                    'number' => 'tai2 wan1 hua2 yu3 luo2 ma3 pin1yin1',
                    'mark'   => 'taí wan huá yǔ luó mǎ pinyin',
                    'none'   => 'tai wan hua yu luo ma pinyin',
                ),
                '仁愛白鵝海鷗長女' => array(
                    'number' => 'ren2ai4 bai2e2 hai3ou1 jhang3nyu3',
                    'mark'   => 'rén\'aì baí\'é haǐ\'ou jhǎng\'nyǔ',
                    'none'   => 'ren\'ai bai\'e hai\'ou jhang\'nyu',
                ),
            ),
            'hy' => array(
                '仁愛白鵝海鷗長女' => array(
                    'number' => 'ren2ai4 bai2e2 hai3ou1 zhang3nü3',
                    'none'   => 'ren\'ai bai\'e hai\'ou zhangnü',
                ),
            ),
            'ty' => array(
                '仁愛白鵝海鷗長女' => array(
                    'number' => 'ren2ai4 bai2e2 hai3ou1 jhang3nyu3',
                    'none'   => 'ren-ai bai-e hai-ou jhang-nyu',
                ),
            ),
            'mps2' => array(
                '仁愛白鵝海鷗長女' => array(
                    'number' => 'ren2ai4 bai2e2 hai3ou1 jang3niu3',
                    'none'   => 'ren-ai bai-e hai-ou jangniu',
                ),
            ),
            'wg' => array(
                '仁愛白鵝海鷗長女' => array(
                    'number' => 'jên2ai4 pai2ê2 hai3ou1 chang3nü3',
                    'none'   => 'jên-ai pai-ê hai-ou changnü',
                ),
            ),
        );
        
        foreach ($notations as $notation => $tests) {
            foreach ($tests as $source => $expect) {
                foreach (array_keys($expect) as $tone) {
                    $output = Pinyin::pinyin($source, array('notation' => $notation, 'split' => 'phrase', 'tone' => $tone));
                    $this->assertSame($expect[$tone], $output, "$notation - $source - $tone");

                    if ($notation == 'thl') {
                        $output = Pinyin::pinyin($source, array('split' => 'phrase', 'tone' => $tone));
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
                'number' => ' tai2 wan1 hua2 yu3 luo2 ma3 pin1 yin1 ',
                'mark'   => ' taí wan huá yǔ luó mǎ pin yin ',
                'none'   => ' tai wan hua yu luo ma pin yin ',
            ),
            ' 台灣華語 羅馬拼音 ' => array(
                'number' => ' tai2 wan1 hua2 yu3 luo2 ma3 pin1 yin1 ',
                'mark'   => ' taí wan huá yǔ luó mǎ pin yin ',
                'none'   => ' tai wan hua yu luo ma pin yin ',
            ),
            '  台灣華語  羅馬拼音  ' => array(
                'number' => '  tai2 wan1 hua2 yu3  luo2 ma3 pin1 yin1  ',
                'mark'   => '  taí wan huá yǔ  luó mǎ pin yin  ',
                'none'   => '  tai wan hua yu  luo ma pin yin  ',
            ),
            '   台灣華語   羅馬拼音   ' => array(
                'number' => '   tai2 wan1 hua2 yu3   luo2 ma3 pin1 yin1   ',
                'mark'   => '   taí wan huá yǔ   luó mǎ pin yin   ',
                'none'   => '   tai wan hua yu   luo ma pin yin   ',
            ),
        );
        
        foreach ($tests as $source => $expect) {
            foreach (array_keys($expect) as $tone) {
                $output = Pinyin::pinyin($source, array('tone' => $tone));
                $this->assertSame($expect[$tone], $output, "$source - $tone");
            }
        }
    }

    public function testPhraseWithSpaces()
    {
        $tests = array(
            ' 台灣華語羅馬拼音 ' => array(
                'number' => ' tai2wan1 hua2yu3 luo2ma3 pin1yin1 ',
                'mark'   => ' taíwan huáyǔ luómǎ pinyin ',
                'none'   => ' taiwan huayu luoma pinyin ',
            ),
            ' 台灣華語 羅馬拼音 ' => array(
                'number' => ' tai2wan1 hua2yu3 luo2ma3 pin1yin1 ',
                'mark'   => ' taíwan huáyǔ luómǎ pinyin ',
                'none'   => ' taiwan huayu luoma pinyin ',
            ),
            '  台灣華語  羅馬拼音  ' => array(
                'number' => '  tai2wan1 hua2yu3  luo2ma3 pin1yin1  ',
                'mark'   => '  taíwan huáyǔ  luómǎ pinyin  ',
                'none'   => '  taiwan huayu  luoma pinyin  ',
            ),
            '  台灣華語   羅馬拼音  ' => array(
                'number' => '  tai2wan1 hua2yu3   luo2ma3 pin1yin1  ',
                'mark'   => '  taíwan huáyǔ   luómǎ pinyin  ',
                'none'   => '  taiwan huayu   luoma pinyin  ',
            ),
        );
        
        foreach ($tests as $source => $expect) {
            foreach (array_keys($expect) as $tone) {
                $output = Pinyin::pinyin($source, array('split' => 'phrase', 'tone' => $tone));
                $this->assertSame($expect[$tone], $output, "$source - $tone");
            }
        }
    }

    public function testWordMixed()
    {
        $tests = array(
            'THL台灣の華語THL羅馬拼音THL' => array(
                'number' => 'THL tai2 wan1 の hua2 yu3 THL luo2 ma3 pin1 yin1 THL',
                'mark'   => 'THL taí wan の huá yǔ THL luó mǎ pin yin THL',
                'none'   => 'THL tai wan の hua yu THL luo ma pin yin THL',
            ),
            ' THL 台灣 の 華語 THL 羅馬拼音 THL ' => array(
                'number' => ' THL tai2 wan1 の hua2 yu3 THL luo2 ma3 pin1 yin1 THL ',
                'mark'   => ' THL taí wan の huá yǔ THL luó mǎ pin yin THL ',
                'none'   => ' THL tai wan の hua yu THL luo ma pin yin THL ',                
            ),
            '  THL 台灣  の  華語  THL  羅馬拼音 THL  ' => array(
                'number' => '  THL tai2 wan1  の  hua2 yu3  THL  luo2 ma3 pin1 yin1 THL  ',
                'mark'   => '  THL taí wan  の  huá yǔ  THL  luó mǎ pin yin THL  ',
                'none'   => '  THL tai wan  の  hua yu  THL  luo ma pin yin THL  ',                
            ),
            '   THL  台灣   の   華語   THL   羅馬拼音  THL   ' => array(
                'number' => '   THL  tai2 wan1   の   hua2 yu3   THL   luo2 ma3 pin1 yin1  THL   ',
                'mark'   => '   THL  taí wan   の   huá yǔ   THL   luó mǎ pin yin  THL   ',
                'none'   => '   THL  tai wan   の   hua yu   THL   luo ma pin yin  THL   ',                
            ),
        );
        
        foreach ($tests as $source => $expect) {
            foreach (array_keys($expect) as $tone) {
                $output = Pinyin::pinyin($source, array('tone' => $tone));
                $this->assertSame($expect[$tone], $output, "$source - $tone");
            }
        }
    }

    public function testPhraseMixed()
    {
        $tests = array(
            'THL台灣の華語THL羅馬拼音THL' => array(
                'number' => 'THL tai2wan1 の hua2yu3 THL luo2ma3 pin1yin1 THL',
                'mark'   => 'THL taíwan の huáyǔ THL luómǎ pinyin THL',
                'none'   => 'THL taiwan の huayu THL luoma pinyin THL',
            ),
            ' THL 台灣 の 華語 THL 羅馬拼音 THL ' => array(
                'number' => ' THL tai2wan1 の hua2yu3 THL luo2ma3 pin1yin1 THL ',
                'mark'   => ' THL taíwan の huáyǔ THL luómǎ pinyin THL ',
                'none'   => ' THL taiwan の huayu THL luoma pinyin THL ',                
            ),
            '  THL 台灣  の  華語  THL  羅馬拼音 THL  ' => array(
                'number' => '  THL tai2wan1  の  hua2yu3  THL  luo2ma3 pin1yin1 THL  ',
                'mark'   => '  THL taíwan  の  huáyǔ  THL  luómǎ pinyin THL  ',
                'none'   => '  THL taiwan  の  huayu  THL  luoma pinyin THL  ',                
            ),
            '   THL  台灣   の   華語   THL   羅馬拼音  THL   ' => array(
                'number' => '   THL  tai2wan1   の   hua2yu3   THL   luo2ma3 pin1yin1  THL   ',
                'mark'   => '   THL  taíwan   の   huáyǔ   THL   luómǎ pinyin  THL   ',
                'none'   => '   THL  taiwan   の   huayu   THL   luoma pinyin  THL   ',                
            ),
        );
        
        foreach ($tests as $source => $expect) {
            foreach (array_keys($expect) as $tone) {
                $output = Pinyin::pinyin($source, array('split' => 'phrase', 'tone' => $tone));
                $this->assertSame($expect[$tone], $output, "$source - $tone");
            }
        }
    }

    public function testDefaultAlternatives()
    {
        $notations = array(
            'thl' => array(
                '補充字 - 崩棚猛奉撥坡摸佛' => array(
                    'number' => 'bu3 chong1 zih4 - bong1 pong2 mong3 fong4 bo1 po1 mo1 fo2',
                    'mark'   => 'bǔ chong zìh - bong póng mǒng fòng bo po mo fó',
                    'none'   => 'bu chong zih - bong pong mong fong bo po mo fo',
                ),
            ),
            
            'hy' => array(
                '補充字 - 崩棚猛奉撥坡摸佛' => array(
                    'number' => 'bu3 chong1 zi4 - beng1 peng2 meng3 feng4 bo1 po1 mo1 fo2',
                    'none'   => 'bu chong zi - beng peng meng feng bo po mo fo',
                ),
            ),
            
            'ty' => array(
                '補充字 - 崩棚猛奉撥坡摸佛' => array(
                    'number' => 'bu3 chong1 zih4 - beng1 peng2 meng3 fong4 bo1 po1 mo1 fo2',
                    'none'   => 'bu chong zih - beng peng meng fong bo po mo fo',
                ),
            ),
        );
        
        foreach ($notations as $notation => $tests) {
            foreach ($tests as $source => $expect) {
                foreach (array_keys($expect) as $tone) {
                    $output = Pinyin::pinyin($source, array('notation' => $notation, 'tone' => $tone));
                    $this->assertSame($expect[$tone], $output, "$notation - $source - $tone");
                }
            }
        }
    }

    public function testCustomAlternatives()
    {
        $source = '補充字 - 崩棚猛奉撥坡摸佛';
        $expect = 'bu3 chong1 zih4 - bong1 pong2 mong3 fong4 buo1 puo1 muo1 fo2';

        $output = Pinyin::pinyin($source, array("override" => array('ㄅㄛ' => 'buo', 'ㄆㄛ' => 'puo', 'ㄇㄛ' => 'muo')));
            
        $this->assertSame($expect, $output);
    }

}
