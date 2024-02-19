<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LogSearchDataTable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Domain\LogSearch\Models\LogSearch;

class LogSearchController
{
    use AuthorizesRequests;

    public function index(LogSearchDataTable $dataTable)
    {
        $this->authorize('view', LogSearch::class);
        return $dataTable->render('admin.log_search.index');
    }
}
 