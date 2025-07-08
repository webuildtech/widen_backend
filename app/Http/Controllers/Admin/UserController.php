<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Users\UserListData;
use App\Data\Admin\Users\UserSelectOptionData;
use App\Data\Admin\Users\UserStoreData;
use App\Data\Admin\Users\UserUpdateData;
use App\Data\Admin\Users\UserData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use LucasDotVin\Soulbscription\Models\Subscription;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
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
//                'balance',
                'discount_on_everything',
                'birthday',
                'phone',
                'is_company',
                'company_name',
                'agreed_newsletter',
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

        return UserListData::collect($users);
    }

    public function store(UserStoreData $data): UserData
    {
        $user = $this->userService->create($data->all());

        return UserData::from($user);
    }

    public function show(User $user): UserData
    {
        return UserData::from($user);
    }

    public function update(UserUpdateData $data, User $user): UserData
    {
        $user = $this->userService->update($user, $data->all());

        return UserData::from($user);
    }

    public function destroy(User $user): array
    {
        $user->groups()->detach();

        Subscription::query()->withoutGlobalScopes()
            ->where('subscriber_id', $user->id)->where('subscriber_type', 'user')
            ->delete();

        $user->delete();

        return [];
    }

    public function all()
    {
        return UserSelectOptionData::collect(User::select(['id', 'first_name', 'last_name'])->get());
    }
}
