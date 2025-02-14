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
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Media|null $logo
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
 * @property string|null $inside_name
 * @property \Illuminate\Support\Carbon $date_from
 * @property \Illuminate\Support\Carbon $date_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
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
 * @property string $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string $balance
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
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCompanyVatCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
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

