<?php
// 永不超时 -- cli    不能用nginx apache
//set_time_limit(0);

include __DIR__ . '/function.php';
require __DIR__ . '/vendor/autoload.php';

use QL\QueryList;

$url  = 'https://news.ke.com/bj/baike/0033/';
$html = http_request($url);

$datalist = QueryList::Query($html, [
    // '.lj-lazy'是class='lj-lazy'，定位到html代码的位置
    // 'data-original'是html标签的属性，我们要获取的，就是这个属性里的值
    "img"   => ['.lj-lazy', 'data-original'],
    // 'text'是指标签里的文本值，如<span>你好</span>，就是获取'你好'这个值
    "title" => ['.item .text .LOGCLICK', 'text'],
    "desn"  => ['.item .text .summary', 'text'],
    // 这里的'a'是指a标签，总之就是为了定位到代码位置
    "href"  => ['.item .text > a', 'href']
])->data;


var_dump($datalist);

