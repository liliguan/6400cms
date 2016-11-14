<?php
	include 'header.php';
	$username=$_SESSION[session_id()]['Username'];


?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<h3> Search Results for Incident <?php 
		if ($_POST['corresponding_incident']!='none'){
			echo $_POST['corresponding_incident'].
		} 


	?>
	</h3>
</head>
<body>
<?php
$con = mysqli_connect($mysql_info['host'],$mysql_info['user'],$mysql_info['pwd'],$mysql_info['dbname']);
	if ($_SERVER['REQUEST_METHOD']=="POST") {
		$query_get_results="SELECT ResourceID,ResourceName,CostType,CostValue, NextAvailableDate, ResourceStatus,User.name AS Owner FROM Resource INNER JOIN User ON ResourceOwner=User.Username;";
		$row_get_results=mysqli_query($con,$query_get_results);
		if (mysqli_num_rows($row_get_results)!=0) {

		}
	}
?>
</body>
</html>