<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\EventsDate;
use Request;
use Carbon\Carbon;

class EventsController extends Controller
{
    //
    public function saveEvent()
    {
    	//Delete events
  		Events::getQuery()->delete();

    	//Save event
    	$event = new Events();
    	$event->eventName = Request::input("eventName");
    	$event->save();

    	//Save date
    		//format date to Year-month-date
    	$dateFrom = date("Y-m-d", strtotime(Request::input('dateFrom')));
    	$dateEnd  = date("Y-m-d", strtotime(Request::input('dateEnd')));
    		//loop dates
    	while($dateFrom <= $dateEnd)
    	{	
    		//if day is included in the day type e.g Mon Tue
    		if(in_array(date('D',strtotime($dateFrom)),Request::input("dayType")))
    		{
	    		$eventsDate 	      = new EventsDate();
	    		$eventsDate->date 	  = $dateFrom;
	    		$eventsDate->eventsId = $event->id;
	    		$eventsDate->save();
    		}
   			//add day
    		$dateFrom = Carbon::parse($dateFrom)->addDay()->format("Y-m-d");
    	}

        return response(["message" => "Event Successfully Saved"]);
    }

    public function getMonthCalendar()
    {
    	//get all events
    	$events = Events::with('eventDates')->first();

    	//add default value
    	$year  	   	   = Request::has('year')  ? Request::input('year')  : 2018;
    	$month     	   = Request::has('month') ? Request::input('month') : 7;

    	//get total days in the month
    	$daysInMonth   = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    	//format calendar month
    	$calendarMonth = date("M", mktime(0, 0, 0, $month, 10)).' '.$year;

    	//get first and last day of the month to set a restriction on datepicker
    	$datePicker["minDate"]       = date($year.'-'.sprintf("%02d", $month).'-'.'01');
    	$datePicker["maxDate"]       = date($year.'-'.sprintf("%02d", $month).'-'.$daysInMonth);

    	//initialize array
    	$calendarArray = array();

    	//loop the dates
    	for ($i = 1; $i <= $daysInMonth;  $i++) 
    	{
    		$calendarArray[$i]["date"]     = date("Y-m-d",strtotime($year.'-'.$month.'-'.$i));	
    		$calendarArray[$i]["year"]     = $year;
    		$calendarArray[$i]["month"]    = $month; 
    		$calendarArray[$i]["day"]      = $i;
    		$calendarArray[$i]["dayType"]  = date('D', strtotime($calendarArray[$i]["date"]));

    		//if date exists in events add eventname to indicate that there is an event
    		if(!empty($events) && in_array($calendarArray[$i]["date"], collect($events->toArray()["event_dates"])->pluck('date')->toArray()))
    		{
    			$calendarArray[$i]["eventName"] = $events["eventName"];
    		}
    	}

        return response(["calendar" => $calendarArray, "calendarMonth" => $calendarMonth, "datePicker" => $datePicker]);
    }
}
