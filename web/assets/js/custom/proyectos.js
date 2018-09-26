$(document).ready(function () {
    var ias = jQuery.ias({
        container: '#timeline .box-contenent',
        item: '.publication-item',
        pagination: '#timeline .pagination',
        next: '#timeline.pagination .next_link',
        triggerPageThreshold: 5

    });
    ias.extension(new IASTriggerExtension({
        text: 'ver mas publicaciones',
        offset: 3
    }));
    ias.extension(new IASSpinnerExtension({
        src: URL + '/assets/images/ajax-loader.gif'
    }));
    ias.extension(new IASNoneLeftExtension({
        text: 'no hay mas Publicaciones'
    }));

    ias.on('ready', function (event) {
      Buttons();
    });
    ias.on('rendered', function (event) {
        Buttons();
    });

});

function Buttons() {
    $(['.data-toggle="tooltip"']).tooltip();
   $(".btn-image").unbind("click").click(function(){
       $(this).parent().find('.pub-img').fadeToggle();
   });
   $(".btn-del-pub").unbind("click").click(function(){
       $(this).parent().addClass('hidden');
       $.ajax({
           url: URL+'publication/remove/'+$(this).attr('data-id'),
           type: 'GET',
           success:function(response){
               console.log(response);
               
           }
       });
       
   });
   $(".btn-like").unbind("click").click(function(){
       $(this).addClass("hidden");
       $(this).parent().find('btn-unlike').removeClass('hidden');
      $.ajax({
          url: URL+'/like/'+$(this).attr("data-id"),
          type: 'GET',
          success: function(response){
              console.log(response);
          }
      }) ;
   });
   
}
