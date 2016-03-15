# PHP 華語拼音轉換工具
將字串中之漢字視為華語，並轉換成各種拼音形式之工具

### 支援拼音法
* 注音拼音 (Bopomofo)
* 臺灣華語羅馬拼音 (THL)
* 漢語拼音
* 通用拼音
* 注音二式
* 威妥瑪拼音

### 使用說明
#### Pinyin::bpmf()
將字串內漢字以華語注音符號表示
```php
string THL\Pinyin::bpmf(string $source)
```
##### 參數
* $source 輸入字串

##### 範例
```php
// returns "THL ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ"
$output = THL\Pinyin::bpmf("THL台灣華語羅馬拼音");
```
#### Pinyin::pinyin()
將字串內漢字以華語拼音表示
```php
string THL\Pinyin::pinyin(string $source, array $options)
```
##### 參數
* $source 輸入字串
* $options 參數
  * notation: 方案，預設為 thl
    * thl (台灣華語羅馬拼音)
    * hy (漢語拼音)
    * ty (通用拼音)
    * mps2 (注音二式)
    * wg (威妥瑪拼音)
  * tone: 聲調，預設為 number
    * number (數字表示)
    * mark (符號表示) 目前暫時只支援臺灣華語羅馬拼音 (THL)
    * none (無聲調)
  * split: 分割，預設為 word
    * word (單字) 
    * phrase (詞)

##### 範例
```php
// returns "THL tai2 wan1 hua2 yu3 luo2 ma3 pin1 yin1"
$output = THL\Pinyin::pinyin("THL台灣華語羅馬拼音");

// returns "THL taíwan huáyǔ luómǎ pinyin"
$output = THL\Pinyin::pinyin("THL台灣華語羅馬拼音", array('tone' => 'mark', 'split' => 'phrase'));

// returns "THL t'ai wan hua yü lo ma p'in yin"
$output = THL\Pinyin::pinyin("THL台灣華語羅馬拼音", array('notation' => 'wg', 'tone' => 'none', 'split' => 'word'));
```

#### Pinyin::slug()
產生適合當網址的格式

```php
string THL\Pinyin::slug(string $source, array $options)
```
##### 參數
* $source 輸入字串
* $options 參數
  * notation: 方案，預設為 thl
    * thl (台灣華語羅馬拼音)
    * hy (漢語拼音)
    * ty (通用拼音)
    * mps2 (注音二式)
    * wg (威妥瑪拼音)
  * tone: 聲調，預設為 none
    * number (數字表示)
    * none (無聲調)
  * split: 分割，預設為 word
    * word (單字)
    * phrase (詞)

##### 範例
```php
// returns "thl-tai-wan-hua-yu-luo-ma-pin-yin"
$output = THL\Pinyin::pinyin("THL台灣華語羅馬拼音");

// returns "thl-taiwan-huayu-luoma-pinyin"
$output = THL\Pinyin::pinyin("THL台灣華語羅馬拼音", array('split' => 'phrase'));

// returns "thl-tai2wan1-hua2yu3-luo2ma3-pin1yin1"
$output = THL\Pinyin::pinyin("THL台灣華語羅馬拼音", array('notation' => 'ty', 'tone' => 'number', 'split' => 'phrase'));
```

### 詞庫
詞庫的部份以[小麥注音](https://mcbopomofo.openvanilla.org)為基礎，字的部份另外使用[cconv](https://github.com/xiaoyjy/cconv) 翻譯成簡體漢字

* 由於詞庫部份尚未翻譯為中式華語，以簡體漢字書寫之句子有大部份的情況會被視為獨立單字組成
