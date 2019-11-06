function showMeaning() {
    var str = $("input#translateContent").val();
    str = str.trim();   //trim消除两边的空格
    if (str.length === 0) {
        document.getElementById("meaning").innerHTML = "没有输入";
        return;
    }
    if (window.XMLHttpRequest) {
        // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行的代码
        xmlhttp = new XMLHttpRequest();     //实例化
    } else {
        //IE6, IE5 浏览器执行的代码
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = connect;   //on[readystate]change这个函数只有在readystate改变时才会触发
    //结尾函数名不能加()。原因是要把整个函数给onreadystatechange，而不是将函数最后处理完的值返回给onreadystatechange。
    function connect(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            //alert("已成功操纵数据库。");
            document.getElementById("meaning").innerHTML = xmlhttp.responseText;
            // XMLHttpRequest.responseText 返回一个 DOMString，该 DOMString 包含对请求的响应，如果请求未成功或尚未发送，则返回 null。
        } //else {
            // 排查问题，得到readyState=1, status=0
            // readyState = 每改变一次readyState，都会调用一次connect()
            //  0：请求未初始化（还没有调用 open()）。
            //  1：请求已经建立，但是还没有发送（还没有调用 send()）。【卡在这里】
            //  2：请求已发送，正在处理中（通常现在可以从响应中获取内容头）。
            //  3：请求在处理中；通常响应中已有部分数据可用了，但是服务器还没有完成响应的生成。
            //  4：响应已完成；您可以获取并使用服务器的响应了。
            //alert("xmlhttp.readyState = " + (xmlhttp.readyState).toString());
            //alert("xmlhttp.status = " + xmlhttp.status);
            //document.getElementById("meaning").innerHTML = "ajax交易失败";
        //}
    }
    xmlhttp.open("GET", "results.php", true);   //async，需要更多熟悉异步的概念
    // url 里需要.php，不然404
    // url 参数是服务器上文件的地址                  //?q="+str要不要?
    // url: A DOMString representing the URL to send the request to.
    xmlhttp.send(null);

}