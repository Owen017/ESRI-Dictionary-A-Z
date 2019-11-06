//  jQuery on()事件方法
//语法: $(selector).on(event,childSelector,data,function)
$(document).ready(function() {
    $("#typeList").on("click", "li", function() {
        $("#type").text($(this).text());
    })
    
    //想办法淡出提示语失败$("#error").fadeOut(3500);

});