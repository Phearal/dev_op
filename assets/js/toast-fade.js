$(document).ready (function(){
   
    window.setTimeout(function() {
        $(".toast").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 5000);

 }); 