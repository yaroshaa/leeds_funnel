<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany};

/**
 * App\Models\Field
 *
 * @property int $id
 * @property int $pipedrive_id
 * @property string $key
 * @property string $type
 * @property string $name
 * @property string $appointment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Option[] $options
 * @property-read int|null $options_count
 * @method static \Illuminate\Database\Eloquent\Builder|Field newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Field newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Field query()
 * @method static \Illuminate\Database\Eloquent\Builder|Field whereAppointment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Field whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Field whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Field whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Field whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Field wherePipedriveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Field whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Field whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Field extends Model
{
    const QUALIFICATION = 12555;
    const NATIONALITY = 12564;
    const DISCIPLINE = 12499;

    protected $fillable = [
        'pipedrive_id',
        'name',
        'key',
        'type',
        'appointment',
    ];

    protected $with = [
        'options',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }
}
