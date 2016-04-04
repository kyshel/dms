<?php

//Display tabel's data in table layout
//When you modify db_tables, you don't need to modify code
//This function require an outside $db connection object created by "new mysqli()"
//Example: 
// $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// if($db->connect_errno > 0){
//     die('Unable to connect to database [' . $db->connect_error . ']');
// }
// if (!$db->set_charset("utf8")) {
//     die('Unable to change character set to utf8 [' . $db->error . ']');
// }
function table_get($table_name){
	global $db;
	$sql="SELECT * FROM ".$table_name;
	$result = $db->query($sql);
	echo "<table border='1' class='table table-bordered'>";
	$i = 0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		if($i == 0){
			echo "<tr>";
			foreach($row as $x=>$x_value) {
				echo "<th>";
				echo $x;
				echo "</th>";
			}
			echo "</tr>";			
		}
		echo "<tr>";
		foreach($row as $x=>$x_value) {
			echo "<td>" .$x_value."</td>" ;
		}
		echo "</tr>";
		$i=1;
	}
}



function php_self(){
    $php_self=substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
    return $php_self;
}

// need
function add_log($action,$db){
	//global $_SERVER['REMOTE_ADDR'];
	//global $_SERVER['HTTP_X_FORWARDED_FOR'];
	$log_time=date("Y-m-d  H:i:s");
	$user_name=$_SESSION['user_name'];
	$user_agent=$_SERVER['HTTP_USER_AGENT'];
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ipa=$_SERVER['REMOTE_ADDR'];
		$ipb=$_SERVER['HTTP_X_FORWARDED_FOR'];
		$sql_log="INSERT INTO log(log_time,user_name,action,ipa,ipb,user_agent) VALUES ('$log_time','$user_name','$action','$ipa','$ipb','$user_agent')";
	}else{
		$ipa=$_SERVER['REMOTE_ADDR'];
		$sql_log="INSERT INTO log(log_time,user_name,action,ipa,user_agent) VALUES ('$log_time','$user_name','$action','$ipa','$user_agent')";
	}

	$db->query($sql_log) or die($db->error);
}
//add_page need
function drop_list_20($col,$i){
	echo "<select name='$col$i' id='$col$i' class= 'form-control'>";
	echo '
	<option value="20" selected="selected">20</option>
	<option value="15">15</option>
	<option value="10">10</option>
	<option value="5">5</option>
	<option value="0">0</option>
	</select>
	';
}

function role_siderbar($user_role){
	switch($user_role)
	{
		case 1:

		// echo "<li ";
		// if (php_self() == 'display.php') { echo 'class="active" '; } 
		// echo "><a href="display.php">显示 </a></li><li ";
		// if (php_self() == 'add.php') { echo 'class="active" '; } 
		// echo "><a href="add.php">添加</a></li><!-- <li ";
		// if (php_self() == 'update.php') { echo 'class="active" '; } 
		// echo "><a href="update.php">更新</a></li> --><li ";
		// if (php_self() == 'del.php') { echo 'class="active" '; } 
		// echo "><a href="del.php">删除</a></li><li ";
		// if (php_self() == 'import.php') { echo 'class="active" '; } 
		// echo "><a href="import.php">导入</a></li><li ";
		// if (php_self() == 'log.php') { echo 'class="active" '; } 
		// echo "><a href="log.php">日志</a></li>";


		echo "<li ";
		if (php_self() == 'del.php') { echo 'class="active" '; } 
		echo '><a href="del.php">删除</a></li>';
		echo "<li ";
		if (php_self() == 'import.php') { echo 'class="active" '; } 
		echo '><a href="import.php">导入</a></li>';
		//echo "<li ";
		// if (php_self() == 'display_table.php') { echo 'class="active" '; } 
		// echo '><a href="display_table.php">显示底层表</a></li>';
		break;

		case 2:
		echo "<li ";
		if (php_self() == 'del.php') { echo 'class="active" '; } 
		echo '><a href="del.php">删除</a></li>';
		break;

		case 3:
		echo "<li ";
		if (php_self() == 'del.php') { echo 'class="active" '; } 
		echo '><a href="del.php">删除</a></li>';
		break;

		case 4:
		echo "";
		break;

		default:
		die("<h1>你没有权限查看此页</h1>");
	}

}

function role_siderbar_develop($user_role){
	switch($user_role)
	{
		case 1:

		echo "<li ";
		if (php_self() == 'd_log.php') { echo 'class="active" '; } 
		echo '><a href="d_log.php">开发日志</a></li>';
		break;

		case 2:
		echo "";
		break;

		case 3:
		echo "";
		break;

		case 4:
		echo "";
		break;

		default:
		die("<h1>你没有权限查看此页</h1>");
	}

}

function check_permission($level){

	$user_role=$_SESSION['user_role'];
	$user_role += 0;
	if($user_role > $level){
		die("你没有权限查看此页！");
	}
	// echo gettype($user_role), "\n";
	// echo gettype($level), "\n";
}





	



?>