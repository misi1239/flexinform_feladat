<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, int $carId)
 */
class Service extends Model
{
    protected $fillable = ['id', 'client_id', 'car_id', 'log_number', 'event', 'event_time', 'document_id'];
    public $timestamps = false;
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
