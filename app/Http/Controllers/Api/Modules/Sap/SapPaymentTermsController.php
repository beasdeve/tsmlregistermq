<?php

namespace App\Http\Controllers\Api\Modules\Sap;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Models\SapPaymentTerms;
use JWTAuth;
use Validator;
use File; 
use Storage;
use Response;
use DB; 
use Mail;

class SapPaymentTermsController extends Controller
{
    /**
     * This is for get Sap Incoterms. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getPaymentTerms(Request $request)
    {
      \DB::beginTransaction();

        try{ 

          $sapPayTerms = SapPaymentTerms::get();

          \DB::commit();

          if(!empty($sapPayTerms))
          {
            return response()->json(['status'=>1,'message' =>'success','result' => $sapPayTerms],config('global.success_status'));
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
