<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Analytics;
use Spatie\Analytics\Period;
use App\Http\Controllers\Controller;

class MostVisitedPageController extends Controller
{
    public function __invoke()
    {
        $startDate = Carbon::today(config('app.timezone'))->startOfMonth();
        $endDate = Carbon::today(config('app.timezone'))->endOfMonth();
        $period = Period::create($startDate, $endDate);

        $mostVisitPages = Analytics::fetchMostVisitedPages($period, 10);

        return view('admin.dashboards.mostVisitedPage', compact('mostVisitPages'));
    }
}
