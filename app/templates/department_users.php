
<?php
    require_once("_Layouts.php");
    getHeader("Manage Users");
?>
	<table style="width:100%;">
		<tbody>
			<tr>
				<td class="vert-top" style="width:35%;">
					<div class="module">
						<h2 class="title">Department Users</h2>
                    	<div class="container-inner">
                    		<div>
                    			<input type="text" id="last-name-dept-input" name="last-name" placeholder="Last Name" onkeydown = "if (event.keyCode == 13)
                        			getUsersFromDept()">
                    			<button type="button" class="btn btn-primary"  onclick="getUsersFromDept()">
									Get Users
								</button>
                    		</div>
                    		<ul class="users-name-dept-list">

                        	</ul>
                    	</div>
					</div>
					<div class="module">
						<h2 class="title">Application Users</h2>
                    	<div class="container-inner">
                    		<div>
                    			<input type="text" id="last-name-input" name="last-name" placeholder="Last Name" onkeydown = "if (event.keyCode == 13)
                        			getUsers()">
                    			<button type="button" class="btn btn-primary"  onclick="getUsers()">
									Get Users
								</button>
                    		</div>
                    		<ul class="users-name-list">
                            	<?php
                            		foreach ($users as $char => $charArr) {
                            			echo "<li class='initial-group'>";
                            				echo "<div class='title' data-status='closed' onclick='levelAccordian(this)'>";
	                            				echo "<h2>".$char." Names</h2>";
												echo '<div class="drop-arrow"><i class="fa fa-chevron-down"></i></div>';
												echo"</div>";
												echo "<ul class='user-list'>";
												if(sizeof($charArr) >0){
													foreach ($charArr as $user)
													{
													echo "<li onclick='getUserDetail(".'"'.$user["rit_id"].'","app"'.")'><h4>".$user["last_name"].", ".$user["first_name"]."</h4></li>";
													}
												}

											echo "</ul>";
										echo "</li>";

                            		}
                            	?>
                        	</ul>
                    	</div>
					</div>
				</td>
				<td class="vert-top" style="width:65%;">
					<div class="module">
						<h2 class="title">Manage</h2>
                    	<div class="container-inner">
                    		<table style="width:100%;">
                    			<tbody>
                    				<tr >
                    					<td style="width:50%;">
                    						<ul style="list-style: none;">
                    							<li id='name'></li>
                    							<li id="rit-id"></li>
                    							<li id="email"></li>
                    							<li id="role"></li>
                    							<li id="departments"></li>

                    						</ul>
                    					</td>
                    					<td style="width:50%;display: none;" id="app-users" class="vert-top" >
                    						<ul id="function-selections" style="list-style:none;">
                    							<li id="change-role">
                    								<div>
                    									<span>Set Role:</span><br />
                    									<select id='role-selection' style="width: 170px;">
                    										<?php
                    										foreach ($roles as $role) {
                    											echo "<option value='".$role["role_id"]."'>".$role["role_name"]."</option>";
                    											//department select
                    										}
                    									?>
                    									</select>
                    									<button type="button" class="btn btn-primary"  onclick="setRole()">
												  			Set
														</button>
                    								</div>
                    							</li>
                    							<li id="add-department">
                    								<div>
                    									<span>Add to department:</span><br />
                    									<select style="width:200px;" id='department-selection'>
                    									<?php
                    										foreach ($departments as$department) {
                    											echo "<option value='".$department["department_id"]."'>".$department["department_name"]."</option>";
                    											//department select
                    										}
                    									?>
                    									</select>
                    									<button type="button" class="btn btn-primary" onclick="addDepartment()" >
												  			Add
														</button>
                    								</div>
                    							</li>
                    						</ul>
                    					</td>
                    					<td style="width:50%;display: none;" id="dept-users" class="vert-top" >
                    						<ul id="function-selections" style="list-style:none;">
                    							<li id="change-role">
                    								<div>
                    									<span>Set Role:</span><br />
                    									<select id='role-selection' style="width: 170px;">
                    										<?php
                    										foreach ($roles as $role) {
                    											echo "<option value='".$role["role_id"]."'>".$role["role_name"]."</option>";
                    											//department select
                    										}
                    									?>
                    									</select>
                    									<button style="margin-top:3px" type="button" class="btn btn-primary"  onclick="setRole()">
												  			Set
														</button>
                    								</div>
                    							</li>
                    							<li id="remove-department" style="margin-top:8px;">
                    								<div>
                    									<button type="button" class="btn btn-primary" onclick="removeUserDept()" >
												  			Remove From Department
														</button>
                    								</div>
                    							</li>
                    						</ul>
                    					</td>
                    				</tr>

                    			</tbody>
                    		</table>
                    	</div>
					</div>
					<div class="module">
    					<h2 class="title">Add New User</h2>
    					<div class="container-inner">
    						<ul>
    							<li>Add an exisiting RIT user into this application.</li><li>Login is connected to their RIT ID through Shibboleth</li>
    							<li id="new-user-info">They will be added into your department</li>
    						</ul>
    							<div style="margin-bottom:3px;">First Name: <input type="text" id="fName-new"/><br /></div>
    							<div style="margin-bottom:3px;">Last Name: <input type="text" id="lName-new"/><br /></div>
    							<div style="margin-bottom:3px;">Email: <input type="text" id="email-new"/><br /></div>
    							<div style="margin-bottom:3px;">RIT ID: <input type="text" id="id-new"/><br /></div>
    							<button style="margin-top:5px;" class="btn btn-primary" onclick="createUser()">Create</button>
    					</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

<script>
	function levelAccordian(ele){
		ele = $(ele);
		if(ele.data("status") == "closed"){
			ele.data("status","open");
			ele.find(".drop-arrow").empty().append("<i class='fa fa-chevron-up'></i>");
			ele.parent().find("ul.user-list").css("display","block");
		}
		else{
			ele.data("status","closed");
			ele.find(".drop-arrow").empty().append("<i class='fa fa-chevron-down'></i>");
			ele.parent().find("ul.user-list").css("display","none");
		}
	}
	function getUserDetail(id,type){
		url = "/api/admin/UserInfo/"+id;
		asyncAjax(url,function(data){
			if(type=="app"){
				displayUserInfo(data,id);
			}
			else{
				displayDeptUserInfo(data,id);
			}

		});
	}

	function createUser(){
		url = "/api/admin/add/user/inDept/";
		url += $("#fName-new").val()+"/";
		url += $("#lName-new").val()+"/";
		url += $("#email-new").val()+"/";
		url += $("#id-new").val()+"/";
		url += $("#department-selection").val();

		insertAjax(url,
			function(data){
		}, function(data){
			alert("failed");
		});

	}
	function removeUserDept(user,dept){
		$url = "/api/admin/remove/user/dept/"+$("#role-selection").data("user")+"/"+$("#department-selection").val();

		asyncAjax($url,
			function(){

			});
	}
	function displayUserInfo(data,id){
		if(data["user-data"].length>0){
			user = data["user-data"][0];
			$("#name").empty().append("Name: "+ user.first_name +" "+user.last_name);
			$("#rit-id").empty().append("RIT ID: " + id);
			$("#department-selection").data("user", id);
			$("#role-selection").data("user", id);
			$("#email").empty().append("Email: " + user.email);
			$("#role").empty().append("User Type: " + user.role_name);
		}
		if(data["user-departments"].length >0){
			departments = data["user-departments"];
			$("#departments").empty().append("Departments:<ul id='department-list'>");
			$.each(departments, function(id, dept){
				$("#department-list").append("<li>"+dept.department_name+"</li>");
			});
			$("#departments").append("</ul>");
		}
		$("#app-users").css("display","block");
		$("#dept-users").css("display","none");
	}
	function displayDeptUserInfo(data,id){
		if(data["user-data"].length>0){
			user = data["user-data"][0];
			$("#name").empty().append("Name: "+ user.first_name +" "+user.last_name);
			$("#rit-id").empty().append("RIT ID: " + id);
			$("#department-selection").data("user", id);
			$("#role-selection").data("user", id);
			$("#email").empty().append("Email: " + user.email);
			$("#role").empty().append("User Type: " + user.role_name);
		}
		if(data["user-departments"].length >0){
			departments = data["user-departments"];
			$("#departments").empty().append("Departments:<ul id='department-list'>");
			$.each(departments, function(id, dept){
				$("#department-list").append("<li>"+dept.department_name+"</li>");
			});
			$("#departments").append("</ul>");
		}
		$("#dept-users").css("display","block");
		$("#app-users").css("display","none");
	}
	function addDepartment(){
		selection = $("#department-selection");
		url = "/api/admin/addUserDepartment/"+selection.data("user")+"/"+selection.val();

		insertAjax(url, function(){
		 	getUserDetail(selection.data("user"));
			}, function(output){
				$("#add-course-output").empty().append(output);
			});

	}

	function setRole(){
		selection = $("#role-selection");
		url = "/api/admin/setRole/"+selection.data("user")+"/"+selection.val();

		insertAjax(url, function(){
		 	getUserDetail(selection.data("user"));
			}, function(output){
				$("#add-course-output").empty().append(output);
			});

	}
	function displayAllUsersHtml(data, nameBit){
		output = "";

		output += "<li class='initial-group'>";
			output += "<div class='title' data-status='open' onclick='levelAccordian(this)'>";
				output += "<h2>'"+nameBit+"' Names</h2>";
				output += '<div class="drop-arrow"><i class="fa fa-chevron-up"></i></div>';
				output +="</div>";
				output += "<ul class='user-list' style='display:block;'>";
				if(data.length >0){
					$.each(data,function(dKey, dataVal){

					output += "<li onclick='getUserDetail("+'"'+dataVal["rit_id"]+'","app"'+")'><h4>"+dataVal["last_name"]+", "+dataVal["first_name"]+"</h4></li>";
					});
				}

			output += "</ul>";
		output += "</li>";
		$(".users-name-list").empty().append(output);

	}

	function displayDeptUsersHtml(data, nameBit){
		output = "";

		output += "<li class='initial-group'>";
			output += "<div class='title' data-status='open' onclick='levelAccordian(this)'>";
				output += "<h2>'"+nameBit+"' Names</h2>";
				output += '<div class="drop-arrow"><i class="fa fa-chevron-up"></i></div>';
				output +="</div>";
				output += "<ul class='user-list' style='display:block;'>";
				if(data.length >0){
					$.each(data,function(dKey, dataVal){


					/// add param for type
					output += "<li onclick='getUserDetail("+'"'+dataVal["rit_id"]+'","dept"'+")'><h4>"+dataVal["last_name"]+", "+dataVal["first_name"]+"</h4></li>";
					});
				}

			output += "</ul>";
		output += "</li>";
		$(".users-name-dept-list").empty().append(output);

	}
	function getUsers(){
		nameBit = $("#last-name-input").val();
		url = "/api/users/lastName/"+nameBit;

		asyncAjax(url,function(data){
				displayAllUsersHtml(data, nameBit);
			});

	}
	function getUsersFromDept(){
		nameBit = $("#last-name-dept-input").val();
		url = "/api/users/lastName/"+nameBit;

		asyncAjax(url,function(data){
				displayDeptUsersHtml(data, nameBit);
			});

	}
</script>
<?php
	getFooter();
?>
