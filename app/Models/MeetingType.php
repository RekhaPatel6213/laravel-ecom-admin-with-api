<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingType extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'status',
        'sort_order',
    ];

    public const SEARCH_FIELDS = ['name'];

    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'type_id', 'id');
    }
}
