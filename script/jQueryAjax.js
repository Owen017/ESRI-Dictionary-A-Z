/*jQueryAjax.js*/
/*ajax里注意data和success的使用*/
/*data里添加languageType，传给results.php执行两种翻译方式*/
/*jQuery万岁！*/

$(document).ready(function() {
    $("button").click(function() {
        var languageType = $("div#type").text(); // 这里只能用.text()不能用val()
        languageType = languageType.trim(); // 避免出现format美化html代码后的元素文本自动添加空格换行
        //注意选择器的xx#yyy有猫腻
        //以及.text()和.val()也有猫腻
        var str = $("input#translateContent").val();
        str = str.trim(); //trim消除两边的空格
        function isValid(inputStr) { return /^[0-9a-zA-Z -]*$/.test(inputStr); } //正则判断合法性
        if (str.length === 0) {
            alert("INPUT IS EMPTY");
            $("#translateContent").val("");
            return;
        } else {
            if (!isValid(str)) {
                alert("只允许输入字母、空格及hyphen(-)");
                $("#translateContent").val("");
                return;
            }
        }
        htmlobj = $.ajax({
            type: "GET",
            url: "script/results.php", //这个路径是相对于homepage而言的，而非js而言的。
            data: { 'q': str, 'languageType': languageType },
            async: true,
            success: function() { $("#meaning").text(htmlobj.responseText) }
        });
    });
});