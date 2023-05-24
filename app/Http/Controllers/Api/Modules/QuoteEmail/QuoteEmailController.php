<?php

namespace App\Http\Controllers\Api\Modules\QuoteEmail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Models\Quote;
use App\Models\Models\Plant;
use App\Models\Models\DeliveryMethod;
// use App\Mail\RfqGeneratedMail;
// use App\Mail\AcceptedRfqMail;
// use App\Mail\OrderConfirmationMail;
// use App\Mail\SalesacceptMail;
use App\ServicesMy\MailService;
use App\Models\User;
use Validator;
use Auth;
use DB;
use \PDF;
use Mail;

class QuoteEmailController extends Controller
{
    public function quotePoMail(Request $request)
    {
         $cc_email = array();

    	 $rfq_no = $request->input('rfq_no');
    	 $user_id = $request->input('user_id');
    	 
         
         $user = User::where('id',$user_id)->first();

         $cam = User::where('zone',$user->zone)->where('id','!=',$user_id)->where('user_type','Kam')->get()->toArray();

         foreach ($cam as $key => $value) {
         	 
         	  array_push($cc_email,$value['email']);
         }

         $sub = 'Your RFQ has been raised successfully'.'   '.$rfq_no;
 
         $html = 'mail.rfqgeneratedmail';

         $data = "";

    	 // $data['name'] = $user['name'];
      //    $data['email'] = $user['email'];
      //    $data['rfq_no'] = $rfq_no;
      //    $data['cc'] = $cc_email;
         // echo "<pre>";print_r($data);exit();
        (new MailService)->dotestMail($sub,$html,$user['email'],$data,$cc_email);
         // Mail::send(new RfqGeneratedMail($data));

         $msg = "Mail sent successfully";
         return response()->json(['status'=>1,'message' =>$msg],200);
    }


    // --------------------  accepted price mail ------------------------------------
    public function acceptedPriceMail(Request $request)
    {
         $cc_email = array();

         $rfq_no = $request->input('rfq_no');
         $user_id = $request->input('user_id');
         $kam_id = $request->input('kam_id');
         
         
         $user = User::where('id',$user_id)->first();

         $cam = User::where('zone',$user->zone)->where('id','!=',$user_id)->where('user_type','Kam')->get()->toArray();

         foreach ($cam as $key => $value) {
             
              array_push($cc_email,$value['email']);
         }

         $sub = 'Sales team has accepted the price, volume and delivery timeline'.'   '.$rfq_no;
 
         $html = 'mail.priceaceptancemail';

         $data = "";

         // $data['name'] = $user['name'];
         // $data['email'] = $user['email'];
         // $data['rfq_no'] = $rfq_no;
         // $data['cc'] = $cc_email;
         // echo "<pre>";print_r($data);exit();
         (new MailService)->dotestMail($sub,$html,$user['email'],$data,$cc_email);
         // Mail::send(new AcceptedRfqMail($data));

         $msg = "Mail sent successfully";
         return response()->json(['status'=>1,'message' =>$msg],200);
    }
    // -------------------------------------------------------------------------------

    // --------------------  order confirmation mail --------------------------------
    public function orderCnrfmMail(Request $request)
    {
         $cc_email = array();

         $rfq_no = $request->input('rfq_no');
         $user_id = $request->input('user_id');
         
         
         
         $user = User::where('id',$user_id)->first();

         $cam = User::where('zone',$user->zone)->where('id','!=',$user_id)->where('user_type','Kam')->get()->toArray();

         foreach ($cam as $key => $value) {
             
              array_push($cc_email,$value['email']);
         }

         $sub = 'TSML Order confirmation'.'   '.$rfq_no;
 
         $html = 'mail.orderconfirmationmail';

         $data = "";

         // $data['name'] = $user['name'];
         // $data['email'] = $user['email'];
         // $data['rfq_no'] = $rfq_no;
         // $data['cc'] = $cc_email;
         // // echo "<pre>";print_r($data);exit();

         // Mail::send(new OrderConfirmationMail($data));
         (new MailService)->dotestMail($sub,$html,$user['email'],$data,$cc_email);

         $msg = "Mail sent successfully";
         return response()->json(['status'=>1,'message' =>$msg],200);
    }
    // ------------------------------------------------------------------------------

    // --------------------  sales acceptance mail --------------------------------
    public function saleAccptMail(Request $request)
    {
         $cc_email = array();

         $rfq_no = $request->input('rfq_no');
         $user_id = $request->input('user_id');
         
                 
         $user = User::where('id',$user_id)->first();

         $cam = User::where('zone',$user->zone)->where('id','!=',$user_id)->where('user_type','Kam')->get()->toArray();

         foreach ($cam as $key => $value) {
             
              array_push($cc_email,$value['email']);
         }

         $sub = 'Your RFQ has been raised successfully'.'   '.$rfq_no;
 
         $html = 'mail.salesacceptmail';

         $data = "";

         // $data['name'] = $user['name'];
         // $data['email'] = $user['email'];
         // $data['rfq_no'] = $rfq_no;
         // $data['cc'] = $cc_email;
         // echo "<pre>";print_r($data);exit();

         // Mail::send(new SalesacceptMail($data));
         (new MailService)->dotestMail($sub,$html,$user['email'],$data,$cc_email);
         $msg = "Mail sent successfully";
         return response()->json(['status'=>1,'message' =>$msg],200);
    }
    // -----------------------------------------------------------------------------

    // -------------------  sc mail ------------------------------------------

    public function scMail(Request $request)

        {
             $cc_email = array();

             $sc_no = $request->input('sc_no');
             $po_no = $request->input('po_no');
             $user_id = $request->input('user_id');
             
             
             $user = User::where('id',$user_id)->first();

             $cam = User::where('zone',$user->zone)->where('id','!=',$user_id)->where('user_type','Kam')->get()->toArray();

             foreach ($cam as $key => $value) {
                 
                  array_push($cc_email,$value['email']);
             }

             $sub = 'Your Sales Contarct is genrated';
     
             $html = 'mail.scgeneratemail';

             $data['sc_no'] = $sc_no;
             $data['po_no'] = $po_no;

             // $data['name'] = $user['name'];
          //    $data['email'] = $user['email'];
          //    $data['rfq_no'] = $rfq_no;
          //    $data['cc'] = $cc_email;
             // echo "<pre>";print_r($data);exit();
            (new MailService)->dotestMail($sub,$html,$user['email'],$data,$cc_email);
             // Mail::send(new RfqGeneratedMail($data));

             $msg = "Mail sent successfully";
             return response()->json(['status'=>1,'message' =>$msg],200);
        }
    // -----------------------------------------------------------------------

    // --------------------  sales head acceptance mail --------------------------------
    public function saleHeadAccptMail(Request $request)
    {
         $cc_email = array();

         $rfq_no = $request->input('rfq_no');
         $user_id = $request->input('user_id');
         
                 
         $user = User::where('id',$user_id)->first();

         $cam = User::where('zone',$user->zone)->where('id','!=',$user_id)->where('user_type','Kam')->get()->toArray();

         foreach ($cam as $key => $value) {
             
              array_push($cc_email,$value['email']);
         }

         $sub = 'Congratulations! Sales team has quoted the price, volume and delivery timeline against your RFQ';
 
         $html = 'mail.salesheadaaceptmail';

         $data = "";

         // $data['name'] = $user['name'];
         // $data['email'] = $user['email'];
         // $data['rfq_no'] = $rfq_no;
         // $data['cc'] = $cc_email;
         // echo "<pre>";print_r($data);exit();

         // Mail::send(new SalesacceptMail($data));
         (new MailService)->dotestMail($sub,$html,$user['email'],$data,$cc_email);
         $msg = "Mail sent successfully";
         return response()->json(['status'=>1,'message' =>$msg],200);
    }
    // -----------------------------------------------------------------------------


}
