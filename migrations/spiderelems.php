<?php
return [
'five' => [
'shangshu' => [
    'list' => [
        'classStr' => '.main-content article', 
        'fields' => ['sort' => ['dom' => 'h2'], 'is_middle' => []],
        'record' => false,
        'subFilter' => [
            'classStr' => '.shi-jianju a',
            'fields' => ['name' => [], 'source_url' => ['method' => 'attr', 'mark' => 'href']],
        ],
    ],
    'middle' => [
        'info' => [
            'classStr' => '.main-content .listtop',
            'fields' => ['content' => ['method' => 'formatContent']],
        ],
        'list' => [
            'classStr' => '.main-content article h2 a',
            'fields' => ['name' => [], 'source_url' => ['method' => 'attr', 'mark' => 'href']],
        ]
    ],
    'info' => [
        'classStr' => '.section-body .grap',
        'fields' => ['content' => ['method' => 'formatContent']],
    ],
],
],
];
