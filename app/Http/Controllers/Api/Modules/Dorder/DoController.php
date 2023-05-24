<?php

namespace App\Http\Controllers\Api\Modules\Dorder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeliveryOrders;
 use App\Models\ProductSubCategory;
use JWTAuth;
use Validator;
use File; 
use Storage;
use Response;
use DB; 
use Mail;
use Auth;


class DoController extends Controller
{
    /**
     * This is for store do.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
   	public function storeDo(Request $request)
   	{ 

   		// dd($request->all());
         
    	$validation = \Validator::make($request->all(),[ 
    		 
        "so_no" => "required", 
        ]);

        if ($validation->fails()) {
            return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
        }

        $input['user_id'] = $request->user_id;
        $input['plant_id'] = $request->plant_id;
        $input['so_no'] = $request->so_no;
        $input['do_no'] = $request->do_no;
        $input['invoice_no'] = $request->invoice_no; 
        $input['invoice_date'] = date("Y-m-d H:i:s", strtotime($request->invoice_date));
        $input['material_grade'] = $request->material_grade;
        $input['do_quantity'] = $request->do_quantity;
        $input['despatch_date'] = date("Y-m-d H:i:s", strtotime($request->despatch_date));
        $input['truck_no'] = $request->truck_no;
        $input['driver_no'] = $request->driver_no;
        $input['premarks'] = $request->premarks;

        if ($request->hasFile('lr_file'))
	    {  

	    	$image = $request->lr_file; 

            $filename = rand(1000,9999).'-'.$image->getClientOriginalName();
                    Storage::putFileAs('public/images/do/', $image, $filename);

            $input['lr_file'] = $filename;

	    }

	    if ($request->hasFile('e_waybill_file'))
        {  

            $image = $request->e_waybill_file; 

            $filename = rand(1000,9999).'-'.$image->getClientOriginalName();
                    Storage::putFileAs('public/images/do/', $image, $filename);

            $input['e_waybill_file'] = $filename;

        }
	    if ($request->hasFile('test_certificate_file'))
	    {
	    	$image = $request->test_certificate_file; 

            $filename = rand(1000,9999).'-'.$image->getClientOriginalName();
                    Storage::putFileAs('public/images/do/', $image, $filename);

            $input['test_certificate_file'] = $filename;

	    	 
	    }
	    if ($request->hasFile('e_invoice_file'))
	    {
	    	$image = $request->e_invoice_file; 

            $filename = rand(1000,9999).'-'.$image->getClientOriginalName();
                    Storage::putFileAs('public/images/do/', $image, $filename);

            $input['e_invoice_file'] = $filename; 
	    	 
	    }

        if ($request->hasFile('misc_doc_file'))
        {
            $image = $request->misc_doc_file; 

            $filename = rand(1000,9999).'-'.$image->getClientOriginalName();
                    Storage::putFileAs('public/images/do/', $image, $filename);

            $input['misc_doc_file'] = $filename; 
             
        }

	    // dd($input);

        $doData = DeliveryOrders::create($input); 

        // return response()->json(['sucs'=>'New category added successfully.'],200);

   	  	return response()->json(['status'=>1,'message' =>'Delivery orders added successfully.','result' => $doData],200);
		 
    }

    /**
     * This is get do details. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getDoDetails(Request $request)
    {

      try{
            $validator = Validator::make($request->all(), [  
            'do_id'        => 'required', 
             
            ]);

            if ($validator->fails()) { 
                return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
            }

            $doData = DeliveryOrders::where('id',$request->do_id)->with('subCategory')->first();
            
              
            if (!empty($doData)) {
                    # code...
                   
                $data['do_id'] = (!empty($doData->id)) ? $doData->id : '';
                $data['so_no'] = (!empty($doData->so_no)) ? $doData->so_no : '';
                $data['do_no'] = (!empty($doData->do_no)) ?  $doData->do_no : '';
                $data['invoice_no'] = (!empty($doData->invoice_no)) ?  $doData->invoice_no : '';
                $data['invoice_date'] = date('d-m-Y H:i:s',strtotime($doData->invoice_date));
                
                $data['material_grade'] = (!empty($doData->material_grade)) ?  $doData->material_grade : '';
                $data['grade_name'] = (!empty($doData['subCategory']->sub_cat_name)) ?  $doData['subCategory']->sub_cat_name : '';
                $data['do_quantity'] = (!empty($doData->do_quantity)) ?  $doData->do_quantity : '';
                $data['despatch_date'] = date('d-m-Y H:i:s',strtotime($doData->despatch_date));
                $data['truck_no'] = (!empty($doData->truck_no)) ?  $doData->truck_no : '';
                $data['driver_no'] = (!empty($doData->driver_no)) ?  $doData->driver_no : '';
                $data['premarks'] = (!empty($doData->premarks)) ?  $doData->premarks : '';
                 

                if (isset($doData->lr_file)) 
                {

                    $data['lr_file'] = asset('storage/app/public/images/do/'.$doData->lr_file);
                }
                else
                {
                    $data['lr_file'] =  null;
                }
                
                if(isset($doData->e_waybill_file))
                {
                    $data['e_waybill_file'] =  asset('storage/app/public/images/do/'.$doData->e_waybill_file);
                }
                else
                {
                    $data['e_waybill_file'] =  null;  
                }

                if(isset($doData->test_certificate_file))
                {
                    $data['test_certificate_file'] =  asset('storage/app/public/images/do/'.$doData->test_certificate_file);
                }
                else
                {
                    $data['test_certificate_file'] =  null;  
                }

                if(isset($doData->e_invoice_file))
                {
                    $data['e_invoice_file'] =  asset('storage/app/public/images/do/'.$doData->e_invoice_file);
                }
                else
                {
                    $data['e_invoice_file'] =  null;    
                }

                if(isset($doData->misc_doc_file))
                {
                    $data['misc_doc_file'] =  asset('storage/app/public/images/do/'.$doData->misc_doc_file);
                }
                else
                {
                    $data['misc_doc_file'] =  null;    
                } 

            
                return response()->json(['status'=>1,'message' =>'Success','result' => $data],200); 
            }
            else{
                return response()->json(['status'=>1,'message' =>'No data found','result' => []],config('global.success_status'));
            }
            
            
          }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json([$response]);
          }
    }



   // ----------------------------- get do sub category id -------------------------

     public function getDoSubCats($so_no)
      {

          try{ 
               
            $res = DB::table('sales_orders')
               ->leftjoin('orders','sales_orders.po_no','orders.po_no')
               ->leftjoin('quotes','orders.rfq_no','quotes.rfq_no')
               ->leftjoin('quote_schedules','quotes.id','quote_schedules.quote_id')
               ->leftjoin('sub_categorys','quote_schedules.sub_cat_id','sub_categorys.id')
               ->where('sales_orders.so_no',$so_no)->whereNull('quotes.deleted_at')->whereNull('quote_schedules.deleted_at')
               ->select('sub_categorys.id','sub_categorys.sub_cat_name')->get();
               
                   // echo "<pre>";print_r($newcount);exit();
             
              return response()->json(['status'=>1,
                'message' =>'success',
                'result' => $res],
                config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

         
      }

   // ---------------------------------------------------------------------------

    // ----------------------------- get do sub category id -------------------------

     public function getAllDo($id)
      {

          try{ 
               
            $res = DB::table('delivery_orders')
              ->leftjoin('sales_orders','delivery_orders.so_no','sales_orders.so_no')
               ->leftjoin('sales_contracts','sales_orders.transact_id','sales_contracts.id')
               // ->leftjoin('sub_categorys','quote_schedules.sub_cat_id','sub_categorys.id')
              ->leftjoin('users','sales_orders.user_id','users.id')
               // ->where('orders.po_no',$po_no)->whereNull('quotes.deleted_at')->whereNull('quote_schedules.deleted_at')
              ->where('delivery_orders.plant_id',$id)
               ->select('sales_orders.so_no','sales_orders.created_at','delivery_orders.do_no','delivery_orders.do_quantity','delivery_orders.created_at as do_date','users.name','sales_contracts.qty_cont','delivery_orders.id as do_id')
               ->get();

               foreach ($res as $key => $value) {
                  
                  $arra[$key]['do_id'] = $value->do_id;
                  $arra[$key]['so_no'] = $value->so_no;
                  $arra[$key]['do_no'] = $value->do_no;
                  $arra[$key]['do_quantity'] = $value->do_quantity;
                  $arra[$key]['so_date'] = date('d-m-Y',strtotime($value->created_at));
                  $arra[$key]['do_date'] = date('d-m-Y',strtotime($value->do_date));
                  $arra[$key]['qty_cont'] = $value->qty_cont;
                  $arra[$key]['cus_name'] = $value->name;
                  
               }
               
                   // echo "<pre>";print_r($newcount);exit();
             
              return response()->json(['status'=>1,
                'message' =>'success',
                'result' => $arra],
                config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

         
      }

      public function validateDo(Request $request)
      {
        // dd('ok');
          try{ 
               
            $res = DB::table('sales_orders')
              // ->leftjoin('sales_orders','delivery_orders.so_no','sales_orders.so_no')
               ->leftjoin('sales_contracts','sales_orders.transact_id','sales_contracts.id')
               // ->leftjoin('sub_categorys','quote_schedules.sub_cat_id','sub_categorys.id')
              // ->leftjoin('users','sales_orders.user_id','users.id')
               // ->where('orders.po_no',$po_no)->whereNull('quotes.deleted_at')->whereNull('quote_schedules.deleted_at')
              ->where('sales_orders.so_no',$request->so_no)
               ->select('sales_orders.so_no','sales_contracts.qty_cont')
               ->first();
               $doQuenty = $res->qty_cont;
                
               // dd($doQuenty);
               // dd($request->do_quantity);

               $chek =  DeliveryOrders::where('so_no',$request->so_no)->get();
                   // echo "<pre>";print_r($newcount);exit();
               $doqua = 0;
               foreach ($chek as $key => $value) {
                $doqua += $value->do_quantity;
               }


                
                if ($doqua>0) {

                  $totalvaldo = $doqua+$request->do_quantity;
                  if ($doQuenty<$doqua || $doQuenty<$request->do_quantity || $doQuenty<$totalvaldo) { 
                  return response()->json(['status'=>0,'message' =>'DO quantity not greater then SO quantity','result' => []],config('global.success_status'));
                   }
                   else{
                    return response()->json(['status'=>1,'message' =>'Success']);
                   }
                }
                else{
                  if ($doQuenty<$doqua || $doQuenty<$request->do_quantity) { 
                  return response()->json(['status'=>0,'message' =>'DO quantity not greater then SO quantity','result' => []],config('global.success_status'));
                   }
                   else{
                    return response()->json(['status'=>1,'message' =>'Success']);
                   }
                }
                
                
                
               
             
              // return response()->json(['status'=>1,
              //   'message' =>'success',
              //   'result' => $doQuenty],
              //   config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

         
      }

   // ---------------------------------------------------------------------------

      // ----------------------------- get do sub category id -------------------------

     public function getAllDoCus($id)
      {

          try{ 
               $arra = array();
            $res = DB::table('sales_orders')->leftjoin('delivery_orders','sales_orders.so_no','delivery_orders.so_no')
               ->leftjoin('sales_contracts','sales_orders.transact_id','sales_contracts.id')
               // ->leftjoin('sub_categorys','quote_schedules.sub_cat_id','sub_categorys.id')
              ->leftjoin('users','sales_orders.user_id','users.id')
               // ->where('orders.po_no',$po_no)->whereNull('quotes.deleted_at')->whereNull('quote_schedules.deleted_at')
               ->select('sales_orders.so_no','sales_orders.created_at','delivery_orders.do_no','delivery_orders.do_quantity','delivery_orders.created_at as do_date','users.name','sales_contracts.qty_cont','delivery_orders.id as do_id')
               ->where('users.id',$id)
               ->get();
               // echo "<pre>";print_r($res);exit();
               if(!empty($res))
               {
               foreach ($res as $key => $value) {
                  
                  $arra[$key]['do_id'] = $value->do_id;
                  $arra[$key]['so_no'] = $value->so_no;
                  $arra[$key]['do_no'] = $value->do_no;
                  $arra[$key]['do_quantity'] = $value->do_quantity;
                  $arra[$key]['so_date'] = date('d-m-Y',strtotime($value->created_at));
                  $arra[$key]['do_date'] = date('d-m-Y',strtotime($value->do_date));
                  $arra[$key]['qty_cont'] = $value->qty_cont;
                  $arra[$key]['cus_name'] = $value->name;
                  
               }
             }
             
              return response()->json(['status'=>1,
                'message' =>'success',
                'result' => $arra],
                config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

         
      }

   // ---------------------------------------------------------------------------

   // ----------------------------- get do for cus -------------------------

     public function get_do_by_cus($id)
      {

          try{ 
               
            $res = DB::table('delivery_orders')
              ->leftjoin('sales_orders','delivery_orders.so_no','sales_orders.so_no')
               ->leftjoin('sales_contracts','sales_orders.transact_id','sales_contracts.id')
               ->leftjoin('orders','sales_contracts.po_no','orders.po_no')
               ->leftjoin('quotes','orders.rfq_no','quotes.rfq_no')
               ->leftjoin('users','quotes.user_id','users.id')
               // ->where('orders.po_no',$po_no)->whereNull('quotes.deleted_at')->whereNull('quote_schedules.deleted_at')
              ->where('users.id',$id)->whereNull('quotes.deleted_at')
               ->select('sales_orders.so_no','sales_orders.created_at','delivery_orders.do_no','delivery_orders.do_quantity','delivery_orders.created_at as do_date','users.name','sales_contracts.qty_cont','delivery_orders.id as do_id')
               ->get();

               foreach ($res as $key => $value) {
                  
                  $arra[$key]['do_id'] = $value->do_id;
                  $arra[$key]['so_no'] = $value->so_no;
                  $arra[$key]['do_no'] = $value->do_no;
                  $arra[$key]['do_quantity'] = $value->do_quantity;
                  $arra[$key]['so_date'] = date('d-m-Y',strtotime($value->created_at));
                  $arra[$key]['do_date'] = date('d-m-Y',strtotime($value->do_date));
                  $arra[$key]['qty_cont'] = $value->qty_cont;
                  $arra[$key]['cus_name'] = $value->name;
                  
               }
               
                   // echo "<pre>";print_r($newcount);exit();
             
              return response()->json(['status'=>1,
                'message' =>'success',
                'result' => $arra],
                config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

         
      }

   // ---------------------------------------------------------------------------


     public function get_do_by_cam($id)
      {

          try{ 

            $zone =  Auth::user()->zone;
               
            $res = DB::table('delivery_orders')
              ->leftjoin('sales_orders','delivery_orders.so_no','sales_orders.so_no')
               ->leftjoin('sales_contracts','sales_orders.transact_id','sales_contracts.id')
               ->leftjoin('orders','sales_contracts.po_no','orders.po_no')
               ->leftjoin('quotes','orders.rfq_no','quotes.rfq_no')
               ->leftjoin('users','quotes.user_id','users.id')
               // ->where('orders.po_no',$po_no)->whereNull('quotes.deleted_at')->whereNull('quote_schedules.deleted_at')
              ->where('users.zone',$zone)->whereNull('quotes.deleted_at')
               ->select('sales_orders.so_no','sales_orders.created_at','delivery_orders.do_no','delivery_orders.do_quantity','delivery_orders.created_at as do_date','users.name','sales_contracts.qty_cont','delivery_orders.id as do_id')
               ->get();

               foreach ($res as $key => $value) {
                  
                  $arra[$key]['do_id'] = $value->do_id;
                  $arra[$key]['so_no'] = $value->so_no;
                  $arra[$key]['do_no'] = $value->do_no;
                  $arra[$key]['do_quantity'] = $value->do_quantity;
                  $arra[$key]['so_date'] = date('d-m-Y',strtotime($value->created_at));
                  $arra[$key]['do_date'] = date('d-m-Y',strtotime($value->do_date));
                  $arra[$key]['qty_cont'] = $value->qty_cont;
                  $arra[$key]['cus_name'] = $value->name;
                  
               }
               
                   // echo "<pre>";print_r($newcount);exit();
             
              return response()->json(['status'=>1,
                'message' =>'success',
                'result' => $arra],
                config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

         
      }


    public function get_all_so()
    {
        try{ 
               $arra = array();
               $res = DB::table('sales_orders')->leftjoin('orders','sales_orders.po_no','orders.po_no')->leftjoin('quotes','orders.rfq_no','quotes.rfq_no')
               ->whereNull('quotes.deleted_at')->groupBy('quotes.rfq_no')
               ->select('sales_orders.so_no','sales_orders.co_no','orders.rfq_no','orders.po_no','quotes.user_id as cus_id')
               ->get();
               // echo "<pre>";print_r($res);exit();
               if(!empty($res))
               {
               foreach ($res as $key => $value) {
                  
                 
                  $arra[$key]['so_no'] = $value->so_no;
                  $arra[$key]['co_no'] = $value->co_no;
                  $arra[$key]['rfq_no'] = $value->rfq_no;
                  $arra[$key]['po_no'] = $value->po_no;
                  $arra[$key]['cus_id'] = $value->cus_id;
      
                  
               }
             }
             
              return response()->json(['status'=>1,
                'message' =>'success',
                'result' => $arra],
                config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }
    }
}
