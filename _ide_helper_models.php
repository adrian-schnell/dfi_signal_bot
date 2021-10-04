<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\DfiMasternode
 *
 * @property int $id
 * @property int $telegramUserId
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserMasternode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMasternode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMasternode query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMasternode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMasternode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMasternode whereTelegramUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMasternode whereUpdatedAt($value)
 */
	class DfiMasternode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TelegramUser
 *
 * @mixin \Eloquent
 * @property string telegramId
 * @property string firstName
 * @property string lastName
 * @property string username
 * @property string language
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property int $id
 * @property string $telegramId
 * @property string|null $firstName
 * @property string|null $lastName
 * @property string|null $username
 * @property string $language
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramUser whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramUser whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramUser whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramUser whereTelegramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramUser whereUsername($value)
 */
	class TelegramUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 */
	class User extends \Eloquent {}
}

