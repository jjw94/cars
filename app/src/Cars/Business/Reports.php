<?php
namespace Cars\Business;

//Autoload function to automatically require classes we don't have
require_once(dirname(__DIR__) . "/../autoload-cars.php");

class Reports extends Common {
    function getRoomSchedule($bld,$room, $term){
    	$data = new \Cars\Data\Reports($this->app);
    	$roomid = $data->getRoomId($bld,$room);
    	$roomid = $roomid[0]["room_id"];

    	$output = array("monday" => $data->getTimesRoomUsedByDay($roomid, $term, "M"),
    		"tuesday" => $data->getTimesRoomUsedByDay($roomid, $term, "T"),
    		"wednesday" => $data->getTimesRoomUsedByDay($roomid, $term, "W"),
    		"thursday" => $data->getTimesRoomUsedByDay($roomid, $term, "TH"),
    		"friday" => $data->getTimesRoomUsedByDay($roomid, $term, "F"),
    		"saturday" => $data->getTimesRoomUsedByDay($roomid, $term, "S"));

    	return $output;
    }

    function getUserSchedule($user,$term){
        $data = new \Cars\Data\Reports($this->app);

        $output = array("monday" => $data->getUserSchedule($user, $term, "M"),
            "tuesday" => $data->getUserSchedule($user, $term, "T"),
            "wednesday" => $data->getUserSchedule($user, $term, "W"),
            "thursday" => $data->getUserSchedule($user, $term, "TH"),
            "friday" => $data->getUserSchedule($user, $term, "F"),
            "saturday" => $data->getUserSchedule($user, $term, "S"));

        return $output;
    }
}