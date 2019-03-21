$(document).ready(function(){
/*********************************      distrito       ************************************/
    $("#retorno_index").hide();

    $("input[id='dist_status']").change(function(){
      var $this = $(this);       
      var idstatus = $this.prev("input").val();
      changeField = document.querySelector('.view'+idstatus).checked;     
      $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            data: {'url':'distrito/check/id/'+idstatus+'/status/'+changeField+'/'},
            success: function(info){
                console.log(info)
             }
       });
      
      
    })
      
    /*****************************************************************************************/
    /***********************                VIEW                  *************************/
    /*****************************************************************************************/
    // $("a[id='distritoView']").tooltip()
    // $("a[id='distritoView']").click(function(){
    //   $("#editor_acao").html('<img src="/images/loading.gif" />');    
    //    var $this = $(this);
    //    var id = $this.prev("input").val();
    //    $.ajax({
    //       url: "/backend/Ajax.php",
    //       type: "POST",
    //       data: {'url':'distrito/viewdistrito/id/'+id},
    //       success: function(dados){
    //         $("#retorno").html("");
    //         $("#editor_lista").hide();
    //         $("#editor_acao").html(dados);
    //         $("#editor_acao").show();
    //         $(".closed_acao").click(function(){
    //           $("#editor_acao").hide();
    //           $("#editor_lista").show();
    //         });          
          
    //        }
    //    });
       
    //     // $('#painel').modal();
            
    // });
    
});