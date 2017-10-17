
<?php
    require_once("_Layouts.php");
    getHeader();
?>
    <div class="contianer">
        <div class="mainTermSel push-left">
            <select class="form-control" id="term" name="term" onchange="changeTerm()">
                <?php
                    if(isset($terms)){
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
    				<td class="vert-top" style="width: 50%;">
    					<div class='module'>
    						<h2 class="title">Courses Selected</h2>
    						<div id="selected" class="container-inner section-list" style="text-align:center;height:425px;overflow-y: scroll;">

    						</div>
    					</div>
    				</td>

    				<td class="vert-top" style="width: 50%;">
    					<div class='module'>
    						<h2 class="title">Assigned Schedule</h2>
    						<div id="schedule" class="container-inner" style="height:425px;overflow-y: scroll;">

    						</div>
    					</div>
    				</td>
    			</tr>
    		</tbody>
    	</table>
    </div>
    <script>
    function getAssignedSchedule(){
        user = '<?php echo $_SESSION['cars-user']; ?>';
        term  = $("#term").val();
        url = "/api/reports/getUserSchedule/"+user+"/"+term;

        asyncAjax(url,
            function(data){
                tables = "";
                $.each(data, function(key,val){
                    tables +="<h4 style='width:100%;text-align:center'>"+key.toUpperCase()+"</h4>";
                    tables +="<table class='table table-striped'><thead><tr><th>Course</th><th>Start Time</th><th>End Time</th><th>Room</th></tr></thead><tbody>";
                    if(val.length > 0){
                        $.each(val,function(courseKey,courseVal){
                            tables +="<tr>";
                                tables +="<td>"+courseVal.program_code+"."+courseVal.course_number+"-";
                                    if(courseVal.section_number < 10){
                                        tables +="0"+courseVal.section_number;
                                    }
                                    else{
                                        tables += courseVal.section_number;
                                    }
                                tables += "</td><td>"+courseVal.start_time+"</td><td>"+courseVal.end_time+"</td><td>"+courseVal.building_abbreviation+"-"+courseVal.room_number+"</td>";
                            tables+="</tr>";
                        })
                    }
                    else{
                        tables += "<tr><td><em>No courses</em></td><td></td><td></td><td></td>"
                    }

                    tables +="</tbody></table>";
                });

                $("#schedule").empty().append(tables);
            });
        }

        function getSelectedSections(){
            term  = $("#term").val();
            url = "/api/bucket/getBucket/"+term;

            asyncAjax(url, function(data) {
                sectionHTML ="";
                $.each(data, function(index, bucket) {
                    sectionHTML += "<h4>Course " + (index+1) + "</h4>";
                    sectionHTML += "<table class='table table-striped'style='width:85%;margin:0 auto;'>";
                    var tblHtml = "";
                    var rowCount = 0;
        			$.each(bucket,function(index, sectionVal){
                        if(sectionVal != undefined) {
                            rowCount++;
    						sectionNum = sectionVal.section_number;
    						if(sectionNum < 10){
    							sectionNum = "0"+sectionNum;
    						}
    						tblHtml += "<tr><td style='width:50%'>Preference "+ (index+1) + "</td>"
                            tblHtml += "<td>" + sectionVal.program_code+"-"+sectionVal.course_number+"."+sectionNum+"</td>";
                        }
        			});
                    if(rowCount == 0) {
                        tblHtml = "<tr><td><em>No courses selected</em></td></tr>";
                    }
                    sectionHTML += tblHtml;
                    sectionHTML += "</table>";
        		});
        		$("#selected").empty().append(sectionHTML);
            });
    	}

        function changeTerm() {
            getSelectedSections();
            getAssignedSchedule();
        }

        $(document).ready(function() {
            getSelectedSections();
            getAssignedSchedule();
        });
    </script>
<?php
    getFooter();
?>
