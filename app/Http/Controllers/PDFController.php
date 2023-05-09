<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;


class PDFController extends Controller
{


    public function __construct(Request $request)
    {
        parent::__construct();

    }
    
    public function generatePDF() {

        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')
        ];

        $pdf = PDF::loadView('pdf.generate', $data);

        return $pdf->download('generate.pdf');
     
    }

}
