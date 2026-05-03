<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
         protected $fillable=['title','slug','summary','description','cat_id','child_cat_id','price','brand_id','discount','status','image','size','stock','is_featured','condition'];
     public function cat_info(){
      return $this->belongsTo(\App\Models\Category::class, 'cat_id', 'id');

    }
     public function sub_cat_info(){
        return $this->belongsTo(\App\Models\Category::class, 'child_cat_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
