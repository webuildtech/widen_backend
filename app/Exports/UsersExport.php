<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements WithHeadings, FromCollection, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    public function collection()
    {
        return User::with(['subscription.plan'])->get()->map(fn (User $user) => [
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->discount_on_everything / 100,
            -$user->overdraft_limit,
            $user->birthday,
            $user->phone,
            $user->is_company ? $user->company_name : null,
            $user->is_company ? $user->company_code : null,
            $user->is_company ? $user->company_vat_code : null,
            $user->is_company ? $user->company_address : null,
            $user->is_company ? $user->company_phone : null,
            $user->agreed_newsletter ? 'Taip' : 'Ne',
            $user->subscription?->plan?->plan->name ?? null,
        ]);
    }

    public function headings(): array
    {
        return [
            'Vardas',
            'Pavardė',
            'El. paštas',
            'Nuolaida viskam',
            'Leidžiamas minusas',
            'Gimimo data',
            'Tel. numeris',
            'Įmonė pavadinimas',
            'Įmonė kodas',
            'Įmonė PVM mokėtojo kodas',
            'Įmonė adresas',
            'Įmonė tel. numeris',
            'Sutiko gauti reklamą',
            'Planas'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => ['bold' => true, 'name' => 'Arial', 'size' => 13, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'ff2f5c7e']],
        ]);

        $sheet->getStyle('A:N')->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true]
        ]);

        return [];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_PERCENTAGE_00,
            'E' => NumberFormat::FORMAT_CURRENCY_EUR,
        ];
    }
}
