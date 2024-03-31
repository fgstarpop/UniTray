<?php

namespace App\Jobs;

use DOMDocument;
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
            $originalDescription = $this->_collectOriginalText($this->chapter->content);

            $options = array(
                'ignore_errors' => true,
                'char_set'  =>  'utf-8'
            );

            $storyDescription = str_replace(["\n", "</br>"], "<br/>", $storyDescription);
            $chapterDescription = str_replace(["\n", "</br>"], "<br/>", $chapterDescription);
            $data = [
                "source" => $source,
                "story" => [
                    "id" => $storyID,
                    "name" => $storyName,
                    "original_description" => "",
                    "description" => \Soundasleep\Html2Text::convert($storyDescription, $options)
                ],
                "chapters" => [
                    [
                        "id" => $chapterID,
                        "name" => $chapterName,
                        "is_vip" => $chapterIsVip,
                        "original_description" => $originalDescription,
                        "description" => \Soundasleep\Html2Text::convert($chapterDescription, $options)
                    ]
                ]
            ];
            if (!empty($chapterDescription) && strlen($chapterDescription) > 10) {
                $client = new Client();
                $response = $client->post('https://api.truyenfox.net/api/file/generate', [
                    'json' => $data
                ]);
                // $response->getBody()->getContents();
            }
        } catch (\Throwable $th) {
        }

    }

    private function _collectOriginalText($_htmlContent) {
        $contentOriginal = "";

        // Create a new DOMDocument
        $doc = new DOMDocument();

        // Suppress errors and warnings during HTML parsing
        libxml_use_internal_errors(true);

        try {
            // Load HTML content into the DOMDocument with UTF-8 encoding
            $doc->loadHTML('<?xml encoding="UTF-8">' . $_htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        } catch (\Exception $e) {
            // Handle parsing errors
        }

        // Restore error handling to its original state
        libxml_use_internal_errors(false);

        // Find all <p> elements
        $pElements = $doc->getElementsByTagName('p');

        // Iterate over <p> elements
        foreach ($pElements as $pElement) {
            // Get HTML content of the <p> element
            $htmlContent = $doc->saveHTML($pElement);

            // Define the regular expression pattern
            $regexPattern = '/<i\s+[^>]*\bt\s*=\s*["\']([^"\']*)["\'][^>]*>(.*?)<\/i>([,\s]*)/';

            // Find all matches of <i> elements within <p> elements
            preg_match_all($regexPattern, $htmlContent, $matches, PREG_SET_ORDER);

            // Iterate over matches
            foreach ($matches as $match) {
                // $match[1] contains the value of the 't' attribute
                // $match[2] contains the text content inside the <i> element

                $contentOriginal .= $match[1] . $match[3];
            }

            $contentOriginal .= "\n\n";
        }

        return $contentOriginal;
    }
}
