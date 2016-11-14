<?php
	include"header.php";
	$username=$_SESSION[session_id()]['Username'];
	$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	$query_getid="select max(incidentid) as max from incident";
	$result_getid=mysqli_query($con,$query_getid);
	 

	if (mysqli_num_rows($result_getid)==0) {
		$new_id=1;
	}
	else {
		$row_getid=mysqli_fetch_array($result_getid,MYSQLI_ASSOC);
		$new_id=$row_getid['max']+1;
	}

?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="jq.js"></script>
	<title></title>
</head>
<body>
	<h4>New Incident Info</h4>
	<form action="add_new_incident.php" method="post" id="new_incident_form"> 
	<table>
	<!-- Main Form -->
	<tr><td> Incident ID</td><td> <?php echo $new_id ?></td></tr>
	<tr> <td> Date Format:yyyy-mm-dd</td> <td><input type="text" name="IncidentDate"> </td><td> <font color="red"> </font></td></tr>
	<tr> <td> Description </td><td> <input type="text" name ="IncidentDescription" > </td><td> <font color="red"> </font></td></tr> 
	<tr><td> Location:Latitude</td><td><input type="text" name="IncidentLatitude"> </td> <td><font color="red"> </font></td></tr>
	<tr><td> Location:Longitude</td><td> <input type="text" name="IncidentLongitude"></td><td> <font color="red"> </font></td></tr>
	<tr><td> <input type="submit" value="Submit" name="submit"></td></tr>
	</table>
	</form>
<script>
		error_1 = 'Invalid Input';
		

		////// functions
		function check_empty(obj, error_text)
		{
			if(obj.val().length <= 0 )
			{
				return prevent(obj,true, error_text);	
			}else{
				return prevent(obj,false, error_text);
			}
		}

		// check type of the value
		function check_type(obj, type, error_text)
		{
			if( type == 'float'){
				if(obj.val().match( /^-{0,1}[0-9]{1,3}\.[0-9]{1,9}$/) == null ){
					return prevent(obj,true, error_text);
				}
			}

			if( type == 'int'){
				if(obj.val().match( /^[0-9]+$/ ) == null ){
					return prevent(obj,true, error_text);
				}
			}

			if( type == 'string'){
				if(obj.val().match( /^[a-z][A-Z]+$/ ) == null){
					return prevent(obj,true, error_text);
				}
			}

			return prevent(obj,false);
		}


		function check_range(obj, min, max, error_text)
		{
			if( obj.val() >= min && obj.val() <= max ){
				return prevent(obj, false, error_text);
			}else{
				return prevent(obj, true, error_text);
			}
		}

		/* need check date 

		function check_date(obj,)

		*/
		function prevent(obj, flag, error_text)
		{
			if(flag){
				$('input:submit').attr('disabled',true);
				obj.parent().next().find('font').text(error_text);
				return false
			}else{
				
				$('input:submit').attr('disabled',false);
				obj.parent().next().find('font').text('');
				return true;
			}
		}	

		
		$("input#IncidentDescription").blur(function (){
			res_empty = check_empty($(this), error_1);
			if( !res_empty ){
				return;
			}
		});
		$("input#IncidentLongitude").blur(function (){
			res_empty = check_empty($(this), error_1);
			if( !res_empty ){
				return;
			}
			res_type = check_type($(this), 'float', error_1) || check_type($(this,'int',error_1));
			if( !res_type ){
				return;
			}
			res_range = check_range($(this), -180, 180, error_1 );
			if( !res_range ){
				return;
			}
		});

		$("input#IncidentLatitude").blur(function (){
			res_empty = check_empty($(this), error_1);
			if( !res_empty ){
				return;
			}
			res_type = check_type($(this), 'float', error_1) || check_type($(this,'int',error_1));
			if( !res_type ){
				return;
			}
			res_range = check_range($(this), -180, 180, error_1 );
			if( !res_range ){
				return;
			}
		});
		$("form").submit(function(){
			
			// description check
			res_empty = check_empty($('input#IncidentDescription'), error_1);
			if( !res_empty ){
				return false;
			}

			//Latitude check
			res_empty = check_empty($('input#IncidentLatitude'), error_1);
			if( !res_empty ){
				return false;
			}
			res_type = check_type($('input#IncidentLatitude'), 'float', error_1) || check_type($('input#IncidentLatitude'),'int',error_1);
			if( !res_type ){
				return false;
			}
			res_range = check_range($('input#IncidentLatitude'), -90, 90, error_1);
			if( !res_range ){
				return false;
			}

			//longtitude check
			res_empty = check_empty($('input#IncidentLongitude'), error_1);
			if( !res_empty ){
				return false;
			}
			res_type = check_type($('input#IncidentLongitude'), 'float', error_1) || check_type($('input#IncidentLongitude'),'int',error_1);
			if( !res_type ){
				return false;
			}
			res_range = check_range($('input#IncidentLongitude'), -180, 180, error_1 );
			if( !res_range ){
				return false;
			}
		});





</script>
</body>
</html>

