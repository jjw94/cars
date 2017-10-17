
<?php
    require_once("_Layouts.php");
    getHeader("Course Management");
?>
	
    
	<div class="modal fade" id="newCourseModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	        <h4 class="modal-title" id="myModalLabel">Add New Course</h4>
	      </div>
	      <div class="modal-body">
	      	<div id="add-course-output">
	      	</div>
	        <form id="addCourse">
	            <fieldset class="course-management">
					<div class="course-management form">
						<div class="float-left">
							<div class="form-section">
								Department:<br>
								<select name="department">
									<option value="#">Pick Department</option>
									<?php
										if(isset($programs)){
											$programCodes = json_decode($programs,true);
											foreach (array_keys($programCodes) as $programCode) {
												echo "<option value='".$programCode."'>".$programCode."</option>";
											}
										}
									?>
								</select>
							</div>
							<div class="form-section">
								Name:<br>
		                		<input type="text" name="name">
							</div>
							<div class="form-section">
								Course Number:<br>
								<input type="number" name="coursenumber">
							</div>
							<div class="form-Section">
								Course Description:<br>
								<textarea rows="4" cols="30" name="description" form="addCourse">Enter a description...
								</textarea>
							</div>
	                	</div>
	                	<div class="float-right course-management">
	                		<div class="form-section">
	                			Credits:<br><select name="credits">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
								</select>
							</div>
							<div class="form-section">
								Class hours:<br>
								<select name="classHrs">
									<option value="0">0</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
								</select>
							</div>
							<div class="form-section">
								Lab Hours:<br>
								<select name="labHrs">
									<option value="0">0</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
								</select>
							</div>
							
	                	</div>
					</div>
				</fieldset>
			</form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary"  onclick="addCourse()" >Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>


	<div class="modal fade" id="newSectionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	        <h4 class="modal-title" id="myModalLabel">Add New Section</h4>
	      </div>
	      <div class="modal-body">
	      <div id="add-section-output">
	      	</div>
	        <form id="addSection">
	            <fieldset class="course-management">
					<div class="course-management form">
						<div class="float-left">
						<input type="hidden" id="class-id" name="course-id">
							<div class="form-section">
								Term:<br>
		                		<select name="term">
									<option value="#">Pick Term</option>
									<?php
										
										if(isset($developingTerms)){
											$developingTerms;
											foreach ($developingTerms as $term) {
												echo "<option value='".$term["academic_term"]."'>".$term["term_description"]."</option>";
											}
										}
									?>
								</select>
							</div>
							<div class="form-section">
								Section Number:<br>
		                		<input type="text" name="section-number">
							</div>
							<div class="form-section">
								Online:<br>
		                		<select name="online" onchange="showRoomAndTime(this.value)">
		                			<option value="">Select</option>
		                			<option value="0">No</option>
		                			<option value="1">Yes</option>
		                		</select>
							</div>
							<div id="room-choice">
								<div class='form-section'>
									Building:<br>
	            					<select name='building' id="building-select" onchange="roomSelectBuilder(this.value)">
	            						<option value="#">Select a Building</option>
								<?php
									foreach ($rooms as $key => $value) {
										echo "<option value='".$key."'>".$key."</option>";
									}

								?>
									</select>
								</div>
								
								<div class="form-section">
									Room:<br>
			                		<select name="room" id="room-select">
			                		</select>
								</div>
							</div>
							
	                	</div>
	                	<div class="float-right course-management" id="time-choice" >
	                		<div class="form-section">
	                			Class Days:<br>
								<input type="checkbox" name="weekday" value="M">Monday<br>
								<input type="checkbox" name="weekday" value="T">Tuesday<br>
								<input type="checkbox" name="weekday" value="W">Wednesday<br>
								<input type="checkbox" name="weekday" value="Th">Thursday<br>
								<input type="checkbox" name="weekday" value="F">Friday<br>
								<input type="checkbox" name="weekday" value="S">Saturday
							</div>
							
							<div class="form-section">
								Start Time:<br>
								<input type="time" name="start-time">
							</div>
							<div class="form-section">
								End Time:<br>
								<input type="time" name="end-time">
							</div>
	                	</div>
					</div>
				</fieldset>
			</form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" onclick="addSection()" class="btn btn-primary" >Save changes</button>
	      </div>
	    </div>
	  </div>
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
                        <div style="padding-top:20px;">
                        	<button type="button" class="btn width-full btn-primary btn-lg" data-toggle="modal" data-target="#newCourseModal">
					  		Add Course
						</button>
                        </div>
                </div>
            </td>
            <td class="vert-top">
                 <div class='module'>
                    <h2 class="title">Manage</h2>
                    <div class="container-inner">
                    	<div class="course-info">
                    		<div class="manage-title">
                    			<h3 class="class-title"></h3>
                    		</div>
                    		<ul class="course-info">
                    			<li id="course-num"></li>
                    			<li id="course-credits"></li>
                    			<li id="course-class-hrs"></li>
                    			<li id="course-lab-hrs"></li>
                    			<br />
                    			<li id="course-description"></li>
                    		</ul>
                    	</div>
	                    
                </div>
            </td>
            <td class="vert-top" style="width:300px;">
            	<div class="module">
            		<h2 class="title">Sections</h2>
            		<div class="container-inner">
            			<div>
        					<select id="sections-term" onchange="getSections()" style="width:100%;">
								<?php
									
									if(isset($allTerms)){
										$terms = json_decode($allTerms,true);
										foreach ($terms as $term) {
											echo "<option value='".$term["academic_term"]."'>".$term["term_description"]."</option>";
										}
									}
								?>
							</select>
            			</div>
            			<ul class="section-list" style="height:400px;overflow-y: scroll;">
            				
            			</ul>
            			<div style="width:100%;padding-top:15px;">
            				<button type="button" class="btn btn-primary btn-lg" style="width:100%" data-toggle="modal" data-target="#newSectionModal">
					  		Create Section
							</button>
						</div>
            		</div>
            	</div>
            </td>
        </tr>
    </tbody>
</table>


<script>
var coursesByProgram = <?php echo $programs ?>;
var sectionsArr;
currentCourse="";
var arr;

var rooms = <?php echo json_encode($rooms); ?>;
	
	function showRoomAndTime(val){
		room = $("#room-choice");
		time = $("#time-choice");
		if(val == 1){
			room.hide();
			time.hide();
		}
		else if (val == 0){

			room.show();
			time.show();
		}

	}

	function roomSelectBuilder(building){
		options = "";
		$.each(rooms[building],function(room, data){
			options += "<option value='"+data.room_id+"'>"+ data.room_number+"</option>";
		});

		$("#room-select").empty().append(options);
	}
	function addCourse(){
		arr = {};
		form = $("#addCourse").serializeArray()
		$.each( form,function(){
			if (arr[this.name] !== undefined) {
            if (!arr[this.name].push) {
                arr[this.name] = [arr[this.name]];
            }
            	arr[this.name].push(this.value || '');
        	} else {
            	arr[this.name] = this.value || '';
        }
		});

		url = "/api/admin/add/course/";
		url += arr["department"] + "/";
		url += arr["name"] + "/";
		url += arr["coursenumber"] + "/";
		url += arr['description'] + "/";
		url += arr["credits"] + "/";
		url += arr["classHrs"] + "/";
		url += arr["labHrs"];

		 insertAjax(url, function(){
		 	asyncAjax("/api/classes/byProgram/byLevel/<?php echo $_SESSION['cars-user']; ?>",function(data){
				setCourseData(data);
				getCourses($("#program-select").val());
				$('#newCourseModal').modal('hide');
				resetFormFields();
			});
			
		 }, function(output){
				$("#add-course-output").empty().append(output);
			});
		//get new list of courses
	}
	function resetFormFields(){
		$("#addCourse")[0].reset();
		$("#addSection")[0].reset();
		
	}
	function setCourseData(data){
		window.coursesByProgram = data;
	}
	function addSection(){
		arr = {};
		form = $("#addSection").serializeArray()
		$.each( form,function(){
			if (arr[this.name] !== undefined) {
            if (!arr[this.name].push) {
                arr[this.name] = [arr[this.name]];
            }
            	arr[this.name].push(this.value || '');
        	} else {
            	arr[this.name] = this.value || '';
        }
		});
		//{courseID}/{sectionNum}/{term}/{online}/{days}/{start}/{end}/{building}/{room}
		url = "/api/admin/add/course/section/";
		url += arr["course-id"] +"/";
		url += arr["section-number"] + "/";
		url += arr["term"] + "/";
		url += arr["online"] + "/";
		if(arr["online"] == 0){
			url += arr["weekday"] + "/";
			url += arr["start-time"] + "/";
			url += arr["end-time"] + "/";
			url += $("#room-select").val();
		}
		else{
			url += "na" + "/";
			url += "na" + "/";
			url += "na" + "/";
			url += "na";
		}
		

		 insertAjax(url, function(){
		 	getSections();
		 	$('#newSectionModal').modal('hide');
		 	$("#room-choice").hide();
			$("#time-choice").hide();
		 	resetFormFields();
			},function(output){
				$("#add-section-output").empty().append(output);
			});
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
					courseHTML += "<li onclick='getCourseDetail(" +'"'+ program+'"'+ ","+ '"'+ level + '"' +"," +'"'+ course+ '"' +")'><h4>" + courseVal.program_code +"-" +courseVal.course_number +": "+ courseVal.course_name + "</h4></li>";
				})
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

	function getCourseDetailHTML(course){
		currentCourse = course;
		$("h3.class-title").empty().append(course.course_name);
		$("li#course-num").empty().append(course.program_code+"-"+course.course_number);
		$("li#course-description").empty().append(course.course_description);
		$("li#course-credits").empty().append("Credits: " + course.student_credits );
		$("li#course-class-hrs").empty().append("Number of Class Hours: " + course.class_contact_hours);
		$("li#course-lab-hrs").empty().append("number of Lab Hours: " + course.lab_contact_hours);
	}
	function getCourseDetail(program,level,courseID){
		course = coursesByProgram[program][level][courseID];
		getCourseDetailHTML(course);

		$("#class-id").val(coursesByProgram[program][level][courseID].course_id);
		getSections();
	}
	function getSectionsHTML(sections){
		sectionHTML =""
		if(sections != ""){
			$.each(sections,function(section, sectionVal){
				sectionTimeArr = sectionVal.section_times;
				sectionHTML += "<li class='section'>";
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
		
		$("ul.section-list").empty().append(sectionHTML);
	}
	function getSections(){
		term = $("#sections-term").val();
		asyncAjax("/api/classes/sectionsForFormatted/"+currentCourse.program_code+"/"+currentCourse.course_id+"/"+"0/"+term, function(data){
			getSectionsHTML(data);
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
	$(document).ready(function(){
		getCourses($("#program-select").val());
	});
</script>

    

<?php
    getFooter();
?>