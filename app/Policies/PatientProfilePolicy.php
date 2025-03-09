<?php

namespace App\Policies;

use App\Models\PatientProfile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function view_activity_log(User $user, PatientProfile $patient)
    {
        return $user->role->hasPermission('view_activity_log');
    }

    public function view_account_setting(User $user, PatientProfile $patient)
    {
        return $user->role->hasPermission('view_account_setting');
    }

    public function view_medical_assesment(User $user, PatientProfile $patient)
    {
        return $user->role->hasPermission('view_medical_assesment');
    }

    public function upload_lab_result(User $user, PatientProfile $patient)
    {
        return $user->role->hasPermission('upload_lab_result');
    }

    public function upload_lab_imaging(User $user, PatientProfile $patient)
    {
        return $user->role->hasPermission('upload_lab_imaging');
    }
}
