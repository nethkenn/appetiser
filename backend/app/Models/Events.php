<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EventsDate;

class Events extends Model
{
    //
    protected $table = 'events';
	protected $primaryKey = "id";
    public $timestamps = false;

    public function eventDates()
    {
        return $this->hasMany(EventsDate::class, 'eventsId');
    }
}
