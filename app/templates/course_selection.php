<?php
    require_once("_Layouts.php");
    getHeader("Course Selection");
?>

<script>
    function dragStarted(evt){
        //start drag
        source=evt.target;
        //set data
        evt.dataTransfer.setData("text/plain", evt.target.innerHTML);
        //specify allowed transfer
        evt.dataTransfer.effectAllowed = "move";
    }

    function draggingOver(evt){
        //drag over
        evt.preventDefault();
        //specify operation
        evt.dataTransfer.dropEffect = "move";
    }

    function dropped(evt){
        //drop
        evt.preventDefault();
        evt.stopPropagation();
        //update text in dragged item
        source.innerHTML = evt.target.innerHTML;
        //update text in drop target
        evt.target.innerHTML = evt.dataTransfer.getData("text/plain");
    }
</script>
<div class="mainTermSel">
    <select class="form-control" id="term" name="term" onchange="changeSemester()">
        <?php
            if(isset($requestTerms)){
                $terms = json_decode($requestTerms,true);
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
                        <select  id="program-select" style="width:100%" onchange="getCourses(this.value)">
                            <?php
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
                <div class='module'>
                   <h2 class="title">Sections</h2>
                   <div id="section-list" class="container-inner section-list" style='height:425px;overflow-y: scroll;'><div class='helptext'>Select a class to view sections</div></div>
               </div>
            </td>
            <td class="vert-top">
                <div id="bucketContain">
                <div id="bucketArea">
                     <table class="full">
                        <tbody>
                            <?php
                                $modulesHtml = "";
                                for($i=1; $i<=$numCourses;$i++) {
                                    $modulesHtml .= "<tr>
                                        <td>
                                            <div class='module'>
                                                <div class='module-title'>
                                                    <h2>Course " . $i . "</h2>
                                                </div>
                                                <div id='course-" . $i . "' class='container-inner' class='droptarget' ondrop='dropped(event)' ondragover='draggingOver(event)'>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>";
                                }
                                echo $modulesHtml;
                        ?>
                        </tbody>
                     </table>
                 </div>
            </td>
        </tr>
    </tbody>
</table>

<?php
    getFooter();
?>
<script>
    var coursesByProgram = <?php echo $programs ?>;
    currentCourse="";
    var arr;
    var selectedCourse;
    var selectedSection;
    var currentSectionArr;
    var requests;

    function changeSemester() {
        displayRequests();
        $("#section-list").empty().append("<div class='helptext'>Select a class to view sections</div>");
        $(selectedCourse).removeClass("selected");
        $(".addReqBtn").addClass('hidden');
        selectedCourse = undefined;
        selectedSection = undefined;
        currentSectionArr = undefined;
    }
    function getCoursesByProgramHTML(program){
        arr = coursesByProgram[program];
        courseHTML = "";
        $.each(arr, function(level, levelVal){
            courseHTML += "<li class='course-levels' >";
                courseHTML += "<div class='title'data-status='closed' onclick='levelAccordian(this)''>";
                    courseHTML += "<h2>" + level + " Level</h2>";
                    courseHTML += '<div class="drop-arrow"><i class="fa fa-chevron-down"></i></div>';
                courseHTML +="</div>"
                courseHTML += "<ul class= 'course-list'>";
                $.each(levelVal, function(course, courseVal){
                    courseHTML += "<li onclick='selectCourse(this)' data-course='" + course
                    + "' data-prog='" + program
                    + "' data-level='" + level
                    + "'><h4>" + courseVal.program_code +"-" +courseVal.course_number +": "+ courseVal.course_name + "</h4></li>";                })
                courseHTML += "</ul>";
            courseHTML += "</li>";
        });
        return courseHTML;
    }

    function getCourses(program){
        list = $("ul.course-levels");
        list.empty();
        list.append(getCoursesByProgramHTML(program));

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
        if(selectedCourse != undefined) {
            $(selectedCourse).removeClass('selected');
        }
        if(selectedSection != undefined) {
            selectedSection = undefined;
            $(".addReqBtn").addClass('hidden');
        }

        selectedCourse = selected;
        $(selected).addClass('selected');

        var prog = selected.getAttribute('data-prog');
        var level = selected.getAttribute('data-level');
        var course = selected.getAttribute('data-course');

        getSections(coursesByProgram[prog][level][course]);
    }

    function getSections(selected){
		term = $("#term").val();
		asyncAjax("/api/classes/sectionsForFormatted/"+selected.program_code+"/"+selected.course_id+"/"+"0/"+term, function(data){
			getSectionsHTML(data);
            currentSectionArr = data;
		});
	}


    function getSectionsHTML(sections){
        sectionHTML =""
        if(sections != ""){
            $.each(sections,function(section, sectionVal){
                sectionTimeArr = sectionVal.section_times;
                sectionHTML += "<li onclick='selectSection(this)' data-sectionId='" + sectionVal.section_id + "'class='section selectable'>";
                    sectionHTML += "<ul class='section-info'>";
                        sectionNum = sectionVal.section_number;
                        if(sectionNum < 10){
                            sectionNum = "0"+sectionNum;
                        }
                        sectionHTML += "<li><h4>"+sectionVal.program_code+"-"+sectionVal.course_number+"."+sectionNum+"</h4></li>"
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

                    sectionHTML += "</ul>";
                sectionHTML += "</li>";
            });
        }
        else{
            sectionHTML = "<li> This class has no section information for this term</li>";
        }

        $("#section-list").empty().append(sectionHTML);
    }

    function selectSection(selected) {
        if(selectedSection != undefined) {
            $(selectedSection).removeClass('selected');
        }

        selectedSection = selected;
        $(selected).addClass('selected');
        $(".addReqBtn").removeClass('hidden');

    }

    function displayRequests() {
        var spinner = makeSpinner("#bucketContain");
        term = $("#term").val();
		ajaxPromise("/api/bucket/getBucket/"+term).done(function(data) {
            getRequestsHTML(data);
        }).always(function(){
            killSpinner(spinner);
        });
    }

    function getRequestsHTML(data) {
        requests = data;
        for(var i=0; i<data.length; i++){
            var html = "<ul class='section-list'>";
            for(var j=0; j<data[i].length; j++) {
                var sectionHtml;
                var sectionIcon;
                if(data[i][j] != null) {
                    var request = data[i][j];
                    var sectionNum = request.section_number;
                    if(sectionNum < 10){
                        sectionNum = "0"+sectionNum;
                    }
                    sectionHtml = "<strong>" + request.program_code + "-" + request.course_number + "-" + sectionNum + "</strong>";
                    sectionIcon = "<i onclick='removeRequest("  + request.section_id + "," + i + "," + j + ")' class='fa fa-trash remReqBtn'></i>";
                } else {
                    sectionHtml = "<em>Empty</em>";
                    sectionIcon = "<i onclick='addRequest(" + i + "," + j + ")' class='fa fa-plus addReqBtn hidden'></i>";
                }
                html += "<li class='section'><ul class='section-info'><li><h5>Preference " + (j+1) + ": "
                    + sectionHtml
                    + "</h5></li></ul>"
                    + sectionIcon
                    + "</li>";
            }
            html += "</ul>"
            $("#course-" + (i+1)).empty().append(html);

            //Show add buttons if section is still selected
            if(selectedSection != undefined) {
                $(".addReqBtn").removeClass('hidden');
            }
        }
    }

    function addRequest(course, preference) {
        var sectionId = selectedSection.getAttribute("data-sectionId");

        //Buckets and preference are 1-based
        course++;
        preference++;

        var url = "/api/bucket/addToBucket/"+sectionId+"/"+course+"/"+preference;
        ajaxPromise(url).done(function(data) {
            console.log("success");
            displayRequests();
        });
    }

    function removeRequest(sectionId, course, preference) {
        //Buckets and preference are 1-based
        course++;
        preference++;

        var url = "/api/bucket/removeFromBucket/"+sectionId+"/"+course+"/"+preference;
        ajaxPromise(url).done(function(data) {
            console.log("success");
            displayRequests();
        });
    }

    $(document).ready(function(){
        getCourses($("#program-select").val());
        displayRequests();
    });

</script>
