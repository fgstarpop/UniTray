<?php

namespace App\Scraper;

use App\Domain\Chapter\Models\Chapter;
use App\Domain\Story\Models\Story;
use Goutte\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class LeechXinyushuwu
{
    /**
     * @var array
     */
    private $_book;

    /**
     * @var array
     */
    private $_chapter;

    /**
     * Scrape qimao construct
     *
     * @param array $book
     * @param array $chapter
     */
    public function __construct(
        array $book = [],
        array $chapter = []
    ) {
        $this->_book = $book;
        $this->_chapter = $chapter;
    }

    /**
     * Scrape story
     *
     * @param $bookId
     * @param $domain
     * @param string $middlePart
     * @param null $user
     * @param string $fullUrl
     * @param bool $isNotAuto
     * @return bool
     */
    public function scrape($bookId, $domain, $middlePart = '', $user = null, $fullUrl = '', $isNotAuto = false)
    {
        if (!empty($fullUrl)) {
            $this->_book['url'] = $fullUrl;
            $link = filter_var($fullUrl, FILTER_VALIDATE_URL);
            $parse = parse_url($link);
            $this->_book['domain'] = $parse['scheme'] . '://' . $parse['host'];
            $this->_book['id'] = explode('/', $parse['path'])[2];
        } else {
            $this->_book['id'] = $bookId;
            $this->_book['domain'] = $domain;
            $this->_book['url'] = "{$domain}{$middlePart}{$bookId}/";
        }
        $listStory = Story::where('origin', $this->_book['url']);
        if ($listStory->exists()) {
            return false;
        }
        $client = new Client();

        $crawler = $client->request('GET', $this->_book['url']);

        if ($crawler->filter('.jump')->count() > 0) {
            return false;
        }
        $this->_book['count_chapters'] = $crawler->filter('.main .ml_content .ml_list > ul > li')->count();
        if ($this->_book['count_chapters'] < 20 && !$isNotAuto) {
            return false;
        }
        $crawler->filter('.main .catalog .catalog1')->each(
            function (Crawler $node) {
                $this->_book['thumb'] = $this->_book['domain'] . $node->filter('.pic img')->attr('src');
                $this->_book['name'] = $node->filter('.introduce h1')->text();
                $this->_book['author'] = str_replace('作者：', '', $node->filter('.introduce .bq > span:nth-child(2)')->text());
                $this->_book['status'] = str_replace('状态：', '', $node->filter('.introduce .bq > span:nth-child(3)')->text());
                $this->_book['description'] = $node->filter('.introduce .jj')->text();
            }
        );
        $crawler->filter('.main .ml_content .ml_list > ul > li')->each(
            function (Crawler $node) {
                $this->_book['chapters'][] = [
                    "name" => $node->filter('a')->text(),
                    "embed_link" => $node->filter('a')->attr('href')
                ];
            }
        );

        try {
            switch ($this->_book['status']) {
                case "连载中":
                    $this->_book['status'] = Story::CONTINUE;
                    break;
                case "完结":
                    $this->_book['status'] = Story::FINISH;
                    break;
                default:
                    $this->_book['status'] = Story::ACTIVE;
            }
            $story = Story::create([
                'name' => _vp_viet($this->_book['name']),
                'description' => _vp_viet($this->_book['description']),
                'user_id' => $user ? $user->id : 0,
                'type' => 1,
                'donate' => 0,
                'status' => $this->_book['status'],
                'is_vip' => 0,
                'origin' => $this->_book['url'],
                'avatar' => $this->_book['thumb'],
                'author' => $this->_book['author'],
                'author_vi' => _vp_viet($this->_book['author']),
                'name_chines' => $this->_book['name'],
                'tags' => '',
                'count_chapters' => $this->_book['count_chapters'],
                'idhost' => $this->_book['id'],
                'host' => Story::ORIGINS[$this->_book['domain']],
                'chapter_updated' => Carbon::now(),
            ]);
            $this->_book['story_id'] = $story->id;
            foreach ($this->_book['chapters'] as $key => $chapter) {
                $this->_book['list_chapter'][] = [
                    "story_id" => $this->_book['story_id'],
                    "name" => $chapter['name'],
                    "embed_link" => "{$this->_book['url']}{$chapter["embed_link"]}",
                    "status" => Chapter::ACTIVE,
                    "order" => $key
                ];
            }
            $this->_book['list_chapter'] = translateArray($this->_book['list_chapter']);
            $story->update([
                'chapters_json' => _vp_viet(json_encode($this->_book['list_chapter']))
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Scrape chap
     *
     * @param Story $story
     * @param null $embedLink
     * @return bool
     */
    public function scrapeChapter( Story $story, $embedLink = null)
    {
        if (!empty($embedLink)) {
            $this->_chapter['embed_link'] = $embedLink;

            $client = new Client();

            $crawler = $client->request('GET', $this->_chapter['embed_link']);
            $this->_chapter['content'] = $crawler->filter('.main .main_content .articlecontent')->html();
            if (empty($this->_chapter['content'])) {
                return false;
            }
            $this->_chapter['content'] = translate(trim($this->_chapter['content']));
            $this->_chapter['content'] = strip_tags($this->_chapter['content'], array('<i>', '<br>', '<p>'));
            $chapterJson = json_decode($story->chapters_json, true);
            foreach ($chapterJson as &$item) {
                if ($item['embed_link'] == $embedLink) {
                    $url = filter_var($embedLink, FILTER_VALIDATE_URL);
                    $parse = parse_url($url);
                    $this->_chapter['name'] = $item['name'];
                    $this->_chapter['story_id'] = $item['story_id'];
                    $this->_chapter['is_vip'] = $story->is_vip;
                    $this->_chapter['status'] = Chapter::ACTIVE;
                    $this->_chapter['order'] = $item['order'];
                    $this->_chapter['host'] = Story::ORIGINS[$parse['scheme'] . '://' . $parse['host']];
                    $this->_chapter['idhost'] = '';
                    $this->_chapter['mod_id'] = null;

                    $chap = Chapter::create($this->_chapter);

                    $this->_chapter['id'] = $chap->id;
                    $item = [
                        "name" => $this->_chapter['name'],
                        "story_id" => $this->_chapter['story_id'],
                        "status" => $this->_chapter['status'],
                        "order" => $this->_chapter['order'],
                        "embed_link" => $this->_chapter['embed_link'],
                        "is_vip" => $this->_chapter['is_vip'],
                        "mod_id" => $this->_chapter['mod_id'],
                        "host" => $this->_chapter['host'],
                        "idhost" => $this->_chapter['idhost'],
                        "idchap" => "68",
                        "updated_at" => Carbon::now(),
                        "created_at" => Carbon::now(),
                        "id" => $chap->id
                    ];
                    break;
                }
            }
            $story->update([
                'chapters_json' => json_encode($chapterJson)
            ]);
            return true;
        }
        return false;
    }
}
