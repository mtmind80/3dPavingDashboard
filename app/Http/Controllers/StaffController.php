<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\SearchRequest;
use App\Models\Staff;
use App\Models\Contact;

class StaffController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct();

    }

    public function index(Request $request, $contact_id)
    {
        if (!$contact = Contact::find($contact_id)) {
            return redirect()->back()->with('Error', 'Contact not found.');
        }

        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 20;

        $staffMembers = Staff::whereHas('company', function ($q) use ($contact_id){
            $q->where('id', $contact_id);
        })
            ->search($needle)
            ->sortable()
            ->paginate($perPage);

        $ids = $staffMembers->pluck('id')->toArray();

        $availableStaffMembers = Staff::whereNotIn('id', $ids)->orderBy('first_name')->orderBy('last_name')->get();

        //   dd($ids, $availableStaffMembers->pluck('id')->toArray());

        //dd($availableStaffMembers->first()->full_name);

        $data = [
            'contact_id'            => $contact_id,
            'contact'               => $contact,
            'staffMembers'          => $staffMembers,
            'availableStaffMembers' => $availableStaffMembers,
            'needle'                => $needle,
        ];

        return view('staff.index', $data);
    }

    public function search(SearchRequest $request, $contact_id)
    {
        return $this->index($request, $contact_id);
    }

    public function remnove(Request $request, $contact_id)
    {
        if (!$staff = Staff::with('company')->find($contact_id)) {
            return redirect()->back()->with('error', 'Staff not found.');
        }

        if (empty($staff->related_to)) {
            return redirect()->back()->with('error', 'This contact is not attached to any company.');
        }

        $company = $staff->company;

        $staff->related_to = null;
        $staff->save();

        return redirect()->back()->with('success', 'Staff <b>' . $staff->full_name . '</b> detached from "' . $company->full_name . '"');
    }

}
