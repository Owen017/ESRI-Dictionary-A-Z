<!DOCTYPE html>
<html>
<head>
    <title>17GIS A-Z Dictionary首页</title>
    <meta charset="UTF-8">
    <link href="style/pc7.css" rel="stylesheet" />
    <!--整饰自己的css失败link href="style/homepageStyle.css" rel="stylesheet" /-->
    <script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
    <script src="script/funcBlock.js"></script>
    <script src="script/typeText.js"></script>
</head>
<body>
    <?php
header("Content-type=text/html;charset=utf-8");
$q = $qErr = $qErr1 = ""; //变量初始化
    var_dump($_GET);
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (empty($_GET["q"])) {
        $qErr1 = "INPUT is required"; //寒酸的方法
    } else {
        $q = test_input($_GET["q"]);
        // 检测词语是否只包含字母空格和hyphen
        if (!preg_match("/^[0-9a-zA-Z -]*$/", $q)) {
            $qErr = "Tips: 只允许输入字母、空格以及hyphen('-')。";
        }
    }
}
function test_input($data)
{
    $data = trim($data);
    //移除字符串两侧的空白字符和其他字符。
    $data = stripslashes($data);
    //stripslashes删除由 addslashes() 函数添加的反斜杠。
    //该函数可用于清理从数据库中或者从 HTML 表单中取回的数据。
    $data = htmlspecialchars($data);
    //把预定义的字符 "<" （小于）和 ">" （大于）转换为 HTML 实体
    return $data;
}
?>
    <div id="margin1"></div>
    <div id="logo">
        <div class="logo"></div>
    </div>
    <div id="margin2"></div>
    <div id="search">
        <div class="wrap">
            <!--尝试查询后，input中有value=q；form method="GET" target="postTo" action="" id="form"-->
            <form method="GET" action="" id="form">
            <!--发送表单数据到当前页面。如果使用<?php //echo htmlspecialchars($_SERVER[" PHP_SELF"]); ?>还将避免黑客攻击。详情见https://www.runoob.com/php/php-form-validation.html-->
            <!--存在使用了$_SERVER[" PHP_SELF"]后报错ERROR403的问题-->
                <img class="logo" src="source/youdao_logo.png" alt="有道图标">
                <div id="selectType" onclick="typeListDisplay()">
                    <!--调用js-->
                    <!--函数后面加括号()！！！！！-->
                    <div id="type">英英</div>
                    <div class="side">1</div>
                    <span id="arrowIcon" class="arrow"></span>
                    <ul id="typeList">
                        <li>英英</li>
                        <li>英汉</li>
                        <li>英韩</li>
                        <li>英日</li>
                        <li>英葡</li>
                        <li>英德</li>
                        <li>英西</li>
                        <li>英俄</li>
                    </ul>
                </div>
                <div id="border">
                    <input type="text" name="le" id="translateType" value="eng">
                    <input type="text" name="q" id="translateContent" placeholder="在此输入要查询的单词或词组" onmouseover="this.focus()" onfocus="this.select()" maxlength="256" autocomplete="off">
                    <!--?php echo "<script>alert('$qErr')</script>";-->
                    <input type="hidden" name="keyfrom" value="dict2.index">
                </div>
                <button>查询</button>
                <?php echo "<span style=\"color: #e00013;margin:auto 120px auto 120px\">$qErr</span>" ?>
                <!--这里使用了php输出整条标签+内嵌样式表的方式完成输出和css的整饰-->
                <!--***想办法创造出更好的提示法，alert最好***-->
                <!--onclick="formCheck()"想用用jQuery写在typeText.js里，js无法调用php变量，失败-->
                <!--若button按钮没有type属性，浏览器默认按照type=submit逻辑处理-->
            </form>
            <!--与form定义标签联动；iframe name="postTo" style="display:none"></iframe-->
        </div>
    </div>
    <div id="margin3">
        <span><a target="_blank">周三GIS专业英语 </a></span>
        <span><a target="_blank">每周都盼着课堂最后3分钟短视频 </a></span>
        <span><a target="_blank" href="https://support.esri.com/en/other-resources/gis-dictionary">点击查看单词本 </a></span>
        <span class="ugc-link"><a>孟老板真棒！ </a></span>
    </div>
    <div id="margin4"></div>
    <!-- 这里的style实现了div中的placeholder -->
    <style type="text/css">
        .input {
            width: 200px;
            height: 30px;
            border: 1px solid grey;
        }
        /* 这里的style实现了div中的placeholder */
        .placeholderDiv:empty::before {
            color: lightgrey;
            content: attr(placeholder);
        }
    </style>
    <div id="meaning" class="placeholderDiv" placeholder="查询得到的结果将会显示在这里"></div>

        <?php
$con = mysqli_connect('localhost', 'root', '');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
// 选择数据库
mysqli_select_db($con, "gisdictionary");
// 设置编码，防止中文乱码
mysqli_set_charset($con, "utf8");

$sql   = "SELECT meaning FROM gisaz WHERE words = '" . $q . "'"; // 两个点'.'是连接三段字符串的连接符
$result = mysqli_query($con, $sql);

if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_object()) {
        if (mysqli_num_rows($result) > 0) {
            echo "<script type='text/javascript'>";
            echo 'document.getElementById("meaning").innerHTML=' . '"' . "$q : " . "$row->meaning" . '"' . ';';
            //echo 'document.getElementById("meaning").innerHTML='.'"'."$row->meaning".'"'.';';
            echo "</script>";
        } else {
            echo "<script type='text/javascript'>;";
            echo "alert('There is NO relevant result in the word book. Don\'t blame the developers, this can\'t be found, the database problem. ')";
            echo "</script>";   //要注意str文本内的单引号，转义
        }
    }
}
?>

</body>
</html>