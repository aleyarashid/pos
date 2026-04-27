<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    //Crealte Invoice
    public function CreateInvoice(Request $request)
    {
        DB::beginTransaction();
        try {
            //Invoice creation logic here
            $user_id = $request->header('id');
            // Validate request data
            $invoice_number = rand(100000, 999999); // Generate a random invoice number
           $data =[
                'user_id' => $user_id,
                'customer_id' => $request->input('customer_id'),
                'invoice_number' => $invoice_number,
                'total' => $request->input('total'),
                'discount' => $request->input('discount'),
                'vat' => $request->input('vat'),
                'payable' => $request->input('payable')
           ];
            // Create invoice
            $invoice = Invoice::create($data);
            // Create invoice products
            $products = $request->input('products'); // Assuming products is an array of product details
            foreach ($products as $product) {
                $existsUnit = Product::where('id', $product['id'])->first();
                if (!$existsUnit) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Product with ID: ' . $product['id'] . ' is not available.'
                    ], 200);
                }
                elseif($existsUnit->unit < $product['unit'])
                {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Product with ID: ' . $product['id'] . ' has only ' . $existsUnit->unit . ' units available.'
                    ], 200);
                }
                else
                {
                    InvoiceProduct::create([
                        'invoice_id' => $invoice->id,
                        'user_id' => $user_id,
                        'product_id' => $product['id'],
                        'qty' => $product['unit'],
                        'sale_price' => $product['price']
                    ]);
                    Product::where('id', $product['id'])->decrement('unit', $product['unit']);
                }
            }

            DB::commit();
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Invoice created successfully'
            // ], 200);
            $data = [
                'message' => 'Invoice created successfully',
                'status' => true,
                'error' => ''
            ];
            return redirect('/invoice-list-page')->with($data);
        }
        catch(Exception $e)
        {
            DB::rollBack();
                // return response()->json([
                //     'status' => 'error',
                //     'message' => $e->getMessage()
                // ], 500);
            $data = [
                'message' => $e->getMessage(),
                'status' => false,
                'error' => ''
            ];
            return redirect('/invoice-list-page')->with($data);
        }
    }//end Create Invoice

    //Invoice List
    public function InvoiceListPage()
    {
        $user_id = request()->header('id');
        $list = Invoice::where('user_id', $user_id)->with('customer', 'invoiceProduct.product')->get();
        // return response()->json([
        //     'status' => 'success',
        //     'data' => $list
        // ], 200);
        return Inertia::render('InvoiceListPage', ['list' => $list]);
    }
    public function InvoiceList()
    {
        $user_id = request()->header('id');
        $invoices = Invoice::with('customer')->where('user_id', $user_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $invoices
        ], 200);
    }//end Invoice List

    //Invoice Details
    public function InvoiceDetails(Request $request)
    {
        $user_id = $request->header('id');
        $invoice_id = $request->input('invoice_id');
        $customerID = $request->input('customer_id');
        $customerDetails = Customer::where('user_id', $user_id)->where('id', $customerID)->first();
        $invoice = Invoice::with(['customer'])->where('user_id', $user_id)->where('id', $invoice_id)->first();
        $invoiceProducts = InvoiceProduct::with('product')->where('invoice_id', $invoice_id)->where('user_id', $user_id)->with('product')->get();
        
        return response()->json([
            'status' => 'success',
            'customerDetails' => $customerDetails,
            'invoice' => $invoice,
            'invoiceProducts' => $invoiceProducts
        ], 200);
    }//end Invoice Details

    //Delete Invoice
    public function DeleteInvoice(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            InvoiceProduct::where('invoice_id', $id)->where('user_id', $user_id)->delete();
            $invoice = Invoice::where('id', $id)->where('user_id', $user_id)->first();
            if (!$invoice) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invoice not found'
                ], 404);    
            }
            $invoice->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice deleted successfully'
            ], 200);
        }
        catch(Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}