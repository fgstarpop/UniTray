<?php
return [
    'tabs' => [
        'mail-template' => 'Mẫu mail mặc định',
    ],
    'mail-template' => [
        'default' => [
            'all' => [
                'type' => 'checkbox',
                'options' => [
                    '1' => 'Tất cả người dùng',
                ]
            ],
//            'type' => 'send-now',
            'user' => [
                'type' => 'select2',
                'options' => [],
            ],
            'subject' => 'text',
//            'cc' => 'textarea',
            'body' => 'wysiwyg',
        ],
    ],
];
