<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    protected $fillable = ['title','body','category_id','reply_count'];

    public function user(){
        return $this->hasOne("App\Models\User",'id','user_id');
    }
    public function category(){
        return $this->hasOne("App\Models\Category",'id','category_id');
    }
}
