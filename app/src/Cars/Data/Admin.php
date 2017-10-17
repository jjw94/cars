<?php
namespace Cars\Data;

//Autoload function to automatically require classes we don't have
require_once(dirname(__DIR__) . "/../autoload-cars.php");

/*
 * Class to handle business methods for class selection
 * Extends the common business class
 */
class Admin extends Common {
    
    /*
     * Returns all the users in a department
     */
    function getUsersInDepartment($dept){
    	$query = "SELECT users.first_name, users.last_name, user.rit_id, department.department_name FROM users
            JOIN user_department as ud ON ud.rit_id = users.rit_id
            JOIN department as d ON d.department_id = ud.department_id
            WHERE d.department_name = ?";
        return $this->makeParamQuery($query, array($dept));
    }
    
    /*
     * Should return all the users not in the department
     */
    function getUsersNotIntDepartment($dept){

    }
    /*
     * adds specified user to the specified department
     */
    function addUserToDeptartment($ritID, $deptID){
    	$query = "INSERT INTO users_department (rit_id, department_id) VALUES
            (?,?)";
        return $this->makeParamExecute($query, array($ritID, $deptID));
    }
    /*
     * returns the row count on seeing if the user id and department id fit for the user
     */
    function doesUserBelongToDepartment($ritID,$deptID){
        $query = "select * from users_department where rit_id = ? AND department_id = ?";
        return $this->makeParamExecute($query, array($ritID, $deptID));
    }
    /*
     * Creates the user in the database
     */
    function createUser($fName, $lName, $email, $ritID){
        $query = "INSERT INTO users(rit_id, first_name, last_name, email,role_id) VALUES (?,?,?,?,4)";

        $this->makeParamExecute($query, array( $ritID, $fName, $lName, $email));
    }
    /*
     * Removes a user from the inputted department
     */
    function removeUserFromDepartment($ritid,$dept){
        $query = "DELETE FROM users_department WHERE users_department.rit_id = ? AND users_department.department_id = ?";

        return $this->makeParamExecute($query, array($ritid,$dept));
    }
    /*
     * Sets the users role
     */
    function setRole($ritID, $roleID){
        $query = "UPDATE users SET role_id=? WHERE rit_id=?";

        return $this->makeParamExecute($query, array($roleID, $ritID));
    }
    /*
     * Adds the inputted course to the database
     */
    function addCourse($dept,$name,$number,$desc,$credits,$classhrs,$labhrs){
        $query = "INSERT INTO course (program_code, course_number, course_name, course_description, student_credits, class_contact_hours, lab_contact_hours) VALUES
            (?,?,?,?,?,?,?)";

        return $this->makeParamReturnID($query, array($dept,$number,$name,$desc,$credits,$classhrs,$labhrs));
    }
    /*
     * Returns the count of courses with the specified program code and course number
     */
    function doesCourseExisit($class,$dept){
        $query = "Select COUNT(*) as count from course where program_code = ? and course_number = ?";

        return $this->makeParamQuery($query, array($dept,$class));
    }
    /*
     * Adds a section to the database with the provided information
     */
    function addSection($courseID,$sectionNum,$term,$online,$days,$start,$end, $roomId){
         $query = "INSERT INTO section (course_id, section_number, academic_term, online) VALUES
            (?,?,?,?)";
            $this->app->logger->debug("PARAMS IS ONLINE?= "+$online);
         if($online ==0){
            $sectionID = $this->makeParamReturnID($query, array($courseID,$sectionNum,$term,$online));
            $dayArr = explode(',',$days);
            $results ="";
            foreach ($dayArr as $day) {
                $results += $this->enterSectionTime($sectionID, $day,$start,$end,$roomId)+",";
            }
            return $results;
         }
         else{
            return $this->makeParamExecute($query, array($courseID,$sectionNum,$term,$online));
         }
    }
    
    /*
     * Sets the status of the term
     */
    function setTermStatus($num,$statusID){
        $query = "UPDATE academic_term SET status = ? WHERE academic_term = ?";

        return $this->makeParamExecute($query,array($statusID,$num));
    }
    /*
     * Adds a term to the database
     */
    function createTerm($num,$desc,$statusID){
        $query = "INSERT INTO academic_term(academic_term, term_description, status) VALUES (?,?,?)";
        return $this->makeParamExecute($query,array($num,$desc,$statusID));
    }
    
    /*
     * Checks to see if the section exists
     */
    function doesSectionExisit($courseId, $sectionNum,$term){
        $query = "Select COUNT(*) as count from section where course_id = ? and section_number = ? and academic_term = ?";

        return $this->makeParamQuery($query, array($courseId,$sectionNum,$term));
    }

    /*
     * Adds a department to the database
     */
    function newDepartment($name, $abbrev){
        $query = "INSERT INTO department(department_name, department_abbreviation) VALUES (?,?)";

        return $this->makeParamExecute($query,array($name,$abbrev));
    }

    /*
     * Adds a program to the database
     */
    function newProgram($name, $code, $degree, $deptID){
        $query = " INSERT INTO program(program_code, program_name, degree, department_id) VALUES (?,?,?,?)";

        return $this->makeParamExecute($query, array($name, $code, $degree, $deptID));
    }

    /*
     * Adds a time to a section
     */
    function enterSectionTime($sectionID, $day,$start,$end,$room){
        /*$query = "INSERT INTO section_time (section_id, day_of_week,start_time,end_time,building_number,room_number) VALUES
            (?,?,?,?,?,?)";

         return $this->makeParamExecute($query, array($sectionID, $day,$start,$end,$bld,$room));*/
         $this->app->logger->debug("PARAMS = "+ $sectionID +" "+$day+" "+ $start+" "+$end+" "+$room);
         $query = "INSERT INTO section_time (section_id, day_of_week,start_time,end_time,room_id) VALUES
            (?,?,?,?,?)";

         return $this->makeParamExecute($query, array($sectionID, $day,$start,$end,$room));
    }
}
