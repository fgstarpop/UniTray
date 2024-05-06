<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Http\Client\Pool;

class ReadFree implements ShouldQueue ,ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $story;
    public $uniqueFor = 1800;
    public $failOnTimeout = false;
    public function __construct($story)
    {
        $this->story = $story;
    }

    public function handle()
    {
        $story = $this->story;
        $url =  $story->origin;
        $data = Http::get("http://103.75.182.190:8000/getlink?link=$url")->json();
        // dd($data);
        $chapterList = $data['listchap'];
        $bookid = $data['bookid'];

        $lock = Cache::lock("freeread$bookid", 1800);
        if ($lock->get()) {
            try{
                foreach ($chapterList as $key => $chapter){

                    if(!$chapter['vip']){

                        $chapid = $chapter['id'];
                        $socks = [
                            'proxy' => config("vipfaloo.proxy")
                        ];
                        $header = [
                            "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0",
                            "Referer" => "https://b.faloo.com/",
                            "Cookie" => "curr_url=https%3A//b.faloo.com/$bookid/$chapid.html; host4chongzhi=b.faloo.com; " . config('vipfaloo.cookie')
                        ];
                        $reobj = '#ObjectKey="(?<key>.*?)";.*?ObjectNode=(?<n>.*?);var.*?NovelClass=(?<nc>.*?);#s';
                        $response = Http::withOptions($socks)->timeout(10)->withHeaders($header)->get("https://b.faloo.com/$bookid/$chapid.html")->body();
                        //convert text từ gb2312 sang utf-8
                        $response = mb_convert_encoding($response, 'utf-8', 'gb2312');
                        preg_match($reobj, $response, $obj);

                        try {
                            $urlobj = "https://dongtai.faloo.com/novel/AppCounter.aspx?id=$bookid&nc=" . $obj['key'] . "&k=" . $obj['key'] . "&n=$chapid";

                            $urlpv = "https://flux.faloo.com/pvdata.aspx?faloo_ch_id=3&faloo_ref=https://b.faloo.com/$bookid/$chapid.html";

                            Http::pool(function (Pool $pool) use ($header, $urlobj, $urlpv,$socks) {
                                return [
                                    $pool->withOptions($socks)->timeout(10)->withHeaders($header)->get($urlpv),
                                    $pool->withOptions($socks)->timeout(10)->withHeaders($header)->get($urlobj),
                                ];
                            });
                        } catch (\Throwable $th) {
                            //throw $th;
                        }

                        sleep(random_int(5, 60));
                    }
                }
            }catch (\Throwable $th) {
                //throw $th;
            }

            $story->update(["confirm_buy"=>1]);
            $lock->release();
        }
    }
}
