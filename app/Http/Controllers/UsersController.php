<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Country;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use function foo\func;

class UsersController extends Controller
{
    public function userLoginRegister(Request $request)
    {
        $meta_title= "User Login/Register - E-com Website";
        return view('users.login_register')->with(compact('meta_title'));
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            //Check if User Already Exists
            $userCount = User::where('email', $data['email'])->count();
            if ($userCount > 0) {
                return redirect()->back()->with('flash_message_error', 'Email is already exist!');
            } else {
                $user = new User;
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                if (empty($data['address'])) {
                    $user->address = "";
                }
                if (empty($data['city'])) {
                    $user->city = "";
                }
                if (empty($data['state'])) {
                    $user->state = "";
                }
                if (empty($data['country'])) {
                    $user->country = "";
                }
                if (empty($data['pincode'])) {
                    $user->pincode = "";
                }
                if (empty($data['mobile'])) {
                    $user->mobile = "";
                }
                date_default_timezone_set('Europe/Istanbul');
                $user->created_at = date("Y-m-d H:i:s");
                $user->updated_at = date("Y-m-d H:i:s");
                $user->save();

                /* //Save Register Email
                 $email = $data['email'];
                 $messageData = ['email' => $data['email'], 'name'=>$data['name']];
                 Mail::send('emails.register',$messageData,function($message) use($email){
                    $message->to($email)->subject('Registration with E-com Website');
                 });*/

                //Send Confirmation Email
                $email = $data['email'];
                $messageData = ['email' => $data['email'], 'name' => $data['name'],
                    'code' => base64_encode($data['email'])];
                Mail::send('emails.confirmation', $messageData, function ($message) use ($email) {
                    $message->to($email)->subject('Confirm your E-com Account');
                });

                return redirect()->back()->with('flash_message_success', 'Please Confirm your email to activate your account!');

                if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                    Session::put('frontSession', $data['email']);
                    return redirect('/cart');
                }

            }
        }
    }

    public function forgotPassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $userCount = User::where('email',$data['email'])->count();
            if($userCount == 0){
                return redirect()->back()->with('flash_message_error', 'Email does not exist!');
            }
            //Get Users Details
            $userDetails = User::where('email',$data['email'])->first();
            //Generate Random Password
            $random_password = Str::random(8);
            //Encode/Secure PAssword
            $new_password = bcrypt($random_password);
            //Update Password
            User::where('email',$data['email'])->update(['password'=>$new_password]);
            //Send Forgot Password Email Code
            $email = $data['email'];
            $name = $userDetails->name;
            $messageData = [
                'email'=>$email,
                'name' =>$name,
                'password'=>$random_password
            ];
            Mail::send('emails.forgotpassword',$messageData,function($message)use($email){
                $message->to($email)->subject('New Password - E-com Website');
            });
            return redirect('login-register')->with('flash_message_success', 'Please check your email for new password!');

        }
        return view('users.forgot_password');
    }

    public function login(Request $request)
    {
        $session_id = Session::get('session_id');
        if (!empty($session_id)) {
            DB::table('carts')->where(['session_id' => $session_id])->delete();
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            $userStatus = User::where('email', $data['email'])->first();
            if (!empty($userStatus)) {
                if ($userStatus->status == 0) {
                    return redirect()->back()->with('flash_message_error', 'Your account is not activated! Please check your email!');
                }
                if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => '1'])) {
                    Session::put('frontSession', $data['email']);
                    return redirect('/');

                } else {
                    return redirect()->back()->with('flash_message_error', 'Password or Email is wrong!');
                }
            }
            else{
                return redirect()->back()->with('flash_message_error', 'Please create an account!');
            }
        }
    }

    public function confirmAccount($email)
    {
        $email = base64_decode($email);
        $usersCount = User::where('email', $email)->count();
        if ($usersCount > 0) {
            $userDetails = User::where('email', $email)->first();
            if ($userDetails->status == 1) {
                return redirect('login-register')->with('flash_message_success', 'Your Email
                account is already activated. You can login now.');
            } else {
                User::where('email', $email)->update(['status' => 1]);
                //Save Register Email
                $messageData = ['email' => $email, 'name'=>$userDetails->name];
                Mail::send('emails.welcome',$messageData,function($message) use($email){
                    $message->to($email)->subject('Welcome to E-com Website');
                });
                return redirect('login-register')->with('flash_message_success', 'Your Email
                account is already activated. You can login now.');
            }
        } else {
            return redirect('login-register')->with('flash_message_error', '!');
        }
    }

    public function account(Request $request)
    {
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id);
        $countries = Country::get();

        if ($request->isMethod('post')) {
            $data = $request->all();
            if (empty($data['address'])) {
                $data['address'] = "";
            }
            if (empty($data['state'])) {
                $data['state'] = "";
            }
            if (empty($data['country'])) {
                $data['country'] = "";
            }
            if (empty($data['pincode'])) {
                $data['pincode'] = "";
            }
            if (empty($data['mobile'])) {
                $data['mobile'] = "";
            }
            $user = User::find($user_id);
            $user->name = $data['name'];
            $user->address = $data['address'];
            $user->city = $data['city'];
            $user->state = $data['state'];
            $user->country = $data['country'];
            $user->pincode = $data['pincode'];
            $user->mobile = $data['mobile'];
            $user->save();
            return redirect()->back()->with('flash_message_success', 'Your account has been updated successfully!');
        }

        return view('users.account')->with(compact('countries', 'userDetails'));
    }

    public function logout()
    {
        Auth::logout();
        $session_id = Session::get('session_id');
        if (!empty($session_id)) {
            DB::table('carts')->where(['session_id' => $session_id])->delete();
        }
        Session::forget('frontSession');
        Session::forget('session_id');
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        return redirect('/');
    }

    public function chkUserPassword(Request $request)
    {
        $data = $request->all();
        $current_password = $data['current_pwd'];
        $user_id = Auth::User()->id;
        $check_password = User::where('id', $user_id)->first();

        if (Hash::check($current_password, $check_password->password)) {
            echo "true";
        } else {
            echo "false";
        }

    }

    public function updateUserPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $old_pwd = User::where('id', Auth::User()->id)->first();
            $current_pwd = $data['current_pwd'];
            if (Hash::check($current_pwd, $old_pwd->password)) {
                $new_pwd = bcrypt($data['new_pwd']);
                User::where('id', Auth::user()->id)->update(['password' => $new_pwd]);
                return redirect()->back()->with('flash_message_success', 'Your password updated successfully!');
            } else {
                return redirect()->back()->with('flash_message_error', 'Your password does mot updated!');

            }
        }
    }

    public function checkEmail(Request $request)
    {
        $data = $request->all();
        //Check if User Already Exists
        $userCount = User::where('email', $data['email'])->count();
        if ($userCount > 0) {
            echo "false";
        } else {
            echo "true";
        }

    }

    public function viewUsers(){
        if(Session::get('adminDetails')['users_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
        }
        $users = User::get();
        return view('admin.users.view_users')->with(compact('users'));
    }


}
