<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $phone
 * @property int $blocked
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read mixed $role
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin global(string $text)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereBlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAdmin {}
}

namespace App\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBaseModel {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $court_type_id
 * @property string $name
 * @property string|null $inside_name
 * @property int $active
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\CourtType $courtType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Downtime> $downtimes
 * @property-read int|null $downtimes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interval> $intervals
 * @property-read int|null $intervals_count
 * @property-read mixed $intervals_ids
 * @property-read mixed $is_available
 * @property-read mixed $logo
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReservationSlot> $reservationSlots
 * @property-read int|null $reservation_slots_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court global(string $text)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereCourtTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereInsideName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCourt {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Court> $courts
 * @property-read int|null $courts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlanCourtTypeRule> $planRules
 * @property-read int|null $plan_rules_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourtType withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCourtType {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $is_active
 * @property string $code
 * @property \App\Enums\DiscountCodeType $type
 * @property numeric $value
 * @property int|null $usage_limit
 * @property int $used
 * @property string|null $date_from
 * @property string|null $date_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode dateFromBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode dateToBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereUsageLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscountCode withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDiscountCode {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $court_id
 * @property string $date_from
 * @property string $date_to
 * @property string $start_time
 * @property string $end_time
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Court $court
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime dateFromBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime dateToBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime whereCourtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Downtime withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDowntime {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $consumable
 * @property int $quota
 * @property int $postpaid
 * @property int|null $periodicity
 * @property string|null $periodicity_type
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \LucasDotVin\Soulbscription\Models\FeaturePlan|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Plan> $plans
 * @property-read int|null $plans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \LucasDotVin\Soulbscription\Models\FeatureTicket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereConsumable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature wherePeriodicity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature wherePeriodicityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature wherePostpaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereQuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feature withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFeature {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string|null $phone
 * @property array<array-key, mixed> $services
 * @property array<array-key, mixed>|null $days
 * @property array<array-key, mixed>|null $times
 * @property string|null $plan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember whereServices($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember whereTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutureMember withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFutureMember {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int|null $plan_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Plan|null $plan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property-read mixed $users_ids
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperGroup {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $uuid
 * @property string $email
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read mixed $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guest withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperGuest {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $inside_name
 * @property \Illuminate\Support\Carbon $date_from
 * @property \Illuminate\Support\Carbon $date_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Court> $courts
 * @property-read int|null $courts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\IntervalPrice> $prices
 * @property-read int|null $prices_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval dateFromBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval dateToBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval global(string $text)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval whereInsideName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperInterval {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $interval_id
 * @property string $day
 * @property string $start_time
 * @property string $end_time
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice whereIntervalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperIntervalPrice {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $owner_type
 * @property int $owner_id
 * @property string|null $paymentable_type
 * @property int|null $paymentable_id
 * @property string|null $transaction_id
 * @property \App\Enums\PaymentStatus $status
 * @property bool $renew
 * @property string|null $invoice_no
 * @property string|null $invoice_path
 * @property numeric $price
 * @property numeric $vat
 * @property numeric $price_with_vat
 * @property numeric $discount
 * @property numeric $paid_amount
 * @property numeric $paid_amount_from_balance
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $owner
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $paymentable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereInvoiceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereInvoicePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAmountFromBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePriceWithVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereRenew($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPayment {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $is_default
 * @property int $grace_days
 * @property string $name
 * @property string $type
 * @property int|null $periodicity
 * @property string|null $periodicity_type
 * @property string $price
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlanCourtTypeRule> $courtTypeRules
 * @property-read int|null $court_type_rules_count
 * @property-read \LucasDotVin\Soulbscription\Models\FeaturePlan|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Feature> $features
 * @property-read int|null $features_count
 * @property-read mixed $has_grace_days
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @property-read \App\Models\Payment|null $payment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \LucasDotVin\Soulbscription\Models\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan global(string $text)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereGraceDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan wherePeriodicity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan wherePeriodicityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPlan {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $plan_id
 * @property int $court_type_id
 * @property int $max_days_in_advance
 * @property int $cancel_hours_before
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\CourtType $courtType
 * @property-read \App\Models\Plan $plan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlanCourtTypeRuleSlot> $slots
 * @property-read int|null $slots_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule whereCancelHoursBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule whereCourtTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule whereMaxDaysInAdvance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRule withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPlanCourtTypeRule {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $plan_court_type_rule_id
 * @property string $day
 * @property string $start_time
 * @property string $end_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\PlanCourtTypeRule $planCourtTypeRule
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot wherePlanCourtTypeRuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanCourtTypeRuleSlot withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPlanCourtTypeRuleSlot {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $owner_type
 * @property int $owner_id
 * @property int|null $reservation_group_id
 * @property int $delete_after_failed_payment
 * @property int|null $court_id
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon $end_time
 * @property string $price
 * @property string $vat
 * @property string $discount
 * @property string $price_with_vat
 * @property bool $is_paid
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property string|null $payment_source
 * @property string $refunded_amount
 * @property \Illuminate\Support\Carbon|null $canceled_at
 * @property string|null $cancellation_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Court|null $court
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $owner
 * @property-read \App\Models\ReservationGroup|null $reservationGroup
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReservationSlot> $slots
 * @property-read int|null $slots_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation canceledAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation paidAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCanceledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCourtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereDeleteAfterFailedPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation wherePaymentSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation wherePriceWithVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereRefundedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereReservationGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReservation {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationGroup withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReservationGroup {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $reservation_id
 * @property int|null $court_id
 * @property \Illuminate\Support\Carbon $slot_start
 * @property \Illuminate\Support\Carbon $slot_end
 * @property string $price
 * @property string $vat
 * @property string $discount
 * @property string $price_with_vat
 * @property bool $try_sell
 * @property bool $is_refunded
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Court|null $court
 * @property-read \App\Models\Reservation $reservation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereCourtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereIsRefunded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot wherePriceWithVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereSlotEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereSlotStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereTrySell($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReservationSlot {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string $balance
 * @property string $discount_on_everything
 * @property string|null $birthday
 * @property string|null $phone
 * @property int $is_company
 * @property string|null $company_name
 * @property string|null $company_code
 * @property string|null $company_vat_code
 * @property string|null $company_address
 * @property string|null $company_phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \LucasDotVin\Soulbscription\Models\FeatureConsumption> $featureConsumptions
 * @property-read int|null $feature_consumptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \LucasDotVin\Soulbscription\Models\FeatureTicket> $featureTickets
 * @property-read int|null $feature_tickets_count
 * @property-read mixed $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection $features
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \LucasDotVin\Soulbscription\Models\SubscriptionRenewal> $renewals
 * @property-read int|null $renewals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reservation> $reservations
 * @property-read int|null $reservations_count
 * @property-read \LucasDotVin\Soulbscription\Models\Subscription|null $subscription
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User birthdayBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User dateBetween(string $column, string $start, ?string $end = null)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User global(string $text)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User updatedAtBetween(string $start, ?string $end = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyVatCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDiscountOnEverything($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

