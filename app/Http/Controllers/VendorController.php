<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Vendor;
use App\Models\VendorType;

use Illuminate\Http\Request;

class VendorController extends Controller
{
    
    public function __construct(Request $request)
    {
        parent::__construct();

    }

    public function index(Request $request)
    {

        $needle = $request->needle ?? null;

        $vendors = vendor::search($needle)->sortable()->get()->toArray();
        $data = [
            'vendors' => $vendors,
            'needle' => $needle,
        ];


        return view('vendors.index', $data);
    }


    public function store(Request $request, $id = 0)
    {


        if($id) {
            $vendor = vendor::find($id);
            \Session::flash('info', 'Your record was updated!');

        } else { //new
            $vendor = new vendor;
            \Session::flash('info', 'Your record was created!');

        }

        $vendor->name = $request['name'];
        $vendor->contact = $request['contact'];
        $vendor->phone = $request['phone'];
        $vendor->address_line1 = $request['address_line1'];
        $vendor->address_line2 = $request['address_line2'];
        $vendor->city = $request['city'];
        $vendor->state = $request['state'];
        $vendor->postal_code = $request['postal_code'];
        $vendor->email = $request['email'];
        $vendor->note = $request['note'];
        $vendor->vendor_type_id = $request['vendor_type_id'];

        $vendor->save();

        return redirect()->route('vendor_list');

    }


    public function new(Request $request)
    {

        $data = [];
        return view('vendors.new', $data);

    }

    public function edit($id)
    {
        $data = array();
        $data['id'] = $id;
        $data['vendor'] = vendor::find($id)->toArray();

        return view('vendors.edit', $data);
    }

    public function search(SearchRequest $request)
    {
        return $this->index($request);
    }


}
