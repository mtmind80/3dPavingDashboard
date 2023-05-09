<?php

namespace App\Http\Controllers;

use Log;
use Exception;
use App\Helpers\Currency;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\GoogleCalendar\Event as Event;
use Spatie\GoogleCalendar\Exceptions;

class HomeController extends Controller
{
    public function dashboard()
    {
        $numberOfSales = '1444';
        $salesRevenue = '38567.7756';
        $averagePrice = '15.39744';

        $lineColumn = [
            'series' => [
                (object)[
                    'name' => '2021',
                    'type' => 'column',
                    'data' => [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16],
                ],
                (object)[
                    'name' => '2022',
                    'type' => 'column',
                    'data' => [23, 32, 27, 10],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'legend' => true,
        ];
        $linehar = [
            'series' => [
                (object)[
                    'name' => '2024',
                    'type' => 'column',
                    'data' => [22, 22, 25, 27, 23, 42, 27, 31, 22, 22, 12, 16],
                ],
                (object)[
                    'name' => '2023',
                    'type' => 'column',
                    'data' => [23, 32, 27, 10, 22, 22, 22, 22, 22, 2],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'legend' => true,
        ];

        $donut = [
            'series' => [42, 42, 42],
            'labels' => ["Striping", "Rock", "Asphalt"],
        ];
        $donutLabels = ["Striping" => "42", "Rock" => "23", "Asphalt" => "32"];

        $data = [
            'numberOfSales' => $numberOfSales,
            'salesRevenue'  => Currency::format($salesRevenue),
            'averagePrice'  => Currency::format($averagePrice),
            'lineColumn'    => json_encode($lineColumn),
            'linehar'       => json_encode($linehar),
            'donut'         => json_encode($donut),
            'donutLabels'   => $donutLabels,

        ];

        return view('dashboard', $data);
    }

    public function getmodel($model)
    {
        return $this->checkmodel($model);
    }

}
