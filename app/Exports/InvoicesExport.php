<?php

namespace App\Exports;

use AllowDynamicProperties;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

#[AllowDynamicProperties]
class InvoicesExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize, WithEvents
{
    public function __construct(
        protected Carbon $from,
        protected Carbon $to
    )
    {
        $this->totals = Invoice::query()
            ->whereBetween('date', [$this->from->toDateString(), $this->to->toDateString()])
            ->selectRaw('SUM(price) as price, SUM(vat) as vat, SUM(price_with_vat) as price_with_vat')
            ->first();
    }

    public function query(): Builder
    {
        return Invoice::query()
            ->with('owner')
            ->whereBetween('date', [$this->from->toDateString(), $this->to->toDateString()])
            ->orderBy('number');
    }

    public function headings(): array
    {
        return [
            'Vartotojas',
            'El. paštas',
            'Tel. numeris',

            'Ar tai įmonė',
            'Įmonės pavadinimas',
            'Įmonės kodas',
            'Įmonės PVM kodas',
            'Įmonės adresas',
            'Įmonės telefonas',

            'Numeris',
            'Sąskaita išrašyta',
            'Kaina',
            'PVM',
            'Kaina su PVM',
        ];
    }

    public function map($row): array
    {
        $owner = $row->owner;

        $isCompany = (bool)data_get($owner, 'is_company', false);
        $companyName = data_get($owner, 'company_name');
        $companyCode = data_get($owner, 'company_code');
        $companyVat = data_get($owner, 'company_vat_code');
        $companyAddr = data_get($owner, 'company_address');
        $companyTel = data_get($owner, 'company_phone');

        return [
            data_get($owner, 'full_name'),
            data_get($owner, 'email'),
            data_get($owner, 'phone'),

            $isCompany ? 'Taip' : 'Ne',
            $companyName,
            $companyCode,
            $companyVat,
            $companyAddr,
            $companyTel,

            "SS {$row->number}",
            ExcelDate::stringToExcel((string)$row->date),
            $row->price,
            $row->vat,
            $row->price_with_vat,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'L' => NumberFormat::FORMAT_CURRENCY_EUR,
            'M' => NumberFormat::FORMAT_CURRENCY_EUR,
            'N' => NumberFormat::FORMAT_CURRENCY_EUR,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $primary = '2f5c7e';
                $primaryLight = 'e9f1f7';
                $totalBg = 'dbe8f2';

                $lastRow = $sheet->getHighestRow();
                $lastCol = 'N';
                $headerRg = "A1:{$lastCol}1";
                $tableRg = "A1:{$lastCol}{$lastRow}";
                $totalRow = $lastRow + 1;

                $sheet->getStyle($headerRg)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 13,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $primary],
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(26);

                $sheet->freezePane('A2');
                $sheet->setAutoFilter($headerRg);

                for ($r = 2; $r <= $lastRow; $r++) {
                    if ($r % 2 === 0) {
                        $sheet->getStyle("A{$r}:{$lastCol}{$r}")
                            ->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB($primaryLight);
                    }
                }

                $sheet->getStyle($tableRg)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => $primary],
                        ],
                        'inside' => [
                            'borderStyle' => Border::BORDER_HAIR,
                            'color' => ['rgb' => $primary],
                        ],
                    ],
                ]);

                $sheet->getStyle('A2:C' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setIndent(1);

                $sheet->getStyle('D2:I' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setIndent(1);

                $sheet->getStyle('J2:N' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setIndent(0);

                $sheet->setCellValue("A{$totalRow}", 'IŠVISO:');
                $sheet->setCellValue("L{$totalRow}", (float)($this->totals->price ?? 0));
                $sheet->setCellValue("M{$totalRow}", (float)($this->totals->vat ?? 0));
                $sheet->setCellValue("N{$totalRow}", (float)($this->totals->price_with_vat ?? 0));

                $sheet->getStyle("A{$totalRow}:{$lastCol}{$totalRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '000000'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $totalBg],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => $primary],
                        ],
                    ],
                ]);

                $sheet->getStyle("J{$totalRow}:N{$totalRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A{$totalRow}")->getAlignment()->setIndent(1);

                $sheet->getStyle("L{$totalRow}:N{$totalRow}")
                    ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_EUR);

                $sheet->getRowDimension($totalRow)->setRowHeight(22);
            },
        ];
    }
}
