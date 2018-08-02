<?php
	
	require 'global.php';
	
	header('Content-Type: application/json');

	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$post_data = file_get_contents('php://input');
		$data = json_decode($post_data);
		
		$orderid = mysql_escape_string($data->orderid);
		$reason = mysql_escape_string($data->reason);
		$photo = mysql_escape_string(base64_decode($data->photo));
		$datetime = date("Y-m-d H:i:s");
		
		$conn = Database::getConnection();
		
		$report_problem = "INSERT INTO `problem_report` (`Order_ID`, `Reason`, `Photo`, `Report_date`) VALUES ('".$orderid."','".$reason."','".$photo."','".$datetime."')";
		
		if (mysqli_query($conn, $report_problem))
		{
			$problem['status'] = "success";
		}
		else
		{
			$problem['status'] = "failed";
		}
		
		if (isset($problem))
			echo json_encode($problem);

		mysqli_close($conn);
		
	}
?>