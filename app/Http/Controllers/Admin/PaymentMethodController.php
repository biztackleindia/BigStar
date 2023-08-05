<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Currency;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return view('admin-views.business-settings.payment-method.index');
    }

    public function update(Request $request, $name)
    {
        if ($name == 'cash_on_delivery') {
            $payment = BusinessSetting::where('type', 'cash_on_delivery')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status']
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('business_settings')->where(['type' => 'cash_on_delivery'])->update([
                    'type' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status']
                    ]),
                    'updated_at' => now()
                ]);
            }
        }
        if ($name == 'digital_payment') {
            DB::table('business_settings')->updateOrInsert(['type' => 'digital_payment'], [
                'value' => json_encode([
                    'status' => $request['status']
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } elseif ($name == 'paypal') {
            $payment = BusinessSetting::where('type', 'paypal')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'paypal',
                    'value' => json_encode([
                        'status' => 1,
                        'environment'=>'sandbox',
                        'paypal_client_id' => '',
                        'paypal_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                if ($request['status'] == 1) {
                    $request->validate([
                        'paypal_client_id' => 'required',
                        'paypal_secret' => 'required'
                    ]);
                }
                DB::table('business_settings')->where(['type' => 'paypal'])->update([
                    'type' => 'paypal',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'environment'=>$request['environment'],
                        'paypal_client_id' => $request['paypal_client_id'],
                        'paypal_secret' => $request['paypal_secret']
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'stripe') {
            $payment = BusinessSetting::where('type', 'stripe')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'stripe',
                    'value' => json_encode([
                        'status' => 1,
                        'api_key' => '',
                        'published_key' => ''
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                if ($request['status'] == 1) {
                    $request->validate([
                        'api_key' => 'required',
                        'published_key' => 'required'
                    ]);
                }
                DB::table('business_settings')->where(['type' => 'stripe'])->update([
                    'type' => 'stripe',
                    'value' => json_encode([
                        'status' => $request['status'],
                        'api_key' => $request['api_key'],
                        'published_key' => $request['published_key']
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'razor_pay') {
            $payment = BusinessSetting::where('type', 'razor_pay')->first();
            if (isset($payment) == false) {
                DB::table('business_settings')->insert([
                    'type' => 'razor_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'razor_key' => '',
                        'razor_secret' => ''
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                if (Currency::where(['code' => 'INR'])->first() == false) {
                    Toastr::error('Please add INR currency before enable this gateway.');
                    return back();
                }

                if ($request['status'] == 1) {
                    $request->validate([
                        'razor_key' => 'required',
                        'razor_secret' => 'required'
                    ]);
                }
                DB::table('business_settings')->where(['type' => 'razor_pay'])->update([
                    'value' => json_encode([
                        'status' => $request['status'],
                        'razor_key' => $request['razor_key'],
                        'razor_secret' => $request['razor_secret']
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'paytm') {
            DB::table('business_settings')->updateOrInsert(['type' => 'paytm'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'environment'=>$request['environment'],
                    'paytm_merchant_key' => $request['paytm_merchant_key'],
                    'paytm_merchant_mid' => $request['paytm_merchant_mid'],
                    'paytm_merchant_website' => $request['paytm_merchant_website'],
                    'paytm_refund_url' => $request['paytm_refund_url'],
                ]),
                'updated_at' => now()
            ]);
        }
        return back();
    }
}