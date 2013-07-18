function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		vars[key] = value;
	});
	return vars;
}


function sg_initing() {
	
var sg_reg = getUrlVars()["cg-reg"];
if(sg_reg == "1") {
	jQuery("#sg-statement").show();
}

}

function repl_titl() {
	if(jQuery("#biz_name").val() == "Business Title") {
		jQuery("#biz_name").val(""); 
	} else if(jQuery("#biz_name").val() == "") {
		jQuery("#biz_name").val("Business Title"); 
	}
}

function repl_user() {
	if(jQuery("#user_login").val() == "Username") {
		jQuery("#user_login").val(""); 
	} else if(jQuery("#user_login").val() == "") {
		jQuery("#user_login").val("Username"); 
	}
}

function repl_email() {
	if(jQuery("#user_email").val() == "E-Mail") {
		jQuery("#user_email").val(""); 
	} else if(jQuery("#user_email").val() == "") {
		jQuery("#user_email").val("E-Mail"); 
	}
}