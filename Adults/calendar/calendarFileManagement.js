

$(document).ready(function() {
   var dateStart;
   var $currentCell;

$("#addFilesButton").click(function(){
    $("input:checked").each(function(){
        var fileName = $(this).val();
        var type = $('.adultEmail').attr('type');
        var childEmail = $('.email').attr('email');
        var dateEnd = $(this).closest('.newFileRow').find('.datepicker').val();
        var adultEmail = $('.adultEmail').attr('email');
        alert ($(this).val());
          //alert(fileName+ childEmail+dateEnd+dateStart);
         $.ajax({
       method: "POST",
       url: "../ajaxHandler/updateFilesHandler.php",
       data: { file: $(this).val(), 
           childEmail: childEmail, 
           adultEmail:$('.adultEmail').attr('email'),
           type:$('.adultEmail').attr('type'),
           dateEnd: dateEnd,         
           dateStart: dateStart
        }
})
  .done(function(msg){
       alert(msg);
       //var li = ' <li class="'+type+'" owner="'+adultEmail+'" > +'+fileName+' </li>';
         var li = ' <li class="item_teaching" </li>';
       $currentCell.find('ul.events_bullets').append(li);
  }); 
    });
    
    
    
    
    
    
     $(".ajout").html('Ajout effectué!');
});


$( 'body' ).on( "click",'button.btn-danger', function(){   
    var file = $(this).parent().attr('file');
    var ownerEmail=$currentCell.find("ul.events li:contains('"+file+"')").attr('owner');
    var type = $currentCell.find("ul.events li:contains('"+file+"')").attr('type');

    console.log(file+' '+$('.email').attr('email')+' '+type);
    /*$(this).closest('.fileRow').detach();
    $currentCell.find("li:contains('"+file+"')").detach();
    $currentCell.find("ul.events_bullets li").detach();*/
//console.log($currentCell.find("ul.events li:contains('"+file+"')").attr('owner'));
    //ul.events li:contains('mouton.txt')
       $button = $(this);
         function handler(msg){
              alert(msg);
    $button.closest('.fileRow').detach();
    $currentCell.find("li:contains('"+file+"')").detach();
     $currentCell.find("ul.events_bullets li:first").detach();
         }
    
    $.ajax({
       method: "POST",
       url: "../ajaxHandler/updateFilesHandler.php",
       data: { file: $(this).parent().attr('file'), 
           childEmail: $('.email').attr('email'), 
           adultEmail:ownerEmail,
           type:type }
})

  .done(handler);
    
    
    }); 

$("td").click(function(){
  var $files = $(this).find("ul.events")
          .children("li");
  dateStart = $(this).find('.hiddenDate').html();
  $currentCell = $(this);
  //alert($(this).find('.hiddenDate').html());
  var contentHtml = "";
  $files.each(function(){
      var file = $(this).html().trim();
      contentHtml += '<div class="fileRow row"> <div class="col-md-2">'+file+'</div><div file="'+file+'" class="col-md-offset-6 col-md-2">  <button class="btn btn-danger"> <span class="glyphicon glyphicon-remove"></span> </button></div></div>';
      //console.log(contentHtml);
      //contentHtml += '<p file='+file+'>'+file +'<button class="btn btn-danger"> <span class="glyphicon glyphicon-remove"></span> </button>'+'</p>';
      // contentHtml += '<p file='+file+'>'+file +'<button class="btn btn-danger"> <span class="glyphicon glyphicon-remove"></span> </button>'+'</p>';
      //alert($(this).html());
      
  })
   
   $(".containerFiles").hide().html(contentHtml).fadeIn(2000);
    $(".filesToAdd").fadeIn(2000);  
    
});



            }); 






