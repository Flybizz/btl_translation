$(document).ready(function(){
/*********************************      contato       ************************************/
    $("#retorno_index").hide();
    $("input[id='cont_status']").change(function(){
      var $this = $(this);       
      var idstatus = $this.prev("input").val();
      changeField = document.querySelector('.view'+idstatus).checked;     
      $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            data: {'url':'contato/check/id/'+idstatus+'/status/'+changeField+'/'},
            success: function(info){
                   //alert(info)
             }
       });
      
      
    })
    /*****************************************************************************************/
    /***********************                DELETAR                  *************************/
    /*****************************************************************************************/
    $("a[id='contatoDel']").tooltip()
    $("a[id='contatoDel']").click(function(){
       
      $(".modal-title").html("");
      $(".modal-body").html('<img src="/../backend/public/img/loading.gif" />');
      $(".modal-footer").html('');
       var $this = $(this);
       
       var id = $this.prev("input").val();
       $(".modal-title").text("Apagar Contato");
       $(".modal-body").html('<h5>Deseja realmente apagar o contato numero '+id+' ?</h5><span id="retorno"></span>');
       $(".modal-footer").html('<span id="retorno"></span><button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button><button name="del_conf" id="del_conf" class="btn btn-primary">Confirmar</button>');
       //confirmar
       $("button[name='del_conf']").click(function(){
           $.ajax({
                url: "/backend/Ajax.php",
                type: "POST",
                data: {'url':'contato/deletar/id/'+id+'/'},
                success: function(info){
                       $("#retorno").html(info);
                       $(".footerBox").html('<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>') 
                       var ident = $('input[name="id_retorno"]').val();
                       $("#"+ident).hide('slow');
                       $(".modal-footer").html('<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>')
                 }
           });
       });
    });
    
     /*****************************************************************************************/
    /***********************                VIEW                  *************************/
    /*****************************************************************************************/
    $("a[id='contatoView']").tooltip()
    $("a[id='contatoView']").click(function(){
      $("#editor_acao").html('<img src="/images/loading.gif" />');    
       var $this = $(this);
       var id = $this.prev("input").val();
       $.ajax({
          url: "/backend/Ajax.php",
          type: "POST",
          data: {'url':'contato/viewcontato/id/'+id},
          success: function(dados){
            $("#retorno").html("");
            $("#editor_lista").hide();
            $("#editor_acao").html(dados);
            $("#editor_acao").show();
            $(".closed_acao").click(function(){
              $("#editor_acao").hide();
              $("#editor_lista").show();
            });          
          
           }
       });
       
        // $('#painel').modal();
            
    });
});