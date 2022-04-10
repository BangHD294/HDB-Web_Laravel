<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['view_count'];

    public function user()
    {
        return $this->belongsTo(User::class); // get user_id
    }
    public function category()
    {
        return $this->belongsTo(Category::class); // get category_id
    }
    public function tags()
    {
        return $this->hasMany(Tag::class, 'postID', 'id');
    }
}
