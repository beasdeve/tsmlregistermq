<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Address;

class RegisterCustomerExcelUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sheetData = $this->data;
        print_r('register');
         foreach($sheetData as $val)
            {
                if(!empty($val[18])  && !empty($val[15]))
                {

                $check_user = User::where('email',$val[18])->first();
                $check_codet = Address::where('cus_code',$val[1])->first();
                // dd($check_user->user_code);

                if(empty($check_user))
                {
                     $password = 'Test@12345';


                $check_email = User::where('name',strtoupper(trim($val[3])))->first();
                // return $check_email;exit();
                if ($check_email=='') {
                $user = new User;
                $user->user_code = trim($val[1]);
                $user->name = strtoupper(trim($val[3]));
                $user->email = $val[18];
                $user->phone = $val[15];
                $user->gstin = $val[126];
                $user->org_pan = $val[203];
                $user->password = $password;
                $user->org_name = strtoupper(trim($val[5]));
                $user->org_address = $val[6];
                $user->user_type = 'C';
                $user->login_attempt = 2;
                $user->company_pan = $val[200];
                $user->addressone = $val[6];
                $user->zone = $val[10];

                $user->save();
                }

                $check_gst_arr = array();
                $check_gst = Address::where('cus_code',$val[1])->get();
                $user_details = User::where('name',strtoupper(trim($val[3])))->first();
                // return $user->id;exit();
                foreach ($check_gst as $key => $value) {
                    array_push($check_gst_arr, $value->id);
                }
                // dd(count($check_gst_arr));
                if (count($check_gst_arr) < 2 && !empty($user_details)) {

                    // dd($val[123]);
                // billing-address
             // if($user->user_code == $val[1])
             // {
                    $valb = strtolower($val[55]);

                    $billing = new Address;
                    $billing->user_id = $user_details->id;
                    $billing->addressone = $val[6];
                    $billing->addresstwo = $val[14];
                    $billing->city = ucwords($valb);
                    $billing->state = $val[9];
                    $billing->pincode = $val[8];
                    $billing->cityc = $val[54];
                    $billing->type = 'B';
                    $billing->company_name = strtoupper(trim($val[5]));
                    $billing->gstin =  $val[203];
                    $billing->cus_code =  $val[1];
                    $billing->country =  $val[2];
                    $billing->cust_group_name = strtoupper(trim($val[3]));
                    $billing->save();
                // }

                // if($user->user_code != $val[1])
                // {
                // shipping-address
                // $vals = strtolower($val[55]);
                $shipping = new Address;
                $shipping->user_id = $user_details->id;
                $shipping->addressone = $val[6];
                $shipping->addresstwo = $val[14];
                $shipping->cityc = $val[54];
                $shipping->city = ucwords($valb);
                $shipping->state = $val[9];
                $shipping->pincode = $val[8];
                $shipping->type = 'A';
                $shipping->company_name = strtoupper(trim($val[5]));
                $shipping->gstin =  $val[203];
                $shipping->cus_code =  $val[1];
                $shipping->country =  $val[2];
                $shipping->cust_group_name = strtoupper(trim($val[3]));
                $shipping->save();
              // }
            }
              // else{
              //       $response['success'] = false;
              //       $response['message'] = 'Same Excel File already uploaded';
              //       return Response::json($response);
              // }
              }

              else if(!empty($check_user) && empty($check_codet))
              {
                 // dd('addr');


                    $check_gst_arr = array();
                    $check_gst = Address::where('cus_code',$val[1])->get();
                    $user_details = User::where('name',strtoupper(trim($val[3])))->first();
                // return $user->id;exit();
                    foreach ($check_gst as $key => $value) {
                        array_push($check_gst_arr, $value->id);
                    }
                // dd(count($check_gst_arr));
                  if (count($check_gst_arr) < 2 && !empty($user_details)) {

                            // dd($val[123]);
                        // billing-address
                     // if($user->user_code == $val[1])
                     // {
                            $valb = strtolower($val[55]);

                            $billing = new Address;
                            $billing->user_id = $user_details->id;
                            $billing->addressone = $val[6];
                            $billing->addresstwo = $val[14];
                            $billing->city = ucwords($valb);
                            $billing->state = $val[9];
                            $billing->pincode = $val[8];
                            $billing->cityc = $val[54];
                            $billing->type = 'B';
                            $billing->company_name = strtoupper(trim($val[5]));
                            $billing->gstin =  $val[203];
                            $billing->cus_code =  $val[1];
                            $billing->country =  $val[2];
                            $billing->cust_group_name = strtoupper(trim($val[3]));
                            $billing->save();
                        // }

                        // if($user->user_code != $val[1])
                        // {
                        // shipping-address
                        // $vals = strtolower($val[55]);
                        $shipping = new Address;
                        $shipping->user_id = $user_details->id;
                        $shipping->addressone = $val[6];
                        $shipping->addresstwo = $val[14];
                        $shipping->cityc = $val[54];
                        $shipping->city = ucwords($valb);
                        $shipping->state = $val[9];
                        $shipping->pincode = $val[8];
                        $shipping->type = 'A';
                        $shipping->company_name = strtoupper(trim($val[5]));
                        $shipping->gstin =  $val[203];
                        $shipping->cus_code =  $val[1];
                        $shipping->country =  $val[2];
                        $shipping->cust_group_name = strtoupper(trim($val[3]));
                        $shipping->save();
                      // }
                    }
              }


              else{


                  $userUp['org_name'] = strtoupper(trim($val[5]));
                  $userUp['org_pan'] = $val[203];
                  $userUp['gstin'] = $val[126];
                  $userUp['phone'] = $val[15];

                  User::where('id',$check_user->id)->update($userUp);

                  $valb = strtolower($val[55]);

                $asddUp['addressone'] = $val[6];
                $asddUp['addresstwo'] = $val[14];
                $asddUp['cityc'] = $val[54];
                $asddUp['city'] = ucwords($valb);
                $asddUp['state'] = $val[9];
                $asddUp['pincode'] = $val[8];
                $asddUp['company_name'] = strtoupper(trim($val[5]));
                $asddUp['gstin'] =  $val[203];
                $asddUp['cus_code'] =  $val[1];
                $asddUp['country'] =  $val[2];
                $add = Address::where('cus_code',$val[1])->where('user_id',$check_user->id)->update($asddUp);

                  // dd($add);

              }
            }

            }
    }
}
