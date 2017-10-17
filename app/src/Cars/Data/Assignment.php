<?php
namespace Cars\Data;

//Autoload function to automatically require classes we don't have
require_once(dirname(__DIR__) . "/../autoload-cars.php");

/*
 * Class to handle business methods for class selection
 * Extends the common business class
 */
class Assignment extends Common {
    /*
     * Assigns the user to the section
     */
    function addAssignment($user, $sectionId) {
        $query="INSERT INTO assignment (rit_id,section_id) VALUES (?,?)";
        return $this->makeParamExecute($query, array($user, $sectionId));
    }
    /*
     * returns all the assignments for a user
     */
    function getAssignmentsForUser($user){
        $query = "select * from assignment where rit_id =?";
        return $this->makeParamQuery($query, array($user));
    }
    /*
     * Returns all of the assignments for a section
     */
    function getAssignmentsForSection($section){
        $query = "select * from assignment where section_id =?";
        return $this->makeParamQuery($query, array($section));
    }
    /*
     * Checks to see if there is a conflict between what a user has already been assigned and what wanted to be assigned
     */
    function checkConflict($user, $sectionId){
        $query="select section_id from assignment where rit_id =?";
        $outputForReturn = array();
        $count = $this->makeParamExecute($query, array($user));
        if($count!=0){
            $assignments = $this->getAssignmentsForUser($user);
            $sectionTimes = $this->makeParamQuery("select section_id,day_of_week, start_time,end_time from section_time where section_id =?", array($sectionId));
            foreach($assignments as $asign){
                $sectionAssigned = $asign['section_id'];
                foreach ($this->makeParamQuery("select section_id,day_of_week,start_time,end_time from section_time where section_id=?", array($sectionAssigned)) as $alreadyAssignedSection){
                    foreach($sectionTimes as $sectionBeingChecked){
                        //echo("arr");
                        //print_r($alreadyAssignedSection);
                        //echo 'sect';
                        //print_r($sectionBeingChecked);

                        //print_r(var_dump($arr['day_of_week'] == $sect['day_of_week']));
                        //print_r(var_dump(DateTime::createFromFormat("H:i:s",$arr['start_time'])));
                        //print_r(($alreadyAssignedSection['day_of_week'] == $sectionBeingChecked['day_of_week']));
                        //print_r((date_parse_from_format("H:m:s",$alreadyAssignedSection['start_time']))<(date_parse_from_format("H:m:s",$sectionBeingChecked['start_time'])));
                        //print_r(var_dump((date_parse_from_format("H:m:s",$alreadyAssignedSection['end_time']))>(date_parse_from_format("H:m:s",$sectionBeingChecked['start_time']))));
                        if((($alreadyAssignedSection['day_of_week'] == $sectionBeingChecked['day_of_week'])==1)&&
                                (((date_parse_from_format("H:m:s",$alreadyAssignedSection['start_time']))<(date_parse_from_format("H:m:s",$sectionBeingChecked['start_time']))==1)||
                                ((date_parse_from_format("H:m:s",$alreadyAssignedSection['start_time']))<(date_parse_from_format("H:m:s",$sectionBeingChecked['end_time']))==1)) &&
                                ((date_parse_from_format("H:m:s",$alreadyAssignedSection['end_time']))>(date_parse_from_format("H:m:s",$sectionBeingChecked['start_time']))==1)){
                            $outputForReturn[]=$alreadyAssignedSection['section_id'];

                        }
                    }
                }
            }
            return $outputForReturn;
        }else{
            return $this->makeParamQuery($query, array($user));
        }
        //return $this->makeParamQuery($query, array($user,$sectionId));
    }
    /*
     * Returns all of the requests a user has
     */
    function getRequestsForUser($user){
        $query="select request.* from request where rit_id =?";
        return $this->makeParamQuery($query, array($user));
    }
    /*
     * Removes the assignment 
     */
    function removeAssignment($assignmentId) {
        $query="delete from assignment where assignment_id =?";
        return $this->makeParamExecute($query, array($assignmentId));
    }
    /*
     * Gets all requests for a section
     */
    function getRequests($sectionId) {
        $query="SELECT request.rit_id, users.first_name, users.last_name, request.request_order FROM request
        JOIN users ON request.rit_id = users.rit_id
        where request.section_id = ?";
        return $this->makeParamQuery($query, array($sectionId));
    }

    /*
     * Gets list of courses by program by level with a boolean stating if it has been assigned
     */
    function getAssignmentStatus($term,$course) {
        $query = "SELECT section.section_id, if(assignment.section_id is null,'false','true') as 'Exists'  from course
        join(section) on (course.course_id = section.course_id) left join assignment on section.section_id = assignment.section_id
        WHERE course.course_id = ? and section.academic_term =?";

        $sections = $this->makeParamQuery($query, array($course,$term));

        $exists = true;
        foreach($sections as $sec) {
            $bool = filter_var($sec['Exists'], FILTER_VALIDATE_BOOLEAN);
            $exists = $exists && $bool;
        }
        return $exists;
    }
}
