<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;
use DB;

class Product extends Model
{
    public function attributes()
    {
        return $this->hasMany('App\ProductsAttribute','product_id');
    }

    public static function cartCount(){
        if(Auth::check()){
            // "User is logged in; we will use Auth"
            $user_email =Auth::user()->email;
            $cartCount = DB::table('carts')->where('user_email',$user_email)->sum('quantity');
        }else{
            // "User is not logged in. We will use Session"
            $session_id = Session::get('session_id');
            $cartCount = DB::table('carts')->where('session_id',$session_id)->sum('quantity');
        }
        return $cartCount;
    }

    public static function productCount($cat_id){
        $catCount = Product::where(['category_id'=>$cat_id,'status'=>1])->count();
        return $catCount;
    }

    public static function getCurrencyRates($price){
        $getCurrencies = Currency::where('status',1)->get();
        foreach($getCurrencies as $currency){
            if($currency->currency_code == "USD"){
                $USD_Rate = round($price/$currency->exchange_rate,2);
            }
            elseif($currency->currency_code == "GBP"){
                $GBP_Rate = round($price/$currency->exchange_rate,2);
            }
            elseif($currency->currency_code == "EUR"){
                $EUR_Rate = round($price/$currency->exchange_rate,2);
            }
        }
        $currenciesArr = array('USD_Rate'=>$USD_Rate,
            'GBP_Rate'=>$GBP_Rate,
            'EUR_Rate'=>$EUR_Rate);
        return $currenciesArr;
    }
}
