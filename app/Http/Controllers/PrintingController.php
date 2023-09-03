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

    public function printExamplePdfWithBAckgroundImage()
    {
        $users = \App\Models\User::get();

        $imgContent = file_get_contents(public_path().'/images/bg-img.jpg');
        $type = 'jpg';
        $img64 = 'data:image/'.$type.';base64,'.base64_encode($imgContent);

        $data = [
            'users' => $users,
            'img64' => $img64,
        ];

        $pdf = Pdf::loadView('pdf.example_report', $data);

        return $pdf->download('example_report.pdf');
    }

}
