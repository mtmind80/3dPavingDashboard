<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use PDF;

class PrintingController extends Controller
{



    public function __construct(Request $request)
    {
        parent::__construct();

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($proposal_id)
    {
        $data =[];
        return view("pdf.index", $data);

    }


    public function proposal($proposal_id)
    {


        $pdfname = 'generate.pdf' . $proposal_id;

        $data = [
            'title' => 'Welcome to Hummingbird',
            'date' => date('m/d/Y')
        ];

        return view('pdf.proposal',$data);

        //$pdf = PDF::loadView('pdf.proposal', $data);

        //return $pdf->download($pdfname);

    }
}
