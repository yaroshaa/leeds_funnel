<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Stage
 *
 * @property int $id
 * @property int $pipedrive_id
 * @property string $name
 * @property string $pipeline
 * @property int $order_nr
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Stage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stage query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stage studentLeads()
 * @method static \Illuminate\Database\Eloquent\Builder|Stage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stage whereOrderNr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stage wherePipedriveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stage wherePipeline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stage extends Model
{
    protected $fillable = [
        'pipedrive_id',
        'pipeline',
        'name',
        'order_nr',
    ];

    /* Scopes */

    public function scopeStudentLeads($builder)
    {
        return $builder->wherePipeline('StudentLeads');
    }
}
