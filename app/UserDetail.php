<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'phone',
        'description',
        'picture',
        'website',
        'user_id',
        'user_type_id',
        'gender_id',
        'location_id',
        'valute_id',
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

    public function type() {
        return $this->belongsTo('App\UserType', 'user_type_id');
    }

    public function gender() {
        return $this->belongsTo('App\Gender', 'gender_id');
    }

    public function location() {
        return $this->belongsTo('App\Location', 'location_id');
    }

    public function valute() {
        return $this->belongsTo('App\Valute', 'valute_id');
    }
}
