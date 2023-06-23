<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function like(){
        return $this->hasMany(like::class);
    }

    public function hashtag(){
        return $this->hasMany(Hashtag::class);
    }

    public function favorite(){
        return $this->hasMany(favorite::class);
    }

    public function comment(){
        return $this->hasMany(comment::class);
    }

    public function status(){
        return $this->hasMany(status::class);
    }

    public function postItem(){
        return $this->hasMany(PostItem::class);
    }
}
