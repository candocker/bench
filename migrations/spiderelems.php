<?php

$baseInfoField1 = ['content' => ['method' => 'formatContent']];
$baseInfoField2 = ['content' => ['method' => 'formatContent', 'pointKey' => 'unscramble']];
$baseInfoField3 = ['classStr' => '.main-content', 'description' => ['dom' => 'div', 'index' => 0]];

$fiveInfoElem1 = ['classStr' => '.section-body .grap', 'fields' => $baseInfoField1];
$fiveInfoElem2 = ['classStr' => '.main-content .listtop', 'fields' => $baseInfoField2];
$fiveInfoElem3 = ['classStr' => '.section-body .grap .nei-img', 'fields' => $baseInfoField2];

$baseListField1 = ['name' => [], 'source_url' => ['method' => 'attr', 'mark' => 'href']];
$baseListField2 = ['sort' => ['dom' => 'h2'], 'is_middle' => []];
$baseListField3 = array_merge($baseListField1, ['is_middle' => []]);

$fiveListBase1 = ['classStr' => '.main-content .layoutSingleColumn li a', 'fields' => $baseListField1];
$fiveListBase2 = ['classStr' => '.main-content li a', 'fields' => $baseListField1];
$fiveListBase3 = ['classStr' => '.main-content article h2 a', 'fields' => $baseListField1];
$fiveListBase4 = ['classStr' => '.main-content .layoutSingleColumn li a', 'fields' => $baseListField2];

$subFilter = ['classStr' => '.shi-jianju a', 'fields' => $baseListField1];
$fiveListSub1 = ['classStr' => '.main-content article', 'fields' => $baseListField3, 'subFilter' => $subFilter];

$fiveMiddleElem1 = ['list' => $fiveListBase3];
$fiveMiddleElem2 = ['list' => $fiveListBase3, 'info' => $fiveInfoElem2];

$resultElemDatas = [];

return [
'five' => [
'liji' => ['list' => array_merge($fiveListBase4, ['subFilter' => $subFilter]), 'middle' => ['list' => $fiveListBase2, 'info' => $baseInfoField3], 'info' => $fileInfoElem],
/*'chuanxilu' => [
    'list' => [
        'classStr' => '.main-content article', 'record' => false, 
        'fields' => ['sort' => ['dom' => 'h2'], 'description' => ['dom' => '.shi-jianju', 'index' => 1], 'is_middle' => []],
        'subFilter' => $subFilter,
    ],
    'middle' => [
        'list' => ['classStr' => '.main-content article h2 a', 'fields' => $baseListField1]
    ],
    'info' => $fiveInfoElem,
],*/
'sunzibingfa' => ['list' => $fiveListBase2, 'info' => $fiveInfoElem],
'hanfeizi' => ['list' => $fiveListBase1, 'info' => $fiveInfoElem3],
'xiaojing' => ['list' => $fiveListBase1, 'info' => $fiveInfoElem3],
'shangshu' => ['list' => $fiveListElem, 'midddle' => $fiveMiddleElem, 'info' => $fiveInfoElem],
],
];
