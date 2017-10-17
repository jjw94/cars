function insertAjax(url,callback,failed){
	var output ="";
	$.ajax({
		url: url,
	}).done(function(data){
		//success
		output = "<h4 style='color:lime'>Insert Successful</h4>";
		callback();
	}).fail(function(){
		output = "<h4 style='color:red'>Insert Failed</h4>";
		failed(output);
	});
}

function asyncAjax(url, callback){
	var output;
	$.ajax({
		url: url
	}).done(function(data){
		output = data;
		callback(data);
	}).fail(function(){
		alert("Failed");
	});

	//return output;
}

function ajaxPromise(url) {
	return $.ajax({
		url: url
	});
}

function makeSpinner(selector) {
	var spinnerHtml = '<div class="spinner"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>';
	$(selector).append(spinnerHtml);
	return $(selector + " .spinner");
}

function killSpinner(spinner) {
	$(spinner).remove();
}
