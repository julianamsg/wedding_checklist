<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'active',
        'period_id',
        'done'
    ];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
