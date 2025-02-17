<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Users\ListUserData;
use App\Data\Admin\Users\SelectUserData;
use App\Data\Admin\Users\StoreUserData;
use App\Data\Admin\Users\UpdateUserData;
use App\Data\Admin\Users\UserData;
use App\Http\Controllers\Controller;
use App\Interfaces\Repositories\Models\UserRepositoryInterface;
use App\Models\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    public function index()
    {
        $users = QueryBuilder::for(User::class)
            ->allowedSorts([
                'first_name',
                'last_name',
                'email',
                'balance',
                'discount_on_everything',
                'birthday',
                'phone',
                'is_company',
                'company_name',
                'updated_at'
            ])
            ->allowedFilters([
                'first_name',
                'last_name',
                'email',
                AllowedFilter::scope('birthday_between'),
                'phone',
                'company_name',
                AllowedFilter::scope('updated_at_between'),
                AllowedFilter::scope('global'),
            ])
            ->paginate(request()->get('rowsPerPage') ?? 15)
            ->appends(request()->query());

        return ListUserData::collect($users);
    }

    public function store(StoreUserData $data): UserData
    {
        $user = $this->userRepository->create($data);

        return UserData::from($user);
    }

    public function show(User $user): UserData
    {
        return UserData::from($user);
    }

    public function update(UpdateUserData $data, User $user): UserData
    {
        $user = $this->userRepository->update($user, $data);

        return UserData::from($user);
    }

    public function destroy(User $user): array
    {
        $this->userRepository->delete($user);

        return [];
    }

    public function all()
    {
        return SelectUserData::collect(User::select(['id', 'first_name', 'last_name'])->get());
    }
}
