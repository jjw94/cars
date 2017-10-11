# API Description
The API utilizes Rest get routes to return requested information to the caller.  
The calls are split into 6 groups of calls with several more calls that do not fit into any of the groups.  
All calls start from all URL's start from https://white.ist.rit.edu/api where /{}/ indicates an input.  
The groups and their calls are as follows:  
<table>
            <tr>
                <td><h4>Group</h4></td>
                <td><h4>Method Name</h4></td>
                <td><h4>Information</h4></td>
            </tr>
            <tr>
                <td><h5>Users</h5></td>
                <td>Get Users by Last Name</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/users/lastName/{query}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Returns users where the Last Name is like {query}</li>

                            </ul></li>
                            <li>Example Input<ul>
                                <li>/users/lastName/Caynoski</li>
                                </ul></li>

                                <li> Output<ul>
                                    <li>First Name</li>
                        <li>Last Name</li>
                        <li> RIT ID</li>
                                    </ul></li>

                    </ul></td>
            </tr>
            <tr>
                <td><h5>Users</h5></td>
                <td>Get Users by Department</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/users/department/{deptCode}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Returns users existing in the specified department</li>

                            </ul></li>
                            <li>Example Input<ul>
                                <li>/users/department/1</li>
                                </ul></li>

                                <li> Output<ul>
                                    <li>First Name</li>
                        <li>Last Name</li>
                        <li> RIT ID</li>
                                    </ul></li>

                    </ul></td>
            </tr>
            <tr><td></td><td></td><td></td></tr>
            <tr>
                <td><h5>Classes</h5></td>
                <td>Retrieve Course by Course Id</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/classes/byCourseId/{id}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves the course information for the course with the Id inputed</li>

                            </ul></li>
                            <li>Example Input<ul>
                                <li>Any Valid Integer</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Classes</h5></td>
                <td>Retrieve Sections for Course</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/classes/sectionsFor/{program}/{code}/{term}/{archived}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves all the sections for the course relating to the provided program and course code in the supplied term</li>

                            </ul></li>
                            <li>Example Input<ul>
                                <li>/classes/sectionsFor/ISTE/422/2171/0</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Classes</h5></td>
                <td>Retrieve Sections for Course Formatted</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/classes/sectionsForFormatted/{program}/{courseId}/{term}/{archived}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Returns a formatted output of all the sections for the inputted course</li>

                            </ul></li>
                            <li>Example Input<ul>
                                <li>/classes/sectionsForFormatted/ISTE/3/2171/0</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Classes</h5></td>
                <td>Retrieve Courses By Level</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/classes/byLevel/{program}/{level}/{archived}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Returns all the courses for a program within a specified level</li>
                            <li>Note Level requires only the initial didgit of a level ex 7 for 700</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/classes/byLevel/ISTE/4/0</li>
                                </ul></li>



                    </ul></td>
            </tr>

            <tr>
                <td><h5>Classes</h5></td>
                <td>Retrieve Courses By Program Code</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/classes/byProgramCode/{program}/{archived}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves all the active courses for a Program code</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/classes/byProgramCode/ISTE/0</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Classes</h5></td>
                <td>Retrieve Courses By Course Code</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/classes/byCourseCode/{program}/{number}/{section}/{archived}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves a course based off the program code, course number, section number combination</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/classes/byCourseCode/ISTE/120/1/0</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Classes</h5></td>
                <td>Retrieve Courses By Program and Level</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/classes/byProgram/byLevel/{user}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves the courses by program and level for a user</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/classes/byProgram/byLevel/cvc6644</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Classes</h5></td>
                <td>Retrieve Courses By Department</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/classes/{department}/{archived}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves all the active courses for a Department</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/classes/IST/0</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr><td></td><td></td><td></td></tr>
            <tr>
                <td><h5>Bucket/Request</h5></td>
                <td>Get Requests for Logged in User</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/bucket/getBucket/{term}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves all the active requests for the logged in user</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/bucket/getBucket/2171</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Bucket/Request</h5></td>
                <td>Remove Section from Current User's Requests</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/bucket/removeFromBucket/{sectionId}/{bucket}/{requestOrder}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Removes the indicated section from the current user's requests</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/bucket/removeFromBucket/1/1/1</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Bucket/Request</h5></td>
                <td>Add Section to Current User's Requests</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/bucket/addToBucket/{sectionId}/{bucket}/{requestOrder}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Adds the indicated section to the current user's requests</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/bucket/addToBucket/1/1/1</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr><td></td><td></td><td></td></tr>
            <tr>
                <td><h5>Terms</h5></td>
                <td>Status</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/terms/{status}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Returns the Terms containing the inputted status</li>
                            </ul></li>
                            <li>Valid Input<ul>
                                <li>Under Development</li>
                                <li>Open for Requests</li>
                                <li>Open for Assignment</li>
                                <li>Closed</li>
                                </ul></li>
                      <li>Output<ul>
                                <li>Academic Term</li>
                                <li>Term Description</li>

                                </ul></li>  


                    </ul></td>
            </tr>
            <tr>
                <td><h5>Terms</h5></td>
                <td>Get Terms</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/terms/</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Returns all of the known Terms</li>
                            </ul></li>

                      <li>Output<ul>
                                <li>Academic Term</li>
                                <li>Term Description</li>
                                <li>Term Status</li>
                                </ul></li>  


                    </ul></td>
            </tr>
            <tr><td></td><td></td><td></td></tr>
            <tr>
                <td><h5>Assignment</h5></td>
                <td>Retrieve Assigmnment Status</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/assignment/courses</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves the Assignment status for all the courses available to current user</li>
                            </ul></li>




                    </ul></td>
            </tr>
            <tr>
                <td><h5>Assignment</h5></td>
                <td>Retrieve Assigmnment for User</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/assignment/assigned/{user}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves all the assignments for the requested user</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/assignment/assigned/cvc6644</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Assignment</h5></td>
                <td>Retrieve Requests for User</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/assignment/requests/forUser/{userId}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves all the requests for a specified user</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/assignment/requests/forUser/cvc6644</li>
                                </ul></li>



                    </ul></td>
            </tr>

            <tr>
                <td><h5>Assignment</h5></td>
                <td>Retrieve Requests for Section</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/assignment/requests/{sectionId}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves all the requests for a section</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/assignment/requests/2</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Assignment</h5></td>
                <td>Check for Assignment Conflict for User and Section</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/assignment/checkConflict/{user}/{sectionId}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Checks to see if the user has any conflicts to being assigned to the provided section</li>
                            <li>Returns the id of the conflicting sections</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/assignment/checkConflict/cvc6644/2</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Assignment</h5></td>
                <td>Assign User to Section</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/assignment/add/{user}/{sectionId}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Assigns the user to the given section</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/assignment/add/cvc6644/2</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Assignment</h5></td>
                <td>Remove Assignment for User and Section</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/assignment/remove/{assignmentId}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Removes the specified assignment</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/assignment/remove/3</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr><td></td><td></td><td></td></tr>
            <tr>
                <td><h5>Admin</h5></td>
                <td>Add Course</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/admin/add/course/{dept}/{name}/{number}/{description}/{credits}/{classHours}/{labHours}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Adds a course to the database with the provided information</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/admin/add/course/IST/ExampleName/120/ExampleDescription/3/5/4</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Admin</h5></td>
                <td>Add Section to Course</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/admin/add/course/section/{courseId}/{sectionNum}/{term}/{online}/{days}/{start}/{end}/{building}/{room}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Adds a section for the given course</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/admin/add/course/section/1/1/2171/0/MWF/08:00:00/10:00:00/GOL/1445</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Admin</h5></td>
                <td>Add Department for User</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/admin/addUserDepartment/{user}/{department}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Marks a user able to access information for supplied department</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/admin/addUserDepartment/cvc6644/CS</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Admin</h5></td>
                <td>Set User's Role</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/admin/setRole/{user}/{role}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Sets the role for a user</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/admin/setRole/cvc6644/1</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr>
                <td><h5>Admin</h5></td>
                <td>Retrieve Info for User</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/admin/UserInfo/{user}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Retrieves the info for a supplied User</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/admin/UserInfo/cvc6644</li>
                                </ul></li>



                    </ul></td>
            </tr>
            <tr><td></td><td></td><td></td></tr>
            <tr>
                <td><h5>Miscellaneous</h5></td>
                <td>Return Current User's Info</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/UserInfo</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Returns the info of the currently active user</li>
                            </ul></li>




                    </ul></td>
            </tr>
            <tr>
                <td><h5>Miscellaneous</h5></td>
                <td>Return Departments for User</td>
                <td><ul>
                        <li> URL
                            <ul>
                                <li>/department/{user}</li>
                            </ul>
                        </li>  

                        <li> Description<ul>
                            <li>Returns the departments the specified user has access to</li>
                            </ul></li>
                            <li>Example Input<ul>
                                <li>/department/cvc6644</li>
                                </ul></li>



                    </ul></td>
            </tr>
        </table>
