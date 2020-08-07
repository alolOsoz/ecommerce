<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use Notifiable;

    protected $fillable = [
        'name', 'mobile', 'password', 'address', 'email', 'logo', 'active', 'category_id', 'created_at', 'updated_at',
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'category_id', 'password',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function getLogoAttribute($v)
    {
        return ($v != null) ? asset('assets/' . $v) : "";
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }


    public function scopeSelection($q)
    {
        return $q->select('id', 'name', 'mobile','address','email', 'category_id', 'logo', 'active');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\MainCategory', 'category_id', 'id');
    }

    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }
}
