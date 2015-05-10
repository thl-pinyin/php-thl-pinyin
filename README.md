# PHP 華語拼音轉換工具
將字串中之漢字視為正體華語，並轉換成各種拼音形式之工具

### 支援拼音法
* 注音拼音 (Bopomofo)
* 臺灣華語羅馬拼音 (THL)

### 使用說明
```php
string THL\Pinyin::bpmf(string $source)
```
##### 參數
* $source 輸入字串

##### 輸出
* 將字串內漢字以華語注音符號表示

##### 範例
```php
// returns "THL ㄊㄞˊ ㄨㄢ ㄏㄨㄚˊ ㄩˇ ㄌㄨㄛˊ ㄇㄚˇ ㄆㄧㄣ ㄧㄣ"
$output = THL\Pinyin::bpmf("THL台灣華語羅馬拼音");
```

```php
string THL\Pinyin::pinyin(string $source, array $options)
```
##### 參數
* $source 輸入字串
* $options 參數
  * tone: 聲調，number (數字表示) 或是 none (無聲調)，預設為 number
  * split: 分割，word (單字) 或是 phrase (詞)，預設為 word

##### 輸出
* 將字串內漢字以華語拼音表示

##### 範例
```php
// returns "THL tai2 wan1 hua2 yu3 luo2 ma3 pin1 yin1"
$output = THL\Pinyin::pinyin("THL台灣華語羅馬拼音");

// returns "THL taiwan huayu luoma pinyin"
$output = THL\Pinyin::pinyin("THL台灣華語羅馬拼音", array('tone' => 'none', 'split' => 'phrase'));
```

```php
string THL\Pinyin::slug(string $source, array $options)
```
##### 參數
* $source 輸入字串
* $options 參數
  * split: 分割，word (單字) 或是 phrase (詞)，預設為 phrase

##### 輸出
* 輸出成適合當網址的格式

##### 範例
```php
// returns "thl-taiwan-huayu-luoma-pinyin"
$output = THL\Pinyin::pinyin("THL台灣華語羅馬拼音");

// returns "thl-tai-wan-hua-yu-luo-ma-pin-yin"
$output = THL\Pinyin::pinyin("THL台灣華語羅馬拼音", array('split' => 'phrase'));
```

### 詞庫
詞庫的部份以[小麥注音](https://mcbopomofo.openvanilla.org)為基礎，字的部份另外使用[cconv](https://code.google.com/p/cconv/) 翻譯成簡體漢字

* 由於詞庫部份沒翻譯成中國華語，以簡體漢字書寫之句字有大部份的情況會被視為獨立單字組成
