<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TypeDataTable;
use App\Domain\Type\Models\Type;
use App\Http\Requests\Admin\TypeRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class TypeController
{
    use AuthorizesRequests;

    public function index(TypeDataTable $dataTable)
    {
        $this->authorize('view', Type::class);
        return $dataTable->render('admin.types.index');
    }

    public function create(): View
    {
        $this->authorize('create', Type::class);
        return view('admin.types.create');
    }

    public function store(TypeRequest $request)
    {
        $this->authorize('create', Type::class);

        $data = $request->all();
//      $data['user_id'] = auth('admins')->user()->id;
        $type = Type::create($data);
        flash()->success(__('Loại truyện ":model" đã tạo thành công !', ['model' => $type->name]));

        logActivity($type, 'create'); // log activity

        return intended($request, route('admin.types.index'));
    }

    public function edit(Type $type): View
    {
        $this->authorize('update', $type);

        return view('admin.types.edit', compact('type'));
    }

    public function update(Type $type, TypeRequest $request)
    {
        $this->authorize('update', $type);

        $type->update($request->all());

        flash()->success(__('Loại truyện ":model" đã cập nhật thành công !', ['model' => $type->name]));

        logActivity($type, 'update'); // log activity

        return intended($request, route('admin.types.index'));
    }

    public function destroy(Type $type)
    {
        $this->authorize('delete', $type);
        if (\App\Enums\TypeState::Active == $type->status) {
            return response()->json([
                'status' => 'error',
                'message' => __('Loại truyện đang được sử dụng không thể xoá!'),
            ]);
        }

        logActivity($type, 'delete'); // log activity

        $type->delete();

        return response()->json([
            'success' => true,
            'message' => __('Loại truyện đã xóa thành công !'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $count_deleted = 0;
        $deletedRecord = Type::whereIn('id', $request->input('id'))->get();
        foreach ($deletedRecord as $type) {
            if (\App\Enums\TypeState::Active != $type->status) {
                logActivity($type, 'delete'); // log activity
                $type->delete();
                $count_deleted++;
            }
        }
        return response()->json([
            'status' => true,
            'message' => __('Đã xóa ":count" loại truyện thành công và ":count_fail" loại truyện đang được sử dụng, không thể xoá',
                [
                    'count' => $count_deleted,
                    'count_fail' => count($request->input('id')) - $count_deleted,
                ]),
        ]);
    }

    public function changeStatus(Type $type, Request $request)
    {
        $this->authorize('update', $type);

        $type->update(['status' => $request->status]);

        logActivity($type, 'update'); // log activity

        return response()->json([
            'status' => true,
            'message' => __('Cập nhật trạng thái thành công !'),
        ]);
    }
}
