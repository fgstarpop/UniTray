<?php

return [
    'class' => [
        1 => [
            'name_class' => 'Tiên',
            'name_mp' => 'tiên lực',
            'name_lv' => [
                0 => 'Luyện khí',
                1 => 'Trúc cơ',
                2 => 'Kim đan',
                3 => 'Nguyên Anh',
                4 => 'Bão đan',
                5 => 'Hư thần',
                6 => 'Hóa thần',
                7 => 'Quy Khư',
                8 => 'Chân tiên',
                9 => 'Kim tiên',
                10 => 'Tiên vương',
                11 => 'Tiên đế',
            ],
            'base' => [
                'str' => 50,
                'agi' => 0,
                'vit' => 0,
                'ene' => 0,
                'hp_regen' => 0.01,
                'mp_regen'  => 0.01,
                'lv_up_point' => 10,
                'can_co' => 1,
                'luk' => 1,

            ],
            'img' => [
                1 =>  'asset/class/Nam Tiên.png',
                2 =>  'asset/class/Nữ Tiên.png',
            ],
        ],
        2 => [
            'name_class' => 'Nhân',
            'name_mp' => 'nội lực',
            'name_lv' => [
                0 => 'Đoán Cốt',
                1 => 'Khí Hải',
                2 => 'Tụ Thần',
                3 => 'Luyện Hư',
                4 => 'Tông Sư',
                5 => 'Võ Vương',
                6 => 'Võ Quân',
                7 => 'Võ Hoàng',
                8 => 'Võ Đế',
                9 => 'Phá Toái',
                10 => 'Võ Thần',
                11 => 'Nhân Hoàng',
            ],
            'base' => [
                'str' => 0,
                'agi' => 50,
                'vit' => 0,
                'ene' => 0,
                'hp_regen' => 0.01,
                'mp_regen'  => 0.01,
                'lv_up_point' => 10,
                'can_co' => 1,
                'luk' => 1,

            ],
            'img' => [
                1 =>  'asset/class/Nam Nhân.png',
                2 =>  'asset/class/Nữ Nhân.png',
            ],
        ],
        3 => [
            'name_class' => 'Ma',
            'name_mp' => 'ma lực',
            'name_lv' => [
                0 => 'Sa Đoạ',
                1 => 'Hắc Hoá',
                2 => 'Ma Thần',
                3 => 'Tâm Ma',
                4 => 'Hắc Ma',
                5 => 'Đại Ma',
                6 => 'Ma Vương',
                7 => 'Ma Hoàng',
                8 => 'Ma Tôn',
                9 => 'Ma Đế',
                10 => 'Ma Tổ',
                11 => 'Cực Ma',

            ],
            'base' => [
                'str' => 0,
                'agi' => 0,
                'vit' => 0,
                'ene' => 50,
                'hp_regen' => 0.01,
                'mp_regen'  => 0.01,
                'lv_up_point' => 10,
                'can_co' => 1,
                'luk' => 1,


            ],
            'img' => [
                1 =>  'asset/class/Nam Ma.png',
                2 =>  'asset/class/Nữ Ma.png',
            ],
        ],

    ],
    'gioitinh' => [
        1 => 'Nam',
        2 => 'Nữ',
    ],
    'doilinhthach' => [
        200 => [
            'stone' => 200,
            'gold' => 10000,
            'bonus' => 0
        ],
        1000 => [
            'stone' => 1000,
            'gold' => 50000,
            'bonus' => 50
        ],
        2000 => [
            'stone' => 2000,
            'gold' => 100000,
            'bonus' => 100
        ],
        10000 => [
            'stone' => 10000,
            'gold' => 500000,
            'bonus' => 500
        ],
    ],
    'formula' => [
        1 => [
            'str' => [
                'atk' => [
                    'name' => 'Sát thương',
                    'stat_point' => 1,
                    'value' => 3,
                ],
            ],
            'agi' => [
                'atk_speed' => [
                    'name' => 'Tốc độ tấn công',
                    'stat_point' => 1,
                    'value' => 0.001,
                ],
                'def' => [
                    'name' => 'Phòng thủ',
                    'stat_point' => 1,
                    'value' => 0.75,
                ],
                'crit' => [
                    'name' => 'Chí mạng',
                    'stat_point' => 7,
                    'value' => 0.0001,
                ],
                'crit_dmg' => [
                    'name' => 'Sát thương chí mạng',
                    'stat_point' => 5,
                    'value' => 0.0001,
                ],
                'dodge' => [
                    'name' => 'Né tránh',
                    'stat_point' => 8,
                    'value' => 0.0001,
                ],

            ],
            'vit' => [
                'max_hp' => [
                    'name' => 'Sinh lực tối đa',
                    'stat_point' => 1,
                    'value' => 20,
                ],
                'hp_regen' => [
                    'name' => 'Tốc độ hồi máu',
                    'stat_point' => 5,
                    'value' => 0.0001,
                ],
            ],
            'ene' => [
                'max_mp' => [
                    'name' => 'Năng lượng tối đa',
                    'stat_point' => 1,
                    'value' => 5,
                ],
                'mp_regen' => [
                    'name' => 'Tốc độ hồi năng lượng',
                    'stat_point' => 10,
                    'value' => 0.0001,
                ],
            ],
        ],
        2 => [
            'str' => [
                'atk' => [
                    'name' => 'Sát thương',
                    'stat_point' => 1,
                    'value' => 3,
                ],
            ],
            'agi' => [
                'atk_speed' => [
                    'name' => 'Tốc độ tấn công',
                    'stat_point' => 1,
                    'value' => 0.001,
                ],
                'def' => [
                    'name' => 'Phòng thủ',
                    'stat_point' => 1,
                    'value' => 1,
                ],
                'dodge' => [
                    'name' => 'Né tránh',
                    'stat_point' => 8,
                    'value' => 0.0001,
                ],

            ],
            'vit' => [
                'max_hp' => [
                    'name' => 'Sinh lực tối đa',
                    'stat_point' => 1,
                    'value' => 17.5,
                ],
                'hp_regen' => [
                    'name' => 'Tốc độ hồi máu',
                    'stat_point' => 5,
                    'value' => 0.0001,
                ],
            ],
            'ene' => [
                'max_mp' => [
                    'name' => 'Năng lượng tối đa',
                    'stat_point' => 1,
                    'value' => 7,
                ],
                'mp_regen' => [
                    'name' => 'Tốc độ hồi năng lượng',
                    'stat_point' => 10,
                    'value' => 0.0001,
                ],
            ],
        ],
        3 => [
            'str' => [],
            'agi' => [
                'atk_speed' => [
                    'stat' => 'atk_speed',
                    'name' => 'Tốc độ tấn công',
                    'stat_point' => 1,
                    'value' => 0.0007,
                ],
                'def'  => [
                    'stat' => 'def',
                    'name' => 'Phòng thủ',
                    'stat_point' => 1,
                    'value' => 0.75,
                ],


            ],
            'vit' => [
                'max_hp' => [
                    'stat' => 'max_hp',
                    'name' => 'Sinh lực tối đa',
                    'stat_point' => 1,
                    'value' => 15,
                ],
                'hp_regen' => [
                    'stat' => 'hp_regen',
                    'name' => 'Tốc độ hồi máu',
                    'stat_point' => 5,
                    'value' => 0.0001,
                ],
            ],
            'ene' => [
                'atk' =>  [
                    'stat' => 'atk',
                    'name' => 'Sát thương',
                    'stat_point' => 1,
                    'value' => 4,
                ],
                'max_mp' => [
                    'stat' => 'max_mp',
                    'name' => 'Năng lượng tối đa',
                    'stat_point' => 1,
                    'value' => 10,
                ],
                'mp_regen' => [
                    'stat' => 'mp_regen',
                    'name' => 'Tốc độ hồi năng lượng',
                    'stat_point' => 10,
                    'value' => 0.0001,
                ],
            ],
        ],
    ],
    'exp_re' => [
        1    => 500,
        2    => 1500,
        3    => 2500,
        4    => 3500,
        5    => 4500,
        6    => 5500,
        7    => 6500,
        8    => 7500,
        9    => 8500,
        10    => 11000,
        11    => 13500,
        12    => 16000,
        13    => 18500,
        14    => 21000,
        15    => 23500,
        16    => 26000,
        17    => 28500,
        18    => 31000,
        19    => 33500,
        20    => 43500,
        21    => 53500,
        22    => 63500,
        23    => 73500,
        24    => 83500,
        25    => 93500,
        26    => 103500,
        27    => 113500,
        28    => 123500,
        29    => 133500,
        30    => 158500,
        31    => 183500,
        32    => 208500,
        33    => 233500,
        34    => 258500,
        35    => 283500,
        36    => 308500,
        37    => 333500,
        38    => 358500,
        39    => 383500,
        40    => 433500,
        41    => 483500,
        42    => 533500,
        43    => 583500,
        44    => 633500,
        45    => 683500,
        46    => 733500,
        47    => 783500,
        48    => 833500,
        49    => 883500,
        50    => 983500,
        51    => 1083500,
        52    => 1183500,
        53    => 1283500,
        54    => 1383500,
        55    => 1483500,
        56    => 1583500,
        57    => 1683500,
        58    => 1783500,
        59    => 1883500,
        60    => 2183500,
        61    => 2483500,
        62    => 2783500,
        63    => 3083500,
        64    => 3383500,
        65    => 3683500,
        66    => 3983500,
        67    => 4283500,
        68    => 4583500,
        69    => 4883500,
        70    => 5383500,
        71    => 5883500,
        72    => 6383500,
        73    => 6883500,
        74    => 7383500,
        75    => 7883500,
        76    => 8383500,
        77    => 8883500,
        78    => 9383500,
        79    => 9883500,
        80    => 10683500,
        81    => 11483500,
        82    => 12283500,
        83    => 13083500,
        84    => 13883500,
        85    => 14683500,
        86    => 15483500,
        87    => 16283500,
        88    => 17083500,
        89    => 17883500,
        90    => 19083500,
        91    => 20283500,
        92    => 21483500,
        93    => 22683500,
        94    => 23883500,
        95    => 25083500,
        96    => 26283500,
        97    => 27483500,
        98    => 28683500,
        99    => 29883500,
        100    => 31383500,
        101    => 32883500,
        102    => 34383500,
        103    => 35883500,
        104    => 37383500,
        105    => 38883500,
        106    => 40383500,
        107    => 41883500,
        108    => 43383500,
        109    => 44883500,
        110    => 46883500,
        111    => 48883500,
        112    => 50883500,
        113    => 52883500,
        114    => 54883500,
        115    => 56883500,
        116    => 58883500,
        117    => 60883500,
        118    => 62883500,
        119    => 64883500,
        120    => 69883500,
    ],

    'setting' => [
        'rare' => [
            8 => [
                1 => [
                    'name' => 'Hạ phẩm',
                    'color' => '#000',
                ],
                2 => [
                    'name' => 'Trung phẩm',
                    'color' => '#00ff00',
                ],
                3 => [
                    'name' => 'Thượng phẩm',
                    'color' => '#0000ff',
                ],
                4 => [
                    'name' => 'Cực phẩm',
                    'color' => '#ff00ff',
                ],
                5 => [
                    'name' => 'Tuyệt phẩm',
                    'color' => '#ff0000',
                ],
            ],
            9 => [
                1 => ['name' => 'Thiên cấp -', 'color' => '#000'],
                2 => ['name' => 'Địa cấp -', 'color' => '#00ff00'],
                3 => ['name' => 'Huyền cấp -', 'color' => '#0000ff'],
                4 => ['name' => 'Hoàng cấp -', 'color' => '#ff00ff'],
                5 => ['name' => 'Thần cấp -', 'color' => '#ff0000'],
            ],
            15 => [
                1 => [
                    'name' => 'Hạ phẩm',
                    'color' => '#000',
                ],
                2 => [
                    'name' => 'Trung phẩm',
                    'color' => '#00ff00',
                ],
                3 => [
                    'name' => 'Thượng phẩm',
                    'color' => '#0000ff',
                ],
                4 => [
                    'name' => 'Cực phẩm',
                    'color' => '#ff00ff',
                ],
                5 => [
                    'name' => 'Tuyệt phẩm',
                    'color' => '#ff0000',
                ],
            ],
        ],

        'max_level' => 120,
        'exp' => 1,
        'min_collect' => 180,
        'max_collect' => 900,
        'slot_ruong' => 10,
        'max_lv_ruong' => 10,
        'max_op' => 4,
        'min_op' => 2,
        'min_rare' => 1,
        'max_rare' => 4,
        'min_online' => 60,
        'item_type' => [
            1 => 'Vũ khí',
            2 => 'Áo',
            3 => 'Quần',
            4 => 'Giày',
            5 => 'Nón',
            6 => 'Dây chuyền',
            7 => 'Nhẫn',
            8 => 'Đan dược',
            9 => 'Công pháp',
            10 => 'Bảo vật',
            11 => 'Phù',
            12 => 'Lệnh bài',
            13 => 'Vật phẩm nhiệm vụ',
            14 => 'Vật phẩm đặc biệt',
            15 => 'Linh thạch',
        ],
        'item_rate' => [
            8 => 98,
            9 => 0,
            15 => 2,

        ],
        'item_rare' => [
            1 => 0,
            2 => 80,
            3 => 15,
            4 => 5,
            5 => 0,
        ],
    ],

    'item' => [
        'item_options' => [
            1 => [
                'name' => 'Sức mạnh: +%1$.2f',
                'stat' => 'sum_str',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            2 => [
                'name' => 'Nhanh nhẹn: +%1$.2f',
                'stat' => 'sum_agi',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            3 => [
                'name' => 'Thể lực: +%1$.2f',
                'stat' => 'sum_vit',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            4 => [
                'name' => 'Năng lượng: +%1$.2f điểm',
                'stat' => 'sum_ene',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],

            5 => [
                'name' => 'Sát thương: +%1$.2f',
                'stat' => 'sum_atk',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            6 => [
                'name' => 'Phòng thủ: +%1$.2f',
                'stat' => 'sum_def',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            7 => [
                'name' => 'Tốc độ đánh: +%1$.4f',
                'stat' => 'sum_atk_speed',
                'value' => 0.001,
                'percent' => false,
                'mul' => false,
            ],

            8 => [
                'name' => 'Sinh lực: +%1$.2f',
                'stat' => 'sum_max_hp',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],

            9 => [
                'name' => '%2$s: +%1$.2f',
                'stat' => 'sum_max_mp',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            10 => [
                'name' => 'Né tránh: +%1$.4f',
                'stat' => 'sum_dodge',
                'value' => 1,
                'percent' => false,
                'mul' => true
            ],
            11 => [
                'name' => 'Chí mạng: +%1$.4f',
                'stat' => 'sum_crit',
                'value' => 1,
                'percent' => false,
                'mul' => true
            ],
            12 => [
                'name' => 'Sát thương chí mạng: +%1$.4f',
                'stat' => 'sum_crit_dmg',
                'value' => 1,
                'percent' => false,
                'mul' => true,
            ],
            13 => [
                'name' => 'Tốc độ hồi sinh lực mỗi phút: +%1$.4f',
                'stat' => 'sum_hp_regen',
                'value' => 1,
                'percent' => false,
                'mul' => true,
            ],
            14 => [
                'name' => 'Tốc độ hồi %2$s mỗi phút: +%1$.4f',
                'stat' => 'sum_mp_regen',
                'value' => 1,
                'percent' => false,
                'mul' => true,
            ],
            15 => [
                'name' => 'Tu vi: +%1$d',
                'stat' => 'exp',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            16 => [
                'name' => 'Căn cơ: +%1$.4f',
                'stat' => 'can_co',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            17 => [
                'name' => 'Khí vận: +%1$.4f',
                'stat' => 'luk',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],

            18 => [
                'name' => 'Level: +%1$.d',
                'stat' => 'level',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            19 => [
                'name' => 'Vip: +%1$.d ngày',
                'stat' => 'vip_time',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            20 => [
                'name' => 'Vip: +%1$.d giờ',
                'stat' => 'vip_time',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            21 => [
                'name' => 'Vip: +%1$.d phút',
                'stat' => 'vip_time',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            22 => [
                'name' => 'Vip: +%1$.d giây',
                'stat' => 'vip_time',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            23 => [
                'name' => 'Linh thạch: +%1$.d',
                'stat' => 'linh_thach',
                'value' => 1,
                'percent' => false,
                'mul' => false,
            ],
            24 => [
                'name' => 'Sinh lực: +%1$.2f%%',
                'stat' => 'sum_max_hp',
                'value' => 1,
                'percent' => true,
                'mul' => false,
            ],
            25 => [
                'name' => '%2$s: +%1$.2f%%',
                'stat' => 'sum_max_mp',
                'value' => 1,
                'percent' => true,
                'mul' => false,
            ],
            26 => [
                'name' => 'Tốc độ hồi sinh lực mỗi phút: +%1$.4f%%',
                'stat' => 'sum_hp_regen',
                'value' => 1,
                'percent' => true,
                'mul' => true,
            ],
            27 => [
                'name' => 'Tốc độ hồi %2$s mỗi phút: +%1$.4f%%',
                'stat' => 'sum_mp_regen',
                'value' => 1,
                'percent' => true,
                'mul' => true,
            ],
            28 => [
                'name' => 'Sát thương: +%1$.2f%%',
                'stat' => 'sum_atk',
                'value' => 1,
                'percent' => true,
                'mul' => false,
            ],
            29 => [
                'name' => 'Phòng thủ: +%1$.2f%%',
                'stat' => 'sum_def',
                'value' => 1,
                'percent' => true,
                'mul' => false,
            ],
            30 => [
                'name' => 'Tốc độ đánh: +%1$.4f%%',
                'stat' => 'sum_atk_speed',
                'value' => 1,
                'percent' => true,
                'mul' => true,
            ],
            31 => [
                'name' => 'Sát thương chí mạng: +%1$.4f%%',
                'stat' => 'sum_crit_dmg',
                'value' => 1,
                'percent' => true,
                'mul' => true,
            ],
            32 => [
                'name' => 'Tỷ lệ chí mạng: +%1$.4f%%',
                'stat' => 'sum_crit',
                'value' => 1,
                'percent' => true,
                'mul' => true,
            ],
            33 => [
                'name' => 'Né tránh: +%1$.4f%%',
                'stat' => 'sum_dodge',
                'value' => 1,
                'percent' => true,
                'mul' => true,
            ],





        ],


        'item_list' => [
            8 => [
                1 => [

                    'name' => 'Tụ khí đan',
                    'gioi_thieu' => 'Do admin giáng thế luyện 7x7 49 ngày mới ra lò, ăn đan này sẽ được tăng tu vi.',
                    'img' => '1.png',
                    'type' => 8,
                    'level' => 1,
                    'rate' => 80,
                    'rare' => [1 => 0, 2 => 80, 3 => 15, 4 => 5, 5 => 0],
                    'rare_add_op' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],

                    'price' => 1000,
                    'price_sell' => 100,
                    'op_bs' => [15 => 100],
                    'op_av' => [],

                    'is_op_bs' => true,

                    'is_op_av' => false,
                    'is_random_op' => false,
                    'is_random' => true,
                    'is_stack' => true,


                    'type_buff' => 1,
                    'type_action' => 1,
                    'max_stack' => 255,

                    'op_bs_count' => 2,
                    'op_av_count' => 3,
                    'op_value' => [
                        15 => [

                            'min' => [
                                1 => 10,
                                2 => 20,
                                3 => 30,
                                4 => 50,
                                5 => 100,
                            ],
                            'max' => [
                                1 => 10,
                                2 => 20,
                                3 => 30,
                                4 => 50,
                                5 => 100,
                            ],
                        ],
                        5 =>[

                            'min' => [
                                1 => 10,
                                2 => 20,
                                3 => 30,
                                4 => 50,
                                5 => 100,
                            ],
                            'max' => [
                                1 => 10,
                                2 => 20,
                                3 => 30,
                                4 => 50,
                                5 => 100,
                            ],
                        ],

                    ],


                ],
                2 => [

                    'name' => 'Tụ chiến đan',
                    'gioi_thieu' => 'Không biết do ai luyện được, khi dùng sẽ tăng sát thương.',
                    'img' => '1.png',
                    'type' => 8,
                    'level' => 1,
                    'rate' => 5,
                    'rare' => [1 => 0, 2 => 80, 3 => 15, 4 => 5, 5 => 0],
                    'rare_add_op' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],

                    'price' => 1000,
                    'price_sell' => 100,
                    'op_bs' => [5 => 100],
                    'op_av' => [],

                    'is_op_bs' => true,

                    'is_op_av' => false,
                    'is_random_op' => false,
                    'is_random' => true,
                    'is_stack' => true,


                    'type_buff' => 1,
                    'type_action' => 1,
                    'max_stack' => 255,

                    'op_bs_count' => 2,
                    'op_av_count' => 3,
                    'op_value' => [
                        5 => [

                            'min' => [
                                1 => 3,
                                2 => 5,
                                3 => 7,
                                4 => 10,
                                5 => 20,
                            ],
                            'max' => [
                                1 => 3,
                                2 => 5,
                                3 => 7,
                                4 => 10,
                                5 => 20,
                            ],
                        ],

                    ],


                ],
				3 =>[

                    'name' => 'Tụ lực đan',
                    'gioi_thieu' => 'Đan dược do dược vương cốc chế luyện, khi dùng sẽ tăng phòng thủ.',
                    'img' => '1.png',
                    'type' => 8,
                    'level' => 1,
                    'rate' => 5,
                    'rare' => [1 => 0, 2 => 80, 3 => 15, 4 => 5, 5 => 0],
                    'rare_add_op' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],

                    'price' => 1000,
                    'price_sell' => 100,
                    'op_bs' => [6 => 100],
                    'op_av' => [],

                    'is_op_bs' => true,

                    'is_op_av' => false,
                    'is_random_op' => false,
                    'is_random' => true,
                    'is_stack' => true,


                    'type_buff' => 1,
                    'type_action' => 1,
                    'max_stack' => 255,

                    'op_bs_count' => 2,
                    'op_av_count' => 3,
                    'op_value' => [
                        6 => [

                            'min' => [
                                1 => 3,
                                2 => 5,
                                3 => 7,
                                4 => 10,
                                5 => 20,
                            ],
                            'max' => [
                                1 => 3,
                                2 => 5,
                                3 => 7,
                                4 => 10,
                                5 => 20,
                            ],
                        ],

                    ],


                ],
                4=>[

                    'name' => 'Tụ huyết đan',
                    'gioi_thieu' => 'Do một tu sĩ bị tụ máu não luyện chế, khi dùng sẽ tăng sinh lực.',
                    'img' => '1.png',
                    'type' => 8,
                    'level' => 1,
                    'rate' => 5,
                    'rare' => [1 => 0, 2 => 80, 3 => 15, 4 => 5, 5 => 0],
                    'rare_add_op' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],

                    'price' => 1000,
                    'price_sell' => 100,
                    'op_bs' => [8 => 100],
                    'op_av' => [],

                    'is_op_bs' => true,

                    'is_op_av' => false,
                    'is_random_op' => false,
                    'is_random' => true,
                    'is_stack' => true,


                    'type_buff' => 1,
                    'type_action' => 1,
                    'max_stack' => 255,

                    'op_bs_count' => 2,
                    'op_av_count' => 3,
                    'op_value' => [
                        8 => [

                            'min' => [
                                1 => 20,
                                2 => 30,
                                3 => 50,
                                4 => 80,
                                5 => 160,
                            ],
                            'max' => [
                                1 => 20,
                                2 => 30,
                                3 => 50,
                                4 => 80,
                                5 => 160,
                            ],
                        ],

                    ],


                ],
                5=>[

                    'name' => 'Tụ nguyên đan',
                    'gioi_thieu' => 'Sản phẩm chất lượng cao đến từ đan tháp, do đan vương nào đó quên tên rồi. Khi dùng sẽ tăng "tiên lực, nội lực, hoặc ma lực" tuỳ hệ.',
                    'img' => '1.png',
                    'type' => 8,
                    'level' => 1,
                    'rate' => 5,
                    'rare' => [1 => 0, 2 => 80, 3 => 15, 4 => 5, 5 => 0],
                    'rare_add_op' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],

                    'price' => 1000,
                    'price_sell' => 100,
                    'op_bs' => [9 => 100],
                    'op_av' => [],

                    'is_op_bs' => true,

                    'is_op_av' => false,
                    'is_random_op' => false,
                    'is_random' => true,
                    'is_stack' => true,


                    'type_buff' => 1,
                    'type_action' => 1,
                    'max_stack' => 255,

                    'op_bs_count' => 2,
                    'op_av_count' => 3,
                    'op_value' => [
                        9 => [

                            'min' => [
                                1 => 20,
                                2 => 30,
                                3 => 50,
                                4 => 80,
                                5 => 160,
                            ],
                            'max' => [
                                1 => 20,
                                2 => 30,
                                3 => 50,
                                4 => 80,
                                5 => 160,
                            ],
                        ],

                    ],


                ]
            ],
            9 => [
                1 => [

                    'name' => 'Thôn Thiên Ma Công',
                    'gioi_thieu' => 'Do Ngoan nhân đại đế sáng tạo công pháp, tu luyện công pháp này thì khắp thiên hạ đều là địch.',
                    'img' => '1.png',
                    'type' => 8,
                    'level' => 1,
                    'rate' => 10,
                    'rare' => [1 => 60, 2 => 25, 3 => 10, 4 => 5, 5 => 0],
                    'rare_add_op' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],

                    'price' => 1000,
                    'price_sell' => 100,
                    'op_bs' => [5 => 10,6 => 10,7 => 10,8 => 10,9 => 10],
                    'op_av' => [],

                    'is_op_bs' => true,

                    'is_op_av' => false,
                    'is_random_op' => false,
                    'is_random' => true,
                    'is_stack' => true,


                    'type_buff' => 1,
                    'type_action' => 3,
                    'max_stack' => 255,

                    'op_bs_count' => 2,
                    'op_av_count' => 3,
                    'op_value' => [
                        5 => [

                            'min' => [
                                1 => 20,
                                2 => 30,
                                3 => 50,
                                4 => 80,
                                5 => 160,
                            ],
                            'max' => [
                                1 => 20,
                                2 => 30,
                                3 => 50,
                                4 => 80,
                                5 => 160,
                            ],
                        ],

                    ],


                ]
            ],
			15 => [
				1 => [

                    'name' => 'Thôn Thiên Ma Công',
                    'gioi_thieu' => 'Do Ngoan nhân đại đế sáng tạo công pháp, tu luyện công pháp này thì khắp thiên hạ đều là địch.',
                    'img' => '1.png',
                    'type' => 8,
                    'level' => 1,
                    'rate' => 10,
                    'rare' => [1 => 60, 2 => 25, 3 => 10, 4 => 5, 5 => 0],
                    'rare_add_op' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],

                    'price' => 1000,
                    'price_sell' => 100,
                    'op_bs' => [5 => 10,6 => 10,7 => 10,8 => 10,9 => 10],
                    'op_av' => [],

                    'is_op_bs' => true,

                    'is_op_av' => false,
                    'is_random_op' => false,
                    'is_random' => true,
                    'is_stack' => true,


                    'type_buff' => 1,
                    'type_action' => 3,
                    'max_stack' => 255,

                    'op_bs_count' => 2,
                    'op_av_count' => 3,
                    'op_value' => [
                        5 => [

                            'min' => [
                                1 => 20,
                                2 => 30,
                                3 => 50,
                                4 => 80,
                                5 => 160,
                            ],
                            'max' => [
                                1 => 20,
                                2 => 30,
                                3 => 50,
                                4 => 80,
                                5 => 160,
                            ],
                        ],

                    ],


                ]
			]

        ],



    ],
];
