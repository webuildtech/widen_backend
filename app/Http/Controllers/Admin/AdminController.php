<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Admins\AdminData;
use App\Data\Admin\Admins\AdminListData;
use App\Data\Admin\Admins\AdminStoreData;
use App\Data\Admin\Admins\AdminUpdateData;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\AdminService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AdminController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    )
    {
    }

    public function index()
    {
        $admins = QueryBuilder::for(Admin::class)
            ->allowedSorts([
                'first_name',
                'last_name',
                'email',
                'phone',
                'updated_at'
            ])
            ->allowedFilters([
                'first_name',
                'last_name',
                'email',
                'phone',
                AllowedFilter::scope('updated_at_between'),
                AllowedFilter::scope('global'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return AdminListData::collect($admins);
    }

    public function store(AdminStoreData $data): AdminData
    {
        $admin = $this->adminService->create($data);

        return AdminData::from($admin);
    }

    public function show(Admin $admin): AdminData
    {
        return AdminData::from($admin);
    }

    public function update(AdminUpdateData $data, Admin $admin): AdminData
    {
        $admin = $this->adminService->update($admin, $data);

        return AdminData::from($admin);
    }

    public function destroy(Admin $admin): array
    {
        $admin->delete();

        return [];
    }
}
