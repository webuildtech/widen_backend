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
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read string $role
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin global(string $text)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin updatedAtBetween(...$interval)
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel updatedAtBetween(...$interval)
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
 * @property string $name
 * @property string|null $inside_name
 * @property int $active
 * @property string|null $description
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read array $intervals_ids
 * @property-read mixed $is_available
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Media|null $logo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interval> $intervals
 * @property-read int|null $intervals_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court global(string $text)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court updatedAtBetween(...$interval)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereInsideName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Court whereType($value)
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
 * @property int|null $plan_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read array $users_ids
 * @property-read \App\Models\Plan|null $plan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group updatedAtBetween(...$interval)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval dateBetween(...$interval)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval global(string $text)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Interval updatedAtBetween(...$interval)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntervalPrice updatedAtBetween(...$interval)
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
 * @property int $grace_days
 * @property string $name
 * @property string $type
 * @property int|null $periodicity
 * @property string|null $periodicity_type
 * @property string $price
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \LucasDotVin\Soulbscription\Models\FeaturePlan|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \LucasDotVin\Soulbscription\Models\Feature> $features
 * @property-read int|null $features_count
 * @property-read mixed $has_grace_days
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \LucasDotVin\Soulbscription\Models\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan global(string $text)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereGraceDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereId($value)
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
 * @property int|null $user_id
 * @property string|null $guest_first_name
 * @property string|null $guest_last_name
 * @property string|null $guest_email
 * @property string|null $guest_phone
 * @property string $price
 * @property string $vat
 * @property string $discount
 * @property string $paid_amount
 * @property string $paid_amount_from_balance
 * @property string $refunded_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReservationSlot> $slots
 * @property-read int|null $slots_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReservationTime> $times
 * @property-read int|null $times_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation updatedAtBetween(...$interval)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereGuestEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereGuestFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereGuestLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereGuestPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation wherePaidAmountFromBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereRefundedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reservation whereUserId($value)
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
 * @property int $reservation_id
 * @property int $reservation_time_id
 * @property int|null $court_id
 * @property \Illuminate\Support\Carbon $slot_start
 * @property \Illuminate\Support\Carbon $slot_end
 * @property string $price
 * @property string $discount
 * @property int $try_sell
 * @property int $is_refunded
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\ReservationTime $reservationTime
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot updatedAtBetween(...$interval)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereCourtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereIsRefunded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereReservationTimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereSlotEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereSlotStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereTrySell($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationSlot whereUpdatedAt($value)
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
 * @property int $reservation_id
 * @property int|null $court_id
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon $end_time
 * @property float $price
 * @property string $discount
 * @property float $refunded_amount
 * @property \Illuminate\Support\Carbon|null $canceled_at
 * @property string|null $cancellation_reason
 * @property int $refund_attempted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Court|null $court
 * @property-read \App\Models\Reservation $reservation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReservationSlot> $slots
 * @property-read int|null $slots_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime updatedAtBetween(...$interval)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereCanceledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereCourtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereRefundAttempted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereRefundedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationTime withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReservationTime {}
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
 * @property-read int $cancel_before
 * @property-read string $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User birthdayBetween(...$interval)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User global(string $text)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User updatedAtBetween(...$interval)
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

