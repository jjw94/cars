<?php
    require_once("_Layouts.php");
    getHeader("Course Assignment");
?>
<div class="mainTermSel">
    <select class="form-control" id="term" name="term" onchange="changeTerm()">
        <?php
            if(isset($assignmentTerms)){
                $terms = json_decode($assignmentTerms,true);
                if(count($terms) != 0) {
                    foreach ($terms as $term) {
                        echo "<option value='".$term["academic_term"]."'>".$term["term_description"]."</option>";
                    }
                } else {
                    echo "<option value='0'>No Terms Available</option>";
                }
            }
        ?>
    </select>
</div>
<table class="full selection">
    <tbody>
        <tr>
            <td class="vert-top">
                <div class='module' style="width:300px;">
                    <h2 class="title">Courses</h2>
                    <div class="container-inner">
                        <select  id="program-select" style="width:100%" onchange="getCourses()">
                            <?php
                                var_dump($programs);
                                        if(isset($programs)){
                                            $programCodes = json_decode($programs,true);
                                            foreach (array_keys($programCodes) as $programCode) {
                                                echo "<option value='".$programCode."'>".$programCode."</option>";
                                            }
                                        }
                                    ?>
                        </select>
                        <ul class="course-levels">

                        </ul>
                </div>
            </td>
            <td class="vert-top">
                 <div id="assignmentMod" class='module' style="position:relative">
                    <h2 class="title">Assignment</h2>
                    <div id="section-list" style='min-height:425px; max-height: 500px; overflow-y: scroll;' class="container-inner section-list">
                        <div class='helptext'>Select one or more courses to assign sections</div>
                    </div>
                </div>
            </td>

        </tr>
    </tbody>
</table>

<?php
    getFooter();
?>
<script>
    currentCourse="";
    var arr;
    var selectedCourses = [];
    var users = <?php echo $users?>;

    function changeTerm() {
        getCourses();
        $.each(selectedCourses, function(index, course) {
            $(course).removeClass("selected");
        });
        selectedCourses = [];
        $("#section-list").empty().append("<div class='helptext'>Select one or more courses to assign sections</div>");
    }

    function getCoursesByProgramHTML(courses, program){
        arr = courses[program];
        courseHTML = "";
        $.each(arr, function(level, levelVal){
            courseHTML += "<li class='course-levels' >";
                courseHTML += "<div class='title'data-status='closed' onclick='levelAccordian(this)''>";
                    courseHTML += "<h2>" + level + " Level</h2>";
                    courseHTML += '<div class="drop-arrow"><i class="fa fa-chevron-down"></i></div>';
                courseHTML +="</div>"
                courseHTML += "<ul class= 'course-list'>";
                $.each(levelVal, function(course, courseVal){
                    courseHTML += "<li class='assign' onclick='selectCourse(this)' data-program='" + program + "' data-course='" + courseVal.course_id + "'><h4>"
                    + courseVal.program_code +"-" +courseVal.course_number +": "+ courseVal.course_name + "</h4>";
                    if(courseVal.assigned) {
                        courseHTML += "<div class='assignStatus'><i class='fa fa-check'></i></div>";
                    } else {
                        courseHTML += "<div class='assignStatus'><i class='fa fa-circle'></i></div>";
                    }
                    courseHTML += "</li>";
                })
                courseHTML += "</ul>";
            courseHTML += "</li>";
        });
        return courseHTML;
    }

    function getCourses(){
        list = $("ul.course-levels");
        list.empty();

        //Get new courses list
        var term = $("#term").val();
        asyncAjax('/api/assignment/courses/'+term, function(data) {
            var program = $("#program-select").val();
            list.append(getCoursesByProgramHTML(data, program));
        });
    }

    function levelAccordian(ele){
        ele = $(ele);
        if(ele.data("status") == "closed"){
            ele.data("status","open");
            ele.find(".drop-arrow").empty().append("<i class='fa fa-chevron-up'></i>");
            ele.parent().find("ul.course-list").css("display","block");
        }
        else{
            ele.data("status","closed");
            ele.find(".drop-arrow").empty().append("<i class='fa fa-chevron-down'></i>");
            ele.parent().find("ul.course-list").css("display","none");
        }
    }

    function selectCourse(selected) {
        // var course = selected.getAttribute("data-course");
        var course = $(selected).attr("data-course");

        //If this course isn't already selected
        if(selectedCourses[course] == undefined) {
            //If this is the fist section, remove the help text
            $("#section-list .helptext").hide();

            //Select it and add sections to box
            var program = $(selected).attr("data-program");
            var term = $("#term").val();
    		asyncAjax("/api/assignment/requests/"+program+"/"+course+"/"+term, function(data){
                getSectionsHTML(data, course);
    		});

            $(selected).addClass("selected");
            selectedCourses[course] = selected;
        } else {
            //Deselct it and remove sections from box
            $(".assignCourse-" + course).remove();
            $(selected).removeClass("selected");
            delete selectedCourses[course];

            //If this is the last section, add the help text
            if(Object.keys(selectedCourses).length === 0) {
                $("#section-list .helptext").show();
            }

        }
    }

    function getSectionsHTML(sections, courseId){
        sectionHTML =""
        if(sections != ""){
            //For each section
            $.each(sections,function(section, sectionVal){
                //Get section times out of the data
                sectionTimeArr = sectionVal.section_times;

                //Get requests and format as one per user
                var requestsArr = sectionVal.requests;
                var reqById = {};
                $.each(requestsArr, function(index, request) {
                    var userId = request.rit_id;
                    if(!(userId in reqById) || reqById[userId] == undefined) {
                        reqById[userId] = [];
                    }
                    reqById[userId].push(request);
                });

                //Get the userid of the assignment
                var assignedId = undefined;
                var assignmentId = undefined;
                if(sectionVal.assignment.length > 0) {
                    assignedId = sectionVal.assignment[0].rit_id;
                    assignmentId = sectionVal.assignment[0].assignment_id;
                }

                //Make the HTML
                sectionHTML += "<li data-sectionId='" + sectionVal.section_id + "'class='section assign assignCourse-" + courseId + "'>";
                    sectionHTML += "<table class='assignTbl'><tr><td class='vert-top'>";
                    sectionHTML += "<ul class='section-info'>";
                        sectionNum = sectionVal.section_number;
                        if(sectionNum < 10){
                            sectionNum = "0"+sectionNum;
                        }
                        sectionHTML += "<li><h4>"+sectionVal.program_code+"-"+sectionVal.course_number+"."+sectionNum+"</h4></li>"
                        //Each section time
                        if(sectionTimeArr != null && sectionTimeArr.length > 0){
                            $.each(sectionTimeArr, function(id, sectionTime){
                                if(id != "building" && id !="room")
                                sectionHTML +="<li>" + sectionTime.day_of_week +": "+sectionTime.start_time+"-"+sectionTime.end_time+"</li>"
                            });
                        }
                        else{
                            sectionHTML +="Online Class";
                        }
                        if(sectionVal.building != null && sectionVal.room != null){
                            sectionHTML +="<li>Room: "+sectionVal.building+"-"+sectionVal.room+"</li>";
                        }

                    sectionHTML += "</ul></td><td class='vert-top'>";
                    sectionHTML += "<ul class='requests'><li><h4>Requests</h4></li>";

                    //Keep track if we've found the assigned user in requests
                    var usrFound = false;

                    //If class is unassigned, check the no assignment button
                    if(assignedId == undefined) {
                        sectionHTML += "<li><input type='radio' checked name='reqRad-"+sectionVal.section_id+"'>No assignment</input></li>";
                        usrFound = true;
                    } else {
                        sectionHTML += "<li><input type='radio' onclick='removeAssignment("+assignmentId+")' name='reqRad-"+sectionVal.section_id+"'>No assignment</input></li>";
                    }

                    //Each requesting faculty
                    $.each(reqById, function(id, requests){
                        //Check if this is the current assigned faculty
                        var checked = "";
                        if(assignedId == id) {
                            checked = "checked";
                            usrFound = true;
                        }
                        sectionHTML +="<li><input type='radio' "+checked+" name='reqRad-"+sectionVal.section_id+"' onclick='assignSection(\"" +id+"\","+sectionVal.section_id+")'>"
                        + requests[0].first_name +" "+requests[0].last_name+"</input></li>";
                        sectionHTML +="<li><ul class='reqPrefList'>";

                        //Each request preference
                        $.each(requests, function(id, request) {
                            var preference = request.request_order.split(".");
                            sectionHTML += "<li>Course: " + preference[0] + ", Preference: " + preference[1] + "</li>";
                        });
                        sectionHTML += "</ul></li>";
                    });

                    //Add a write in section
                    //If we've already found the assigned user, we don't need to bother looking for them in write-in
                    if(usrFound) {
                        sectionHTML += "<li><input type='radio' name='reqRad-"+sectionVal.section_id+"'>Write In</input></li><li class='sel-push-right'>";
                        sectionHTML += buildWriteInSel(sectionVal.section_id, undefined);
                    } else {
                        //If we haven't found the user, we can assume they're in this list
                        sectionHTML += "<li><input type='radio' checked name='reqRad-"+sectionVal.section_id+"'>Write In</input></li><li class='sel-push-right'>";
                        sectionHTML += buildWriteInSel(sectionVal.section_id, assignedId);
                    }

                    sectionHTML += "</li></ul></td></tr></table>";
                sectionHTML += "</li>";
            });
        }
        else{
            sectionHTML += "<div class='helptext assignCourse-" + courseId + "'>This course has no section data</div>"
        }

        $("#section-list").append(sectionHTML);
    }

    function buildWriteInSel(sectionId, assignedId) {
        var html = "<select onchange='assignWriteIn(this.value,"+sectionId+")'>";
        html += "<option value='undefined'>Select a User</option>";
        curAssignedId = assignedId;
        $.each(users, function(index, user) {
            if(user.rit_id == curAssignedId) {
                html += "<option selected value='" + user.rit_id + "'>"+user.last_name+", "+user.first_name+"</option>";
            } else {
                html += "<option value='" + user.rit_id + "'>"+user.last_name+", "+user.first_name+"</option>";
            }
        });
        html += "</select>";
        return html;
    }

    function assignWriteIn(userId, sectionId) {
        if(userId != undefined) {
            assignSection(userId, sectionId);
        }
    }

    function assignSection(userId, sectionId) {
        var spin = makeSpinner("#assignmentMod");
        var url = "/api/assignment/add/" + userId + "/" + sectionId;
        ajaxPromise(url).done(function() {
            resetCourses();
        }).fail(function() {
            alert("There was an error assigning this section");
        }).always(function() {
            killSpinner(spin);
        });
    }

    function removeAssignment(assignId) {
        var spin = makeSpinner("#assignmentMod");
        var url = "/api/assignment/remove/" + assignId;
        ajaxPromise(url).done(function() {
            resetCourses();
        }).fail(function() {
            alert("There was an error assigning this section");
        }).always(function() {
            killSpinner(spin);
        });
    }

    function resetCourses() {
        var tmpSelected = selectedCourses;
        selectedCourses = [];

        $("#section-list").empty();
        $.each(tmpSelected, function(index, selected) {
            if(selected != undefined) {
                selectCourse($(selected).get());
            }
        });
    }

    $(document).ready(function(){
        getCourses();
    });

</script>
