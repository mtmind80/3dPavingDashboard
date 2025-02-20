<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Requests\NoteRequest;
use App\Http\Requests\SearchRequest;
use App\Models\ContactType;
use App\Models\County;
use App\Models\LeadSource;
use App\Models\Location;
use App\Models\Staff;
use App\Models\Proposal;
use Exception;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactsController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct();

    }


    public function index(Request $request)
    {

        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 10;

        $contacts = Contact::search($needle)->sortable()->with(['contactType', 'company'])->paginate($perPage);

        $data = [
            'contacts' => $contacts,
            'needle'   => $needle,
        ];

        return view('contacts.index', $data);
    }

    public function changeclient(Request $request, $id)  //proposal id
    {


        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 10;


        $proposal = Proposal::where('id', '=', $id)->first();
        $contacts = Contact::search($needle)->sortable()->with(['contactType', 'company'])->paginate($perPage);
        $data = [
            'id' => $id,
            'proposal' => $proposal,
            'contacts' => $contacts,
            'needle'   => $needle,
        ];

        return view('contacts.index_change_client', $data);
    }

    public function search(SearchRequest $request)
    {
        return $this->index($request);
    }


    public function ajaxCheckIfContactExists2(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {

            $validator = \Validator::make(
                $request->only(['first_name', 'last_name', 'email', 'fill_form']),
                [
                    'first_name' => 'required|personName',
                    'last_name'  => 'nullable|personName',
                    'email'      => 'required|email',
                    'fill_form'  => 'nullable|boolean',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                $proposal_id = $request->proposal_id;
                $query = Contact::where('email', $request->email);

                if (!empty($request->last_name)) {
                    $query->where(function ($q) use ($request){
                        $q->orWhere('first_name', 'LIKE', '%' . $request->last_name . '%')->orWhere('last_name', 'LIKE', '%' . $request->last_name . '%');
                    });
                } else {
                    $query->where('first_name', 'LIKE', '%' . $request->last_name . '%');
                }

                if (!$contacts = $query->get()) {
                    $response = [
                        'success'  => true,
                        'contacts' => 0,
                    ];
                } else {
                    $html = '<h4 class="fs16">' . __('translation.existing_contacts') . ' (' . $contacts->count() . ')</h4>';
                    foreach ($contacts as $number => $contact) {
                        $contactData = '';
                        if (! $request->fill_form) {
                            $tooltipCaption = 'Go to Contact Details';
                            $link = route('selectclient', ['contact_id' => $contact->id, 'proposal_id'=>$proposal_id]);
                        } else {
                            $tooltipCaption = 'Fill contact info fields';
                            $link = 'javascript:';

                            $contactData .= 'data-contact_id="'.$contact->id.'"';
                            $contactData .= 'data-contact_type_id="'.$contact->contact_type_id.'"';
                            $contactData .= 'data-first_name="'.$contact->first_name.'"';
                            $contactData .= 'data-last_name="'.$contact->last_name.'"';
                            $contactData .= 'data-email="'.$contact->email.'"';
                            $contactData .= 'data-phone="'.$contact->phone.'"';
                            $contactData .= 'data-address1="'.$contact->address1.'"';
                            $contactData .= 'data-address2="'.$contact->address2.'"';
                            $contactData .= 'data-city="'.$contact->city.'"';
                            $contactData .= 'data-state="'.$contact->state.'"';
                            $contactData .= 'data-zipcode="'.$contact->zipcode.'"';
                            $contactData .= 'data-county="'.$contact->county.'"';
                        }

                        $html .= '<p class="mt0 mb5 fs13 contact-container">' . ($number + 1) . '- <a href="' . $link . '" class="a-link" '.$contactData.'><b>' . $contact->full_name . '</b></a><br>';
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

    public function ajaxCheckIfContactExists(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {

            $validator = \Validator::make(
                $request->only(['first_name', 'last_name', 'email', 'fill_form']),
                [
                    'first_name' => 'required|personName',
                    'last_name'  => 'nullable|personName',
                    'email'      => 'required|email',
                    'fill_form'  => 'nullable|boolean',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                $query = Contact::where('email', $request->email);

                if (!empty($request->last_name)) {
                    $query->where(function ($q) use ($request){
                        $q->orWhere('first_name', 'LIKE', '%' . $request->last_name . '%')->orWhere('last_name', 'LIKE', '%' . $request->last_name . '%');
                    });
                } else {
                    $query->where('first_name', 'LIKE', '%' . $request->last_name . '%');
                }

                if (!$contacts = $query->get()) {
                    $response = [
                        'success'  => true,
                        'contacts' => 0,
                    ];
                } else {
                    $html = '<h4 class="fs16">' . __('translation.existing_contacts') . ' (' . $contacts->count() . ')</h4>';
                    foreach ($contacts as $number => $contact) {
                        $contactData = '';
                        if (! $request->fill_form) {
                            $tooltipCaption = 'Go to Contact Details';
                            $link = route('contact_details', ['contact' => $contact->id]);
                        } else {
                            $tooltipCaption = 'Fill contact info fields';
                            $link = 'javascript:';

                            $contactData .= 'data-contact_id="'.$contact->id.'"';
                            $contactData .= 'data-contact_type_id="'.$contact->contact_type_id.'"';
                            $contactData .= 'data-first_name="'.$contact->first_name.'"';
                            $contactData .= 'data-last_name="'.$contact->last_name.'"';
                            $contactData .= 'data-email="'.$contact->email.'"';
                            $contactData .= 'data-phone="'.$contact->phone.'"';
                            $contactData .= 'data-address1="'.$contact->address1.'"';
                            $contactData .= 'data-address2="'.$contact->address2.'"';
                            $contactData .= 'data-city="'.$contact->city.'"';
                            $contactData .= 'data-state="'.$contact->state.'"';
                            $contactData .= 'data-zipcode="'.$contact->zipcode.'"';
                            $contactData .= 'data-county="'.$contact->county.'"';
                        }

                        $html .= '<p class="mt0 mb5 fs13 contact-container">' . ($number + 1) . '- <a href="' . $link . '" class="a-link" '.$contactData.'><b>' . $contact->full_name . '</b></a><br>';
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

    public function create()
    {

        $data = [
            'types' => ContactType::typesActive(),
            'sourcesCB'       => LeadSource::sourcesCB(['0' => 'Select source']),
            'assignedToCB'    => Contact::assignedToCB(['0' => 'Select assigned to']),
            'countiesCB'      => County::countiesCB(['' => 'Select county']),
            'contact_type_id' => null,
            'lead_source'     => null,
            'assigned_to'     => null,
            'county'          => null,
        ];

        return view('contacts.create', $data);
    }


    public function createforproposal($proposal_id)
    {
        $data = [
            'typesCB'         => ContactType::typesCBActive(['0' => 'Select type']),
            'sourcesCB'       => LeadSource::sourcesCB(['0' => 'Select source']),
            'assignedToCB'    => Contact::assignedToCB(['0' => 'Select assigned to']),
            'countiesCB'      => County::countiesCB(['' => 'Select county']),
            'contact_type_id' => null,
            'proposal_id'     => $proposal_id,
            'lead_source'     => null,
            'assigned_to'     => null,
            'county'          => null,
        ];

        return view('contacts.createforproposal', $data);
    }



    public function storeforproposal(ContactRequest $request)
    {
        $contact = new Contact();
        $contact->fill($request->only([
            'contact_type_id',
            'lead_id',
            'first_name',
            'last_name',
            'email',
            'alt_email',
            'phone',
            'alt_phone',
            'address1',
            'address2',
            'city',
            'county',
            'state',
            'postal_code',
            'billing_address1',
            'billing_address2',
            'billing_city',
            'billing_state',
            'billing_postal_code',
            'contact',
            'note',
        ]));
        try {
            $contact->save();
            $data['id']= $contact->id;

        } catch (Exception $e) {
            \Session::flash('message', 'Sorry your entry was not recorded!');
            return back()->withInput();

        }

        //update proposal
        $proposal = Proposal::find($request['proposal_id']);
        $proposal->contact_id = $data['id'];
        $proposal->update();

        \Session::flash('message', 'New Contact Created!');

        return redirect()->route('show_proposal',['id' => $request['proposal_id']])->with('info', 'Proposal cloned with New Client.');

        //return redirect()->route('contact_details', ['contact'=>$contact->id]);


    }


    public function store(ContactRequest $request)
    {
        $contact = new Contact();
        $contact->fill($request->only([
            'contact_type_id',
            'lead_id',
            'first_name',
            'last_name',
            'email',
            'alt_email',
            'phone',
            'alt_phone',
            'address1',
            'address2',
            'city',
            'county',
            'state',
            'postal_code',
            'billing_address1',
            'billing_address2',
            'billing_city',
            'billing_state',
            'billing_postal_code',
            'contact',
            'note',
         ]));

        try {
            $contact->save();
            $data['id']= $contact->id;

        } catch (Exception $e) {
            \Session::flash('message', 'Sorry your entry was not recorded!');
            return back()->withInput();

        }

        \Session::flash('message', 'New Contact Created!');
        return redirect()->route('contact_details', ['contact'=>$contact->id]);


    }

    public function details($contactId)
    {
        if (! $contact = Contact::with(['contactType'])->find($contactId)){
            return view('pages-404');
        }

        $data = [
            'contact' => $contact,
            'tabSelected' => 'proposals',
        ];

        return view('contacts.detailsfull', $data);
    }

    public function edit($contactId)
    {
        if (! $contact = Contact::with(['contactType'])->find($contactId)){
            return view('pages-404');
        }

        $data = [
            'contact' => $contact,
            'types' => ContactType::typesActive(),
            'sourcesCB' => LeadSource::sourcesCB(['0' => 'Select source']),
            'assignedToCB' => Contact::assignedToCB(['0' => 'Select assigned to']),
            'countiesCB' => County::countiesCB(),
        ];

        return view('contacts.edit', $data);
    }

    public function update(Contact $contact, ContactRequest $request)
    {
        $inputs = $request->only([
            'contact_type_id',
            'lead_id',
            'first_name',
            'last_name',
            'email',
            'alt_email',
            'phone',
            'alt_phone',
            'address1',
            'address2',
            'city',
            'county',
            'state',
            'postal_code',
            'billing_address1',
            'billing_address2',
            'billing_city',
            'billing_state',
            'billing_postal_code',
            'contact',
            'note',
        ]);

        if (empty($request['is_lead'])) {
            //$inputs['is_lead'] = false;
            $inputs['lead_source'] = null;
            $inputs['assigned_to'] = null;
        }

        $contact->update($inputs);

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Contact updated.');
        } else {
            return redirect()->back()->with('success', 'Contact updated.');
        }
    }

    public function updateNote(Contact $contact, NoteRequest $request)
    {
        $contact->update(['note' => $request->note]);

        return redirect()->back()->with('success', 'Note added.');
    }

    public function destroy(Request $request)
    {
        if (!$contact = Contact::find($request->item_id)) {
            return redirect()->back()->with('error', 'Contact not found.');
        }

        try {
            $fullName = $contact->full_name;
            $contact->delete();
        } catch (\Exception $e) {
            if (env('APP_ENV') == 'local') {
                return redirect()->back()->with('error', $e->getMessage());
            } else {
                \Log::error(get_class() . ' - ' . $e->getMessage());
                return redirect()->back()->with('error', 'Exception error');
            }
        }

        return redirect()->route('contact_list', !empty($request->http_query) ? explode('&', $request->http_query) : [])->with('success', 'Contact <b>' . $contact->full_name . '</b> deleted.');
    }

    public function restore(Contact $contact)
    {
        $contact->restore();

        return redirect()->back()->with('success', 'Contact <b>' . $contact->full_name . '</b> restored.');
    }

    public function detachFromCompany(Request $request)
    {
        $validator = \Validator::make(
            $request->only(['contact_id']), [
                'contact_id' => 'required|positive',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages()->first());
        }

        if (!$contact = Contact::with('company')->find($request->contact_id)) {
            return redirect()->back()->with('error', 'Contact not found.');
        }

        if (empty($contact->related_to)) {
            return redirect()->back()->with('error', 'This contact is not attached to any company.');
        }

        $company = $contact->company;

        $contact->related_to = null;
        $contact->save();

        return redirect()->back()->with('success', 'Contact <b>' . $contact->full_name . '</b> detached from "' . $company->full_name . '"');
    }

    public function addStaff(Request $request)
    {

        $validator = \Validator::make(
            $request->only(['staff_id']), [
                'staff_id' => 'required|positive',
            ]
        );
        if ($validator->fails()) {
            if (!empty($this->returnTo)) {
                return redirect()->to($this->returnTo)->with('error', $validator->messages()->first());
            } else {
                return redirect()->back()->with('error', $validator->messages()->first());
            }
        }

        if (!$staff = Staff::find($request->staff_id)) {
            if (!empty($this->returnTo)) {
                return redirect()->to($this->returnTo)->with('error', 'Staff not found.');
            } else {
                return redirect()->back()->with('error', 'Staff not found.');
            }
        }

        $staff->related_to = $contact->id;
        $staff->save();

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Contact <b>' . $staff->full_name . '</b> added as staff to "' . $contact->full_name . '".');
        } else {
            return redirect()->back()->with('success', 'Contact <b>' . $staff->full_name . '</b> added as staff to "' . $contact->full_name . '".');
        }
    }


    public function addNewStaff(Request $request)
    {

        $validator = \Validator::make(
            [
                'first_name' => $request->first_name,
                'address1'  => $request->address1,
                'phone'      => $request->phone,
                'email'      => $request->email,
            ],
            [
                'first_name' => 'required|personName',
                'last_name'  => 'nullable|personName',
                'email'      => 'required|email',
                'phone'      => 'required|text',
            ]
        );

        if ($validator->fails()) {
            if (!empty($this->returnTo)) {
                return redirect()->to($this->returnTo)->with('error', $validator->messages()->first());
            } else {
                return redirect()->back()->with('error', $validator->messages()->first());
            }
        }

        $contact = Contact::find($request['contact_id'])->first();

        $staff = New Contact();
        $staff->contact_type_id = 18;
        $staff->first_name = $request->first_name;
        $staff->address1  = $request->address1;
        $staff->phone = $request->phone;
        $staff->city = $request->city;
        $staff->state = $request->state;
        $staff->email = $request->email;
        $staff->related_to = $request['contact_id'];
        $staff->save();

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Contact <b>' . $staff->full_name . '</b> added as staff to "' . $contact->full_name . '".');
        } else {
            return redirect()->back()->with('success', 'Contact <b>' . $staff->full_name . '</b> added as staff to "' . $contact->full_name . '".');
        }
    }

    public function removeStaff(Contact $contact, Request $request)
    {
        $validator = \Validator::make(
            $request->only(['staff_id']), [
                'staff_id' => 'required|positive',
            ]
        );
        if ($validator->fails()) {
            if (!empty($this->returnTo)) {
                return redirect()->to($this->returnTo)->with('error', $validator->messages()->first());
            } else {
                return redirect()->back()->with('error', $validator->messages()->first());
            }
        }

        if (!$staff = Staff::find($request->staff_id)) {
            if (!empty($this->returnTo)) {
                return redirect()->to($this->returnTo)->with('error', 'Staff not found.');
            } else {
                return redirect()->back()->with('error', 'Staff not found.');
            }
        }

        $staff->related_to = null;
        $staff->save();

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Contact <b>' . $staff->full_name . '</b> added as staff to "' . $contact->full_name . '".');
        } else {
            return redirect()->back()->with('success', 'Contact <b>' . $staff->full_name . '</b> added as staff to "' . $contact->full_name . '".');
        }
    }

}
