<?php

use Illuminate\Database\Seeder;
use App\Domain\Category\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            array(
                'name' => 'Huyễn huyễn',
                'status' => Category::ACTIVE,
                'name_chines' => json_encode(array('玄幻', '玄幻奇幻', '异世大陆', '神话传说', '东方玄幻', '转世重生',
                    '王朝争霸', '上古神话', '变身情缘', '穿越附身')),
            ),
            array(
                'name' => 'Đô thị',
                'status' => Category::ACTIVE,
                'name_chines' => json_encode(array('都市', '都市言情', '浪漫言情', '都市生活', '都市异能', '商海沉浮',
                    '宦海风云', '职场生涯', '豪门恩怨', '青春校园', '菁菁校园', '校园言情', '另类校园', '贵爵童话',
                    '魔法校园', '校园同人')),
            ),
            array(
                'name' => 'Võ hiệp/tiên hiệp',
                'status' => Category::ACTIVE,
                'name_chines' => json_encode(array('武侠', '仙侠', '武侠仙侠', '传统武侠', '武侠修真', '浪子异侠',
                    '古典仙侠', '星际修真', '现代修真')),
            ),
            array(
                'name' => 'Lịch sử/quân sự',
                'status' => Category::ACTIVE,
                'name_chines' => json_encode(array('历史', '军事', '军事历史', '穿越时空', '架空历史', '历史传记',
                    '三国梦想', '人文历史', '现代战争', '战争幻想', '特种军旅')),
            ),
            array(
                'name' => 'Đồng nhân',
                'status' => Category::ACTIVE,
                'name_chines' => json_encode(array('同人小说', '动漫同人', '小说同人', '影视同人')),
            ),
            array(
                'name' => 'Linh dị',
                'status' => Category::ACTIVE,
                'name_chines' => json_encode(array('悬疑', '恐怖灵异', '恐怖惊悚', '推理悬念', '灵异鬼怪', '神秘时空')),
            ),
            array(
                'name' => 'Võng du/cạnh kỹ',
                'status' => Category::ACTIVE,
                'name_chines' => json_encode(array('小说', '游戏竞技', '科幻网游', '虚拟网游', '电子竞技', '足球运动',
                    '篮球运动', '其它竞技')),
            ),
            array(
                'name' => 'Tiểu thuyết nữ',
                'status' => Category::ACTIVE,
                'name_chines' => json_encode(array('女生小说', '穿越附身', '都市言情', '女尊天下', '架空历史', '豪门恩怨',
                    '重生异能', '古典言情')),
            ),
            array(
                'name' => 'Ngôn tình',
                'status' => Category::ACTIVE,
                'name_chines' => json_encode(array('浪漫青春', '玄幻言情', '现代言情')),
            ),
            array(
                'name' => 'Tiểu thuyết nhẹ',
                'status' => Category::ACTIVE,
                'name_chines' => json_encode(array('轻小说', '轻幻想', '重幻想', '日常类',
                    '短篇其他', '历史演义', '散文诗词', '休闲美文', '杂文笔记', '短篇小说', '改编剧本', '其他')),
            ),
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
