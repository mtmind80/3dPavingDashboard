<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ActivityByContactTypeExport implements FromView, WithEvents, WithColumnFormatting
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
        $this->totalRows = count($rows);
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        return view('reports.exports.activity_by_contact_type', [
            'rows' => $this->rows,
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->setTitle($this->title);

                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);
                for ($i = 1; $i <= $this->totalRows; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i+1)->setRowHeight(30);
                }
                $event->sheet->getDelegate()->getRowDimension($this->totalRows+2)->setRowHeight(35);

                $event->sheet->getDelegate()->getDefaultColumnDimension()->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(60);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(40);

                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'aec8f8',
                        ],
                        'endColor' => [
                            'argb' => 'aec8f8',
                        ],
                    ],
                ];
                $event->sheet->getStyle('A1:Y1')->applyFromArray($styleArray);

                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'dddddd'],
                        ],
                    ],
                ];
                $event->sheet->getStyle('A1:Y'.($this->totalRows+2))->applyFromArray($styleArray);

                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                ];
                $event->sheet->getStyle('A'.($this->totalRows+2))->applyFromArray($styleArray);
                $event->sheet->getStyle('B'.($this->totalRows+2))->applyFromArray($styleArray);
            }
        ];
    }

}
