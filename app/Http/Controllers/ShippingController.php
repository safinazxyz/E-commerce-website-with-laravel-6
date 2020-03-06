<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShippingCharge;

class ShippingController extends Controller
{
    public function editShipping($id, Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            ShippingCharge::where('id',$id)->update(['shipping_charges'=>$data['shipping_charges']]);
            return redirect()->back()->with('flash_message_success', 'Shipping Charges has been updated successfully!');
        }
        $shippingDetails = ShippingCharge::where('id',$id)->first();
        return view('admin.shipping.edit_shipping')->with(compact('shippingDetails'));
    }
   public function viewShipping(){
           $shipping_charges = ShippingCharge::get();
           return view('admin.shipping.view_shipping')->with(compact('shipping_charges'));
   }
}
