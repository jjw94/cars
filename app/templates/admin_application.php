<?php
    require_once("_Layouts.php");
    getHeader("Manage Application");
?>

<div class="modal fade" id="newTermModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Add New Term</h4>
      </div>
      <div class="modal-body">
      <div id="add-section-output">
      	</div>
        <form id="addTerm">
            <fieldset class="new-term">
				<div class="form-section">
					Term:<br />
					<input type="text" name="term" id="new-term-desc" />
				</div>
				<div class="form-section">
					Term:<br />
					<input type="text" name="term-num" id="new-term-number" />
				</div>
				<div class="form-section">
					Term Status:<br />
					<select id="new-term-select">
						<?php
							foreach ($status as $key => $value) {
								echo "<option value='".$value["status"]."' >".$value["status_description"]."</option>";
							}
						?>
						</select>
				</div>
			</fieldset>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="newTerm()" class="btn btn-primary" >Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="newDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Add New Department</h4>
      </div>
      <div class="modal-body">
      <div id="add-section-output">
      	</div>
        <form id="addTerm">
            <fieldset class="new-term">
				<div class="form-section">
					Department Name:<br />
					<input type="text" name="term" id="new-dept-name" />
				</div>
				<div class="form-section">
					Department Abbreviation:<br />
					<input type="text" name="term-num" id="new-dept-abbrev" />
				</div>
			</fieldset>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="newDepartment()" class="btn btn-primary" >Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="newProgramModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Add New Program</h4>
      </div>
      <div class="modal-body">
      <div id="add-section-output">
      	</div>
        <form id="addTerm">
            <fieldset class="new-term">
				<div class="form-section">
					Program Name:<br />
					<input type="text" name="term" id="new-program-name" />
				</div>
				<div class="form-section">
					Program Code:<br />
					<input type="text" name="term-num" id="new-program-code" />
				</div>
				<div class="form-section">
					Degree Type:<br />
					<select id="program-select">
					<option value="BS">BS</option>
					<option value="MS">MS</option>
					<option value="Ph.D.">Ph.D.</option>
					</select>
				</div>
			</fieldset>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="newProgram()" class="btn btn-primary" >Save changes</button>
      </div>
    </div>
  </div>
</div>

	<table style="width:100%;">
		<tbody>
			<tr>
				<td class="vert-top" style="width: 30%;">
					<div class="module" >
						<h2 class="title">Manage</h2>
						<div class="container-inner">
							<ul style="list-style: none;padding-left:0">
								<li><button class="btn btn-primary btn-lg" style="width:100%;" onclick="showDepartments();">Departments</button></li>
								<li style="margin-top:10px;"><button class="btn btn-primary btn-lg" style="width:100%;" onclick="showTerms();">Terms</button></li>
							</ul>
						</div>
					</div>
				</td>
				<td class="vert-top" style="width:65%'">
					<div class="module" id="departments">
						<h2 class="title">Departments</h2>
						<div class="container-inner">
							<table>
								<tbody>
									<tr>
										<td>
											<ul style="list-style: none;" id="dept-list" >
												<?php
													foreach($departments as $key => $value){
														echo "<li style='margin-bottom:5px;width:100%;'><button class='btn btn-primary' style='width:100%;' onclick='showDeptDetail(".'"'.$value["department_abbreviation"].'"'.")' >".$value["department_abbreviation"]."</button></li>";
													}
												?>
											</ul>
											<ul>
												<li><button type="button" class="btn btn-primary" style="width:100%" data-toggle="modal" data-target="#newDepartmentModal">
											  		New Department
													</button>
												</li>
											</ul>
										</td>
										<td class="vert-top">
											<input type="hidden" id="dept-hidden" />
											<ul>
												<li>Department: <span id="dept-desc"></span></li>
												<li>Abbreviation: <span id="dept-abbr"></span></li>
												<li>
													Programs<br/>
													<ul style="list-style:circle; " id="program-list">

													</ul>
												</li>
												<li style="margin-top:5px;">
													<button type="button" class="btn btn-primary" style="width:100%" data-toggle="modal" data-target="#newProgramModal">
											  		New Program
													</button>
												</li>
											</ul>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="module" id="terms" style="display: none;">
						<h2 class="title">Terms</h2>
						<div class="container-inner">
							<table>
								<tbody>
									<tr>
										<td>
											<ul style="list-style: none;" id="term-list">
											<?php
													foreach($terms as $key => $value){
														echo "<li style='margin-bottom:5px;width:100%;'><button class='btn btn-primary' style='width:100%;' onclick='showTermDetail(".$value["academic_term"].")'>".$value["term_description"]."</button></li>";
													}
												?>
											</ul>
											<ul>
												<li><button type="button" class="btn btn-primary" style="width:100%" data-toggle="modal" data-target="#newTermModal">
											  		New Term
													</button>
												</li>
											</ul>
										</td>
										<td class="vert-top">
											<ul>
												<li>Term: <span id="term-desc"></span></li>
												<li>Term Number: <span id="term-num"></span></li>
												<li>Term Status: <span id="term-status"></span></li>
												<li>
													Set Term Status<br/>
													<select id="term-select">
													<?php
														foreach ($status as $key => $value) {
															echo "<option value='".$value["status"]."' >".$value["status_description"]."</option>";
														}
													?>
													</select>
												</li>
												<li style="margin-top:5px;">
													<button class="btn btn-primary" onclick="setStatus()">Set Term Status</button>
												</li>
											</ul>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

<script>
function showTerms(){
	$("#terms").show();
	$("#departments").hide();
}
function showDepartments(){
	$("#terms").hide();
	$("#departments").show();
}
function showTermDetail(termNum){
	url = "/api/terms/term/"+ termNum;

	asyncAjax(url,
		function(data){
			$("#term-desc").empty().append(data[0].term_description);
			$("#term-num").empty().append(data[0].academic_term);
			$("#term-status").empty().append(data[0].status_description);

		});
}

function setStatus(){
	url = "/api/admin/term/setStatus/"+$("#term-num").text()+"/"+$("#term-select").val();

	insertAjax(url,
		function(data){
			showTermDetail($("#term-num").text());
		},
		function(){
			alert("failed")
		})
}
function loadTerms(){
	url="/api/terms/";

	asyncAjax(url,
		function(data){
			output ="";
			$.each(data,function(term, termVal){
				output+= "<li style='margin-bottom:5px;width:100%;'><button class='btn btn-primary' style='width:100%;' onclick='showTermDetail("+termVal["academic_term"]+")'>"+termVal["term_description"]+"</button></li>";
			})
			$("#term-list").empty().append(output);
		})
}

function loadDepartments(){
	url="/api/department/get/all";

	asyncAjax(url,
		function(data){
			output ="";
			$.each(data,function(dept, deptVal){
				output+= "<li style='margin-bottom:5px;width:100%;'><button class='btn btn-primary' style='width:100%;' onclick='showDeptDetail("+'"'+deptVal["department_abbreviation"]+'"'+")' >"+deptVal["department_abbreviation"]+"</button></li>";
			})
			$("#dept-list").empty().append(output);
		})
}

function newTerm(){
	url = "/api/admin/term/new/"+$("#new-term-number").val()+"/"+$("#new-term-desc").val()+"/"+$("#new-term-select").val();

	insertAjax(url,
		function(data){
			loadTerms();
			$("#newTermModal").modal("hide");
			$("#new-term-number").empty();
			$("#new-term-desc").empty();
			$("#new-term-select").empty();
		},
		function(){
			alert("failed")
		})
}

function newDepartment(){
	url = "/api/admin/add/department/"+$("#new-dept-name").val()+"/"+$("#new-dept-abbrev").val();

	insertAjax(url,
		function(data){
			//loadDepts();
			$("#newDepartmentModal").modal("hide");
			$("#new-dept-abbrev").empty();
			$("#new-dept-name").empty();
			loadDepartments();
		},
		function(){
			alert("failed")
		})
}

function newProgram(){
	url = "/api/admin/add/program/"+$("#new-program-name").val()+"/"+$("#new-program-code").val()+"/"+$("#program-select")+"/"+$("#dept-hidden").val();

	insertAjax(url,
		function(data){
			//loadDepts();
			$("#newProgramModal").modal("hide");
			$("#new-program-code").empty();
			$("#new-program-name").empty();
			showDeptDetail($("#dept-abbr").text())
		},
		function(){
			alert("failed");
		});
}



function showDeptDetail(abbrev){
	url = "/api/department/get/"+ abbrev;

	asyncAjax(url,
		function(data){
			console.log(data);
			$("#dept-desc").empty().append(data["deptInfo"][0].department_name);
			$("#dept-abbr").empty().append(data["deptInfo"][0].department_abbreviation);
			$("#dept-hidden").val(data["deptInfo"][0].department_id);
			string = "";
			$.each(data["programs"], function(key, val){
				string += "<li>"+val.program_code+ " - " + val.program_name+"</li>";
			});
			$("#program-list").empty().append(string);

		});
}
</script>

<?php getFooter(); ?>
