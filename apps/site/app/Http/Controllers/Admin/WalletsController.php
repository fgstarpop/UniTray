<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WalletDataTable;
use Illuminate\Http\Request;
use App\Domain\Admin\Models\Wallet;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use App\Http\Requests\Admin\WalletRequest;
class WalletsController
{
    use AuthorizesRequests;

    public function index(WalletDataTable $dataTable)
    {
        $this->authorize('view', Wallet::class);
        return $dataTable->render('admin.wallets.index');
    }
 
    public function edit(Wallet $wallet): View
    {
        $this->authorize('update', $wallet);

        return view('admin.wallets.edit', compact('wallet'));
    }

    public function update(Wallet $wallet, WalletRequest $request)
    {
        $this->authorize('update', $wallet);

        $wallet->update($request->all());

        flash()->success(__('Ví đã cập nhật thành công !', ['model' => $wallet->name]));

        logActivity($wallet, 'update'); // log activity

        return intended($request, route('admin.wallets.index'));
    }
}
