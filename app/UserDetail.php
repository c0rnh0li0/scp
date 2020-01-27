<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'phone',
        'description',
        'picture',
        'user_id',
        'user_type_id',
        'gender_id',
        'location_id',
        'created_at',
        'updated_at',
        'modified_by',
        'deleted_by',
        'deleted_at',
        'is_company'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function userType() {
        return $this->hasOne('App\UserType', 'user_type_id');
    }

    public function gender() {
        return $this->hasOne('App\Gender', 'gender_id');
    }

    public function location() {
        return $this->hasOne('App\Location', 'gender_id');
    }
}
