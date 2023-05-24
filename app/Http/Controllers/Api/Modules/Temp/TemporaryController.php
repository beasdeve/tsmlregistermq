<?php

namespace App\Http\Controllers\Api\Modules\Temp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportDocs;
use DB;

class TemporaryController extends Controller
{
    public function scExcelSubmit(Request $request)
    {
    	 // echo "<pre>";print_r($request->all());exit();
          $ids = array();
         foreach ($request->all() as $k => $vals) {
           
        
          
    	 foreach ($vals as $key => $value) {
    	 	
    	 	  
    	 	$data['ContractType'] = $vals['OrganizationalData']['ContractType'];
    	 	$data['SalesOrganization'] = $vals['OrganizationalData']['SalesOrganization'];
    	 	$data['DistributionChannel'] = $vals['OrganizationalData']['DistributionChannel'];
    	 	$data['Division'] = $vals['OrganizationalData']['Division'];
    	 	$data['ContractValidFrom'] = $vals['OrganizationalData']['ContractValidFrom'];
    	 	$data['ContractValidTo'] = $vals['OrganizationalData']['ContractValidTo'];
    	 	$data['Salesoffice'] = $vals['OrganizationalData']['Salesoffice'];
    	 	$data['Salesgroup'] = $vals['OrganizationalData']['Salesgroup'];
    	 	$data['Incoterms'] = $vals['OrganizationalData']['Incoterms'];
    	 	$data['Paymentterms'] = $vals['OrganizationalData']['Paymentterms'];


    	 	$data['QtyContractTSML'] = $vals['SoldToParty']['QtyContractTSML'];
    	 	$data['Sold_To_Party'] = $vals['SoldToParty']['Sold_To_Party'];
    	 	$data['Ship_To_Party'] = $vals['SoldToParty']['Ship_To_Party'];
    	 	$data['Cust_Referance'] = $vals['SoldToParty']['Cust_Referance'];
    	 	$data['NetValue'] = $vals['SoldToParty']['NetValue'];
    	 	$data['Cust_Ref_Date'] = $vals['SoldToParty']['Cust_Ref_Date'];


    	 	$data['Shp_Cond'] = $vals['Sales']['Shp_Cond'];

            // echo "<pre>";print_r($vals['Items'][$k]['item']);exit();
    	 	$data['item'] = $vals['Items'][0]['item'];
    	 	$data['Material'] = $vals['Items'][0]['Material'];
    	 	$data['Quantity'] = $vals['Items'][0]['Quantity'];
    	 	$data['CustomarMaterialNumber'] = $vals['Items'][0]['CustomarMaterialNumber'];
    	 	$data['OrderQuantity'] = $vals['Items'][0]['OrderQuantity'];
    	 	$data['Plant'] = $vals['Items'][0]['Plant'];


    	 	$data['ItemNumber'] = $vals['Conditions'][0]['ItemNumber'];
    	 	$data['CnTy'] = $vals['Conditions'][0]['CnTy'];
    	 	$data['Amount'] = $vals['Conditions'][0]['Amount'];



            $data['Freight'] = $vals['AdditionalDataA']['Freight'];
    	 	$data['CustomerGroup4'] = $vals['AdditionalDataA']['CustomerGroup4'];
    	 	$data['FreightIndicator'] = $vals['AdditionalDataforPricing']['FreightIndicator'];

    	 	$data['date'] = date('Y-m-d');

            
            

    	 }

         $res = DB::table('sc_excel_datas')->insertGetId($data);

            array_push($ids, $res);
        }

    	 // echo "<pre>";print_r($data);exit();

            $file = time().'sc.xlsx';
    	 	// $this->scExceldown($ids,$file);
            $excel['ids'] = $ids;
            $excel['file'] = $file;
    	 	return response()->json(['status'=>1, 'message' =>'success','result' => $excel],config('global.success_status'));
    	 
    }


    public function scExceldown(Request $request)
    {  
    	$ids = [1,2];
        $file = time().'sc.xlsx';
        // $ids = $request->ids;
        // $file = $request->file;
    	// dd($ids);

    	return Excel::download(new ExportDocs($ids), $file);
    }
}
