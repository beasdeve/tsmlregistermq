<?php

namespace App\Http\Controllers\Api\Modules\Freight;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Models\Freights;
use JWTAuth;
use Validator;
use DB;


class FreightController extends Controller
{
   	/**
     * This is for store new Freights.
     *
     * @param Http\Controllers\Api\Modules\Freight  $product
     * @return \Illuminate\Http\Response
    */
   public function storeFreights(Request $request)
   {
   		\DB::beginTransaction();

   		try{

   			$validator = Validator::make($request->all(), [
                'pickup_from'        => 'required', 
                'pickup_location'     => 'required',
                'destation_location'     => 'required',  
                'freight_charges' => ['required','regex:/^\d+(((,\d+)?,\d+)?,\d+)?$/'], 
	        ]);

	        if ($validator->fails()) { 
	            return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
	        }

	        $input['pickup_from'] = $request->pickup_from;
    	   	$input['location'] = $request->pickup_location;
          $input['destation_location'] = $request->destation_location;
    	   	$input['freight_charges'] = $request->freight_charges;
    	   	$input['status'] = $request->status;

    	   	// dd($input);

    	   	$freightsData = Freights::create($input);

    	   	\DB::commit();

    	   	if($freightsData)
                {
		            return response()->json(['status'=>1,'message' =>'New freights added successfully','result' => $freightsData],config('global.success_status'));
		        }
		        else{ 
		         	return response()->json(['status'=>1,'message' =>'Somthing went wrong','result' => []],config('global.success_status'));
		        } 
    		 

   		}catch(\Exception $e){ 
    	  	\DB::rollback(); 
           	return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
        }
   	}
 
	/**
     * This is for freights list.
     *
     * @param  Http\Controllers\Api\Modules\Freight  $product
     * @return \Illuminate\Http\Response
    */
	public function getFreights(Request $request)
	{
		\DB::beginTransaction();

      	try{
      		if ($request->pickupfrom && $request->status) {
      			 
      			$freightsData = Freights::orderBy('id','desc')->where('status','!=',3)->where('pickup_from','LIKE',"%{$request->pickupfrom}%")->where('status',$request->status)->get();
      		}
      		elseif($request->pickupfrom)
      		{ 
      	  		$freightsData = Freights::orderBy('id','desc')->where('status','!=',3)->where('pickup_from','LIKE',"%{$request->pickupfrom}%")->get();
      		}

      		elseif ($request->status) {
      			$freightsData = Freights::orderBy('id','desc')->where('status','!=',3)->where('status',$request->status)->get();
      		}
      		else{
      			$freightsData = Freights::orderBy('id','desc')->where('status','!=',3)->get();
      		}


            \DB::commit();

            if(!empty($freightsData))
            {
	            return response()->json(['status'=>1,'message' =>config('global.sucess_msg'),
	            	'result' => $freightsData],config('global.success_status'));
	        }
	        else{ 
	         	 return response()->json(['status'=>1,'message' =>'No data found','result' => []],
	        		config('global.success_status'));
	        }


       	}catch(\Exception $e){ 
        	\DB::rollback(); 
            return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
      	}
	}

	/**
     * This is for freights details.
     *
     * @param  Http\Controllers\Api\Modules\Freight  $product
     * @return \Illuminate\Http\Response
    */
	public function editFreights($id)
	{
		\DB::beginTransaction();

      	try{ 
      	  	$freightsData = Freights::find($id);


                \DB::commit();

                if(!empty($freightsData))
                {
		            return response()->json(['status'=>1,'message' =>config('global.sucess_msg'),
		            	'result' => $freightsData],config('global.success_status'));
		        }
		        else{ 
		         	 return response()->json(['status'=>1,'message' =>'Data not found','result' => []],
		        		config('global.success_status'));
		        }


            }catch(\Exception $e){ 
            	   \DB::rollback(); 
                   return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
          }
	}

	/**
     * This is for update freights details.
     *
     * @param  Http\Controllers\Api\Modules\Freight $freights
     * @return \Illuminate\Http\Response
    */
	public function updateFreights(Request $request)
	{
		\DB::beginTransaction();

   		try{

   			$validator = Validator::make($request->all(), [
   				'freights_id'        => 'required|numeric', 
                'pickup_from'        => 'required', 
                'pickup_location'     => 'required',
                'destation_location'     => 'required',  
                'freight_charges' => ['required','regex:/^\d+(((,\d+)?,\d+)?,\d+)?$/'], 
	        ]);

	        if ($validator->fails()) { 
	            return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
	        }

	        $update['pickup_from'] = $request->pickup_from;
    	   	$update['location'] = $request->pickup_location;
          $update['destation_location'] = $request->destation_location;
    	   	$update['freight_charges'] = $request->freight_charges;
    	   	$update['status'] = $request->status;

    	   	// dd($input);

    	   	$freightsData = Freights::where('id',$request->freights_id)->update($update);

    	   	\DB::commit();

    	   	if($freightsData)
                {
		            return response()->json(['status'=>1,'message' =>'Freights data updated successfully','result' => $freightsData],config('global.success_status'));
		        }
		        else{ 
		         	return response()->json(['status'=>1,'message' =>'Somthing went wrong','result' => []],config('global.success_status'));
		        } 
    		 

   		}catch(\Exception $e){ 
    	  	\DB::rollback(); 
           	return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
        }
	}

	/**
     * This is for active freights .
     *
     * @param  Http\Controllers\Api\Modules\Freight $freights
     * @return \Illuminate\Http\Response
    */
	public function activeFreights($id)
	{

		\DB::beginTransaction();

  	  	try{ 

  	  		$getFreight = Freights::where('id',$id)->first();  

	        if(!empty($getFreight))
	        { 
	    		$input['status'] = 1; //2=> Inactive/1=>Active. 

	        	$updateFreight = Freights::where('id',$getFreight->id)->update($input);

	 			\DB::commit();

	        	return response()->json(['status'=>1,'message' =>'Freight status active successfully.']); 
	        	 
	        }
	        else
	        {
	        	\DB::commit();

	        	return response()->json(['status'=>0,'message' =>'No data found'],config('global.success_status')); 
	            // return response()->json(['status'=>0,'message'=>'No data found'],200);
	        } 
	         


        }catch(\Exception $e){

        	   \DB::rollback();

               return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
      	}
	}


	/**
     * This is for inactive freights.
     *
     * @param  Http\Controllers\Api\Modules\Freight $freights
     * @return \Illuminate\Http\Response
    */
	public function inactiveFreights($id)
	{

		\DB::beginTransaction();

  	  	try{ 

  	  		$getFreight = Freights::where('id',$id)->first();  

	        if(!empty($getFreight))
	        { 
	    		$input['status'] = 2; //2=> Inactive/1=>Active. 

	        	$updateFreight = Freights::where('id',$getFreight->id)->update($input);

	 			\DB::commit();

	        	return response()->json(['status'=>1,'message' =>'Freight status inactive successfully.']); 
	        	 
	        }
	        else
	        {
	        	\DB::commit();

	        	return response()->json(['status'=>0,'message' =>'No data found'],config('global.success_status')); 
	            // return response()->json(['status'=>0,'message'=>'No data found'],200);
	        } 
	         


        }catch(\Exception $e){

        	   \DB::rollback();

               return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
      	}
	}

	/**
     * This is for delete inactive.
     *
     * @param  Http\Controllers\Api\Modules\Freight $freights
     * @return \Illuminate\Http\Response
    */
	public function deleteFreights($id)
	{

		\DB::beginTransaction();

  	  	try{ 

  	  		$getFreight = Freights::where('id',$id)->first();  

	        if(!empty($getFreight))
	        { 
	    		$input['status'] = 3; //2=> Inactive/1=>Active. 

	        	$updateFreight = Freights::where('id',$getFreight->id)->update($input);

	 			\DB::commit();

	        	return response()->json(['status'=>1,'message' =>'Freight deleted successfully.']); 
	        	 
	        }
	        else
	        {
	        	\DB::commit();

	        	return response()->json(['status'=>0,'message' =>'No data found'],config('global.success_status')); 
	            // return response()->json(['status'=>0,'message'=>'No data found'],200);
	        } 
	         


        }catch(\Exception $e){

        	   \DB::rollback();

               return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
      	}
	}
}
