<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPCodeMail;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class UserController extends Controller
{
    //Login Page
    public function LoginPage()
    {
        return Inertia::render('LoginPage');
    }//End of Login Page
    //Registration Page
    public function RegistrationPage()
    {
        return Inertia::render('RegistrationPage');
    }//End of Registration Page
    //Send OTP Page
    public function SendOTPPage()
    {
        return Inertia::render('SendOTPPage');
    }//End of Send OTP Page
    //Verify OTP Page
    public function VerifyOTPPage()
    {
        return Inertia::render('VerifyOTPPage');
    }//End of Verify OTP Page
    //Reset Password Page
    public function ResetPasswordPage()
    {
        return Inertia::render('ResetPasswordPage');
    }//End of Reset Password Page
    //User Resistration
    public function UserRegistration(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'mobile' => 'required|unique:users,mobile'
            ]);

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'mobile' => $request->input('mobile')
            ]);
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'User registered successfully',
            //     'data' => $user
            // ]);
           $data = ['message' => 'User registered successfully',
                    'status' => true,
                    'error' => '',
                ];
            return redirect('/login')->with($data);
        }
        catch(Exception $e)
        {
            // return response()->json([
            //     'status' => 'error',
            //     'message' => $e->getMessage()
            // ]);
            $data = ['message' => 'Ragistration Failed, '.$e->getMessage(),
                    'status' => false,
                    'error' => ''
                ];
            return redirect('/registration')->with($data);
        }

    }
    //User Login
    public function UserLogin(Request $request)
    {
        $count = User::where('email', $request->input('email'))->where('password', $request->input('password'))->select('id')->first();
        if($count!==null)
        {
            //user login success-> jwt token create
          // $token = JWTToken::createToken($request->input('email'), $count->id);
           // return response()->json([
            //     'status' => 'success',
            //     'message' => 'User login successfully',
            //     'token' => $token
            // ], 200)->cookie('token', $token, 60 * 24 * 30); // Set cookie for 30 days
            $email = $request->input('email');
            $user_id = $count->id;
            $request->session()->put('email', $email);
            $request->session()->put('user_id', $user_id);
            $data = ['message' => 'User login successfully',
                    'status' => true,
                    'error' => '',
                ];
                return redirect('/dashboardPage')->with($data);
        }
        else
        {
            // return response()->json([
            //     'status' => 'failed',
            //     'message' => 'unauthorized'
            // ], 200);
            $data = ['message' => 'Login Failed, Invalid email or password',
                    'status' => false,
                    'error' => ''
                ];
                return redirect('/login')->with($data);

        }
    }//End of User Login

    public function dashboardPage(Request $request){
        $user_id = request()->header('id');

        $product = Product::where('user_id', $user_id)->count();
        $category = Category::where('user_id', $user_id)->count();
        $customer = Customer::where('user_id', $user_id)->count();
        $invoice = Invoice::where('user_id', $user_id)->count();
        $total = Invoice::where('user_id', $user_id)->sum('total');
        $vat = Invoice::where('user_id', $user_id)->sum('vat');
        $payable = Invoice::where('user_id', $user_id)->sum('payable');
        $discount = Invoice::where('user_id', $user_id)->sum('discount');

        $data = [
            'product' => $product,
            'category' => $category,
            'customer' => $customer,
            'invoice' => $invoice,
            'total' => round($total),
            'vat' => round($vat),
            'payable' => round($payable),
            'discount' => $discount
        ];

        return Inertia::render('DashboardPage',['list'=>$data]);
    }//end dashboardPage

    //User Logout
    public function UserLogout(Request $request)
    {        
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'User logout successfully'
        // ], 200)->cookie('token', '', -1); // Clear the token cookie
        $request->session()->forget('email');
        $request->session()->forget('user_id');
        $data = ['message' => 'User logout successfully',
                    'status' => true,
                    'error' => ''
                ];
        return redirect('/login')->with($data);
    }//end of User Logout

    //Send OTP Code
    public function sendOTPCode(Request $request)
    {
        $email = $request->input('email'); // Get the email from the request
        $otpCode = rand(100000, 999999); // Generate a random 6-digit OTP code

        // Here, you can implement the logic to send the OTP code to the user's email or phone number.
        // For example, you can use Laravel's built-in notification system or an external service like Twilio.
        $count = User::where('email', $email)->count();
        if($count==1)
        {
            //Mail ::to($email)->send(new OTPCodeMail($otpCode)); // Send the OTP code via email
            User::where('email', $email)->update(['otp' => $otpCode]); // Store the OTP code in the database for later verification
            $request->session()->put('email', $email);
            //     return response()->json([
        //     'status' => 'success',
        //     'message' => 'OTP code sent successfully',
        //     'otp_code' => $otpCode
        // ], 200);
            $data = ['message' => '6 digit ' . $otpCode . ' OTP code sent successfully',
                    'status' => true,
                    'error' => ''
                ];
            return redirect('/verify-otp')->with($data);
        }
        else
        {
            // return response()->json([
            //     'status' => 'failed',
            //     'message' => 'Unauthorized'
            // ], 200);
            $data = ['message' => 'Unauthorized, No user found with this email',
                    'status' => false,
                    'error' => ''
                ];
            return redirect('/registration')->with($data);
        }
        
    }//end of sendOTPCode

    //Verify OTP Code
    public function verifyOTPCode(Request $request)
    {
        $email = $request->session()->get('email'); // Get the email from the request
        $otpCode = $request->input('otp'); // Get the OTP code from the request

        $count = User::where('email', $email)->where('otp', $otpCode)->count();
        if($count==1)
        {
            User::where('email', $email)->update(['otp' => 0]); // Clear the OTP code from the database after successful verification
            //$token = JWTToken::createTokenForSetPassword($request->input('email'));// Create a JWT token for the user after successful OTP verification
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'OTP code verified successfully'
            // ], 200)->cookie('token', $token, 60 * 24 * 30); // Set cookie for 30 day

             $request->session()->put('otp_verify','yes');
             $data = ['message' => 'OTP code verified successfully',
                    'status' => true,
                    'error' => ''
                ];
            return redirect('/reset-password-page')->with($data);
        }
        else
        {
            $data = ['message' => 'Unauthorized, Invalid OTP Code',
                    'status' => false,
                    'error' => ''
                ];
            return redirect('/verify-otp')->with($data);
        }
    }//end of verifyOTPCode

    //Reset Password
  
    public function resetPassword(Request $request)
    {

        //$newPassword = $request->input('new_password'); // Get the new password from the request

        try{
            //$email = $request->headers->get('userEmail'); // Get the email from the request header
             $email = $request->session()->get('email','default');
            $password = $request->input('password'); // Get the new password from the request
            $otp_verify = $request->session()->get('otp_verify','default');
            if($otp_verify==='yes')
            {
                User::where('email', $email)->update(['password' => $password]); // Update the user's password in the database
                $request->session()->flash('otp_verify',''); // Clear the OTP verification session variable
                $data = ['message' => 'User password updated successfully',
                    'status' => true,
                    'error' => ''
                ];
                return redirect('/login')->with($data);
            }
            else
            {
                $data = ['message' => 'Unauthorized, Invalid OTP Code',
                    'status' => false,
                    'error' => ''
                ];
                return redirect('/reset-password-page')->with($data);
            }
             // return response()->json([
            //     'status' => 'success',
            //     'message' => 'User password updated successfully'
            // ], 200);
        }
        catch(Exception $e)
        {
            // return response()->json([
            //     'status' => 'failed',
            //     'message' => $e->getMessage(),
            //     'error' => ''
            // ], 200);
            $data = ['message' => 'User password update failed, '.$e->getMessage(),
                    'status' => false,
                    'error' => ''
                ];
            return redirect('/login')->with($data);
        }
    }//end of resetPassword

    //Profile Page
    public function ProfilePage(Request $request)
    {
        $email = $request->header('email');
        $user_id = $request->header('id');
        $user = User::where('id', $user_id)->first();
        return Inertia::render('ProfilePage', ['user' => $user]);
    }//end of ProfilePage
    //User Update
    public function UserUpdate(Request $request){
        $email = request()->header('email');
        User::where('email', $email)->update([
            'name' => $request->input('name'),
            'email'=> $request->input('email'),
            'mobile'=> $request->input('mobile'),
        ]);
        $data = ['message'=> 'Profile update successfully','status'=>true, 'error'=>'' ];
        return redirect()->back()->with($data);
    }
}