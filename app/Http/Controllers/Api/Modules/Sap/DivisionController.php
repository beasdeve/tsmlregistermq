<?php

namespace App\Http\Controllers\Api\Modules\Sap;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Models\SapDivision;
use JWTAuth;
use Validator;
use File; 
use Storage;
use Response;
use DB; 
use Mail;

class DivisionController extends Controller
{
    /**
     * This is for get  Sap Contract Type. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getSapDivision(Request $request)
    {
      \DB::beginTransaction();

        try{ 

           

          $sapDivision = SapDivision::where('distri_chanal_id',$request->distr_chan_code)->get();

          \DB::commit();

          if(!empty($sapDivision))
          {
            return response()->json(['status'=>1,'message' =>'success','result' => $sapDivision],config('global.success_status'));
          }
          else
          { 
            return response()->json(['status'=>1,'message' =>'Somthing went wrong','result' => []],config('global.success_status'));
          } 
           

        }catch(\Exception $e){ 
          \DB::rollback(); 
          return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
        }
    }
}
