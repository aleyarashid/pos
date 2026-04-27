<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    protected $fillable = ['user_id', 'customer_id', 'invoice_number', 'total', 'discount', 'vat', 'payable'];

        public function users()
            {
                return $this->belongsTo(User::class);
            }
            public function customer()
            {
                return $this->belongsTo(Customer::class);
            }
            public function invoiceProduct()
            {
                return $this->hasMany(InvoiceProduct::class);
            }
}
