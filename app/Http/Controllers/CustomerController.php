<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    //Custmer Page
    public function CustomerPage(Request $request){
        $user_id = $request->header('id');
        $customers = Customer::where('user_id', $user_id)->get();
        return Inertia::render('CustomerPage', [
            'customers' => $customers
        ]);
    }
    //End Customer Page
    //Customer Save Page
    public function CustomerSavePage(Request $request){
        $user_id = $request->header('id');
        $id = $request->query('id');
        $customer = Customer::where('user_id', $user_id)->where('id', $id)->first();
        return Inertia::render('CustomerSavePage', [
            'customer' => $customer
        ]);
    }
    //End Customer Save Page
    //Create Customer
    public function CreateCustomer(Request $request)
    {
        $user_id = $request->header('id'); 
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:customers,email',
            'mobile' => 'required'
        ]);       
        Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'user_id' => $user_id
        ]);
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Customer created successfully'
        // ], 200);
        $data = [
            'message' => 'Customer created successfully',
            'status' => true,
            'error' => ''
        ];
        return redirect('/customer-page')->with($data);
    }//End Create Customer

    //Customer List
    public function CustomerList()
    {
        $user_id = request()->header('id');
        $customers = Customer::where('user_id', $user_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $customers
        ], 200);
    }//End Customer List

    //Customer By Id
    public function CustomerById(Request $request)
    {   
        $user_id = $request->header('id');
        $id = $request->input('id');
        $customer = Customer::where('user_id', $user_id)->where('id', $id)->first();
        if($customer)
        {
            return response()->json([
                'status' => 'success',
                'data' => $customer
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found'
            ], 404);
        }
    }//End Customer By Id

    //Update Customer
    public function UpdateCustomer(Request $request)    
    {
        $user_id = $request->header('id');
        

        $request->validate([
            'id' => 'required|exists:customers,id',
            'name' => 'required',
            'email' => 'required|email|unique:customers,email,'.$request->input('id'),
            'mobile' => 'required'
        ]);

        $id = $request->input('id');
        $customer = Customer::where('user_id', $user_id)->where('id', $id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile')
        ]);

            // $customer->name = $request->input('name');
            // $customer->email = $request->input('email');
            // $customer->mobile = $request->input('mobile');
            // $customer->save();

            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Customer updated successfully'
            // ], 200);

            $data = [
                'message' => 'Customer updated successfully',
                'status' => true,
                'error' => ''
            ];
            return redirect('/customer-page')->with($data);
    }

    //Delete Customer
    public function DeleteCustomer(Request $request, $id)
    {
        $user_id = $request->header('id');
        $customer = Customer::where('user_id', $user_id)->findOrFail($id);
        $customer->delete();
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Customer deleted successfully'
        // ], 200);

        $data = [
            'message' => 'Customer deleted successfully',
            'status' => true,
            'error' => ''
        ];
        return redirect('/customer-page')->with($data);
    } //End Delete Customer
}
