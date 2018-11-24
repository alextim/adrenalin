$(document).ready(function($){
    $("div#questions ul a").click(function(){
        var selected = $(this).attr('href');    
        selected += '"'+selected+'"';
        /*--Removing the Current class and the top button from previous current FAQs---*/
        $('.top-button').remove();
        $('.current-faq').removeClass();
        $.scrollTo(selected, 400 ,function(){ 
            $(selected).addClass('current-faq', 400, function(){
                $(this).append('<a href="#" class="top-button">TOP</a>');
            });
        });     
        return false;
    });