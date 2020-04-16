<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Events;

class EventsDate extends Model
{
    //
    protected $table = 'events_date';
	protected $primaryKey = "id";
    public $timestamps = false;

    public function events()
    {
        return $this->belongsTo(Events::class, 'eventsId');
    }
}
