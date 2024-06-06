<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Unlike;

class Micropost extends Model
{
    use HasFactory;

    protected $fillable = ['content'];

    /**
     * この投稿を所有するユーザー。（ Userモデルとの関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function favorites_users(){
        return $this->belongsToMany(User::class, "favorites", "micropost_id", "user_id")->withTimestamps();
    }
    
    public function favoriteCounts(){
        return $this->favorites_users()->count();
    }
    
    
    public function unlike_users(){
        return $this->belongsToMany(User::class, "favorites", "micropost_id", "user_id")->withTimestamps();;
    }
    
    public function isUnlikedBy($user):bool{
        return Unlike::where('user_id', $user->id)->where('micropost_id', $this->id)->first() !==null;
    }
    
}
