<?php
/* GetMeaning.php */
//两个解决方案：1、不用ajax了，直接嵌php代码到HomePage里
//2、用jQuery来发出Ajax请求，https://ask.csdn.net/questions/797234

header("Content-type=text/html;charset=utf-8");

$q = isset($_GET['q']) ? ($_GET['q']) : '';     //获取

/*  与 ajax.js(line:4) 功能重复
if (empty($q)) {                                //判断为空，是则exit
    die("<script>alert('缺少输入！')</script>"); //die = exit
} 
*/

$con = mysqli_connect('localhost', 'root', '');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
} /*else {
    echo "<script type='text/javascript'>";
    echo "alert('Connect to MySQL SUCCEED! ');";
    echo "</script>";
}*/
// 选择数据库
mysqli_select_db($con, "gisdictionary");
// 设置编码，防止中文乱码
mysqli_set_charset($con, "utf8");

$sql = "SELECT meaning FROM gisaz WHERE words = '" . $q . "'";  // 两个点'.'是连接三段字符串的连接符

$result = mysqli_query($con, $sql);

if ($result instanceof mysqli_result){
    echo "123";
    while ($row = mysql_fetch_array($result)) {        //$row = mysql_fetch_array($result)      $row = $result->fetch_object()
        echo "456";
        if (mysqli_num_rows($result) > 0) {
            echo "$row['meaning']";                    //$row['meaning']                        $row->meaning
        } else {
            echo "There is NO relevant result in the word book. ";
        }
    }
}
/*
if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_object()) {
        if (mysqli_num_rows($result) > 0) {
            echo "<script type='text/javascript'>";z
            echo 'document.getElementById("meaning").innerHTML=' . '"' . "$q: " . "$row->meaning" . '"' . ';';
            //echo 'document.getElementById("meaning").innerHTML='.'"'."$row->meaning".'"'.';';
            echo "</script>";
        } else {
            echo "<script>alert('There is NO relevant result in the word book. ')</script>";
        }
    }
}
*/