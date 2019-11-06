<?php
/* results.php */
/*面向对象的mysqli类方法*/
/*从jQeryAjax.js里传值得到两个表单元素:q和languageType*/
/*引入了php格式的有道api文件*/
/*后续要添加other属性时再做变更*/

require 'youdaoApi.php';

header("Content-type=text/html;charset=utf-8");

$word = $_GET['q']; //jQuery里的data
$lt   = $_GET['languageType']; //languageType缩写lt
//$word = "gis";
//$lt = "英英";

//数据库select
@$db = new mysqli('localhost', 'xxx','xxx', 'gisdictionary');
if (mysqli_connect_errno()) {
    die("Could not connect to database.<br/>Please try again later.");
}

$query = "SELECT meaning FROM gisaz WHERE words = ? ";
$stmt  = $db->prepare($query);
$stmt->bind_param('s', $word);
$stmt->execute();
$stmt->store_result();

$stmt->bind_result($mean); // bind_result($a,$b,$c)函数给出需要获得结果的字段列表
// 如注释中给出三个php变量，即绑定这三个变量分别对应查询返回结果的三个字段。但只有$stmt->fetch()之后变量才有意义。

// 数据回溯给js进行展示，只有$stmt->fetch()之后，变量$mean才有意义
// $stmt->fetch()：从结果集获取下一个数据行，从该数据行获取对应字段值，并且赋值给相应的绑定参数。
if ($stmt->num_rows > 0) {
    $stmt->fetch(); //本项目只有1个返回值，故$stmt->fetch()只调用一次
    //判断是否需要英汉翻译，是则调用有道api，否则直接输出
    if ($lt == "英汉") {
        $q   = $mean; // q即为需要翻译的文本
        $ret = do_request($q); // json数据
        $ret = json_decode($ret, true); // array数据
        //echo($ret["translation"][0]);
        $mean = $ret["translation"][0];
        $mean .= "	$q";
    }
    echo "$word : $mean";
} else {
    echo "There is NO relevant result. ";
}

$stmt->free_result();
$db->close();
