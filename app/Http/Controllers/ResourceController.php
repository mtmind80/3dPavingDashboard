<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;



class ResourceController extends Controller
{
    // allowed to edit these
    public $models = ['Equipment', 'RockVendorsCosts','AsphaltVendorCost','MediaType', 'Material', 'Vehicle', 'VehicleType','LaborRate', 'StripingCost', 'StripingService', 'LeadSource', 'OfficeLocations', 'WebConfig', 'Term'];
    public $orderby = [
        'Equipment' => ['name'],
        'Material' => ['service_category_id'],
        'Vehicle' => ['id'],
        'VehicleType' => ['name' ],
        'LaborRate' => ['id'],
        'StripingCost' => ['striping_service_id'],
        'StripingService' => ['dsort'],
        'OfficeLocations' => ['id'],
        'WebConfig' => ['id'],
        'Term' => ['section'],
        'LeadSource' => ['id'],
        'MediaType' => ['id'],
        'AsphaltVendorCost' => ['vendor_name'],
        'RockVendorsCosts' => ['vendor_name'],
    ];

    public $columns = [
        'Equipment' => ['id', 'name', 'rate_type', 'rate'],
        'Material' => ['id', 'name', 'cost','service_category_id'],
        'Vehicle' => ['id', 'vehicle_types_id', 'name', 'description', 'active', 'office_location_id'],
        'VehicleType' => ['id', 'name', 'description','rate'],
        'LaborRate' => ['id', 'name', 'rate'],
        'StripingCost' => ['id', 'striping_service_id', 'description', 'cost'],
        'StripingService' => ['id', 'name', 'dsort'],
        'OfficeLocations' => ['id','name', 'address', 'city', 'state', 'zip', 'phone', 'manager'],
        'WebConfig' => ['id', 'key', 'value'],
        'Term' => ['id', 'text', 'section', 'title'],
        'LeadSource' => ['id', 'name'],
        'MediaType' => ['id', 'type'],
        'AsphaltVendorCost' => ['id','vendor_name','cost'],
        'RockVendorsCosts' => ['id','vendor_name','cost'],
    ];


    public $headers = [
        'Equipment' => ['Edit', 'Name', 'Normally Billed', 'Rate'],
        'Material' => ['Edit', 'Name', 'Cost','Service'],
        'Vehicle' => ['Edit', 'Type', 'Name', 'Description', 'Active', 'Location'],
        'VehicleType' => ['Edit', 'Name', 'Description','Rate'],
        'LaborRate' => ['Edit', 'Name', 'Rate'],
        'StripingCost' => ['Edit', 'Service', 'Description', 'Rate'],
        'StripingService' => ['Edit', 'Name', 'Sort'],
        'OfficeLocations' => ['Edit','Name', 'Address', 'Phone', 'Manager'],
        'WebConfig' => ['Edit', 'key', 'value'],
        'Term' => ['Edit', 'Section', 'Text'],
        'LeadSource' => ['Edit', 'Source'],
        'MediaType' => ['Edit', 'Type'],
        'AsphaltVendorCost' => ['Edit', 'Name', 'Cost'],
        'RockVendorsCosts' => ['Edit','Name','Cost'],
    ];

    public $views = [
        'Equipment' => "resources.equipment",
        'Material' => "resources.materials",
        'Vehicle' => "resources.vehicles",
        'VehicleType' => "resources.vehicletype",
        'LaborRate' => "resources.labor",
        'StripingCost' => "resources.stripingcost",
        'StripingService' => "resources.stripingservice",
        'OfficeLocations' => "resources.office",
        'WebConfig' => "resources.webconfig",
        'Term' => "resources.terms",
        'LeadSource' => "resources.leadsource",
        'MediaType' => "resources.mediatype",
        'AsphaltVendorCost' => "resources.asphalttype",
        'RockVendorsCosts' => "resources.rocktype",

    ];

    public $headernames = [
        'Equipment' => "Equipment",
        'Material' => "Materials",
        'Vehicle' => "Vehicles",
        'VehicleType' => "Vehicle Types",
        'LaborRate' => "Labor Rates",
        'StripingCost' => "Striping Costs",
        'StripingService' => "Striping Services",
        'OfficeLocations' => "Office Locations",
        'WebConfig' => "Web Defaults",
        'Term' => "Terms and Conditions",
        'LeadSource' => "Lead Sources",
        'MediaType' => 'Proposal Media Types',
        'AsphaltVendorCost' => "Asphalt Options",
        'RockVendorsCosts' => "Rock Options",
    ];




    public function __construct(Request $request)
    {
        parent::__construct();

    }
    
    public function index()
    {
        return view('resources.index');
    }

    public function getmodel($modelname = null)
    {

        if(in_array($modelname,$this->models)) {
            $data["headername"] = $this->headernames[$modelname];
            $data["model"] = $modelname;
            $modelname = 'App\\Models\\' . $modelname;
            $string_table_name = (new $modelname)->getTable();

            if(class_exists($modelname) && in_array($data['model'], $this->models)) {
                if($data['model'] =='StripingCost') {
                    $data['p'] = true;
                    $datum = $modelname::orderBy('striping_service_id')->paginate(75);
                } elseif($data['model'] =='Equipment') {
                    $datum = $modelname::all()->where('do_not_use' ,'=', 0)->toArray();

                } else {
                    $data['p'] = false;
                    $datum = $modelname::all()->sortBy($this->orderby[$data["model"]])->toArray();
                }
                
                $headers = $this->headers[$data["model"]];
                $columns = $this->columns[$data["model"]];
                $view = $this->views[$data["model"]];
                $data['columns'] = $columns; //Schema::getColumnListing($myModel->getTable());
                $data['headers'] = $headers;
                $data['view'] = $view;
                $data['tablename'] = $string_table_name;
                $data['datum'] = $datum;
                return view($view, $data);

            }
        }

        return view('pages-404');
    }

    /**
     * Store update or save a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $model, $id = 0)
    {

        $modelname = 'App\\Models\\' . $model;
        if(class_exists($modelname) && in_array($model, $this->models)) {

            $data = $this->prepareData($model, $request);
            //print_r($data);
            if($id>0) {
                $modelname::whereId($id)->update($data);
                \Session::flash('info', 'Your record was updated!');

                return redirect()->route('getmodel', ['model' => $model]);

            }
            //echo $model;
           // print_r($data);
           // exit();
            $modelname::create($data);
            \Session::flash('info', 'Your record was created!');

            return redirect()->route('getmodel', ['model' => $model]);
        }

        return view('pages-404');
    }

    public function prepareData($model, $request)
    {

        foreach($this->columns[$model] as $column) {
            if($column != 'id') {
                $data[$column] = $request[$column];
            }
        }
        return $data;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function editresource($model = null, $id = null)
    {

        //echo $model;
        //exit();
        if(!$model || !$id) {

            \Session::flash('info', "No records found!");
            return redirect()->back();

        }
        $modelname = 'App\\Models\\' . $model;

        $data["model"] = $model;
        $data["headername"] = $this->headernames[$model];

        if(class_exists($modelname) && in_array($model, $this->models)) {
            //echo "yeah";
            $tablename = new $modelname;
            $records = $tablename::find($id)->toArray();
        } else {
            //echo "NoWay";
            \Session::flash('info', "No model found!");
            return redirect()->back();

        }

        if(!$records) {
            //echo "NoWay";
            \Session::flash('info', "No records found!");
            return redirect()->back();

        }
        $headers = $this->headers[$model];
        $columns = $this->columns[$model];
        $view = $this->views[$model];
        $data['columns'] = $columns; //Schema::getColumnListing($myModel->getTable());
        $data['id'] = $id;
        $data['headers'] = $headers;
        $data['records'] = $records;
        if($model == "Material")
        {
            $data['services'] = ServiceCategory::all();
        }
        return view($view . "_edit", $data);

    }

    public function newresource($model)
    {

        $data["headername"] = $this->headernames[$model];
        $data["model"] = $model;
        $modelname = 'App\\Models\\' . $model;
        if($model == "Material")
        {
            $data['services'] = ServiceCategory::all();
        }
        if(class_exists($modelname) && in_array($model, $this->models)) {
            $view = $this->views[$model] . '_create';
            return view($view, $data);

        }
        return view('pages-404');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($model, $id)
    {
        $modelname = 'App\\Models\\' . $model;
        if(class_exists($modelname) && in_array($model, $this->models)) {
            $data['numrec']=$modelname::where('id',$id)->delete();
            \Session::flash('info', 'Your record was deleted!');
            return redirect()->route('getmodel', ["model"=>$model]);


        }
        return view('pages-404');
        
    }
    
}
