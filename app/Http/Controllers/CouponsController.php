<?php

namespace App\Http\Controllers;

use App\Coupon;
use Illuminate\Http\Request;
use Session;

class CouponsController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $coupon = new Coupon;
            $coupon->coupon_code = $data['coupon_code'];
            $coupon->amount = $data['amount'];
            $coupon->amount_type = $data['amount_type'];
            $coupon->expiry_date = $data['expiry_date'];
            if (empty($data['status'])) {
                $data['status'] = 0;
            }
            $coupon->status = $data['status'];
            $coupon->save();
            return redirect()->action('CouponsController@view')->with('flash_message_success', 'New Coupon Code Added Successfully!');
        }
        return view('admin.coupons.add_coupon');
    }

    public function edit(Request $request, $id = null)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $coupon = Coupon::find($id);
            $coupon->coupon_code = $data['coupon_code'];
            $coupon->amount = $data['amount'];
            $coupon->amount_type = $data['amount_type'];
            $coupon->expiry_date = $data['expiry_date'];
            if (empty($data['status'])) {
                $data['status'] = 0;
            }
            $coupon->status = $data['status'];
            $coupon->save();
            return redirect()->action('CouponsController@view')->with('flash_message_success', 'Coupon Has  Updated Been Successfully!');

        }
        $couponDetails = Coupon::find($id);
        return view('admin.coupons.edit_coupon')->with(compact('couponDetails'));
    }

    public function view()
    {
        $coupons = Coupon::orderby('id', 'DESC')->get();
        return view('admin.coupons.view_coupons')->with(compact('coupons'));
    }

    public function delete($id = null)
    {
        if (!empty($id)) {
            Coupon::where(['id' => $id])->delete();
            return redirect()->back()->with('flash_message_success', 'Coupon Deleted Successfully!');
        }
    }
}
