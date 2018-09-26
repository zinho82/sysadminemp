$(document).ready(function(){
    3
4
5
6
7
8
9
10
11
12
13
	
    if($(".label-notifications").text()==0){
        $(".label-notifications").addClass('hidden');
    }else{
        $(".label-notifications").removeClass('hidden');
    }
//    notifications();
//    setInterval(function(){
//        notifications();
//    },60000);
    
});

function notifications(){
    $.ajax({
        url: URL+'/notifications/get',
        type: 'GET',
        success: function(response){
            $(".label-notifications").html(response);
              if(response == 0){
        $(".label-notifications").addClass('hidden');
    }else{
        $(".label-notifications").removeClass('hidden');
    }
        }
    });
}
function Proyectos(){
    $.ajax({
        url: URL+'/proyectos/get',
        type: 'GET',
        success: function(response){
            $(".label-notifications").html(response);
              if(response == 0){
        $(".label-notifications").addClass('hidden');
    }else{
        $(".label-notifications").removeClass('hidden');
    }
        }
    });
}