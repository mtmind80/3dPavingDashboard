<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Requests\NoteRequest;
use App\Http\Requests\SearchRequest;
use App\Models\ContactType;
use App\Models\LeadSource;
use App\Models\Location;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Models\Contact;

class AjaxController extends Controller
{

    public $modelarray = [
        'proposal_details',
        'proposal_detail_vehicles',
        'proposal_detail_subcontractors',
        'proposal_detail_striping_services',
        'proposal_detail_labor',
        'proposal_detail_equipment',
        'proposal_detail_additional_costs',
        ];
    public function __construct(Request $request)
    {
        parent::__construct();

    }


    /*
    public function ajaxEstimator(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {

            $validator = \Validator::make(
                $request->only(['proposal_id', 'proposal_detail_id', 'model', 'method']),
                [
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id'  => 'required|positive',
                    'model'      => 'required|plainText',
                    'method'  => 'required|plainText',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => "0",
                ];
            } else {
                $model = $request['model'];
                $proposal_id = $request['proposal_id'];
                $proposal_detail_id = $request['proposal_detail_id'];
                $method = $request['method'];
                //check model is in array
                if(!in_Array($model,$this->modelarray))
                {
                    $response = [
                        'success' => false,
                        'message' => "0",
                    ];
                }

                if($method == "ADD"){
                    // add item
                } else {
                    //remove item
                }
                $number =0;
                //get all items
                $results = $model::all()->get();

                if (!$results) {
                    $response = [
                        'success'  => true,
                        'contacts' => 0,
                    ];
                } else {
                    $html = '<table>';
                    foreach ($results as $data) {


                            $resultData .= '<td>'.$contact->id.'"';
                            $resultData .= 'data-contact_type_id="'.$contact->contact_type_id.'"';
                            $resultData .= 'data-first_name="'.$contact->first_name.'"';
                            $resultData .= 'data-last_name="'.$contact->last_name.'"';
                            $resultData .= 'data-email="'.$contact->email.'"';
                            $resultData .= 'data-phone="'.$contact->phone.'"';
                            $resultData .= 'data-address1="'.$contact->address1.'"';
                            $resultData .= 'data-address2="'.$contact->address2.'"';
                            $resultData .= 'data-city="'.$contact->city.'"';
                            $resultData .= 'data-state="'.$contact->state.'"';
                            $resultData .= 'data-zipcode="'.$contact->zipcode.'"';
                            $resultData .= 'data-county="'.$contact->county.'"';
                        }

                        $html .= '<p class="mt0 mb5 fs13 contact-container">' . ($number + 1) . '- <a href="' . $link . '" class="a-link" '.$resultData.'><b>' . $contact->full_name . '</b></a><br>';
                        $html .= '<span class="pl17">' . $contact->full_address_one_line . '</span>';
                        $html .= '</span></p>';
                    }

                    $response = [
                        'success'  => true,
                        'contacts' => $contacts->count(),
                        'html'     => $html,
                    ];
                }
            }

            return response()->json($response, 200);
        } else {
            return response()->json(['error' => 'Invalid request.'], 500);
        }
    }
    */


}
