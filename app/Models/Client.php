<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find(int $clientId)
 * @method static where(string $string, string $string1, string $string2)
 */
class Client extends Model
{
    protected $fillable = ['id', 'name', 'card_number'];
    public $timestamps = false;

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
