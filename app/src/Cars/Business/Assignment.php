<?php
namespace Cars\Business;

//Autoload function to automatically require classes we don't have
require_once(dirname(__DIR__) . "/../autoload-cars.php");

/*
 * Class to handle business methods for class selection
 * Extends the common business class
 */
class Assignment extends Common { 
	/*
     * Takes in a user and a section ID
	 * Checks if the section and user exist and if the user is able to be assigned to the section
	 * Checks for current section assignments and removes the assignments so the new assignment can be made
     * Calls the Data method addAssignment if all checks are met and assigns the section given as a parameter to the specified user
	 * Throws an excpetion if the assignment is not able to be made
     */
	 function assignSection($user, $sectionId) {
        $data = new \Cars\Data\Assignment($this->app);

        if(($data->isValidSection($sectionId)==1)&&($data->userExists($user)==1)&&($data->isValidSectionForUser($sectionId, $user)==0)){
            $curAssignment = $this->getAssignmentsForSection($sectionId);
            foreach ($curAssignment as $assign) {
                $this->unassignSection($assign['assignment_id']);
            }
            return $data->addAssignment($user, $sectionId);
        }else{
            throw new \Cars\CarsException("Invalid Assignment", $this->app);
        }
    }
	/*
     * Takes in a user and a section ID
	 * Checks if the section and user exist
     * Calls the Data method checkConflict to see if the user given as a parameter is already assigned to a section at a conflicting time
	 * Throws an excpetion if the user or section are invalid
     */
    function checkConflicts($user,$section){
        $data = new \Cars\Data\Assignment($this->app);
        if(($data->isValidSection($section)) && ($data->userExists($user))){
            return $data->checkConflict($user, $section);
        }else{
            throw new \Cars\CarsException("Invalid_user_or_section", $this->app);
        }
    }
	/*
     * Takes in a user ID
	 * Checks if user exists
     * Calls the Data method of the same name to return the requests made by the user given as the parameter
	 * Throws an excpetion if the user is invalid
     */
    function getRequestsForUser($user){
        $data = new \Cars\Data\Assignment($this->app);
        if($data->userExists($user)){
            return $data->getRequestsForUser($user);
        }else{
            throw new \Cars\CarsException("Invalid_User", $this->app);
        }
    }
	/*
     * Takes in a section ID
     * Calls the Data method of the same name to return the requests made for the section given as the parameter
	 * Throws an excpetion if the section is invalid
     */
    function getRequests($sectionId) {
        $data = new \Cars\Data\Assignment($this->app);

        //TODO - check that user has access to this section,
        if($data->isValidSectionForUser($sectionId, $_SESSION['cars-user']) == 0){
            return $data->getRequests($sectionId);
        }else{
            throw new \Cars\CarsException("Invalid_section_for_user", $this->app);
        }

    }
	/*
     * Takes in a program, a course ID, an academic term, and a user ID
     * Calls the Data method getRequests to list all the sections & their requests
	 * Calls the Data method getRequests to list all the sections & their assignments
     */
    function getRequestsByCourse($program, $courseId, $term, $user) {
        $sections = $this->getSectionsForFormatted($program,$courseId,0,$term,$user);
        foreach($sections as &$section) {
            $section['requests'] = $this->getRequests($section['section_id']);
            $section['assignment'] = $this->getAssignmentsForSection($section['section_id']);
        }
        return $sections;
    }
	/*
     * Takes in an assignment
	 * Calls the Data method removeAssignment to remove the assignment from the database
     */
    function unassignSection($assignmentId) {
        $data = new \Cars\Data\Assignment($this->app);
        return $data->removeAssignment($assignmentId);
    }
    function getAssignmentStatus($term,$user) {
        $data = new \Cars\Data\Assignment($this->app);
        $courses = $this->getCoursesByProgramByLevel($user);
        foreach($courses as &$prog) {
            foreach($prog as &$level) {
                foreach($level as &$course) {
                    $exists = $data->getAssignmentStatus($term,$course['course_id']);
                    $course['assigned'] = $exists;
                }
            }
        }
        return $courses;
    }
    /*
     * Takes in a user ID
 	 * Checks if user exists
	 * Calls the Data of the same name to get assignments for user in a given term
	 * Throws an excpetion if the user is invalid
     */
    function getAssignmentsForUser($user){
        $data = new \Cars\Data\Assignment($this->app);
        if($data->userExists($user)){
            return $data->getAssignmentsForUser($user);
        }else{
            throw new \Cars\CarsException("Invalid_User", $this->app);
        }
    }
    /*
     * Takes in a section ID
	 * Calls the Data of the same name to get assignments for section in a given term
	 * Throws an excpetion if the section is invalid
     */
    function getAssignmentsForSection($section){
        $data = new \Cars\Data\Assignment($this->app);
        if($data->isValidSectionForUser($section, $_SESSION['cars-user']) == 0){
            return $data->getAssignmentsForSection($section);
        }else{
            throw new \Cars\CarsException("Invalid_section_for_user", $this->app);
        }
    }
}
