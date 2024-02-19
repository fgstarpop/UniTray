<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\GoldGiftDataTable;
use Illuminate\Http\Request;
use App\Domain\Admin\Models\GoldGift;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GoldGiftController
{
    use AuthorizesRequests;

    public function index(GoldGiftDataTable $dataTable)
    {
        $this->authorize('view', GoldGift::class);
        return $dataTable->render('admin.gold-gifts.index');
    }
}
