<?php
namespace Cars\Business;
//Autoload function to automatically require classes we don't have
require_once(dirname(__DIR__) . "/../autoload-cars.php");
/*
 * Class to handle business methods for class selection
 * Extends the common business class
 */
class Selection extends Common {
    /*
     * Takes in the department, whether you want achived courses or not, and the requesting user
     * Checks if the department code is visible for the User
     * Calls the Data method with the same name to return the course information for the Inputted Department.
     */
    function getCoursesByDepartment($dept,$archived, $user) {
        $data = new \Cars\Data\Selection($this->app);
        if(in_array($dept, $data->getUserDepartmentCodes($user))){
            return $data->getCoursesByDepartment($dept,$archived);
        }else{
            throw new \Cars\CarsException("invalid_department_for_user ", $this->app);
        }

    }
    /*
     * Takes in the program code, the Course Number, the section number, whether you want achived courses or not, and the requesting user
     * Checks if the program code is valid for the user
     * Calls the Data method with the same name to return the course and section information for the inputted information.
     */
    function getCourseByCourseCode($program,$num,$section,$archived,$user){
        $data = new \Cars\Data\Selection($this->app);
        //add business check when home
        if(in_array ($program, $data->getUserProgramCodes($user))){
            return $data->getCourseByCourseCode($program, $num, $section,$archived);
        }else{
            throw new \Cars\CarsException("invalid_program_for_user", $this->app);
        }
    }
    /*
     * Takes in a Program code, whether archived courses are wanted or not, and the requesting user
     * Checks if the program code is valid for the user
     * Calls the Data method with the same name to return the course information for all the courses in the inputted program.
     */
    function getCoursesByProgram($program,$archived,$user){
        $data = new \Cars\Data\Selection($this->app);
        if(in_array ($program, $data->getUserProgramCodes($user))){
            return $data->getCoursesByProgram($program,$archived);
        }else{
            throw new \Cars\CarsException("invalid_program", $this->app);
            
        }
    }
   /*
     * Takes in the program code, the course level ex. 5 for 500,  whether you want achived courses or not, and the requesting user
     * Checks if the program code is valid for the user
     * Calls the Data method with the same name to return the information about all the courses within a level for inputted program.
     */
    
    function getCoursesByLevel($program,$level,$archived,$user){
        $data = new \Cars\Data\Selection($this->app);
        //add business check when home
        if(in_array ($program, $data->getUserProgramCodes($user))){
            return $data->getCoursesByLevel($program, $level,$archived);
        }else{
           
            
            throw new \Cars\CarsException("invalid_program_for_user", $this->app);
        }
    }
    /*
     * Takes in the course Id, and the requesting user
     * Checks if the course is a valid course for the user
     * Calls the Data method with the same name to return the course information for the Inputted course ID.
     */
    function getCourseById($id,$user){
        $data = new \Cars\Data\Selection($this->app);
        if($data->isValidIdForUser($id, $user)==1){
            return $data->getCourseById($id);
        }else{
            
            throw new \Cars\CarsException("invalid_id_for_user", $this->app);
        }

    }
     /*
     * Takes in the user and term 
     * Calls the Data method with the same name to return all the current requests for the user 
     */
    function getBucket($user, $term){
        $data = new \Cars\Data\Selection($this->app);
        $requests = $data->getBucket($user);
        $bucket = array();

        //Get the number of courses and preferences from the config file
        $numCourses = $this->app['settings']['numCourses'];
        $numPrefs = $this->app['settings']['numPreferences'];


        //Create an array with set numbers of courses and preference levels
        for($i=0; $i<$numCourses; $i++) {
            $bucket[$i] = array();
            for($j=0; $j<$numPrefs; $j++) {
                $bucket[$i][$j] = null;
            }
        }
        //Loop through all of a user's requests
        foreach($requests as $request) {
            //Loop through all requests to see if they're from this terms
            $secDetails = $data->getSectionDetails($request['section_id']);
            if($term == $secDetails['academic_term']) {
                $reqOrder = explode(".", $request['request_order']);
                $bucket[$reqOrder[0]-1][$reqOrder[1]-1] = array(
                        'request_id' => $request['request_id'],
                        'section_id' => $request['section_id'],
                        'program_code' => $secDetails['program_code'],
                        'course_number' => $secDetails['course_number'],
                        'section_number' => $secDetails['section_number']
                );
            }
        }

        return $bucket;

    }
    /*
     * Takes in the section ID, the bucket, request order, and the requesting user
     * Calls the Data method with the same name to remove the inputted section in the inputted bucket and request order for the inputted user.
     */
    function removeFromBucket($sectionId,$bucket,$requestOrder,$user){
        $data = new \Cars\Data\Selection($this->app);

        return $data->removeFromBucket($sectionId,  floatval($bucket.".".$requestOrder),$user)==1?"Removal_Successful":"Removal_Unsuccessful";
    }
    /*
     * Takes in the section ID, the desired bucket, the desired request order and the requesting user
     * Checks if the section exists and whether or not the section is available to the user
     * Calls the Data method with the same name to add the section to the users request and returns sucess or falure.
     */
    function addToBucket($sectionId,$bucket,$requestOrder, $user) {
        $data = new \Cars\Data\Selection($this->app);

        //Get the number of courses and preferences from the config file
        $numCourses = $this->app['settings']['numCourses'];
        $numPrefs = $this->app['settings']['numPreferences'];

        //add business check when home
        if(($data->isValidSection($sectionId)==1) && ($data->isValidSectionForUser($sectionId,$user)==0)){
            if(floatval($bucket <= $numCourses)) {
                //If the preference is over the allowed amount, set it to the largest
                if(floatval($requestOrder) > $numPrefs) {
                    $requestOrder = $numPrefs;
                }

                //If there is a current request for this preference level, remove it
                $data->removeFromBucket($sectionId,  floatval($bucket.".".$requestOrder),$user);

                //Add new request to bucket
               return  $data->addToBucket($sectionId,  floatval($bucket.".".$requestOrder), $user)==1?"Addition_Success":"Addition_Falure";
           } else {
               throw new \Cars\CarsException("invalid bucket number", $this->app);
           }
        }else{
            throw new \Cars\CarsException("invalid section", $this->app);
        }
    }
}
