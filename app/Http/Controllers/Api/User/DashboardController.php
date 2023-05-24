<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\OtpVerification;
use App\Mail\Register;
use App\Models\User;
use App\Jobs\UserCreated;
use App\Models\Models\Order;
use App\Models\Models\Quote;
use Illuminate\Support\Facades\Hash;
use App\Models\Models\RegistrationLog;
use App\ServicesMy\MailService;
use JWTAuth;
use Validator;
use Response;
use Mail;
use DB;
use Nullix\CryptoJsAes\CryptoJsAes;

class DashboardController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function userDashboard(Request $request)
   {
   	  
   		$userid = $request->user_id;

   		$getuser = User::where('id',$userid)->first();

   		// dd($getuser);
   		// C -- Customer
   		// Kam -- cam
   		 
   		 if ($getuser->user_type == 'C') {
   		 	$quote = DB::table('orders')
            ->leftjoin('quotes','orders.rfq_no','quotes.rfq_no')             
            ->where('quotes.user_id',$userid)
            ->whereNull('quotes.deleted_at')
            ->count();
	        $data['total_no_of_orders'] = $quote;

	         $rfqNego = DB::table('quotes') 
	            ->where('quotes.user_id',$userid)
	            ->where('quotes.kam_status',6)
	            ->whereNull('quotes.deleted_at')
	            ->count();
	        $data['rfq_under_negotiation'] = $rfqNego;

	        $orderCon = DB::table('orders')
	            ->leftjoin('quotes','orders.rfq_no','quotes.rfq_no')             
	            ->where('quotes.user_id',$userid)
	            ->where('orders.status',1)
	            ->whereNull('quotes.deleted_at')
	            ->count();
	        $data['orders_confirmed_&_pending_for_delivery'] = $orderCon; 
	       

	        $custComplain = DB::table('complain_main') 
	            ->where('complain_main.user_id',$userid)
	            ->where('complain_main.closed_status',1) 
	            ->count();

	        $data['Total_no_of_open_complaints'] = $custComplain;
   		 }
   		 else if ($getuser->user_type == 'Kam') {
   		 	$quote = DB::table('orders')
            ->leftjoin('quotes','orders.rfq_no','quotes.rfq_no')  
            ->leftjoin('users','quotes.user_id','users.id')           
            ->where('users.zone',$getuser->zone)
            ->whereNull('quotes.deleted_at')
            ->count();
	        $data['total_no_of_orders'] = $quote;
	        // dd($getuser->zone);
	        $orderCon = DB::table('orders')
	            ->leftjoin('quotes','orders.rfq_no','quotes.rfq_no')             
	            ->leftjoin('users','quotes.user_id','users.id')
	            ->where('orders.status',1)
	            ->where('users.zone',$getuser->zone)
	            ->whereNull('quotes.deleted_at')
	            ->count();
	        $data['orders_confirmed_&_pending_for_delivery'] = $orderCon;

	        $rfqNego = DB::table('quotes') 
	            ->leftjoin('users','quotes.user_id','users.id')
	            ->where('quotes.kam_status',6)
	            ->where('users.zone',$getuser->zone)
	            ->whereNull('quotes.deleted_at')
	            ->count();
	        $data['rfq_under_negotiation'] = $rfqNego;

	        $custComplain = DB::table('complain_main') 
	        	->leftjoin('users','complain_main.user_id','users.id')
	            ->where('users.zone',$getuser->zone)
	            ->where('complain_main.closed_status',1) 
	            ->count();

	        $data['Total_no_of_open_complaints'] = $custComplain;
   		 }

   		
         
        // $password = "123456";
        // $encrypted = CryptoJsAes::encrypt($data, $password);
            
        return response()->json(['status'=>1,'message' =>'success.','result' => $data],200);
   }	
}
