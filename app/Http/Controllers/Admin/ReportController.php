<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Reports\UserBalancesExportReportData;
use App\Exports\UsersReportExport;
use App\Http\Controllers\Controller;
use App\Services\Users\UserService;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function userBalances(UserBalancesExportReportData $data): BinaryFileResponse
    {
        $from = $data->date_from->startOfDay();
        $to = $data->date_to->endOfDay();

        $filename = sprintf('vartotoju_balansu_ataskaita_%s_%s.xlsx', $from->format('Ymd'), $to->format('Ymd'));

        return Excel::download(new UsersReportExport(
            $from,
            $to,
            $data->hide_zero_balances,
            $data->hide_zero_invoices,
            new UserService()
        ), $filename);
    }
}
