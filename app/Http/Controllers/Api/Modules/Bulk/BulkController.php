<?php

namespace App\Http\Controllers\Api\Modules\Bulk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use App\Models\Address;
use App\Models\ProductSubCategory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Response;
use Hash;
class BulkController extends Controller
{
    public function storeUser(Request $request)
    {
        $response = [];
        try{
         if ($request->hasFile('excel'))
         {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($request->excel);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            // return $sheetData;
            $removed = array_shift($sheetData);
            $data = json_encode($sheetData);

            

            foreach($sheetData as $val)
            {

                $check_email = User::where('email',$val[14])->first();

                if ($check_email=='') {
                $user = new User;
                $user->cus_code = $val[0]; 
                $user->country = $val[1];
                $user->cust_group_name = $val[2];
                $user->org_name = $val[4];
                $user->org_address = $val[5];
                $user->email = $val[14];
                $user->gstin = $val[31];
                $user->phone = $val[25];
                $user->company_gst = $val[31];
                $user->login_attempt = 1;
                $user->save();
                }

                $check_gst = Address::where('cus_code',$val[0])->first();
                $user_details = User::where('email',$val[14])->first();
                
                if ($check_gst=="") {
                
                // billing-address
                $billing = new Address;
                $billing->user_id = $user_details->id;
                $billing->addressone = $val[5];
                $billing->city = $val[6];
                $billing->state = $val[8];
                $billing->pincode = $val[7];
                $billing->type = 'B';
                $billing->company_name = $val[4];
                $billing->gstin =  $val[31];
                $billing->cus_code =  $val[0];
                $billing->country =  $val[1];
                $billing->cust_group_name = $val[2];

                $billing->cam =  $val[10];
                $billing->cam_email =  $val[11];
                $billing->zone = $val[9];
                $billing->region = $val[8];
                $billing->tel_two = $val[13];
                $billing->erdat = $val[17];
                $billing->ernam = $val[18];
                $billing->order_type = $val[19];
                $billing->lifnr = $val[20];
                $billing->cityc = $val[23];
                $billing->sprac = $val[24];
                $billing->lzone = $val[26];
                $billing->vbund = $val[27];
                $billing->gform = $val[28];
                $billing->duefl = $val[29];
                $billing->kdkg2 = $val[30];
                $billing->knurl = $val[32];
                $billing->j_1 = $val[33];
                $billing->aedat = $val[34];
                $billing->usnam = $val[35];
                $billing->save();


                // shippingshipping-address
                $shipping = new Address;
                $shipping->user_id = $user_details->id;
                $shipping->addressone = $val[12].','.$val[15];
                $shipping->city = $val[16];
                $shipping->state = $val[8];
                $shipping->pincode = $val[7];
                $shipping->type = 'A';
                $shipping->company_name = $val[4];
                $shipping->gstin =  $val[31];
                $shipping->cus_code =  $val[0];
                $shipping->country =  $val[1];
                $shipping->cust_group_name = $val[2];

                $shipping->cam =  $val[10];
                $shipping->cam_email =  $val[11];
                $shipping->zone = $val[9];
                $shipping->region = $val[8];
                $shipping->tel_two = $val[13];
                $shipping->erdat = $val[17];
                $shipping->ernam = $val[18];
                $shipping->order_type = $val[19];
                $shipping->lifnr = $val[20];
                $shipping->cityc = $val[23];
                $shipping->sprac = $val[24];
                $shipping->lzone = $val[26];
                $shipping->vbund = $val[27];
                $shipping->gform = $val[28];
                $shipping->duefl = $val[29];
                $shipping->kdkg2 = $val[30];
                $shipping->knurl = $val[32];
                $shipping->j_1 = $val[33];
                $shipping->aedat = $val[34];
                $shipping->usnam = $val[35];
                $shipping->save();
            }



















                

                
            }

            $response['success'] = true;
            $response['message'] = 'User Uploaded Successfully';
            return Response::json($response);

         }else{
            $response['success'] = false;
            $response['message'] = 'No Excel File Found';
            return Response::json($response);
         }   
         

         
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }



    public function storeProduct(Request $request)
    {
        $response = [];
        try{
         if ($request->hasFile('excel'))
         {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            $spreadsheet = $reader->load($request->excel);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            // return $sheetData;
            $removed = array_shift($sheetData);
            $removed_2 = array_shift($removed);
            $data = json_encode($sheetData);
           
            
            foreach($sheetData as $key => $value)
            {

                if ($key!=0 && $value[5]!="") {

                    // return $value;
                    // category-division//////////////////////
                    if (@$value[4]=='HC Ferochrome Lumps') {
                        $cat_id = 1;
                    }else{
                        $cat_id = 2;
                    }

                    // code-explode//////////////////////////
                    $explode = explode('-', @$value[10]);
                    // return $explode[1];

                    // check if the code explode code exits or not
                    $check = ProductSubCategory::where('code',$explode[1])->where('sub_cat_name',$value[6])->where('plant_code',$value[0])->first();
                    // return $check;

                    
                    
                    if ($check=="") {
                        // return $value[5];
                        $sub = new ProductSubCategory;
                        $sub->plant_code = @$value[0];
                        $sub->cat_id = $cat_id;
                        $sub->pro_id = 1;
                        $sub->sub_cat_name = $value[6];
                        $sub->slug = str_slug($value[5]);
                        $sub->sub_cat_dese = @$value[10];
                        $sub->pro_size = @$value[7];
                        $sub->mat_no = @$value[9];
                        $sub->Cr = @$value[11].'-'.@$value[12];
                        $sub->C =   @$value[15].'-'.@$value[16];
                        $sub->Phos =   @$value[17].'-'.@$value[18];
                        $sub->S =   @$value[19].'-'.@$value[20];
                        $sub->Ti =   @$value[21].'-'.@$value[22];
                        $sub->Si =   @$value[13].'-'.@$value[14];
                        $sub->code = $explode[1];
                        $sub->save();

                        \DB::table('product_size_mat_no')->insert([
                            'plant_id'=>1,
                            'sub_cat_id'=>$sub->id,
                            'product_size'=>@$value[7],
                            'mat_no'=>@$value[9],
                            'plant_type'=>@$value[0],
                        ]);



                    }else{

                       if (!str_contains($check->pro_size,@$value[7])){ 
                         $des_concat = $check->sub_cat_dese.','.@$value[10];
                         $pro_concat = $check->pro_size.','.@$value[7];
                         $mat_concat = $check->mat_no.','.@$value[9];
                         $upd = [];
                         $upd['sub_cat_dese'] = $des_concat;
                         $upd['pro_size'] = $pro_concat;
                         $upd['mat_no'] = $mat_concat;
                         ProductSubCategory::where('id',$check->id)->update($upd);

                         \DB::table('product_size_mat_no')->insert([
                            'plant_id'=>1,
                            'sub_cat_id'=>$check->id,
                            'product_size'=>@$value[7],
                            'mat_no'=>@$value[9],
                            'plant_type'=>@$value[0],
                        ]);
                    }
                    
                  }

                }
            }
            $response['success'] = true;
            $response['message'] = 'Product Uploaded Successfully';
            return Response::json($response);

         }else{
            $response['success'] = false;
            $response['message'] = 'No Excel File Found';
            return Response::json($response);
         }


        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }    
    }
}

