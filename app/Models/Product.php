<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ['name', 'category_id', 'brand_id', 'price', 'unit', 'image', 'user_id'];

     public function category(){
        return $this->belongsTo(Category::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function invoiceProducts(){
        return $this->hasMany(InvoiceProduct::class);
    }
}
