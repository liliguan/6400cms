<?php
include 'header.php';
$username=$_SESSION[session_id()]['Username'];
$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
$query_getid="select max(resourceid) as max from resource";
$result_getid=mysqli_query($con,$query_getid);
$row_getid=mysqli_fetch_array($result_getid,MYSQLI_ASSOC); 
$new_id=$row_getid['max']+1;
$_SESSION[session_id()]['New_ResourceId']=$new_id;
mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="jq.js"></script>
<head>
<body>
	<!--Main form -->
	<h4> New Resource Info </h4>
	<form action="add_new_resource.php" method="post" id ="new_resource_form">
	<table>
		<!-- row of  resource id -->
		<tr> <td> ResourceID </td> <td> <?php echo $new_id ?> </td> </tr>
		<!-- row of owner -->
		<tr> <td> Owner</td> <td> <?php echo $_SESSION[session_id()]['Name'] ?></td> </tr>
		<!-- row of resource name -->
		<tr> <td> Resource Name </td> <td> <input type="text" name="ResourceName" id="ResourceName" maxlength=45 > </td> <td><font color="red"></font></td></tr>
		<!-- row of resource description -->
		<tr> <td> Resource Description</td> <td><input type="text" name="Description" id="Description" maxlength=250 > </td><td><font color="red"></font></td></tr>
		<!-- row of primary esf -->
		<tr> <td> Primary ESF</td>
				<td> <select name="PrimaryESFNumber">
				<?php 
					foreach ($_SESSION['esf'] as $key => $value) {
						echo '<option value='.$key.'>'.$key.":".$value.'</option>';
						}
				?>
					</select></td></tr>
		<!-- row of addtional esfs-->
		<tr><td> Addtional ESFs</td>
			<td> <select multiple="multiple" name="Sec_ESFNumber[]" size="5">
				<?php
					foreach ($_SESSION['esf'] as $key => $value) {
						echo '<option value='.$key.'>'.$key.":".$value.'</option>';
					}
				?></select></td></tr>
		<!-- row of model -->
		<tr> <td> Model</td><td> <input type="text" name="ModelName" maxlength=45 id="ModelName" ></td><td><font color="red"></font></td></tr>
		<!-- Location-->
		<tr> <td>Location:Latitude </td> <td> <input type="text" name="ResourceLatitude" id="Latitude" maxlength=19 ></td><td><font color="red"></font></td></tr>
		<tr> <td>Location Longitude </td><td><input type="text" name="ResourceLongitude" id="Longitude" maxlength=20 ></td><td><font color="red"></font></td></tr>
		<!-- row of Cost Type-->
		<tr> <td>Cost Type</td>
			<td> <select name="CostType">
				<?php
				foreach($_SESSION['cost'] as $value) {
					echo '<option value='.$value.'>'.$value.'</option>';
				}?></select></td></tr>
		<!-- Cost Value-->
		<tr><td> Cost Value$</td> <td>  <input type="text" name="CostValue" id="CostValue"></td><td><font color="red"></font></td></tr>
		<!-- row of Capabilities-->
		<tr> <td> Capabilities</td>
			<td> <input type="text" id ="one_cap" > </td>
			<td> <button type="button" onclick="add_cap()">Add</button>
		</tr>
		<tr> <td></td> 
		<td>
			<textarea nrows="10" cols="50" name ="Capabilities" readonly="true" id="re_cap" >  </textarea></td>
			<script>
			function add_cap() {
				x=document.getElementById('one_cap');
				y=document.getElementById('re_cap');
				y.value=y.value+'\r\n'+x.value;
				x.value="";
			}</script>
		</tr>
		<!-- Submit and main menu button -->
		<tr> <td><input type="submit" name="Submit"><td><a href="main_menu.php">Main Menu</a></td></tr> 
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


		function check_range( obj, min, max, error_text)
		{
			if( obj.val() >= min && obj.val() <= max ){
				return prevent(obj, false, error_text);
			}else{
				return prevent(obj, true, error_text);
			}
		}

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

		////// events
		$("input#ResourceName").blur(function (){
			res_empty = check_empty($(this), error_1);
			if( !res_empty ){
				return;
			}
		});

		
		$("input#Description").blur(function (){
			res_empty = check_empty($(this), error_1);
			if( !res_empty ){
				return;
			}
		});


		$("input#Latitude").blur(function (){
			res_empty = check_empty($(this), error_1);
			if( !res_empty ){
				return;
			}
			res_type = check_type($(this), 'float', error_1) || check_type($(this,'int',error_1));
			if( !res_type ){
				return;
			}
			res_range = check_range($(this), -90, 90, error_1);
			if( !res_range ){
				return;
			}
		});

	
		$("input#Longitude").blur(function (){
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
	
		$("input#ModelName").blur(function (){
			res_empty = check_empty($(this), error_1);
			if( !res_empty ){
				return;
			}
		});

		$("input#CostValue").blur(function (){
			res_empty = check_empty($(this), error_1);
			if( !res_empty ){
				return;
			}
			res_type = check_type( $(this), 'int', error_1 );
			if( !res_type ){
					return;
			}

		});

		// submit event
		$("form").submit(function(){
			//resroucename check
	
			res_empty = check_empty($('input#ResourceName'), error_1);
			if( !res_empty ){
				return false;
			}
		
			// description check
			res_empty = check_empty($('input#Description'), error_1);
			if( !res_empty ){
				return false;
			}

			//Latitude check
			res_empty = check_empty($('input#Latitude'), error_1);
			if( !res_empty ){
				return false;
			}
			res_type = check_type($('input#Latitude'), 'float', error_1);
			if( !res_type ){
				return false;
			}
			res_range = check_range($('input#Latitude'), -90, 90, error_1);
			if( !res_range ){
				return false;
			}

			//longtitude check
			res_empty = check_empty($('input#Longitude'), error_1);
			if( !res_empty ){
				return false;
			}
			res_type = check_type($('input#Longitude'), 'float', error_1);
			if( !res_type ){
				return false;
			}
			res_range = check_range($('input#Longitude'), -180, 180, error_1 );
			if( !res_range ){
				return false;
			}

			//costvalue check
			res_empty = check_empty($('input#CostValue'), error_1);
			if( !res_empty ){
				return false;
			}
			res_type = check_type( $('input#CostValue'), 'int', error_1 );
			if( !res_type ){
					return false;
			}

			//modelname check
			res_empty = check_empty($('input#ModelName'), error_1);
			if( !res_empty ){
				return false;
			}
		});


	</script>
</body>
</html>
