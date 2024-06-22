<?php

namespace App\Http\Controllers\Shop;

use App\Domain\Chapter\Models\Chapter;
use App\Jobs\GenerateAudioJob;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChapterAPIController
{

    /**
     * For player
     * */
    public function fetch($lastId, Request $request)
    {
        $chapter = Chapter::with(['story'])->where('id', '>', $lastId)->orderBy('id', 'asc')->first();

        if (!strpos($chapter->content, '<i h=')) {
            $chapter->content = translate(trim($chapter->content));
        }

        if (empty($chapter['content']) && !empty($chapter['telegram_id'])) {
            $dataFromTele = $this->getRemoteData($chapter['telegram_id'], $chapter['story_id'], $chapter["id"]);
            $chapter['content'] = $dataFromTele;
        }

        if (config('constants.GENERATING_AUDIO', false)) {
            // Dispatch GenerateAudioJob
            GenerateAudioJob::dispatch($chapter)
                ->afterResponse()->onQueue('redis');
        }

        return ['last_id' => $chapter->id];
    }

    public function getRemoteData($telegram_id, $storyId, $id)
    {
        $content = '';
        try {
            $url = "http://154.26.130.48:3002/chapter/file-by-id/{$telegram_id}/storyId/{$storyId}/chapterId/${id}";
            $client = new Client();
            $response = $client->get($url);

            $content = $response->getBody()->getContents();
        } catch (\Throwable $th) {
        }


        return $content;
    }

    public function getRemoteVipData($storyId, $id)
    {
        $url = "http://154.26.130.48:3002/chapter/vip/storyId/{$storyId}/id/{$id}";


        $client = new Client();
        $response = $client->get($url);

        if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();
            return $content;
        } else {
            return "Không thể lấy dữ liệu từ URL.";
        }
    }

    public function extractNumbersFromURL($url)
    {
        if (preg_match('/(\d+)_(\d+)\.html/', $url, $matches)) {
            $firstNumber = $matches[1];
            $secondNumber = $matches[2];
            return [$firstNumber, $secondNumber];
        } else {
            return false;
        }
    }
}
