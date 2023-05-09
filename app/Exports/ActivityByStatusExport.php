<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ActivityByStatusExport implements FromView, WithEvents, WithColumnFormatting
{
    private $title;
    private $rows;
    private $from;
    private $to;
    private $totalRows;

    public function __construct($rows, $from, $to, $title)
    {
        $this->rows = $rows;
        $this->from = $from;
        $this->to = $to;
        $this->title = $title;
        $this->totalSalesPersons = count($rows);
        $this->totalRows = $this->totalSalesPersons * 10;
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        return view('reports.exports.activity_by_status', [
            'from' => $this->from,
            'to'   => $this->to,
            'rows' => $this->rows,
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $event->sheet->setTitle($this->title);

                // for all cells - set width, height and alignments:

                $event->sheet->getDelegate()->getDefaultColumnDimension()->setWidth(60);
                for ($i = 1; $i <= $this->totalRows + 1; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(30);
                }
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'dddddd'],
                        ],
                    ],
                ];
                $event->sheet->getStyle('A1:C' . ($this->totalRows + 1))->applyFromArray($styleArray);

                // first row (header columns row):

                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'aec8f8',
                        ],
                        'endColor'   => [
                            'argb' => 'aec8f8',
                        ],
                    ],
                ];
                $event->sheet->getStyle('A1:C1')->applyFromArray($styleArray);

                // for each sales person's rows:

                $styleSalesPersonHeaderRowsArray = [  // first and second rows:
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'f4fcff',
                        ],
                        'endColor'   => [
                            'argb' => 'f4fcff',
                        ],
                    ],
                ];
                $styleSalesPersonRegularRowsArray = [  // next 7 rows (status rows):
                    'font' => [
                        'bold' => false,
                    ],
                ];
                $styleSalesPersonFooterRowArray = [  // last row:
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'f4f4f4',
                        ],
                        'endColor'   => [
                            'argb' => 'f4f4f4',
                        ],
                    ],
                ];

                $i = 2;
                for ($j = 0; $j < $this->totalSalesPersons; $j++) {
                    // header rows (2):
                    $event->sheet->getStyle('A'.$i.':C'.($i+1))->applyFromArray($styleSalesPersonHeaderRowsArray);

                    // status rows (7):
                    $event->sheet->getStyle('A'.($i+2).':C'.($i+8))->applyFromArray($styleSalesPersonRegularRowsArray);

                    // footer row (1):
                    $event->sheet->getStyle('A'.($i+9).':C'.($i+9))->applyFromArray($styleSalesPersonFooterRowArray);

                    $i += 10;
                }
            },
        ];
    }

}
