<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $fillable = ['name', 'email', 'mobile', 'user_id'];

        public function users()
        {
            return $this->belongsTo(User::class);
        }
        public function invoices()
        {
            return $this->hasMany(Invoice::class);
        }
        public function invoiceProducts()
        {
            return $this->hasMany(InvoiceProduct::class);
        }
}
