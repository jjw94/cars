
<?php
    require_once("_Layouts.php");
    getHeader("Reports");
?>



    <table style="width:100%;">
        <tbody>
            <tr>
                <td class="vert-top" style="width:30%;">
                    <div class='module'>
                        <h2 class="title margin-top-reset">Reports</h2>
                        <div class="container-inner">
                            <div id="sidenav" class="sidenavbar">
                                <ul>
                                    <li><button class="btn btn-primary" onclick="showRoom()" style="width:100%;">Room Schedule</button></li>
                                    <li style="margin-top:5px;"><button onclick="showAssigned()" class="btn btn-primary" style="width:100%;">User Assigned Schedule</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="vert-top" style="width:65%;">
                    <div class="module" id="room-schedule">
                        <h2 class="title" style="margin-top:0;">Room Schedule Report</h2>
                        <div class="container-inner">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="width:25%;">
                                            <div class="form-section" style="width:150px;margin: 0 auto;">
                                                Term:
                                                <select name="term-select-room" id=term-select>
                                                    <option value="#">Pick Term</option>
                                                    <?php

                                                        if(isset($terms)){
                                                            foreach ($terms as $term) {
                                                                echo "<option value='".$term["academic_term"]."'>".$term["term_description"]."</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td style="width:33%;">
                                            <div class='form-section'>
                                                Building:
                                                <select name='building' id="building-select-room" onchange="roomSelectBuilder(this.value)">
                                                    <option value="#">Select a Building</option>
                                            <?php
                                                foreach ($rooms as $key => $value) {
                                                    echo "<option value='".$key."'>".$key."</option>";
                                                }

                                            ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td style="width:25%;" >
                                            <div class="form-section" style="width:120px;margin: 0 auto;">
                                                Room:
                                                <select name="room" id="room-select-room">
                                                </select>
                                            </div>
                                        </td>
                                        <td style="width:16px;">
                                            <button class="btn btn-primary" onclick="getRoomSchedule()">Get Report</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="module" id="assigned-schedule" style="display: none;">
                        <h2 class="title" style="margin-top:0;">User Assigned Schedule Report</h2>
                        <div class="container-inner">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="width:25%;">
                                            <div class="form-section" style="width:150px;margin: 0 auto;">
                                                Term:<br />
                                                <select name="term-select-assigned" id=term-select-assigned>
                                                    <option value="#">Pick Term</option>
                                                    <?php

                                                        if(isset($terms)){
                                                            foreach ($terms as $term) {
                                                                echo "<option value='".$term["academic_term"]."'>".$term["term_description"]."</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td style="width:33%;">
                                            <div class='form-section'>
                                                Department:<br />
                                                <select  id="dept-select-assigned" onchange="deptUserSelectBuilder(this.value)">
                                                    <option value="#">Select a Department</option>
                                            <?php
                                                var_dump($departments);
                                                foreach ($departments as $key => $value) {
                                                    echo "<option value='".$value["department_id"]."'>".$value["department_abbreviation"]."</option>";
                                                }

                                            ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td style="width:25%;" >
                                            <div class="form-section" style="width:120px;margin: 0 auto;">
                                                User:<br />
                                                <select  id="user-select-assigned">
                                                </select>
                                            </div>
                                        </td>
                                        <td style="width:16%;">
                                            <button class="btn btn-primary" onclick="getAssignedSchedule()">Get Report</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class='module' id="report-view" style="display: none;margin-top:20px">
                        <h2 class="title" id="room-title"></h2>
                        <div class="container-inner">
                            <div class="report-pane">
                                <table style="width:100%;">
                                    <tbody id="report-table"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>


    <script>
        var rooms = <?php echo json_encode($rooms); ?>;

        function roomSelectBuilder(building){
            options = "";
            $.each(rooms[building],function(room, data){
                options += "<option value='"+data.room_number+"'>"+ data.room_number+"</option>";
            });

            $("#room-select-room").empty().append(options);
        }

        function getRoomSchedule(){
            term = $("#term-select");
            building = $("#building-select-room").val();
            room = $("#room-select-room").val();
            url = '/api/reports/getRoomSchedule/'+building+'/'+room+'/'+term.val();

            asyncAjax(url,
                function(data){
                    console.log(data);
                    tables = "";
                    $.each(data, function(key,val){
                        tables +="<tr><td><h4 style='width:100%;text-align:center'>"+key.toUpperCase()+"</h4>";
                        tables +="<table class='table table-striped'><thead><tr><th>Course</th><th>Start Time</th><th>End Time</th></tr></thead><tbody>";
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
                                    tables += "</td><td>"+courseVal.start_time+"</td><td>"+courseVal.end_time+"</td>";
                                tables+="</tr>";
                            })
                        }
                        else{
                            tables += "<tr><td>No data found for this room's usage during this semester</td><td></td><td></td>"
                        }

                        tables +="</tbody></table></td></tr>";
                    });

                    $("#report-table").empty().append(tables);
                    $("#room-title").empty().append(building+"-"+room+" usage during Term: "+term.val());
                    $("#report-view").show();
                });
        }


        function deptUserSelectBuilder(id){
            url = "/api/users/department/"+id;

            asyncAjax(url,
                function(data){
                    select = "";
                    $.each(data,function(key,val){
                        select += "<option value='"+val.rit_id+"'>"+val.last_name+","+val.first_name+"</option>";
                    });
                    $("#user-select-assigned").empty().append(select);
                });
        }

        function getAssignedSchedule(){
            user = $("#user-select-assigned").val();
            term  = $("#term-select-assigned").val();
            url = "/api/reports/getUserSchedule/"+user+"/"+term;

            asyncAjax(url,
                function(data){
                    console.log(data);
                    tables = "";
                    $.each(data, function(key,val){
                        tables +="<tr><td><h4 style='width:100%;text-align:center'>"+key.toUpperCase()+"</h4>";
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
                            tables += "<tr><td>No data found for this Users Assignment during this semester and day</td><td></td><td></td><td></td>"
                        }

                        tables +="</tbody></table></td></tr>";
                    });

                    $("#report-table").empty().append(tables);
                    $("#room-title").empty().append("Assigned Schedule for "+user);
                    $("#report-view").show();
                });
        }

        function showRoom(){
            $("#room-schedule").show();
            $("#assigned-schedule").hide();
        }
        function showAssigned(){
            $("#room-schedule").hide();
            $("#assigned-schedule").show();
        }

    </script>
<?php
    getFooter();
?>
