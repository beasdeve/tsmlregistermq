<?php

namespace App\Http\Controllers\Api\Modules\Sap;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Models\SapCustomerGroup;
use JWTAuth;
use Validator;
use File; 
use Storage;
use Response;
use DB; 
use Mail;

class SapCustomerGroupController extends Controller
{
    /**
     * This is for get  Sap Customer Group. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getSapCustomerGroup(Request $request)
    {
      \DB::beginTransaction();

        try{ 

          $sapCustGroupData = SapCustomerGroup::get();

          \DB::commit();

          if(!empty($sapCustGroupData))
          {
            return response()->json(['status'=>1,'message' =>'success','result' => $sapCustGroupData],config('global.success_status'));
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
