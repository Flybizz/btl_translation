$(document).ready(function(){

   notifications.bind();

   behaviours.bind();     
   
})


var behaviours = {

   bind : function(){
      $("[data-smooth-scroll]").click(function(){
         event.preventDefault();

         $('html, body').animate({
            scrollTop: $($.attr(this, 'href')).offset().top
         }, 500);
      })
   }

}


var notifications = {

   total_notifications : function(){
      if($("[data-task-list]>li").length)
         return $("[data-task-list]>li").length;
      else
         return 0;
   },

   update_total_notifications : function(){
      
      $("[data-tasks-total]").each(function(){
         $(this).html(notifications.total_notifications());
      })

   },

   bind : function(){

      $(".btn-finish-task").click(function(){
   
         var ref = $(this).data("task-ref"); //1 - get task id
         var type = $(this).data("task-type"); //2 - check if task or follow up
        
         //3 - submit via ajax
         $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            data: { url : `${type}/complete`, ref : ref},
            dataType : "json",
            success: function(info){
                        
               // //get result
               if(info.status == "success"){
                  new PNotify({
                     title: info.title,
                     text: info.message,
                     type: info.status,
                     delay: 1500
                  });
   
                  $(`li[data-task-ref=${ref}]`).fadeOut(function(){
                     $(this).remove();
                     notifications.update_total_notifications();
                  });
                  
               }
               else{
                  new PNotify({
                     title: translate('Erro'),
                     text: translate("Ocorreu um erro ao actualizar a base de dados"),
                     type: 'success',
                     delay: 1500
                  });
   
               }
               
            }
         });
         
         //prevent clicks
         event.preventDefault();
         return false;
      });
      
   }

}


function translate(string) {

   if(!LOCALE[string]) console.log(string + " => not set");
   return LOCALE[string] ? "||" + LOCALE[string] + "||" : string;
}