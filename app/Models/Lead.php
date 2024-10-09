<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Lead
 *
 * @property int $id
 * @property int|null $deal_id
 * @property int|null $person_id
 * @property string|null $lead_user_id
 * @property int|null $user_id
 * @property int $credits
 * @property string|null $state
 * @property string $channel
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static Builder|Lead fromPipedrive()
 * @method static Builder|Lead newModelQuery()
 * @method static Builder|Lead newQuery()
 * @method static Builder|Lead query()
 * @method static Builder|Lead whereChannel($value)
 * @method static Builder|Lead whereCreatedAt($value)
 * @method static Builder|Lead whereCredits($value)
 * @method static Builder|Lead whereData($value)
 * @method static Builder|Lead whereDealId($value)
 * @method static Builder|Lead whereId($value)
 * @method static Builder|Lead whereLeadUserId($value)
 * @method static Builder|Lead wherePersonId($value)
 * @method static Builder|Lead whereState($value)
 * @method static Builder|Lead whereUpdatedAt($value)
 * @method static Builder|Lead whereUserId($value)
 * @mixin \Eloquent
 */
class Lead extends Model
{
    const PIPEDRIVE = 'pipedrive';
    const FACEBOOK = 'facebook';
    const INTERCOM = 'intercom';
    const LEADGEN = 'leadgen';

    protected $fillable = [
        'deal_id',
        'person_id',
        'lead_user_id',  // Intercom user Id
        'user_id',
        'credits',
        'state',
        'channel',
        'data',
    ];

    /* Relationships */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* Scopes */

    public function scopeFromPipedrive(Builder $builder): Builder
    {
        return $builder
            ->where('channel', self::PIPEDRIVE)
            ->whereNotNull('deal_id');
    }
}
