<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\StoryDataTable;
use App\Domain\Story\Models\Story;
use App\Domain\Category\Models\Category;
use App\Domain\Type\Models\Type;
use App\Http\Requests\Admin\StoryRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View; 

class StoryController
{
    use AuthorizesRequests;

    public function index(StoryDataTable $dataTable)
    {
        $this->authorize('view', Story::class);
        return $dataTable->render('admin.stories.index');
    }

    public function bulkDelete(Request $request)
    {
        $count_deleted = 0;
        $deletedRecord = Story::whereIn('id', $request->input('id'))->get();
        foreach ($deletedRecord as $story) {
            if (\App\Enums\StoryState::Active != $story->status) {
                logActivity($story, 'delete'); // log activity
                $story->delete();  
                $count_deleted++;
            }
        }
        return response()->json([
            'status' => true,
            'message' => __('Đã xóa ":count" truyện thành công và ":count_fail" truyện đang được sử dụng, không thể xoá',
                [
                    'count' => $count_deleted,
                    'count_fail' => count($request->input('id')) - $count_deleted,
                ]),
        ]);
    }

    public function changeStatus(Story $story, Request $request)
    {
        $this->authorize('update', $story);

        $story->update(['status' => $request->status]);

        logActivity($story, 'update'); // log activity

        return response()->json([ 
            'status' => true,
            'message' => __('Cập nhật trạng thái thành công !'),
        ]);
    }
    public function destroy(Story $story)
    {
        $this->authorize('delete', $story);

        logActivity($story, 'delete'); // log activity

        $story->delete();

        return response()->json([
            'success' => true,
            'message' => __('Truyện đã xóa thành công !'),
        ]);
    }
}
