<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<h4>New Incident Info</h4>
</head>
<body>
<?php
	if ($_SESSION['currentid']!=session_id()) {
		header("log_in.php");
		exit;
	}
	else {
		$username=$_SESSION[session_id()]['Username'];
	}
	$con = mysqli_connect("localhost","root","123456","erms");
	$query_getid="select max(incidentid) as max from incident";
	$result_getid=mysqli_query($con,$query_getid);
	 

	if (mysqli_num_rows($result_getid)==0) {
		$new_id=1;
	}
	else {
		$row_getid=mysqli_fetch_array($result_getid,MYSQLI_ASSOC);
		$new_id=$row_getid['max']+1;
	}
	//$_SESSION[session_id()]['New_ResourceId']=$new_id;
	$incident_date_err="";
	$incident_des_err="";
	$Latitude_err="";
	$Longitude_err="";
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$des_success=0;
		$date_success=0;
		$longitude_success=0;
		$latitude_success=0;
		//check descripton
		if (empty($_GET['IncidentDescription'])) {
			$incident_des_err="Description is mandatory";
		} else {
			$des_success=true;
		}

		//check date
		if (empty($_GET['IncidentDate'])) {
			$incident_date_err="Date is mandatory";
		} elseif (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_GET['IncidentDate'])) {
			$incident_date_err="Please input valid date";
		} else {
			$date_success=true;
		}
		//check latitude
		if (empty($_GET['IncidentLatitude'])){
			$Latitude_err="Latitude is mandatory";
		} elseif (!preg_match('/^-?\d*\.{0,1}\d+$/', $_GET['IncidentLatitude']) || (float)$_GET['IncidentLatitude'] < -90 || (float)$_GET['IncidentLatitude'] >90 ) {
			$Latitude_err="Please input valid Latitude";
		} else {
			$_GET['IncidentLatitude']=(float)$_GET['IncidentLatitude'];
			$latitude_success=true;
		}
		//check longitude
		if (empty($_GET['IncidentLongitude'])) {
			$Longitude_err="Longitude is mandatory";
		} elseif (!preg_match('/^-?\d*\.{0,1}\d+$/',$_GET['IncidentLongitude']) ||(float)$_GET['IncidentLongitude'] <-180 || (float)$_GET['IncidentLongitude'] >180) {
			$Longitude_err="Please input valid Longitude";
		} else {
			$_GET['IncidentLongitude']=(float)$_GET['IncidentLongitude'];
			$longitude_success=true;
		}
		if ($date_success && $latitude_success && $longitude_success && $des_success) {
				$query_add_incident="INSERT INTO Incident (IncidentDescription,IncidentLongitude,IncidentLatitude,OwnerUsername,IncidentDate) VALUES 
			('".$_GET['IncidentDescription']."',".$_GET['IncidentLongitude'].",".$_GET['IncidentLatitude'].",'".$_SESSION[session_id()]['Username']."','".$_GET['IncidentDate']."');";
			echo $query_add_incident;
			$result_add_incident=mysqli_query($con,$query_add_incident);

		}
	}
?>
<form action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="get">
<table>
	<!-- row of incident id -->
	<tr><td> Incident ID</td><td> <?php echo $new_id ?></td></tr>
	<tr> <td> Date Format:yyyy-mm-dd</td> <td><input type="text" name="IncidentDate"> </td><td> <font color="red"> <?php echo $incident_date_err ?></font></td></tr>
	<tr> <td> Description </td><td> <input type="text" name ="IncidentDescription" > </td><td> <font color="red"> <?php echo $incident_des_err ?> </font></td></tr>
	<tr><td> Location:Latitude</td><td><input type="text" name="IncidentLatitude"> </td> <td><font color="red"> <?php echo $Latitude_err?></font></td></tr>
	<tr><td> Location:Longitude</td><td> <input type="text" name="IncidentLongitude"></td><td> <font color="red"> <?php echo $Longitude_err?></font></td></tr>
	<tr><td> <input type="submit" value="Submit" name="submit"></td></tr>
</table>
</form>

</body>
</html>
