<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Model\HelpTopic;
use Illuminate\Http\Request;
use App\Model\Pincode;

class GeneralController extends Controller
{
    public function faq(){
        return response()->json(HelpTopic::orderBy('ranking')->get(),200);
    }

    public function PincodeChecking(Request $request){
        $Pincode = Pincode::Where('from_pincode','<=', $request->pincode)
            ->Where('to_pincode','>=', $request->pincode)
            ->orderBy('created_at', 'DESC')
            ->first();
        return response()->json($Pincode,200);
    }
}
