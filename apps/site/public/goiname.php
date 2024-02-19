<?

$servername = "";
$username   = "";
$password   = "";
$dbname     = "";
$port=3306;

if (!isset($_REQUEST['ajax']))
    die("Lỗi");
$conn = new mysqli($servername, $username, $password, $dbname,$port);
// sửa lại truy vấn cho phù hợp với file js

//json mẫu {"name":"Mèo meo meo","user_id":12,"desc":"gói name test","tag":"lol,conan,meomeo","data":"$test=meo"}
switch ($_REQUEST['ajax']) {
    case "mynamepack": //get tất cả gói name của user hiện tại
        if (!isset($_REQUEST['user_id'])){
            die("Lỗi");
        }
        $id =  $_REQUEST['user_id'];      // up lên host nên kiểm tra user id, đây là bản demo nên bỏ luôn trong request
        $query = 'SELECT * FROM packagename WHERE user_id = ? ' ;
        $sql = $conn->prepare($query);
        $sql->bind_param('i', $id);
        $sql->execute();
        $result = $sql->get_result();
        $sql->close();
        while ($tempRow = $result->fetch_assoc()) {
            if($tempRow['tag']!=""){
                $taglist = explode(",", $tempRow['tag']);
                $tags= "<h3>".implode("\\taglist/", $taglist)."</h3>";
            }else $tags="";

            echo "$tempRow[name] | $tags<br>
                $tempRow[description] | $tempRow[download]  Lượt tải <br> Độ dài ".strlen($tempRow['content'])." ký tự <br>
                 Thời gian edit: $tempRow[addtime] <br>
                 ID gói name: $tempRow[id]";
        }
        break;
    case "downcustomname": //tải gói name bất kỳ của người khác xuống.
        if (!isset($_REQUEST['id'])){
            die("Lỗi");
        }
        $id = $_REQUEST['id'];
        $query = "SELECT id,content FROM packagename WHERE id= ?";
        $sql = $conn->prepare($query);
        $sql->bind_param('i', $id);
        $sql->execute();
        $result = $sql->get_result();
        $sql->close();
        if($result->num_rows>0){
            $tempRow = $result->fetch_assoc();
            echo  $tempRow['content'];
            $conn->query("UPDATE packagename SET download=download+1 WHERE id= $tempRow[id]");
        }else{
            die("error");
        }
        break;
    case "makenewnamepack": //upload gói name mới lên server, json encode từ js
        if (!isset($_REQUEST['data'])){
            die("Lỗi");
        }
        $data = json_decode($_REQUEST['data'],true); //giải mã json xử lý
        if($data==null){
            die("Lỗi không xác định.");
        }
        $name=$data['name'];
        $desc=$data['desc'];
        $tag=$data['tag'];
        $content=$data['data'];
        $user_id=$data['user_id']; // up lên host nên kiểm tra user id, đây là bản demo nên bỏ luôn trong json
        if(strlen($name)<6){
            die("Tên quá ngắn.");
        }
        if(strlen($content)<1){
            die("Gói name quá ngắn.");
        }
        $query = "INSERT INTO packagename(name,user_id,content,tag,description) 
        VALUES(?,?,?,?,?)";
        $sql = $conn->prepare($query);
        $sql->bind_param('sisss', $name,$user_id,$content,$tag,$desc);
        $sql->execute();
        $sql->close();
		echo "success";
        break;
    case "editnamepack": //download gói name của chính mình để edit
        if (!isset($_REQUEST['id'])||!isset($_REQUEST['user_id'])){
            $sql->close();
            die("Lỗi");
        }
        $user_id = $_REQUEST['user_id'];  // up lên host nên kiểm tra user id, đây là bản demo nên bỏ luôn trong request
        $id = $_REQUEST['id'];
        $query = 'SELECT * FROM packagename WHERE user_id = ? and id = ?' ;
        $sql = $conn->prepare($query);
        $sql->bind_param('ii',$user_id, $id);
        $sql->execute();
        $result = $sql->get_result();
        $sql->close();
        if($result->num_rows>0){
            $tempRow = $result->fetch_assoc();
            $obj = new stdClass();
            $obj->code=0;
            $obj->name = $tempRow['name'];
            $obj->desc = $tempRow['description'];
            $obj->tag = $tempRow['tag'];
            $obj->data = $tempRow['content'];
            echo json_encode($obj);
        }else{
            die('{"error":"Không phải chủ gói name","code":1}');
        }
        break;
    case "editnamepacked"; //upload gói name chỉnh sửa của mình lên server,json encode từ js
        if (!isset($_REQUEST['data'])){
            die("Lỗi");
        }
        $data = json_decode($_REQUEST['data'],true); //giải mã json xử lý
        if($data==null){
            die("Lỗi");
        }
        $id = $data['id'];
        $name=$data['name'];
        $desc=$data['desc'];
        $tag=$data['tag'];
        $content=$data['data'];
        $user_id=$data['user_id']; // up lên host nên kiểm tra user id, đây là bản demo nên bỏ luôn trong json
        if(strlen($name)<6){
            die("Tên quá ngắn.");
        }
        if(strlen($content)<100){
            die("Gói name quá ngắn.");
        }
        $query = 'SELECT * FROM packagename WHERE user_id = ? and id = ?' ;
        $sql = $conn->prepare($query);
        $sql->bind_param('ii',$user_id, $id);
        $sql->execute();
        $result = $sql->get_result();
        if($result->num_rows>0){
            $query = "UPDATE packagename SET name = ?,content = ?,tag = ?,description = ? WHERE id = ? and user_id= ?";
            $sql = $conn->prepare($query);
            $sql->bind_param('ssssii',$name,$content,$tag,$desc,$user_id, $id);
            $sql->execute();
            $sql->close();
            echo "success";
        }else{
            die('{"error":"Không phải chủ gói name không thể edit","code":1}');
        }
        break;
    case "searchnamepack"; //tìm kiếm và load gói name
        if (!isset($_REQUEST['keyw'])){
            die("Lỗi");
        }
        $key = "%{$_REQUEST['keyw']}%";
        $query = "SELECT * FROM packagename WHERE name LIKE ? OR tag LIKE ? ORDER BY download DESC LIMIT 20";
        $sql = $conn->prepare($query);
        $sql->bind_param('ss',$key, $key);
        $sql->execute();
        $result = $sql->get_result();
        $sql->close();
        while ($tempRow = $result->fetch_assoc()) {
            if($tempRow['tag']!=""){
                $taglist = explode(",", $tempRow['tag']);
                $tags= "<h3>".implode("\\taglist/", $taglist)."</h3>";
            }else $tags="";

            echo "$tempRow[name] | $tags<br>
                $tempRow[description] | $tempRow[download]  Lượt tải <br> Độ dài ".strlen($tempRow['content'])." ký tự <br>
                 Thời gian edit: $tempRow[addtime] <br>
                 ID gói name: $tempRow[id]";
        }
        break;
    default: //mặc định
        echo ("Meo Meo");
        break;
}


?>
