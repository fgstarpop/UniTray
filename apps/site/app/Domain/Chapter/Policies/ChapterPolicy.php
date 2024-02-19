<?php

namespace App\Domain\Chapter\Policies;

use App\Domain\Chapter\Models\Chapter;
use App\Domain\Admin\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChapterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @return mixed
     */
    public function viewAny(Admin $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @return mixed
     */
    public function view(Admin $user)
    {
        return $user->hasPermissionTo('chapters.view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return $user->hasPermissionTo('chapters.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Domain\Admin\Models\Admin $user
     * @param Chapter $chapter
     * @return mixed
     */
    public function update(Admin $user, Chapter $chapter)
    {
        return $user->hasPermissionTo('chapters.update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Domain\Admin\Models\Admin $user
     * @param Chapter $chapter
     * @return mixed
     */
    public function delete(Admin $user, Chapter $chapter)
    {
        return $user->hasPermissionTo('chapters.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Domain\Admin\Models\Admin $user
     * @param Chapter $chapter
     * @return mixed
     */
    public function restore(Admin $user, Chapter $chapter)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @param  \App\Chapter  $chapter
     * @return mixed
     */
    public function forceDelete(Admin $user, Chapter $chapter)
    {
        //
    }
}
