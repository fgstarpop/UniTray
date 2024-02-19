<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RechargePackageDataTable;
use App\Domain\Recharge\Models\RechargePackage;
use App\Http\Requests\Admin\RechargePackageRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class RechargePackageController
{
    use AuthorizesRequests;

    public function index(RechargePackageDataTable $dataTable)
    {
        $this->authorize('view', RechargePackage::class);
        return $dataTable->render('admin.recharge_packages.index');
    }

    public function create(): View
    {
        $this->authorize('create', RechargePackage::class);
        return view('admin.recharge_packages.create');
    }

    public function store(RechargePackageRequest $request)
    {
        $this->authorize('create', RechargePackage::class);
 
        $data = $request->all();
//        $data['user_id'] = auth('admins')->user()->id;
        $rechargePackage = RechargePackage::create($data);
        flash()->success(__('Gói nạp ":model" đã tạo thành công !', ['model' => $rechargePackage->name]));

        logActivity($rechargePackage, 'create'); // log activity

        return intended($request, route('admin.recharge-packages.index'));
    }

    public function edit(RechargePackage $rechargePackage): View
    {
        $this->authorize('update', $rechargePackage);

        return view('admin.recharge_packages.edit', compact('rechargePackage'));
    }

    public function update(RechargePackage $rechargePackage, RechargePackageRequest $request)
    {
        $this->authorize('update', $rechargePackage);

        $rechargePackage->update($request->all());

        flash()->success(__('Gói nạp ":model" đã cập nhật thành công !', ['model' => $rechargePackage->name]));

        logActivity($rechargePackage, 'update'); // log activity

        return intended($request, route('admin.recharge-packages.index'));
    }

    public function destroy(RechargePackage $rechargePackage)
    {
        $this->authorize('delete', $rechargePackage);
        if (\App\Enums\RechargePackageState::Active == $rechargePackage->status) {
            return response()->json([
                'status' => 'error',
                'message' => __('Gói nạp đang được sử dụng không thể xoá!'),
            ]);
        }

        logActivity($rechargePackage, 'delete'); // log activity

        $rechargePackage->delete();

        return response()->json([
            'success' => true,
            'message' => __('Gói nạp đã xóa thành công !'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $count_deleted = 0;
        $deletedRecord = RechargePackage::whereIn('id', $request->input('id'))->get();
        foreach ($deletedRecord as $rechargePackage) {
            if (\App\Enums\PageState::Active == $rechargePackage->status) {
                logActivity($rechargePackage, 'delete'); // log activity
                $rechargePackage->delete();
                $count_deleted++;
            }
        }
        return response()->json([
            'status' => true,
            'message' => __('Đã xóa ":count" gói nạp thành công và ":count_fail" gói nạp đang được sử dụng, không thể xoá',
                [
                    'count' => $count_deleted,
                    'count_fail' => count($request->input('id')) - $count_deleted,
                ]),
        ]);
    }

    public function changeStatus(RechargePackage $rechargePackage, Request $request)
    {
        $this->authorize('update', $rechargePackage);

        $rechargePackage->update(['status' => $request->status]);

        logActivity($rechargePackage, 'update'); // log activity

        return response()->json([
            'status' => true,
            'message' => __('Cập nhật trạng thái thành công !'),
        ]);
    }

    public function upLoadFileImage(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file' => ['mimes:jpeg,jpg,png', 'required', 'max:2048'],
            ],
            [
                'file.mimes' => __('Tệp tải lên không hợp lệ !'),
                'file.max' => __('Tệp quá lớn !'),
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first('file'),
            ], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $file = $request->file('file')->storePublicly('tmp/uploads');

        return response()->json([
            'file' => $file,
            'status' => true,
        ]);
    }
}
