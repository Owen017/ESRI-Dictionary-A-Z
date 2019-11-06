/*外部脚本不能包含<script>标签*/
//在 JavaScript 中，首字符必须是字母、下划线（-）或美元符号（$）。

var flag = true;
function typeListDisplay() {    //用自带的arrow arrow-up完成小箭头翻转
    if (flag) {
        document.getElementById("typeList").style.display = "block";
        document.getElementById("arrowIcon").className = "arrow arrow-up";
    } else {
        document.getElementById("typeList").style.display = "none";
        document.getElementById("arrowIcon").className = "arrow";
    }
    flag = !flag;
}