<?php
namespace Cars\Data;

class Common {
    function __construct($_app) {
        $this->app = $_app;
    }
    /*Checks to see if a user exists
    *returns the count of the users with the inputted username
    *should never return more than 1
     */
    function userExists($user){
	 $query="select users.rit_id from users where users.rit_id =?";
	 return $this->makeParamExecute($query,array($user));

    }
    /*
     * Takes in section and user name
     * Checks to see if the section is available to the user by checking the program of the section
     * against the valid programs for a user
     */
    function isValidSectionForUser($section,$user){
	$query="select section.* from section join (course, program,users_department) on (section.course_id=course.course_id and course.program_code = program.program_code and program.department_id = users_department.department_id) where users_department.rit_id = ? and section.section_id=?";
	return (count($this->makeParamQuery($query,array($user,$section)))>=1) ? 0:1;
    }
    /*
     * Checks to see if the inputted section id exists
     */
    function isValidSection($section){

	$query="select section.section_id from section where section.section_id = ?";
	return $this->makeParamExecute($query,array($section));
    }
    /*
     * Returns the users whos last names fit the like of the input
     */
    function getUsersByLastNameInit($init){
        $query = "select first_name, last_name, rit_id from users where last_name like ?";
        $initial = $init."%";
        return $this->makeParamQuery($query, array($initial));
    }
    /*
     * Returns the users in the inputted department whos lastnames fit in the like
     */
    function getUsersByLastNameInitAndDept($init,$dept){
        $query = "select first_name, last_name, users.rit_id from users join users_department on users.rit_id = users_department.rit_id where users.last_name like ? and users_department.department_id = ?";
        $initial = $init."%";
        return $this->makeParamQuery($query, array($initial, $dept));
    }
    /*
     * Retrieves all the departments a user has access to
     */
    function getUserDepartments($user) {
        $query = "SELECT users_department.department_id, department.department_name, department.department_abbreviation   FROM users_department
            JOIN department on users_department.department_id = department.department_id
            WHERE users_department.rit_id = ?";
        return $this->makeParamQuery($query, array($user));
    }
/*
 * Returns all the departments in the database
 */
    function getAllDepartments(){
        $query = "select * from department";

        return $this->makeQuery($query);
    }
    /*
     * Returns the department based on the department abreviation
     */
    function getDepartment($abbrev){
        $query = "select * from department where department_abbreviation = ?";

        return $this->makeParamQuery($query, array($abbrev));
    }
    /*
     * Returns all the programs that are in a department
     */
    function getProgramsInDepartment($deptID){
        $query = "select * from program where department_id = ? order by program_code asc";

        return $this->makeParamQuery($query, array($deptID));
    }
/*
 * Returns all the terms in the database
 */
    function getAllTerms(){
        $query = "select * from academic_term ORDER BY academic_term asc";
        return $this->makeQuery($query);
    }
    /*
     * Returns all of the possible term statuses
     */
    function getTermStatuses(){
        $query = "select * from status";

        return $this->makeQuery($query);
    }
    /*
     * returns the information for the term based on the term number
     */
    function getTerm($num){
        $query = "select * from academic_term left join status on academic_term.status = status.status where academic_term.academic_term = ?";

        return $this->makeParamQuery($query, array($num));
    }
    /*
     * returns all of the departments available to a user
     */
    function getUserDepartmentCodes($user){
        $query="select department.department_abbreviation from department join users_department on department.department_id = users_department.department_id where users_department.rit_id = ?";
        $out = $this->makeParamQuery($query, array($user));
        $return = array();
        foreach($out as $i){
            $return[]=$i["department_abbreviation"];
        }
        return $return;

    }
    /*
     * Returns all the program codes available to a user
     */
    function getUserProgramCodes($user){
        $query = "select program.program_code from program join (department, users_department) on (department.department_id=program.department_id  and users_department.department_id = department.department_id) where users_department.rit_id = ?";
        $out = $this->makeParamQuery($query, array($user));
        $return = array();
        foreach($out as $i){
            $return[]=$i["program_code"];
        }
        return $return;
    }
    /*
     * returns a user's role 
     */
    function getUserRole($user){
        $query = "SELECT role.role_name FROM users
            JOIN role ON users.role_id = role.role_id
            WHERE users.rit_id = ?";
        return $this->makeParamQuery($query, array($user));
    }
    /*
     * Return all the valid roles
     */
    function getAllRoles(){
        $query = "select * from role";

        return $this->makeQuery($query);
    }
    /*
     * return the role with the name
     */
    function getRoleID($name){
        $query = "select role_id from role where role_name = ?";

        return $this->makeParamQuery($query, array($name));
    }
    /*
     *  returns all the roles that are greater than the one provided
     */
    function getRolesGreaterThan($num){
        $query = "select * from role where role_id >= ?";

        return $this->makeParamQuery($query, array($num));
    }
    /*
     * Returns all the terms from the database
     */
    function getTerms() {
        return $this->makeQuery(
            "SELECT academic_term, term_description, status_description AS status
                FROM academic_term JOIN status ON academic_term.status = status.status");

    }
    /*
     * Returns all the terms with the provided status
     */
    function getTermByStatus($status){
        $query = "SELECT academic_term.academic_term, academic_term.term_description FROM academic_term
            JOIN status ON status.status = academic_term.status
            WHERE status.status_description = ?";

        return $this->makeParamQuery($query,array($status));
    }
    /*
     * Returns all of the building abbreviations
     */
    function getBuildingAbbreviations(){
        $query = "select building_abbreviation from building ORDER BY building_abbreviation ASC";

        return $this->makeQuery($query);
    }
    /*
     * Returns all of the rooms in the provided building
     */
    function getRoomsByBuilding($bld){
        $query = "select * from room where building_abbreviation = ? ORDER BY room_number ASC";

        return $this->makeParamQuery($query,array($bld));
    }
    /*
     * returns the room id of the room number provided in building provided
     */
    function getRoomId($bld, $room){
        $query = "select room_id from room where room_number = ? and building_abbreviation = ?";

        return $this->makeParamQuery($query,array($room,$bld));
    }
    /*
     * returns the details of the section with the provided section id
     */
    function getSectionDetails($sectionId){
        $query="select section.academic_term, section.section_number, course.program_code, course.course_number, course.course_name
	           from section join course on section.course_id = course.course_id
               where section.section_id = ?";
        $res = $this->makeParamQuery($query, array($sectionId));
        if(count($res) == 1) {
            return $this->makeParamQuery($query, array($sectionId))[0];
        }
        return null;
    }
    /*
     * Returns the time information for the provided section id
     */
    function getSectionTime($sectionId){
        $query="select * from section_time where section_id = ?";
        return $this->makeParamQuery($query, array($sectionId));
    }
    /*
     * returns if the inputted time for the inputted room is not already taken or overlaping
     */
    function isRoomOpen($bld,$room,$day,$start,$end,$term){


        $query = "SELECT
              *
            FROM
              room
            LEFT JOIN
              section_time ON room.room_id = section_time.room_id
            JOIN
              section ON section.section_id = section_time.section_id
            WHERE
              room.building_abbreviation = ? AND room.room_number = ? AND section.academic_term = ? AND section_time.day_of_week = ?
              AND
              (
                (
                  section_time.start_time >= ? AND section_time.end_time <= ?
                )
                OR
                (
                  (
                    section_time.start_time <= ? or section_time.start_time <= ?
                  )
                  AND section_time.end_time >= ?
                )
                OR
                (
                  section_time.start_time >= ?
                  AND
                  (
                    section_time.end_time <= ? AND section_time.end_time >= ?
                  )
                )
              )

          ";

        return $this->makeParamQuery($query,array($bld, $room, $term, $day,  $start, $end,$start, $end, $end,$end ,$start,$end));
    }
    /*
     * Returns the room information for a room based on the room ID
     */
    function getRoomByID($id){
        $query = "Select building_abbreviation, room_number from room where room_id = ?";
        return $this->makeParamQuery($query, array($id));
    }
    
    /*
     * Method used to make querys to the database with no parameters
     */
    function makeQuery($query) {
        try {
            //Prepare and log statement
            $stmt = $this->app->db->prepare($query);
            $this->app->logger->debug($stmt->queryString);

            //Execute statement and return escaped result
            $stmt->execute();
            $result = \Cars\Constants::escapeArr($stmt->fetchAll());
            return $result;
        } catch(\PDOException $e) {
            throw new \Cars\CarsException("Error on database query", $this->app, $e);
        }
    }
    /*
     * Method used to make querys to the database with parameters
     */
    function makeParamQuery($query, $params) {
        try {
            //Prepare and log statement
            $stmt = $this->app->db->prepare($query);
            $this->app->logger->debug($stmt->queryString . " | PARAMS: " . implode($params));

            //Execute and return escaped result
            $stmt->execute($params);
            $result = \Cars\Constants::escapeArr($stmt->fetchAll());
            return $result;
        } catch(\PDOException $e) {
            throw new \Cars\CarsException("Error on database query", $this->app, $e);
        }
    }
    /*
     * Executes inputted query and returns the number of rows modified or retrieved
     */
    function makeParamExecute($query, $params) {
        try {
            //Prepare and log statement
            $stmt = $this->app->db->prepare($query);
            $this->app->logger->debug($stmt->queryString . " | PARAMS: " . implode($params));

            //Execute and return escaped result
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch(\PDOException $e) {
            throw new \Cars\CarsException("Error on database query", $this->app, $e);
        }
    }
    /*
     * Preforms a query and returns the last insert ID
     */
    function makeParamReturnID($query, $params) {
        try {
            //Prepare and log statement
            $stmt = $this->app->db->prepare($query);
            $this->app->logger->debug($stmt->queryString . " | PARAMS: " . implode($params));

            //Execute and return escaped result
            $stmt->execute($params);
            return $this->app->db->lastInsertId();
        } catch(\PDOException $e) {
            throw new \Cars\CarsException("Error on database query", $this->app, $e);
        }
    }
    /*
     * returns the inputted user's data
     */
    function getUserData($user){
        $query = 'select users.first_name,users.last_name,users.email,role.role_name from users join role on users.role_id = role.role_id where users.rit_id = ?';
        return $this->makeParamQuery($query, array($user));
    }

}
