<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;
use Session;

class Filter
{
    private $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $chapter = $this->getViewedChapter();

        if (!is_null($chapter))
        {
            $chapter = $this->cleanExpiredViews($chapter);
            $this->storeChapter($chapter);
        }

        return $next($request);
    }

    private function getViewedChapter()
    {
        return $this->session->get('viewed_chapter', null);
    }

    private function cleanExpiredViews($chapter)
    {
        $time = time();

        // Let the views expire after one hour.
        $throttleTime = 3600;

        return array_filter($chapter, function ($timestamp) use ($time, $throttleTime)
        {
            return ($timestamp + $throttleTime) > $time;
        });
    }

    private function storeChapter($chapter)
    {
        $this->session->put('viewed_chapter', $chapter);
    }
}
