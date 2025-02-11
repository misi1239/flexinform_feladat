<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    protected $fillable = ['client_id', 'car_id', 'type', 'registered', 'ownbrand', 'accidents'];
    public $timestamps = false;

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
