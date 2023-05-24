<?php

namespace App\Http\Controllers\Api\Modules\Sap;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Models\SapDistributionChannel;
use JWTAuth;
use Validator;
use File; 
use Storage;
use Response;
use DB; 
use Mail;

class DistributionChannelController extends Controller
{
    /**
     * This is for get  Sap Contract Type. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getDistriChannel(Request $request)
    {
      \DB::beginTransaction();

        try{ 

          $sapSalesOffice = SapDistributionChannel::get();

          \DB::commit();

          if(!empty($sapSalesOffice))
          {
            return response()->json(['status'=>1,'message' =>'success','result' => $sapSalesOffice],config('global.success_status'));
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
