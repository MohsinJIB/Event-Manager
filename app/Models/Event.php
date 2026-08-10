<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['venue_id', 'title', 'description', 'starts_on', 'capacity'];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
