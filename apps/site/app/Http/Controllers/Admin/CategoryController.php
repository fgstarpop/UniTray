<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Domain\Category\Models\Category;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class CategoryController
{
    use AuthorizesRequests;

    public function index(CategoryDataTable $dataTable)
    {
        $this->authorize('view', Category::class);
        return $dataTable->render('admin.categories.index');
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);
        return view('admin.categories.create');
    }  
    public function store(CategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        $data = $request->all();
//      $data['user_id'] = auth('admins')->user()->id;
        $category = Category::create($data);
        flash()->success(__('Thể loại ":model" đã tạo thành công !', ['model' => $category->name]));

        logActivity($category, 'create'); // log activity

        return intended($request, route('admin.categories.index'));
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Category $category, CategoryRequest $request)
    {
        $this->authorize('update', $category);

        $category->update($request->all());

        flash()->success(__('Thể loại ":model" đã cập nhật thành công !', ['model' => $category->name]));

        logActivity($category, 'update'); // log activity

        return intended($request, route('admin.categories.index'));
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        if (\App\Enums\CategoryState::Active == $category->status) {
            return response()->json([
                'status' => 'error',
                'message' => __('Thể loại đang được sử dụng không thể xoá!'),
            ]);
        }

        logActivity($category, 'delete'); // log activity

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => __('Thể loại đã xóa thành công !'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $count_deleted = 0;
        $deletedRecord = Category::whereIn('id', $request->input('id'))->get();
        foreach ($deletedRecord as $category) {
            if (\App\Enums\CategoryState::Active != $category->status) {
                logActivity($category, 'delete'); // log activity
                $category->delete();
                $count_deleted++;
            }
        }
        return response()->json([
            'status' => true,
            'message' => __('Đã xóa ":count" thể loại thành công và ":count_fail" thể loại đang được sử dụng, không thể xoá',
                [
                    'count' => $count_deleted,
                    'count_fail' => count($request->input('id')) - $count_deleted,
                ]),
        ]);
    }

    public function changeStatus(Category $category, Request $request)
    {
        $this->authorize('update', $category);

        $category->update(['status' => $request->status]);

        logActivity($category, 'update'); // log activity

        return response()->json([
            'status' => true,
            'message' => __('Cập nhật trạng thái thành công !'),
        ]);
    }
}
