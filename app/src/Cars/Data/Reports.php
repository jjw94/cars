<?php
namespace Cars\Data;

//Autoload function to automatically require classes we don't have
require_once(dirname(__DIR__) . "/../autoload-cars.php");

class Reports extends Common {
    function getTimesRoomUsedByDay($roomId, $term, $day){
    	$query = "select course.program_code, course.course_number,section.section_number, section_time.start_time,section_time.end_time from section_time
    	join section on section_time.section_id = section.section_id
    	join course on course.course_id = section.course_id
    	where section.academic_term = ?
    	and section_time.room_id = ?
    	and section_time.day_of_week = ?
    	order by section_time.start_time asc";

    	return $this->makeParamQuery($query,array($term,$roomId,$day));
    }

    function getUserSchedule($user,$term, $day){
        $query = "select course.program_code, course.course_number,section.section_number, section_time.start_time,section_time.end_time, room.building_abbreviation, room.room_number from section_time
        join section on section_time.section_id = section.section_id
        join course on course.course_id = section.course_id
        join assignment on assignment.section_id = section.section_id
        join room on room.room_id = section_time.room_id
        where section.academic_term = ?
        and section_time.day_of_week = ?
        and assignment.rit_id = ?
        order by section_time.start_time asc";

        return $this->makeParamQuery($query,array($term,$day,$user));
    }
}