<?php
namespace App\Listeners;

use App\Domain\Story\Models\Story;
use App\Events\ChapterViewed;
use Illuminate\Session\Store;

class ViewChapterHandler
{
    private $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    public function handle(ChapterViewed $event)
    {
        $chapter = $event->chapter;
        if (!$this->isChapterViewed($chapter))
        {
            $chapter->increment('view');
            Story::where('id', $chapter->story_id)->increment('view');
            $this->storeChapter($chapter);
        }
    }

    private function isChapterViewed($chapter)
    {
        $viewed = $this->session->get('viewed_chapter', []);

        return array_key_exists($chapter->id, $viewed);
    }

    private function storeChapter($chapter)
    {
        $key = 'viewed_chapter.' . $chapter->id;

        $this->session->put($key, time());
    }
}
