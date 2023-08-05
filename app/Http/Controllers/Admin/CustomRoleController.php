<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\AdminRole;
use App\CPU\Helpers;
use App\Model\Pincode;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use  Log;

class CustomRoleController extends Controller
{
    public function create()
    {
        $rl=AdminRole::whereNotIn('id',[1])->latest()->get();
        return view('admin-views.custom-role.create',compact('rl'));
    }
    public function createPincode(Request $request){
        $query_param = [];
        $search = $request['search'];

        if ($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $pincodes_data = Pincode::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('from_pincode','<=', $value)
                    ->Where('to_pincode','>=', $value);
                }
            });       
            $query_param = ['search' => $request['search']];
        }else{
            $pincodes_data = new Pincode();
           
        }
        $pincodes = $pincodes_data->orderBy('id', 'asc')->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.pincode-role.create',compact('search','pincodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:admin_roles'
        ],[
            'name.required'=>'Role name is required!'
        ]);

        DB::table('admin_roles')->insert([
            'name'=>$request->name,
            'module_access'=>json_encode($request['modules']),
            'status'=>1,
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        Toastr::success('Role added successfully!');
        return back();
    }
    public function storePincode(Request $request)
    {
        
        $request->validate([
            'from_pincode'=>'required',
            'to_pincode'=>'required'
        ],[
            'from_pincode.required'=>'From Pincode is required!',
            'to_pincode.required'=>'To Pincode is required!'
        ]);
 
        
        $data = DB::table('pincodes')->where(['from_pincode' => $request->from_pincode])->count();
        $from_pincode = $request->from_pincode;
        $to_pincode = $request->to_pincode;
        if($data==0){
        DB::table('pincodes')->insert([
            'from_pincode' => $request->from_pincode,
            'to_pincode' => $request->to_pincode,
        ]);
        Toastr::success('Pincode added successfully!');
       }
       else{
        Toastr::info('Pincode Already added!');
       }

        
        return back();
    }
    public function edit($id)
    {
        $role=AdminRole::where(['id'=>$id])->first(['id','name','module_access']);
        return view('admin-views.custom-role.edit',compact('role'));
    }
    public function Pincodeedit($id)
    {
        $pincodes = Pincode::where(['id' => $id])->first();
        return view('admin-views.pincode-role.edit',compact('pincodes'));
    }
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
        ],[
            'name.required'=>'Role name is required!'
        ]);

        DB::table('admin_roles')->where(['id'=>$id])->update([
            'name'=>$request->name,
            'module_access'=>json_encode($request['modules']),
            'status'=>1,
            'updated_at'=>now()
        ]);

        Toastr::info('Role updated successfully!');
        return back();
    }

    public function updatePincode(Request $request,$id)
    {
        $request->validate([
            'pincode'=>'required',
            
        ],[
            'pincode.required'=>'From Pincode is required!',
        ]);
        
        DB::table('pincodes')->where(['id'=>$id])->update([
            'from_pincode'=>$request->from_pincode,
            'to_pincode' => $request->to_pincode,
        ]);

        Toastr::success('Pincode updated successfully!');
        return back();
    }
    public function CashonDelivery_status(Request $request)
    {
        $Pincode = Pincode::find($request->id);
       
        $Pincode->is_cash_on_delivery = ($Pincode['is_cash_on_delivery'] == 0) ? 1 : 0;

        $data = DB::table('pincodes')->where(['id'=>$request->id])->update([
            'is_cash_on_delivery'=>$Pincode->is_cash_on_delivery,
        ]);
        return response()->json($data);
    }
    public function OnlineDelivery_status(Request $request)
    {
        $Pincode = Pincode::find($request->id);
       
        $Pincode->is_online_payment = ($Pincode['is_online_payment'] == 0) ? 1 : 0;

        $data = DB::table('pincodes')->where(['id'=>$request->id])->update([
            'is_online_payment'=>$Pincode->is_online_payment,
        ]);
        return response()->json($data);
    }
    public function DeletePincode(Request $request)
    {
        
        $pincode = Pincode::find($request->id);
        $pincode->delete();
        return back();
    }

 
}