<?php
$servername = "";
$username   = "";
$password   = "";
$dbname     = "";
$port=3306;
 
if (!isset($_REQUEST['ajax']))
    die("Lỗi");
$conn = new mysqli($servername, $username, $password, $dbname,$port);
//sửa lại cho hợp yêu cầu với file js, hoặc trả về html theo frontend
switch ($_REQUEST['ajax']) {
	case "namesys": 
		if (!isset($_REQUEST['book'])||!isset($_REQUEST['host']))
					die("lỗi");
				
		$book_id =  $_REQUEST['book'];    
		$host =   $_REQUEST['host'];
		$query = "select * from uploadedname where host = ? and book_id = ? order by `time` desc limit 20" ; 
		$sql = $conn->prepare($query);
		$sql->bind_param('ss', $host,$book_id);
		$sql->execute();
		$result = $sql->get_result();
		$sql->close();
		if ($result->num_rows > 0) {
			while ($temprow = $result->fetch_assoc()) {
				echo "$temprow[user_id] | $temprow[time]  lượt tải <br> độ dài $temprow[length] ký tự <br>
					 thời đăng: $temprow[data] <br>";
			}
		}
		break;
	case "upload":
		if (!isset($_REQUEST['data'])||!isset($_REQUEST['bookid'])||!isset($_REQUEST['host'])||!isset($_REQUEST['username']))
					die("Lỗi");
		$data= $_REQUEST['data'];
		$data= urldecode($data);
		if(strlen($data)<100){
		   die("Name quá ngắn");
		}			
		$host = $_REQUEST['host'];
		$bookid = $_REQUEST['bookid'];
		$username = $_REQUEST['username'];		 // có thể dùng như tên gói
		$query = "INSERT INTO uploadedname (host,book_id,user_id,data,length) VALUES(?,?,?,?,".strlen($data).")"; 
		$sql = $conn->prepare($query);
		$sql->bind_param('ssss', $host,$book_id,$username,$data);
		$sql->execute();
		$sql->close();
		die(" success");
		break;
	default:
		echo "meo meo";
}
