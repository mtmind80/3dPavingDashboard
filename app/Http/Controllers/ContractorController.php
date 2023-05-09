<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Contractor;
use App\Models\ContractorType;

use Illuminate\Http\Request;

class ContractorController extends Controller
{
    
    public function __construct(Request $request)
    {
        parent::__construct();

    }

    public function index(Request $request)
    {

        $needle = $request->needle ?? null;

        $contractors = Contractor::search($needle)->sortable()->get()->toArray();
        $data = [
            'contractors' => $contractors,
            'needle' => $needle,
        ];


        return view('contractors.index', $data);
    }


    public function store(Request $request, $id = 0)
    {


        if($id) {
            $contractor = Contractor::find($id);
            \Session::flash('info', 'Your record was updated!');

        } else { //new
            $contractor = new Contractor;
            \Session::flash('info', 'Your record was created!');

        }

        $contractor->name = $request['name'];
        $contractor->contact = $request['contact'];
        $contractor->phone = $request['phone'];
        $contractor->address_line1 = $request['address_line1'];
        $contractor->address_line2 = $request['address_line2'];
        $contractor->city = $request['city'];
        $contractor->state = $request['state'];
        $contractor->postal_code = $request['postal_code'];
        $contractor->email = $request['email'];
        $contractor->note = $request['note'];
        $contractor->contractor_type_id = $request['contractor_type_id'];

        $contractor->save();

        return redirect()->route('contractor_list');

    }


    public function new(Request $request)
    {

        $data = [];
        return view('contractors.new', $data);

    }

    public function edit($id)
    {
        $data = array();
        $data['id'] = $id;
        $data['contractor'] = Contractor::find($id)->toArray();

        return view('contractors.edit', $data);
    }

    public function search(SearchRequest $request)
    {
        return $this->index($request);
    }


}
