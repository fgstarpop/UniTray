<?php

namespace App\Domain\Recharge\Policies;

use App\Domain\Acl\Models\Role;
use App\Domain\Admin\Models\Admin;
use App\Domain\Recharge\Models\RechargePackage;
use Illuminate\Auth\Access\HandlesAuthorization;

class RechargePackagePolicy
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
        return $user->hasPermissionTo('recharge_packages.view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return $user->hasPermissionTo('recharge_packages.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Domain\Admin\Models\Admin $user
     * @param RechargePackage $rechargePackage
     * @return mixed
     */
    public function update(Admin $user, RechargePackage $rechargePackage)
    {
        return $user->hasPermissionTo('recharge_packages.update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param Admin $user
     * @param RechargePackage $rechargePackage
     * @return mixed
     */
    public function delete(Admin $user, RechargePackage $rechargePackage)
    {
        return $user->hasPermissionTo('recharge_packages.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function restore(Admin $user, Role $role)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function forceDelete(Admin $user, Role $role)
    {
        //
    }
}
