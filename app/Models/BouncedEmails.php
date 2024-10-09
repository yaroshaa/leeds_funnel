<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BouncedEmails
 *
 * @property int $id
 * @property string|null $email
 * @property int|null $deal_id
 * @property int|null $person_id
 * @property string|null $datetime
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BouncedEmails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BouncedEmails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BouncedEmails query()
 * @method static \Illuminate\Database\Eloquent\Builder|BouncedEmails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BouncedEmails whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BouncedEmails whereDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BouncedEmails whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BouncedEmails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BouncedEmails wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BouncedEmails whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BouncedEmails extends Model
{
    protected $table = 'bounced_emails';
    protected $fillable = [
        'id',
        'email',
        'deal_id',
        'person_id',
        'datetime',
    ];
}
