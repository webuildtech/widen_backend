<?php

namespace App\Exports;

use AllowDynamicProperties;
use App\Models\User;
use App\Services\Users\UserService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

#[AllowDynamicProperties]
class UsersReportExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents, WithStrictNullComparison
{
    public function __construct(
        protected Carbon      $from,
        protected Carbon      $to,
        protected bool        $hideZeroBalances,
        protected bool        $hideZeroInvoices,
        protected UserService $userService,
    )
    {
    }

    public function collection()
    {
        $fromDate = $this->from->toDateString();
        $toDate = $this->to->toDateString();

        $users = User::query()
            ->with('invoices', fn($q) => $q->whereBetween('date', [$this->from, $this->to]))
            ->with('balanceEntries', fn($q) => $q->whereDate('created_at', '<=', $this->to))
            ->get()
            ->map(function (User $user) use ($fromDate, $toDate) {
                $isCompany = $user->is_company;

                $price = $user->invoices->sum('price');
                $vat = $user->invoices->sum('vat');
                $priceWithVat = $user->invoices->sum('price_with_vat');

                $balanceEntriesAmount = $user->balanceEntries->sum('amount');

                return [
                    'key' => 0,
                    'name' => $isCompany ? $user->company_name : $user->full_name,
                    'company_code' => $isCompany ? $user->company_code : 'ND',
                    'company_vat_code' => $isCompany && $user->company_vat_code ? $user->company_vat_code : 'ND',
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                    'start_balance' => $this->userService->getBalanceByDay($user, $this->from->copy()->subDay()),
                    'price_all' => $price,
                    'price' => $price,
                    '-',
                    '-',
                    'vat' => $vat,
                    'price_with_vat' => $priceWithVat,
                    'end_balance' => $this->userService->getBalanceByDay($user, $this->to),
                    'balance_entries_amount' => $balanceEntriesAmount > 0 ? $balanceEntriesAmount : '-',
                ];
            });

        if ($this->hideZeroBalances) {
            $users = $users->filter(fn($user) => $user['start_balance'] !== 0.0 || $user['end_balance'] !== 0.0);;
        }

        if ($this->hideZeroInvoices) {
            $users = $users->filter(fn($user) => $user['price'] > 0);
        }

        $key = 0;

        return $users->sortBy('name')->map(function ($user) use (&$key) {
            $key++;

            $user['key'] = $key;

            return $user;
        });
    }

    public function headings(): array
    {
        return [
            'Ei. Nr.',
            'Klientas',
            'Įm. k.',
            'PVM mok. kodas',
            'Periodo pradžia',
            'Periodo pabaiga',
            'Balansas ' . $this->from->toDateString() . ' ("+" - permoka=avansas, "-" nepriemoka=skola):',
            'Išrašyta sąskaitų per periodą iš viso (be PVM, EUR)',
            'Išrašyta sąskaitų per periodą (be PVM, EUR)',
            'Panaudotas admin krepšelis (be PVM, EUR)',
            'Nuolaida 100 proc. (admin krepšelio panaudojimas)',
            'PVM (EUR)',
            'Išrašyta sąskaitų per periodą (su PVM, EUR)',
            'Balansas ' . $this->to->toDateString(),
            'Dovanoti admin pinigai iki '. $this->to->toDateString() . '(nuo sistemos veikimo pradžios)'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'G' => NumberFormat::FORMAT_CURRENCY_EUR,
            'H' => NumberFormat::FORMAT_CURRENCY_EUR,
            'I' => NumberFormat::FORMAT_CURRENCY_EUR,
            'L' => NumberFormat::FORMAT_CURRENCY_EUR,
            'M' => NumberFormat::FORMAT_CURRENCY_EUR,
            'N' => NumberFormat::FORMAT_CURRENCY_EUR,
            'O' => NumberFormat::FORMAT_CURRENCY_EUR,
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
                $lastCol = 'O';
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

                $sheet->getStyle('I1:K1')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '5a6270'],
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(50);

                $sheet->freezePane('A2');
                $sheet->setAutoFilter($headerRg);

                $sheet->getColumnDimension('A')->setWidth(10);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(25);
                $sheet->getColumnDimension('H')->setWidth(25);
                $sheet->getColumnDimension('I')->setWidth(25);
                $sheet->getColumnDimension('J')->setWidth(25);
                $sheet->getColumnDimension('K')->setWidth(25);
                $sheet->getColumnDimension('L')->setWidth(25);
                $sheet->getColumnDimension('M')->setWidth(25);
                $sheet->getColumnDimension('N')->setWidth(25);
                $sheet->getColumnDimension('O')->setWidth(25);

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
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => $primary],
                        ],
                    ],
                ]);

                if ($lastRow >= 2) {
                    $sheet->getStyle('E2:F' . $lastRow)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'e8c064'],
                        ],
                    ]);
                }

                // Toliau paliekam tavo sumas, geltoną E/F ir borderius

                $sheet->setCellValue("A{$totalRow}", 'IŠVISO:');

                foreach (range('G', 'O') as $col) {
                    $sheet->setCellValue(
                        $col . $totalRow,
                        sprintf('=SUM(%1$s2:%1$s%2$d)', $col, $lastRow)
                    );
                }

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

                $sheet->getStyle("G{$totalRow}:O{$totalRow}")
                    ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_EUR);

                $sheet->getRowDimension($totalRow)->setRowHeight(22);
            },
        ];
    }
}
