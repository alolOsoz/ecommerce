<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{

    //
    protected $table = 'main_categories';
    protected $fillable = [
        'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active', 'created_at', 'updated_at',
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeSelection($q)
    {
        return $q->select('id', 'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active');
    }

    public function getPhotoAttribute($v)
    {
        return ($v != null) ? asset('assets/' . $v) : "";
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }

    public function categories()
    {
        return $this->hasMany(self::class, 'translation_of');
    }

    public function vendors()
    {
        return $this->hasMany('App\Models\vendor','category_id','id');
    }

}
