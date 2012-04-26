$(document).ready(function() {
    var defaultTips = '查询问题的标题';
    
    $('#searchButton').unbind();
    $('#searchButton').click(function(){
       var qVal = jQuery.trim($('#ssearchkey').val());
       if (qVal == defaultTips) {
           $('#ssearchkey').focus();
           return false;
       }
       $('#form_search').submit();
       return false;
    });
});