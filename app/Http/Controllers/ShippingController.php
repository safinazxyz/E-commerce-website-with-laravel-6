<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShippingCharge;

class ShippingController extends Controller
{
    public function editShipping($id, Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            if (!empty($data['shipping_charges0_500g'])) {
                $shipping_charges0_500g = $data['shipping_charges0_500g'];
            } else {
                $shipping_charges0_500g = 0;
            }
            if (!empty($data['shipping_charges501_1000g'])) {
                $shipping_charges501_1000g = $data['shipping_charges501_1000g'];
            } else {
                $shipping_charges501_1000g = 0;
            }
            if (!empty($data['shipping_charges1001_2000g'])) {
                $shipping_charges1001_2000g= $data['shipping_charges1001_2000g'];
            } else {
                $shipping_charges1001_2000g= 0;
            }
            if (!empty($data['shipping_charges2001_5000g'])) {
                $shipping_charges2001_5000g = $data['shipping_charges2001_5000g'];
            } else {
                $shipping_charges2001_5000g = 0;
            }
            ShippingCharge::where('id',$id)->update(['shipping_charges0_500g'=> $shipping_charges0_500g,
                'shipping_charges501_1000g' => $shipping_charges501_1000g,
                'shipping_charges1001_2000g' => $shipping_charges1001_2000g,
                'shipping_charges2001_5000g' => $shipping_charges2001_5000g ]);
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
