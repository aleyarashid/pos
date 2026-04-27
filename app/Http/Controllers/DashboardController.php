<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    
    //Dashboard PageSummery
    public function DashboardSummery(Request $request)
    {
        $user_id = $request->header('id');
        $totalProducts = Product::where('user_id', $user_id)->count();
        $totalCustomers = Customer::where('user_id', $user_id)->count();
        $totalInvoices = Invoice::where('user_id', $user_id)->count();
        $totalRevenue = Invoice::where('user_id', $user_id)->sum('total');
        $totalDiscount = Invoice::where('user_id', $user_id)->sum('discount');
        $totalVat = Invoice::where('user_id', $user_id)->sum('vat');
        $totalPayable = Invoice::where('user_id', $user_id)->sum('payable');
        
        
        return response()->json([
            'status' => 'success',
            'total_products' => $totalProducts,
            'total_customers' => $totalCustomers,
            'total_invoices' => $totalInvoices,
            'total_revenue' => $totalRevenue,
            'total_discount' => $totalDiscount,
            'total_vat' => $totalVat,
            'total_payable' => $totalPayable
        ], 200);


}//End of Dashboard PageSummery

}
