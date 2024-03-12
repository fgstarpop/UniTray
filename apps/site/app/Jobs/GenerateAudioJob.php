<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;

class GenerateAudioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $chapter;

    public function __construct($chapter)
    {
        $this->chapter = $chapter;
    }

    public function handle()
    {
        try {
            $source = 'giangthe';
            $storyID = $this->chapter->story->id;
            $storyName = $this->chapter->story->name;
            $storyDescription = $this->chapter->story->description;
            $chapterID = $this->chapter->id;
            $chapterName = $this->chapter->name;
            $chapterIsVip = $this->chapter->is_vip ? true : false;
            $chapterDescription = $this->chapter->content;

            $data = [
                "source" => $source,
                "story" => [
                    "id" => $storyID,
                    "name" => $storyName,
                    "original_description" => "",
                    "description" => strip_tags($storyDescription)
                ],
                "chapters" => [
                    [
                        "id" => $chapterID,
                        "name" => $chapterName,
                        "is_vip" => $chapterIsVip,
                        "original_description" => "",
                        "description" => strip_tags($chapterDescription)
                    ]
                ]
            ];

            $client = new Client();
            $response = $client->post('https://api.truyenfox.net/api/file/generate', [
                'json' => $data
            ]);

            $response->getBody()->getContents();
        } catch (\Throwable $th) {
        }

    }
}
