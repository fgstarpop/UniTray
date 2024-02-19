<?php

namespace App\Composers;

use App\Domain\Menu\Models\MenuItem;
use App\Domain\Page\Models\Page;
use App\Enums\PageState;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Bind data to view.
     */
    public function compose(View $view)
    {
        $pageMenus = Cache::rememberForever('menu-page', function () {
            return Page::where('status', PageState::Active())->get();
        });
        $view->with('pageMenus', $pageMenus);
    }
}
