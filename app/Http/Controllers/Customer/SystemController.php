<?php

namespace App\Http\Controllers\Customer;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\CartShipping;
use App\Model\ShippingMethod;
use Illuminate\Http\Request;
use App\Model\Pincode;
use App\Model\ShippingAddress;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;

class SystemController extends Controller
{
    public function set_payment_method($name)
    {
        if (auth('customer')->check() || session()->has('mobile_app_payment_customer_id')) {
            session()->put('payment_method', $name);
            return response()->json([
                'status' => 1
            ]);
        }
        return response()->json([
            'status' => 0
        ]);
    }

    public function set_shipping_method(Request $request)
    {
        if ($request['cart_group_id'] == 'all_cart_group') {
            foreach (CartManager::get_cart_group_ids() as $group_id) {
                $request['cart_group_id'] = $group_id;
                self::insert_into_cart_shipping($request);
            }
        } else {
            self::insert_into_cart_shipping($request);
        }

        return response()->json([
            'status' => 1
        ]);
    }

    public static function insert_into_cart_shipping($request)
    {
        $shipping = CartShipping::where(['cart_group_id' => $request['cart_group_id']])->first();
        if (isset($shipping) == false) {
            $shipping = new CartShipping();
        }
        $shipping['cart_group_id'] = $request['cart_group_id'];
        $shipping['shipping_method_id'] = $request['id'];
        $shipping['shipping_cost'] = ShippingMethod::find($request['id'])->cost;
        $shipping->save();
    }

    public function choose_shipping_address(Request $request)
    {
        $shipping = [];
        $billing = [];
        parse_str($request->shipping, $shipping);
        parse_str($request->billing, $billing);
        
        // $Pincode = Pincode::where('pincode', $shipping['zip'])
        $Pincode = Pincode::Where('from_pincode','<=', $shipping['zip'])
        ->Where('to_pincode','>=', $shipping['zip'])
        ->orderBy('created_at', 'DESC')
        ->count();
      
        if (isset($shipping['save_address']) && $shipping['save_address'] == 'on') {
        
            if ($shipping['contact_person_name'] == null || $shipping['address'] == null || $shipping['city'] == null ) {
                return response()->json('Please fill all required fields of shipping/billing address', 403);
            }
            if ($Pincode==0 ) {
                return response()->json("Delivery not available for your pincode", 403);
            }

            $address_id = DB::table('shipping_addresses')->insertGetId([
                'customer_id' => auth('customer')->id(),
                'contact_person_name' => $shipping['contact_person_name'],
                'address_type' => $shipping['address_type'],
                'address' => $shipping['address'],
                'city' => $shipping['city'],
                'zip' => $shipping['zip'],
                'state' => $shipping['state'],
                'phone' => $shipping['phone'],
                'latitude' => $shipping['latitude'],
                'longitude' => $shipping['longitude'],
                'is_billing' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } else if ($shipping['shipping_method_id'] == 0) {
            if ($shipping['contact_person_name'] == null || $shipping['address'] == null || $shipping['city'] == null ) {
                return response()->json('Please fill all required fields of shipping/billing address', 403);
            }
            if ($Pincode==0 ) {
                return response()->json("Delivery not available for your pincode", 403);
            }
            $address_id = DB::table('shipping_addresses')->insertGetId([
                'customer_id' => 0,
                'contact_person_name' => $shipping['contact_person_name'],
                'address_type' => $shipping['address_type'],
                'address' => $shipping['address'],
                'city' => $shipping['city'],
                'zip' => $shipping['zip'],
                'state' => $shipping['state'],
                'phone' => $shipping['phone'],
                'latitude' => $shipping['latitude'],
                'longitude' => $shipping['longitude'],
                'is_billing' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            
            $shipping_address = ShippingAddress::where('id',$shipping['shipping_method_id'])->first();

            // $Pincode_check = Pincode::where('pincode', $shipping_address->zip)
            // ->orderBy('created_at', 'DESC')
            // ->count();
            $Pincode_check = Pincode::Where('from_pincode','<=', $shipping_address->zip)
            ->Where('to_pincode','>=', $shipping_address->zip)
            ->orderBy('created_at', 'DESC')
            ->count();
            if ($Pincode_check==0 ) {
                return response()->json('Delivery not available for your pincode', 403);
            }
            else{
                $address_id = $shipping['shipping_method_id'];
            }
        }


        if ($request->billing_addresss_same_shipping == 'false') {

            if (isset($billing['save_address_billing']) && $billing['save_address_billing'] == 'on') {

                if ($billing['billing_contact_person_name'] == null || $billing['billing_address'] == null || $billing['billing_city'] == null ) {
                    return response()->json('Please fill all required fields of shipping/billing address', 403);
                }

                $billing_address_id = DB::table('shipping_addresses')->insertGetId([
                    'customer_id' => auth('customer')->id(),
                    'contact_person_name' => $billing['billing_contact_person_name'],
                    'address_type' => $billing['billing_address_type'],
                    'address' => $billing['billing_address'],
                    'city' => $billing['billing_city'],
                    'zip' => $billing['billing_zip'],
                    'state' => $billing['billing_state'],
                    'phone' => $billing['billing_phone'],
                    'latitude' => $billing['billing_latitude'],
                    'longitude' => $billing['billing_longitude'],
                    'is_billing' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);


            } else if ($billing['billing_method_id'] == 0) {

                if ($billing['billing_contact_person_name'] == null || $billing['billing_address'] == null || $billing['billing_city'] == null ) {
                    return response()->json('Please fill all required fields of shipping/billing address', 403);
                }

                $billing_address_id = DB::table('shipping_addresses')->insertGetId([
                    'customer_id' => 0,
                    'contact_person_name' => $billing['billing_contact_person_name'],
                    'address_type' => $billing['billing_address_type'],
                    'address' => $billing['billing_address'],
                    'city' => $billing['billing_city'],
                    'zip' => $billing['billing_zip'],
                    'phone' => $billing['billing_phone'],
                    'state' => $billing['billing_state'],
                    'latitude' => $billing['billing_latitude'],
                    'longitude' => $billing['billing_longitude'],
                    'is_billing' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $billing_address_id = $billing['billing_method_id'];
            }
        } else {
            
            $billing_address_id = $shipping['shipping_method_id'];
        }

        session()->put('address_id', $address_id);
        session()->put('billing_address_id', $billing_address_id);
        $shipping_model = Helpers::get_business_settings('shipping_method');
        if ($shipping_model == 'third_party_shipping') {
            $ship_total_weight = 1000;
            $shipping_address = ShippingAddress::where('id',$address_id)->first();
            $ship_weight =  Helpers::WeightWithShippingCost($shipping_address->zip,$ship_total_weight);
            if($ship_weight==0){
                session()->put('pin_not_match', 0);
            }
           
        }

        return response()->json([], 200);
    }

}
