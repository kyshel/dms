<?php
require_once("auth.php"); // verify whether login
require_once("db_connection.php");




if($_GET["step"]=="1"){
	$region=$_GET["region"];
	if ($region=="1") {
		$region="南";
	}elseif ($region=="2") {
		$region="北";
	}else{
		die();
	}
	echo '<option value="" selected="selected">请选择楼号</option>';
	$sql="SELECT DISTINCT build_num FROM dorm WHERE region = '".$region."'";
	$result = $db->query($sql);
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		if ($row['build_num']==0) {
			echo "<option value='".$row['build_num']."'>" . "不住宿" . "</option>";
		}else{
		echo "<option value='".$row['build_num']."'>" . $row['build_num'] . "号楼</option>";
		}	
	}

}elseif ($_GET["step"]=="2") {
	$build_num=$_GET["build_num"];
	$region=$_GET["region"];
	if ($region=="1") {
		$region="南";
	}elseif ($region=="2") {
		$region="北";
	}else{
		die();
	}
	echo '<option value="" selected="selected">请选择AB区</option>';
	$sql="SELECT DISTINCT part FROM dorm WHERE build_num = '".$build_num."' and region = '".$region."'";
	$result = $db->query($sql);
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		if ($row['part']==0) {
			//echo "<option value='".$row['part']."'>" . "此楼不分AB区" . "</option>";
			echo "<option value='".$row['part']."'>" . "此楼不分AB区" . "</option>";
		}else{
		echo "<option value='".$row['part']."'>" . $row['part'] . "区</option>";
		}
	}
}elseif ($_GET["step"]=="3") {
	$region=$_GET["region"];
	if ($region=="1") {
		$region="南";
	}elseif ($region=="2") {
		$region="北";
	}else{
		die("不住宿");
	}
	$build_num=$_GET["build_num"];
	$part=$_GET["part"];
	echo '<option value="" selected="selected">请选择楼层</option>';
	$sql="SELECT DISTINCT floor FROM dorm WHERE build_num = '".$build_num."' and region = '".$region."' and part = '".$part."'";
	$result = $db->query($sql);
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		echo "<option value='".$row['floor']."'>" . $row['floor'] . "层</option>";
	}
}
/*
echo '
<!--This is for check box-->
<link href="css/bootstrap-switch.min.css" rel="stylesheet">
<script src="js/bootstrap-switch.min.js"></script>
<script>
$(function(argument) {
  $(\'[type="checkbox"]\').bootstrapSwitch();
 $(document).ajaxComplete(function(event, xhr, settings) {
      $(\'[type="checkbox"]\').bootstrapSwitch();
   });
})
</script>
';*/
//>>>>>>>>>>>>>>>>>>>>>View Code at here>>>>>>>>>>>>>>>>>>>>
elseif ($_GET["view_step"]=="1") {
	$date=$_GET["date"];
	//echo $date;
echo '<p>您选择的日期为：<strong class="text-danger">'.$date.'</strong>  &nbsp;&nbsp;   <strong class="bg-success text-success">绿色</strong>代表有记录 (<strong>0区</strong>代表此楼不分AB区)</p> ';
echo '<hr />';



$sql_region="SELECT DISTINCT region FROM dorm";
$result_region= $db->query($sql_region);

while($row_region = $result_region->fetch_array(MYSQLI_ASSOC)){
    //echo $row_region['region'];
    $region = $row_region['region'];

    $sql_build_num="SELECT DISTINCT build_num FROM dorm WHERE region = '$region' ORDER BY build_num ASC";
    $result_build_num= $db->query($sql_build_num);
    while($row_build_num = $result_build_num->fetch_array(MYSQLI_ASSOC)){
        //echo $row_build_num['build_num'];
        $build_num = $row_build_num['build_num'];


        $sql_part="SELECT DISTINCT part FROM dorm WHERE build_num = '$build_num' and region = '$region'";
        $result_part= $db->query($sql_part);
        while($row_part = $result_part->fetch_array(MYSQLI_ASSOC)){
            //echo $row_part['part'];
            $part = $row_part['part'];

        echo '<table class="table table-bordered _responsive-utilities build_model" >
            <tr><th>'.$build_num.'号楼'.$part.'区</th></tr>';
            

            $sql_floor="SELECT DISTINCT floor FROM dorm WHERE part = '$part' and build_num = '$build_num' and region = '$region' ORDER BY floor DESC";
            $result_floor= $db->query($sql_floor);
            while($row_floor = $result_floor->fetch_array(MYSQLI_ASSOC)){
                //echo $row_floor['floor'];
                $floor = $row_floor['floor'];

                $this_floor=$region."$build_num"."#"."$part"."区"."$floor"."层";
                $sql_check="SELECT * FROM routine_add WHERE add_floor = '$this_floor' and date = '$date'";
                $result_of_check = $db->query($sql_check);
                if ($result_of_check->num_rows != 0){
                    echo '<tr><td class="is_green">';
					$str="date=$date&region=$region&build_num=$build_num&part=$part&floor=$floor";
					echo "<button name = 'add_step1' class='btn btn-success' value = '$str' style='width: 100%;' onclick='get_dorm_list(this.value)'>".$floor."层</button>";
					                  
                    echo '</td></tr>';
                }else{
                    echo '<tr><td class="is_gray">';

					$str="date=$date&region=$region&build_num=$build_num&part=$part&floor=$floor";
					//echo "<p>$str</p>";
					//echo "<p>$this_floor</p>";
					//$str="bbbbbbbbbbbb";
					echo "<a href='#dorm_top'><button name = 'add_step1' class='btn btn-default' value = '$str' style='width: 100%;' onclick='get_dorm_list(this.value)'>".$floor."层</button></a>";
					
                    
                    echo '</td></tr>';
                }


               
            }
        echo '</table>';
        }
    }
}
echo '
    </div>
</div>

<style type="text/css">
td.is_green {
    color: #468847;
    background-color: #dff0d8!important;
}                       
td.is_gray {
    color: #ccc;
    background-color: #f9f9f9!important;
}
.build_model{
    width: 100px ;
    /*float: left;*/
    vertical-align: bottom;
    display: inline-table;
    /* ie6/7 */
    *display: inline;
    zoom: 1;
    margin-left:10px;
}

.build_model_container{

}
</style>
';


// echo '
// <!--This is for check box-->
// <link href="css/bootstrap-switch.min.css" rel="stylesheet">
// <script src="js/bootstrap-switch.min.js"></script>
// <script>
// $(function(argument) {
//   $(\'[type="checkbox"]\').bootstrapSwitch();
//  $(document).ajaxComplete(function(event, xhr, settings) {
//       $(\'[type="checkbox"]\').bootstrapSwitch();
//    });
// })
// </script>
// ';








// $sql_region="SELECT DISTINCT region FROM dorm";
// $result_region= $db->query($sql_region);

// while($row_region = $result_region->fetch_array(MYSQLI_ASSOC)){
//     //echo $row_region['region'];
//     $region = $row_region['region'];

//     $sql_build_num="SELECT DISTINCT build_num FROM dorm WHERE region = '$region' ORDER BY build_num ASC";
//     $result_build_num= $db->query($sql_build_num);
//     while($row_build_num = $result_build_num->fetch_array(MYSQLI_ASSOC)){
//         //echo $row_build_num['build_num'];
//         $build_num = $row_build_num['build_num'];


//         $sql_part="SELECT DISTINCT part FROM dorm WHERE build_num = '$build_num' and region = '$region'";
//         $result_part= $db->query($sql_part);
//         while($row_part = $result_part->fetch_array(MYSQLI_ASSOC)){
//             //echo $row_part['part'];
//             $part = $row_part['part'];

//         echo '<table class="table table-bordered _responsive-utilities build_model" >
//             <tr><th>'.$build_num.'号楼'.$part.'区</th></tr>';
            

//             $sql_floor="SELECT DISTINCT floor FROM dorm WHERE part = '$part' and build_num = '$build_num' and region = '$region' ORDER BY floor DESC";
//             $result_floor= $db->query($sql_floor);
//             while($row_floor = $result_floor->fetch_array(MYSQLI_ASSOC)){
//                 //echo $row_floor['floor'];
//                 $floor = $row_floor['floor'];

//                 $this_floor=$region."$build_num"."#"."$part"."区"."$floor"."层";
//                 $sql_check="SELECT * FROM routine_add WHERE add_floor = '$this_floor' and date = '$date'";
//                 $result_of_check = $db->query($sql_check);
//                 if ($result_of_check->num_rows != 0){
//                     echo '<tr><td class="is_green">
//                		<form action="display.php" style="margin: 0;" method="post">';
//                     echo "<input name = 'date' value = '$date' style='display: none;' />";
// 					echo "<input name = 'region' value = '$region' style='display: none;' />";
// 					echo "<input name = 'build_num' value = '$build_num' style='display: none;' />";
// 					echo "<input name = 'part' value = '$part' style='display: none;' />";
// 					echo "<input name = 'floor' value = '$floor' style='display: none;' />";
// 					echo "<button name = 'display_date_floor' class='btn btn-success' value = 'display_date_floor' type='submit' style='width: 100%;' >".$floor."层</button>";
// 					echo '
//                     </form>';                  
//                     echo '</td></tr>';
//                 }else{
//                     echo '<tr><td class="is_gray">
//                		<form style="margin: 0;" action="add.php" method="post">';
//                     echo "<input name = 'date' value = '$date' style='display: none;' />";
// 					echo "<input name = 'region' value = '$region' style='display: none;' />";
// 					echo "<input name = 'build_num' value = '$build_num' style='display: none;' />";
// 					echo "<input name = 'part' value = '$part' style='display: none;' />";
// 					echo "<input name = 'floor' value = '$floor' style='display: none;' />";
// 					echo "<button name = 'add_step1' class='btn btn-default' value = 'add_step1' type='submit' style='width: 100%;' >".$floor."层</button>";
// 					echo '
//                     </form>';
                    
//                     echo '</td></tr>';
//                 }


               
//             }
//         echo '</table>';
//         }
//     }
// }
// echo '
//     </div>
// </div>

// <style type="text/css">
// td.is_green {
//     color: #468847;
//     background-color: #dff0d8!important;
// }                       
// td.is_gray {
//     color: #ccc;
//     background-color: #f9f9f9!important;
// }
// .build_model{
//     width: 100px ;
//     /*float: left;*/
//     vertical-align: bottom;
//     display: inline-table;
//     /* ie6/7 */
//     *display: inline;
//     zoom: 1;
//     margin-left:10px;
// }

// .build_model_container{

// }
// </style>
// ';



}


elseif ($_GET["view_step"]=="2"){
	$date=$_GET["date"];
	$region=$_GET["region"];
	if ($region=="1") {
		$region="南";
	}elseif ($region=="2") {
		$region="北";
	}
	// else{
	// 	die("不住宿");
	// }

	$build_num=$_GET["build_num"];
	$part=$_GET["part"];
	$floor=$_GET["floor"];

	//echo "$region$build_num$part$floor";
	//$sql="SELECT * FROM dorm";
	$sql="SELECT * FROM dorm WHERE region = '$region' and build_num = '$build_num' and part = '$part' and floor = '$floor'";
	$result = $db->query($sql) or die($db->error);

	$floor_add=$region.'苑'.$build_num.'号楼'.$part.'区'.$floor.'层';
	echo '<p>您选择的日期为：<strong class="text-danger">'.$date.'</strong>  &nbsp;&nbsp;  楼层为：<strong class="text-danger">'.$floor_add.'</strong>  &nbsp;&nbsp;  (<strong>0区</strong>代表此楼不分AB区)</p> ';

	echo '<form method="post" action="view_add.php">';

	echo "<input name = 'date' value = '$date' style='display: none;' />";
	echo "<input name = 'region' value = '$region' style='display: none;' />";
	echo "<input name = 'build_num' value = '$build_num' style='display: none;' />";
	echo "<input name = 'part' value = '$part' style='display: none;' />";
	echo "<input name = 'floor' value = '$floor' style='display: none;' />";


	echo "<input name = 'add_floor' value = '".$region."$build_num"."#"."$part"."区"."$floor"."层' style='display: none;' />";
	echo '<div class="dorm_list_div">';
	$i=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$dorm_num=$row['dorm_num'];
		//echo "<button class='btn btn-default'>$dorm_num</button>";

		$sql_check="SELECT * FROM routine_list WHERE dorm_num = '$dorm_num' and date = '$date' ";
		$result_of_check = $db->query($sql_check) or die($db->error);
	//echo "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaafaffff";
		if ($result_of_check->num_rows != 0){
			echo '<input type="checkbox" name="dorm_check'.$i.'" value="'.$dorm_num.'" data-label-text="'.$dorm_num.'" data-on-text="添加" data-off-text="已添加" data-switch-toggle="readonly" readonly/>';
		}else{
		echo '<input type="checkbox" name="dorm_check'.$i.'" value="'.$dorm_num.'" checked="checked" data-label-text="'.$dorm_num.'" data-on-text="添加" data-off-text="不添加" />';
		$i=$i+1;
		}

	}

	echo "</div>";


	echo '<div class="dorm_list_right">
	<button class="btn btn-default " type="submit"  name="view_add_submit" value="'.$i.'" >为左侧选中的宿舍添加成绩</button>
	</div>';



	echo '</form>';






	echo "<style>
        .dorm_model_container_panel{
            /*visibility:hidden;*/
            /*height:500px;*/

        }
        .main{
            height:1500px;
        }  
        .dorm_list_div{
        	width:200px;
        	float: left;
        } 
        .dorm_list_right{
        	float:left;
        }     
    </style>";


}


















?>