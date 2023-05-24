<?php

namespace App\Http\Controllers\Api\Modules\Sap\SalesOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Models\SalesContarct;
use App\Models\Models\SalesContractMaterial;
use App\Models\Models\SalesContarctSpecs;
use App\Models\Models\ScPriceDetail;
use App\Models\Models\SalesOrder;
use App\Models\Models\ScPermissible;
use App\Models\Models\ScmaterialDescription;
use DB;

class SapSummaryController extends Controller
{
    public function getPoSummary($po_no)
    {
    	\DB::beginTransaction();

        try{ 

          $res = DB::table('orders')
                      ->leftjoin('sales_contracts','orders.po_no','sales_contracts.po_no')
                      ->leftjoin('sales_orders','orders.po_no','sales_orders.po_no')
                      ->leftjoin('delivery_orders','sales_orders.so_no','delivery_orders.so_no')
                      ->select('sales_contracts.sc_no','sales_orders.so_no','orders.po_no','orders.cus_po_no','sales_contracts.qty_cont',DB::raw("SUM(delivery_orders.do_quantity) as do_sum"))
                      ->where('orders.po_no',$po_no)
                      ->first();

                $arr['sc_no'] = $res->sc_no;
                $arr['so_no'] = $res->so_no;
                $arr['po_no'] = $res->po_no;
                $arr['po_qty'] = $res->qty_cont;
                $arr['cus_po_no'] = $res->cus_po_no;
                $arr['do_sum'] = $res->do_sum;
                $arr['balance'] = $res->qty_cont - $res->do_sum;
                $arr['do_no'] = $this->getDoNoByPo($res->so_no);


		         \DB::commit();


		         return response()->json(['status'=>1,
		          'message' =>config('global.sucess_msg'),
		          'result' => $arr]);
      
		     }catch(\Exception $e){

		       \DB::rollback();

		       return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
		     }
    }


    public function getDoNoByPo($so_no)
    {
    	 $data = array();
    	$res = DB::table('delivery_orders')->where('so_no',$so_no)->get();
         
        if(!empty($res))
        {
	    	foreach ($res as $key => $value) {
	    		 
	    		 $data[$key]['do_no'] = $value->do_no;
	    		 $data[$key]['do_quantity'] = $value->do_quantity;
	    	}
        }else{

        	$data = [];
        }

    	return $data;
    }
}
