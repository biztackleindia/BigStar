<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\CPU\Helpers;
use Illuminate\Http\Request;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\BusinessSetting;
use App\Model\Product;
use App\User;
use Log;
use Barryvdh\DomPDF\Facade as PDF;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
class ReportController extends Controller
{
    public function order_index(Request $request)
    {

        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        return view('admin-views.report.order-index');
    }

    public function earning_index(Request $request)
    {
        if (!$request->has('from_date')) {
            $from = $to = date('Y-m-01');
        } else {
            $from = $request['from_date'];
            $to = $request['to_date'];
        }
        return view('admin-views.report.earning-index', compact('from', 'to'));
    }
    public function order_detail_index(Request $request)
    {
        
       
        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
            
        }
        $from = session('from_date');
        $to = session('to_date');
       
        $customer_type = session('order_type');
        if($customer_type ==''){
           $customer_type = session()->put('order_type', 'customer');

        }
       
        

        $order_data = Order::whereRaw(
            "(created_at >= ? AND created_at <= ?)", 
            [
               $from ." 00:00:00", 
               $to ." 23:59:59"
            ]
          )->where('order_type','default_type')->get();

          $orders = [];

        foreach ($order_data as $item) {
      
        if($customer_type=='customer'){
         $list_member =User::where('id', $item['customer_id'])
         ->where('member_approval', 0)->count();
        
         if($list_member==1){
          
            $orders[] = [
                'id' => $item['id'],
                'customer_id' => $item['customer_id'],
                'discount_amount' => $item['discount_amount'],
                'shipping_cost' => $item['shipping_cost'],
                'order_amount' => $item['order_amount'],
                'created_at' => $item['created_at'],
                'order_status'=>$item['order_status'],
                'shipping_address_data'=>$item['shipping_address_data'],
                'payment_status' =>$item['payment_status'],
                'order_details' => OrderDetail::where('order_id', $item['id'])->get(),
                
            ];
         }
        }
        else{
           
            $list_member =User::where('id', $item['customer_id'])
            ->where('member_approval', 1)->count();
            if($list_member==1){
               $orders[] = [
                   'id' => $item['id'],
                   'customer_id' => $item['customer_id'],
                   'discount_amount' => $item['discount_amount'],
                   'shipping_cost' => $item['shipping_cost'],
                   'order_amount' => $item['order_amount'],
                   'created_at' => $item['created_at'],
                   'order_status'=>$item['order_status'],
                   'shipping_address_data'=>$item['shipping_address_data'],
                   'payment_status' =>$item['payment_status'],
                   'order_details' => OrderDetail::where('order_id', $item['id'])->get(),
                   
               ];
            }
        }
            
        }
        if($request->has('download')){  
            
            $mpdf_view = \View::make('admin-views.report.order-pdf')->with('orders', $orders)->with('from', $from)->with('to', $to);
            $now= date("d-m-y");
            Helpers::gen_mpdf($mpdf_view, 'order_pdf_', $now); 
        }  
        $orders = $this->paginate($orders);

        $previousUrl = strtok(url()->current(), '?');
        $orders->setPath($previousUrl . '?' . http_build_query(['from_date' => $from, 'to_date' => $to]));

        return view('admin-views.report.order-detail-report', compact('from', 'to','orders'));
    }
   
    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    public function set_date(Request $request)
    {
        $from = $request['from'];
        $to = $request['to'];

        session()->put('from_date', $from);
        session()->put('to_date', $to);

        $previousUrl = strtok(url()->previous(), '?');
        return redirect()->to($previousUrl . '?' . http_build_query(['from_date' => $request['from'], 'to_date' => $request['to']]))->with(['from' => $from, 'to' => $to]);
    }
    function OrderTypeSet(Request $request){
        $from = session('from_date');
        $to = session('to_date');
   
        $order_type = $request['order_type'];
        session()->put('order_type', $order_type);
        $previousUrl = strtok(url()->previous(), '?');
        return redirect()->to($previousUrl . '?' . http_build_query(['from_date' => $from, 'to_date' => $to]))->with(['from' => $from, 'to' => $to]);
    }
    public function orderReportExport()
    {
     
        $from = session('from_date');
        $to = session('to_date');
       
        $order_data = Order::whereRaw(
            "(created_at >= ? AND created_at <= ?)", 
            [
               $from ." 00:00:00", 
               $to ." 23:59:59"
            ]
          )->get();

        // Log::error($order_data);
        // export from report
        $storage = [];
        foreach ($order_data as $item) {
           
            $storage[] = [
                'Order Id' => $item['id'],
                'Customer Id' => $item['customer_id'],
                'Discount Amount' => $item['discount_amount'],
                'Shipping Cost' => $item['shipping_cost'],
                'Order Amount' => $item['order_amount'],
                'Date' => $item['created_at']
                
            ];
        }
        return (new FastExcel($storage))->download('order_report.xlsx');
    }
    public function ProductSaleReportExport(Request $request){
        $from = $request['from'];
        $to = $request['to'];

        session()->put('from_date', $from);
        session()->put('to_date', $to);
        $OrderDetail = OrderDetail::whereRaw(
            "(created_at >= ? AND created_at <= ? AND delivery_status = ?)", 
            [
               $from ." 00:00:00", 
               $to ." 23:59:59" ,
               "delivered"
            ]
          )
          ->groupBy('product_id')
          ->get();
          $products = [];
          
          foreach ($OrderDetail as $det) {
            $det['product_details'] = Helpers::product_data_formatting(json_decode($det['product_details'], true));
        }
          foreach ($OrderDetail as $item) {
                $single_count = OrderDetail::whereRaw(
                    "(created_at >= ? AND created_at <= ? AND delivery_status = ? AND product_id = ? )", 
                    [
                    $from ." 00:00:00", 
                    $to ." 23:59:59" ,
                    "delivered",
                    $item['product_details']['id']
                    ]
                )->count();

                $products[] = [
                    'product_id' => $item['product_details']['id'],
                    'name' => $item['product_details']['name'],
                    'delivery_status' => $item['delivery_status'],
                    'count' =>$single_count
                ];
          }
     
         // export from report
        $storage = [];
        foreach ($products as $product) {
           
            $storage[] = [
                'Product Id' => $product['product_id'],
                'Product Name' => $product['name'],
                'Delivery Status' => $product['delivery_status'],
                'Count' => $product['count'],
            ];
           
        }
        return (new FastExcel($storage))->download('product_sale_report.xlsx');
        
    }
    public function DeliveryFormat_index(Request $request)
    {
        
       
        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
            
        }
        $from = session('from_date');
        $to = session('to_date');
       
        $customer_type = session('order_type');
        if($customer_type ==''){
           $customer_type = session()->put('order_type', 'customer');

        }
        $transport_mode = BusinessSetting::where('type', 'transport_mode')->first();
        $order_data = Order::whereRaw(
            "(created_at >= ? AND created_at <= ?)", 
            [
               $from ." 00:00:00", 
               $to ." 23:59:59"
            ]
          )->where('order_type','default_type')->get();

          $orders = [];

        foreach ($order_data as $item) {
      
        if($customer_type=='customer'){
         $list_member =User::where('id', $item['customer_id'])
         ->where('member_approval', 0)->count();
        
         if($list_member==1){
            $order_details = OrderDetail::where('order_id', $item['id'])->get();
            foreach ($order_details as $order_item) {
              
            $orders[] = [
                'id' => $item['id'],
                'customer_id' => $item['customer_id'],
                'discount_amount' => $item['discount_amount'],
                'shipping_cost' => $item['shipping_cost'],
                'order_amount' => $item['order_amount'],
                'created_at' => $item['created_at'],
                'order_status'=>$item['order_status'],
                'payment_method'=>$item['payment_method'],
                'shipping_address_data'=>$item['shipping_address_data'],
                'billing_address_data' =>$item['billing_address_data'],
                'payment_status' =>$item['payment_status'],
                'transport_mode' =>$transport_mode->value,
                'product' => $order_item->product_details,
                'qty' => $order_item->qty,
                'price' => $order_item->price,
                'product_sku' => Product::where(['id' => $order_item->product_id])->first()
                
            ];
            
        }
         }
        }
        else{
           
            $list_member =User::where('id', $item['customer_id'])
            ->where('member_approval', 1)->count();
            if($list_member==1){
                $order_details = OrderDetail::where('order_id', $item['id'])->get();
                foreach ($order_details as $order_item) {
                    
                $orders[] = [
                    'id' => $item['id'],
                    'customer_id' => $item['customer_id'],
                    'discount_amount' => $item['discount_amount'],
                    'shipping_cost' => $item['shipping_cost'],
                    'order_amount' => $item['order_amount'],
                    'created_at' => $item['created_at'],
                    'order_status'=>$item['order_status'],
                    'payment_method'=>$item['payment_method'],
                    'shipping_address_data'=>$item['shipping_address_data'],
                    'billing_address_data' =>$item['billing_address_data'],
                    'payment_status' =>$item['payment_status'],
                    'transport_mode' =>$transport_mode->value,
                    'product' => $order_item->product_details,
                    'product_sku' => Product::where(['id' => $order_item->product_id])->first(),
                    'qty' => $order_item->qty,
                    'price' => $order_item->price,
                    
                ];
            }
            }
        }
            
        }
        if($request->has('download')){  
            $storage = [];
            $store_locations = DB::table('pickup_locations')->where(['is_active' => 1])->get();
            
            $pickup_id=  DB::table('business_settings')->where(['type' => 'pickup_location_id'])->get();
            $pick_up_location ='';
            foreach ($store_locations as $location) {
                if($location->id == $pickup_id[0]->value){
                    $pick_up_location =$location->pickup_name;
                }
            }

            foreach ($orders as $order) {

                $payment_method="Prepaid";
                $cod_order_amount =0;
                if($order['payment_method'] =='cash_on_delivery'){
                    $payment_method="COD";
                    $cod_order_amount =$order['order_amount'];
                }
                $shipping_address=json_decode($order['shipping_address_data']);
                $billing_address_data=json_decode($order['billing_address_data']);
                $product=json_decode($order['product']);
                $sku=json_decode($order['product_sku']);
                $customer = User::find($order['customer_id']);
                // dd($sku->product_sku);
                $storage[] = [
                    'Sale Order Number*' => $order['id'],
                    'Pickup Location Name*' =>$pick_up_location,
                    'Transport Mode*' => $order['transport_mode'],
                    'Payment Mode*' => $payment_method,
                    'COD Amount' => $cod_order_amount,
                    'Customer Name*' => $shipping_address ? $shipping_address->contact_person_name :'', 
                    'Customer Phone*' => $shipping_address ? $shipping_address->phone :'',
                    'Shipping Address Line1*' =>$shipping_address ? $shipping_address->address : '',
                    'Shipping Address Line2' => '',
                    'Shipping City*' =>$shipping_address ? $shipping_address->city :'',
                    'Shipping State*' =>$shipping_address ? $shipping_address->state :'',
                    'Shipping Pincode*' =>$shipping_address ? $shipping_address->zip: '',
                    'Item Sku Code*' => $sku ? $sku->product_sku : '',
                    'Item Sku Name*' => $product ? $product->name :'',
                    'Quantity Ordered*' => $order['qty'],
                    'Unit Item Price*' => $order['price'],
                    'Length (cm)' => '',
                    'Breadth (cm)' => '',
                    'Height (cm)' =>'',
                    'Weight (gm)' =>'',
                    'Fragile Shipment' =>'',
                    'Discount Type' =>$product ? $product->discount_type :'',
                    'Discount Value' =>$product ? $product->discount :'',
                    'Tax Class Code' =>'',
                    'Customer Email' =>$customer->email,
                    'Billing Address same as Shipping Address' =>'',
                    'Billing Address Line1' =>$billing_address_data ? $billing_address_data->address :'',
                    'Billing Address Line2' =>'',
                    'Billing City' =>$billing_address_data ? $billing_address_data->city :'',
                    'Billing State' =>$billing_address_data ? $billing_address_data->state :'',
                    'Billing Pincode' =>$billing_address_data ? $billing_address_data->zip :'',
                    'e-Way Bill Number' =>'',
                    'Seller Name' =>'',
                    'Seller GST Number' =>'',
                    'Seller Address Line1' =>'',
                    'Seller Address Line2' =>'',
                    'Seller City' =>'',
                    'Seller State' =>'',
                    'Seller Pincode' =>'',
                    
                ];
            }
            $now= date("d-m-y");
            return (new FastExcel($storage))->download('delivery_format_'.$now.'.xlsx');
        }  
        $orders = $this->paginate($orders);

        $previousUrl = strtok(url()->current(), '?');
        $orders->setPath($previousUrl . '?' . http_build_query(['from_date' => $from, 'to_date' => $to]));

        return view('admin-views.report.delivery_format', compact('from', 'to','orders'));
    }
    public function PickupUpdate(Request $request)
    {
        $location_id = $request['location_id'];
        $data= DB::table('business_settings')->where(['type' => 'pickup_location_id'])->update([
            'type' => 'pickup_location_id',
            'value' => $location_id,
            'updated_at' => now()
        ]);
        return $data;
    }
    
}
