<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Domain\Chapter\Models\Chapter;
use App\Domain\Story\Models\Story;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class UserController
{
    use AuthorizesRequests;

    public function index(UserDataTable $dataTable)
    {
        $this->authorize('view', User::class);

        return $dataTable->render('admin.users.index');
    }

    public function destroy(User $user)
    {
        $this->authorize('update', $user);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => __('Người dùng đã được xóa thành công!'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $deletedRecord = User::whereIn('id', $request->input('id'))->delete();

        return response()->json([
            'status' => true,
            'message' => __('Đã xóa ":count" bản ghi', ['count' => $deletedRecord]),
        ]);
    }

    public function bulkStatus(Request $request)
    {
        $total = User::whereIn('id', $request->id)->get();
        foreach ($total as $user)
        {
            $user->update(['status' => $request->status]);
            logActivity($user, 'update'); // log activity
        }

        return response()->json([
            'status' => true,
            'message' => __(':count người dùng đã được cập nhật trạng thái thành công !', ['count' => $total->count()]),
        ]);
    }

    public function changeStatus(User $user, Request $request)
    {
        $this->authorize('update', $user);

        $user->update(['status' => $request->status]);

        logActivity($user, 'update'); // log activity

        return response()->json([
            'status' => true,
            'message' => __('Người dùng đã được cập nhật trạng thái thành công !'),
        ]);
    }

    public function bulkType(Request $request)
    {
        $total = User::whereIn('id', $request->id)->get();
        foreach ($total as $user)
        {
            $user->update(['is_vip' => $request->type]);

            if (strpos(url()->current(), 'truyenvipfaloo')) {
                if ($request->input('type') == 0) {
                    $stories = Story::where('user_id', $user->id)->get();
                    foreach ($stories as $story) {
                        if ($story->type == 1) {
                            $story->update(['user_id' => 4]);
                        }
                    }
                }
            }

            logActivity($user, 'update'); // log activity
        }

        return response()->json([
            'status' => true,
            'message' => __(':count người dùng đã được cập nhật loại tài khoản thành công !', ['count' => $total->count()]),
        ]);
    }

    public function changeType(User $user, Request $request)
    {
        $this->authorize('update', $user);

        $user->update(['is_vip' => $request->type]);

        if (strpos(url()->current(), 'truyenvipfaloo')) {
            if ($request->input('type') == 0) {
                $stories = Story::where('user_id', $user->id)->get();
                foreach ($stories as $story) {
                    if ($story->type == 1) {
                        $story->update(['user_id' => 4]);
                    }
                }
            }
        }

        logActivity($user, 'update'); // log activity

        return response()->json([
            'status' => true,
            'message' => __('Người dùng đã được cập nhật loại tài khoản thành công !'),
        ]);
    }
}
