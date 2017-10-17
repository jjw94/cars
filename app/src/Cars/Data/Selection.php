<?php
namespace Cars\Data;

//Autoload function to automatically require classes we don't have
require_once(dirname(__DIR__) . "/../autoload-cars.php");

/*
 * Class to handle business methods for class selection
 * Extends the common business class
 */
class Selection extends Common {
    /*
     * Returns all the available courses by the Department Code ex. IST
     */
    function getCoursesByDepartment($dept,$archived) {
        $query = "select course.* from course join (program, department) on (course.program_code=program.program_code and  program.department_id=department.department_id) where department.department_abbreviation = ? and course.archived =?";
        return $this->makeParamQuery($query, array($dept,$archived));
    }
    /*
     * Returns the course and section information for the inputted course code ex. ISTE-120-1
     */
    function getCourseByCourseCode($program,$num,$section,$archived){
        $query="select course.*,section.*,section_time.* from course join (section,section_time) on (course.course_id=section.course_id and section.section_id = section_time.section_id) where course.program_code= ? and course.course_number=? and section.section_number = ? and course.archived=?";
        return $this->makeParamQuery($query, array($program,$num,$section,$archived));
    }
    /*
     * Returns all the available courses for the inputted Program code ex. ISTE
     */
    function getCoursesByProgram($program,$archived){
        $query="select course.* from course where course.program_code = ? and course.archived = ?";
        return $this->makeParamQuery($query, array($program,$archived));
    }
/*
 * Returns all the sections for the inputed program code ex. ISTE-120 during the inputted term ex. 2171
 */
    function getSectionsFor($program,$code,$term,$archived){
        $query="select section.*, section_time.* from section join (section_time, course) on (section.section_id= section_time.section_id and section.course_id = course.course_id) where course.program_code = ? and course.course_number = ? and course.archived=? and section.academic_term = ?";
        return $this->makeParamQuery($query, array($program,$code,$archived,$term));
    }
    /*
     * Returns the section info for the inputted course code ex ISTE-120-1
     */
    function getSectionInfo($program,$code,$term,$archived){
        $query="select section_id, section_number, course.program_code,course.course_number from section join course on section.course_id = course.course_id where course.program_code = ? and section.course_id = ? and course.archived = ? and section.academic_term = ? order by section.section_number asc";
        return $this->makeParamQuery($query, array($program,$code,$archived,$term));
    }
    /*
     * Returns the string true or false for whether the inputed term exists  in the database
     */
    function isValidTerm($term){
        $query ="select * from academic_term where academic_term.academic_term = ?";
       return ($this->makeParamExecute($query,array( $term))==1?'true':'false');
    }
    /*
     * Returns the course information for the course using the databases course Id which is shown from other calls
     * Purpose a shorter way of retrieving course information once you know the course Id
     */
    function getCourseById($id){
        $query="select * from course where course.course_id=?";
        return $this->makeParamQuery($query, array($id));
    }
    /*
     * Takes in the program ex. ISTE and the level ex 5 and whether or not the desired course is legacy or not
     * returns the course information from the database
     */
    function getCoursesByLevel($program,$level,$archived){
        $query="select course.* from course where course.program_code=? and course.course_number like concat(?,'%') and course.archived = ? order by course.course_number asc";
        return $this->makeParamQuery($query, array($program,$level,$archived));

    }
    /*
     * Takes in the section id and the Bucket|order and removes that request for the user 
     * returns the number of rows changed
     */
    function removeFromBucket($sectionId,$bucketorder,$user){
        $query = "delete from request where request.rit_id = ? and request.section_id = ? and request.request_order like ?";
        return $this->makeParamExecute($query, array($user,$sectionId,$bucketorder));
    }
    /*
     * Returns the request Information for the username in the parameter
     */
    function getBucket($user){
        $query="select request.* from request where rit_id = ?";
        return $this->makeParamQuery($query, array($user));
    }
    /*
     * Inserts the section Id into the bucket|order for the user
     */
    function addToBucket($sectionid,$bucketorder, $user) {
        $query="insert into request (rit_id,section_id,request_order) values (?,?,?)";
        return $this->makeParamExecute($query, array($user,$sectionid,$bucketorder));
    }
    /*
     * Will return the count (should be 1 or 0) 
     */
    function isValidIdForUser($id,$user){
        $query="select * from course join(program,users_department) on (course.program_code = program.program_code and program.department_id = users_department.department_id) where course.course_id = ? and users_department.rit_id=?";
        return $this->makeParamExecute($query, array($id,$user));
    }


}
