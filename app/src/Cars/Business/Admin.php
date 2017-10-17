<?php
namespace Cars\Business;

//Autoload function to automatically require classes we don't have
require_once(dirname(__DIR__) . "/../autoload-cars.php");

/*
 * Class to handle business methods for class selection
 * Extends the common business class
 */
class Admin extends Common {
    /*
     * Takes in department
     * Calls the Data method with the same name to return all users within the department given as the parameter
     */
	 function getUsersInDeptartment($dept) {
        $data = new \Cars\Data\Admin($this->app);
        return $data->getUsersInDeptartment($dept);
    }
    /*
     * Takes in department
     * Calls the Data method with the same name to return all users not within the department given as the parameter
     */
    function getUsersNotInDepartment($dept){
    	$data = new \Cars\Data\Admin($this->app);
        return $data->getUsersNotInDeptartment($dept);
    }
	/*
     * Takes in a specific RIT ID and department ID
	 * Checks if user is already in the department.
     * Calls the Data method of the same name if the user does not belong to the department and adds the user to the department given as a parameter
     */
    function addUserToDepartment($ritID,$deptID){
    	$data = new \Cars\Data\Admin($this->app);
        if($data->doesUserBelongToDepartment($ritID,$deptID) == 0){
            return $data->addUserToDeptartment($ritID,$deptID);
        }
    }
    /*
     * Takes in first name, last name, email, and RIT ID of a user to be added 
     * Calls the Data method of the same name to add a user with all the parameters to the database
     */
    function createUser($fName, $lName, $email, $ritID){
        $data = new \Cars\Data\Admin($this->app);
        return $data->createUser($fName, $lName, $email, $ritID);
    }
    /*
     * Takes in first name, last name, email, RIT ID, and department of a user to be added 
     * Calls the Data method createUser to add a user with all the parameters to the database
	 * Calls the Business method addUserToDepartment to add the user to the department given as a parameter
     */
	 function createUserInDept($fName, $lName, $email, $ritID, $dept){
        $data = new \Cars\Data\Admin($this->app);
        $data->createUser($fName, $lName, $email, $ritID);
        return $this->addUserToDepartment($ritID,$dept);
    }
	/*
     * Takes in a specific RIT ID and department ID
     * Calls the Data method removeUserFromDepartment to remove the user from the department given as a parameter
     */
    function removeUserFromDept($ritID, $dept){
        $data = new \Cars\Data\Admin($this->app);
        return $data->removeUserFromDepartment($ritID,$dept);
    }
	/*
     * Takes in a specific RIT ID and role ID
     * Calls the Data method of the same name to set the role given as the parameter to the specified user
     */
    function setRole($ritID, $roleID){
        $data = new \Cars\Data\Admin($this->app);
        return $data->setRole($ritID,$roleID);
    }
	/*
     * Takes in a department, course name, course number, course description, credit hours, class hours, and lab hours
	 * Checks if the course already exists in the database
     * Calls the Data method of the same name if the course does not already exist in the database and adds the course to the database with all the given parameters
	 * Throws an excpetion if the course already exists in the database
     */
    function addCourse($dept,$name,$number,$desc,$credits,$classhrs,$labhrs){
        $data = new \Cars\Data\Admin($this->app);
        $exisitCount = $data->doesCourseExisit($number,$dept);
        if($exisitCount[0]["count"] != 0){
            throw new \Cars\CarsException("A class with that number and department already exisits. {count = ".$exisitCount[0]["count"]."}", $this->app);
        }
        else{
            return $data->addCourse($dept,$name,$number,$desc,$credits,$classhrs,$labhrs);
        }
    }
	/*
     * Takes in an academic term, academic term description, and a status ID for the academic term
     * Calls the Data method of the same name to create an academic term with the given parameters
     */
    function createTerm($num,$desc,$statusID){
        $data = new \Cars\Data\Admin($this->app);
        return $data->createTerm($num,$desc,$statusID);
    }
	/*
     * Takes in an academic term and a status ID for the academic term
     * Calls the Data method of the same name to set the academic term given as a parameter to the specified academic term status
     */
    function setTermStatus($num,$statusID){
        $data = new \Cars\Data\Admin($this->app);
        return $data->setTermStatus($num,$statusID);
    }
	/*
     * Takes in department name and abbreviation
     * Calls the Data method of the same name to create a department with the given parameters
     */
    function newDepartment($name, $abbrev){
        $data = new \Cars\Data\Admin($this->app);
        return $data->newDepartment($name, $abbrev);
    }
	/*
     * Takes in program name, program code, degree type, and department ID
     * Calls the Data method of the same name to create a program with the given parameters
     */
    function newProgram($name, $code, $degree, $deptID){
        $data = new \Cars\Data\Admin($this->app);
        return $data->newProgram($name, $code, $degree, $deptID);
    }
	/*
     * Takes in a course ID, a section number, an academic term, if the section is offered online
	 * and if not: the days of the week the section is offered and the start time, end time, and room of the section on each week day it is offered
	 * Checks if the section already exists in the database
     * Calls the Data method of the same name if the section does not already exist in the database and adds the section to the database with all the given parameters
	 * Throws an excpetion if the section already exists in the database
     */
    function addSection($courseID,$sectionNum,$term,$online,$days,$start,$end,$room){
        $data = new \Cars\Data\Admin($this->app);
        $exisitCount = $data->doesSectionExisit($courseID,$sectionNum,$term);
        if($exisitCount[0]["count"] != 0){
            throw new \Cars\CarsException("A section with that number and term already exisits for the given class. {count = ".$exisitCount[0]["count"]."}", $this->app);
        }
        else{
            return $data->addSection($courseID,$sectionNum,$term,$online,$days,$start,$end,$room);
        }
    }
}