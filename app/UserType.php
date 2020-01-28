<?php

namespace App;

use App\SCPModel;

class UserType extends SCPModel
{
    const ADMIN_USER_TYPE = 1;
    const TOURIST_USER_TYPE = 2;
    const COMPANY_USER_TYPE = 3;

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'modified_by',
        'deleted_by',
        'deleted_at'
    ];
}
