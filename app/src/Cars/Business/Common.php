<?php
namespace Cars\Business;

//Autoload function to automatically require classes we don't have
require_once(dirname(__DIR__) . "/../autoload-cars.php");

/*
 * Class to hold business layer functions common to all operations
 * This should be extended for all other business classes
*/
class Common {

    //Contructor - get a reference to the Slim application
    function __construct($_app) {
        $this->app = $_app;
    }
    //Returns the all of the Users in a formatted way for use by the front end
    function getUsersFormatted(){
        $data=new \Cars\Data\Common($this->app);
        $output = array();
        $char = 'A';
        while($char >= 'A' && $char <= 'Z' && strlen($char) == 1){
            $output[$char] = $data->getUsersByLastNameInit($char);
            $char++;
        }


        return $output;
    }
    //Returns all users with last names like the parameter
    function getUsersLastNameLike($in){
        $data=new \Cars\Data\Common($this->app);
        return $data->getUsersByLastNameInit($in);

    }
    //returns all users with last names like the parameter in the department given
    function getUsersLastNameLikeInDept($in, $dept){
        $data=new \Cars\Data\Common($this->app);
        return $data->getUsersByLastNameInitAndDept($in,$dept);

    }
    //Gets all the users marked to be in a specific department
    function getUsersInDept($dept) {
        $data=new \Cars\Data\Common($this->app);
        return $data->getUsersByLastNameInitAndDept("",$dept);
    }
    //returns the user data for the inputted username
    function getUserData($user){
        $data = new \Cars\Data\Common($this->app);
        return $data->getUserData($user);
    }

     //Get all terms
    function getTerms() {
        $data = new \Cars\Data\Common($this->app);
        return $data->getTerms();
    }

    //Get the department for a user
    function getUserDepartments($user) {
        $data = new \Cars\Data\Common($this->app);

        return $data->getUserDepartments($user);

    }
    //returns program information for the inputted department
    function getDepartment($abbrev){
        $data = new \Cars\Data\Common($this->app);

        $dept = $data->getDepartment($abbrev);
        $deptId = $dept[0]["department_id"];
        $programs = $data->getProgramsInDepartment($deptId);

        $output = array("deptInfo"=>$dept, "programs"=>$programs);
        return $output;
    }
    //Returns the departments that a user has access to
    function getUserDataWithDepartments($user){
        $data=new \Cars\Data\Common($this->app);
        $output = array('user-data' =>$data->getUserData($user), 'user-departments' =>$data->getUserDepartments($user));
        return $output;

    }
    //Get a user's role - returns null if user is not in the database
    function getUserRole($user){
        $data = new \Cars\Data\Common($this->app);
        $users = $data->getUserRole($user);
        //We should only ever have one user
        if(count($users) == 1) {
            return $users[0]['role_name'];
        } else if(count($users) == 0) {
            return null;
        } else {
            throw new \Cars\CarsException("Multiple users with same uid", $this->app);
        }
    }
    //Returns all the available roles
    function getAllRoles(){
        $data = new \Cars\Data\Common($this->app);
        return $data->getAllRoles();
    }
    // retrieves all the Roles below the inputted rolw
    function getLesserRoles($role){
        $data = new \Cars\Data\Common($this->app);

        $roleId = $data->getRoleID($role)[0]["role_id"];

        return $data->getRolesGreaterThan($roleId);
    }

    //Get all departments in the database
    function getAllDepartments() {
        $data = new \Cars\Data\Common($this->app);
        return $data->getAllDepartments();
    }
    // Returns all the terms with the specific status
    function getTermByStatus($status){
        $data = new \Cars\Data\Common($this->app);
        return $data->getTermByStatus($status);
    }
    //Return all of the available terms
    function getAllTerms(){
        $data = new \Cars\Data\Common($this->app);
        return $data->getAllTerms();
    }
    // returns all the available term statuses
    function getTermStatuses(){
        $data = new \Cars\Data\Common($this->app);
        return $data->getTermStatuses();
    }
    // Returns the term based off the term number
    function getTerm($num){
        $data = new \Cars\Data\Common($this->app);
        return $data->getTerm($num);
    }
    //Returns the all the courses available to the user organized by the course level
    function getCoursesByProgramByLevel($user){
        $common = new \Cars\Data\Common($this->app);
        $selection = new \Cars\Data\Selection($this->app);
        $programs = $common->getUserProgramCodes($user);
        $courses;
        foreach ($programs as $program) {
            for($i = 1;$i<=7;$i++){
                $courses[$program][$i.("00")] = $selection->getCoursesByLevel($program,$i,0);
            }
        }
        return $courses;
    }
    //retuns the sections for a program, course and term combination formatted for the front end
    function getSectionsForFormatted($program,$code,$term,$archived,$user){
        $data = new \Cars\Data\Selection($this->app);

        $sectionData = $data->getSectionInfo($program,$code,$archived,$term);
        $sections = array();

        if(sizeof($sectionData)>0){
            foreach ($sectionData as $section) {
                $sections[$section["section_number"]] = $section;
                $sectionTimes = $data->getSectionTime($section["section_id"]);
                $roomId;
                foreach ($sectionTimes as $sectionTime) {
                    $sections[$section["section_number"]]["section_times"][] = $sectionTime;
                    if(isset($sectionTime["room_id"])){
                        $roomId = $sectionTime["room_id"];
                    }

                }
                if(isset($roomId) && $roomId != ""){
                    $room = $data->getRoomByID($roomId);
                    $sections[$section["section_number"]]["building"] = $room[0]["building_abbreviation"];
                    $sections[$section["section_number"]]["room"] = $room[0]["room_number"];
                    unset($room);
                    unset($roomId);
                }
            }
        }

        return $sections;

    }
    // returns the all the rooms organized by the building
    function getAllRoomsByBuilding(){
        $common = new \Cars\Data\Common($this->app);
        $blds = $common->getBuildingAbbreviations();

        $output = array();
        foreach ($blds as $bld => $bldData) {
            $rooms = $common->getRoomsByBuilding($bldData["building_abbreviation"]);
            foreach ($rooms as $room => $val) {
                $output[$bldData["building_abbreviation"]][$val["room_number"]] = $val;
            }
        }

        return $output;
    }
    // returns the rooms that are available on the inputted time and organizes it by building
    function getOpenRoomsByBuilding($days, $start, $end, $term){
        //WIP
        $common = new \Cars\Data\Common($this->app);
        $blds = $common->getBuildingAbbreviations();
        $days = split(',', $days);
        $output = array();

        //return $common->isRoomOpen("GOL","2650","M",$start,$end,$term);

        foreach ($blds as $bld => $bldData) {
            $rooms = $common->getRoomsByBuilding($bldData["building_abbreviation"]);
            foreach ($rooms as $room => $val) {
                $open = true;
                foreach ($days as $day) {
                    if($common->isRoomOpen($bldData["building_abbreviation"],$val["room_number"],$day,$start,$end,$term) == 0){
                        $open = false;
                    }
                    if($open){
                        $output[$bldData["building_abbreviation"]][$val["room_number"]] = $val;
                    }
                }
            }
        }

        return $output;
    }
    // returns the room based on the room ID
    function getRoomByID($id){
        $common = new \Cars\Data\Common($this->app);
        return $common->getRoomByID($id);
    }

}
