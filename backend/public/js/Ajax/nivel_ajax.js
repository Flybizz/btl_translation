$(document).ready(function(){
/*********************************      NIVEL       ************************************/
/***********************   CADASTRAR  *************************/  

/*********************************      USUARIOS       ************************************/
/*GET ROUTER*/
let router = $("router").attr("base");

/*****************************************************************************************/ 
/***********************               GENERICOS                 *************************/
/*****************************************************************************************/


//ativa swicth
// window.Switchery && $('[data-init-plugin="switchery"]').each(function() {
//   new Switchery($(this).get(0), {
//       color: "#0088cc",
//       size: 'small' 
//   })
// });

$("form").bind("submit", function(){
  event.preventDefault();
  return false;
})

$("#toggle_all_permissions").bind("change", function(){
  
  var box_class = $(this).prop("checked") ? "on" : "off";
  var box_status = $(this).prop("checked");

  $("input[type=checkbox]").each(function(){
    $(this).prev(".ios-switch").removeClass("on");
    $(this).prev(".ios-switch").removeClass("off");
    $(this).prev(".ios-switch").addClass(box_class);
    $(this).prop("checked", box_status);

  });
})

/*****************************************************************************************/ 
/***********************                REGISTAR                  *************************/
/*****************************************************************************************/

if(router == "nivel/registar"){
      
  $("a[name='nivCad'").click(function(){              
    ajax_enviar_dados2()
  })

  $("input[type=checkbox]").bind("change", function(){
    //ajax_enviar_dados2();
  })
    
  

  function ajax_enviar_dados2(){

    var str_permissao = $("input[id='nivel_permissao_array']").val().replace( /[\/]/g, "" );
    var arr_permissao = $.parseJSON(str_permissao);
    var nivel_permissao = "";
    $.each( arr_permissao, function( key, value ) {                
        nivel_permissao += "per_"+value+":"+document.querySelector('#nivel_permissao_'+value).checked+",";
    });

    var nome = $("input[id='niv_nome']").val().replace( /[\/]/g, "" );
    var id_inst = $("input[id='niv_id']").val().replace( /[\/]/g, "" );
  
    if(nome == 0){
      new PNotify({
        title: translate('OPS!'),
        text: '<h5>'+translate("Dados Obrigatórios")+':</h5>'+nome,
        type: 'danger'
      });  
    }else{
      $("#ref").html("");
      var nome = nome.replace(/'/g, "\\'");          
      
      //request para alteração de nome & permissões
      $.ajax({
          url: "/backend/Ajax.php",
          type: "POST",
          data: {'url':'nivel/inserir/id/'+id_inst+'/nome/'+nome+'/dados/'+nivel_permissao+'/'},
          success: function(alterar){
            console.log(alterar);
            new PNotify({
              title: translate('Sucesso!'),
              text: translate("Informação actualizada com sucesso"),
              type: 'success'
            }); 
            setTimeout(function(){
              window.location = "/backend/nivel/listar"
            }, 0);
          }
      });

    }
  }
 
}
  

  /*****************************************************************************************/ 
    /***********************                ALTERAR                  *************************/
    /*****************************************************************************************/
    if(router == "nivel/alterar"){
      
      $("a[name='nivAlt'").click(function(){              
        ajax_enviar_dados2()
      });

      $("input[type=checkbox]").bind("change", function(){
        ajax_enviar_dados2();
      })

      function ajax_enviar_dados2(){

        var str_permissao = $("input[id='nivel_permissao_array']").val().replace( /[\/]/g, "" );
        var arr_permissao = $.parseJSON(str_permissao);
        var nivel_permissao = "";
        $.each( arr_permissao, function( key, value ) {                
            nivel_permissao += "per_"+value+":"+document.querySelector('#nivel_permissao_'+value).checked+",";
        });

        var nome = $("input[id='niv_nome']").val().replace( /[\/]/g, "" );
        var id_inst = $("input[id='niv_id']").val().replace( /[\/]/g, "" );
      
        if(nome == 0){
          new PNotify({
            title: translate('OPS!'),
            text: '<h5>'+translate("Dados Obrigatórios")+':</h5>'+nome,
            type: 'danger',
            delay: 3500
          });  
        }else{
          $("#ref").html("");
          var nome = nome.replace(/'/g, "\\'");          
          
          //request para alteração de nome & permissões
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'nivel/alteracao/id/'+id_inst+'/nome/'+nome+'/dados/'+nivel_permissao+'/'},
              success: function(alterar){
                console.log(alterar);
                new PNotify({
                  title: translate('Sucesso!'),
                  text: alterar,
                  type: 'success',
                  delay: 2000
                }); 
                //setTimeout('location.reload()', 0);
              }
          });

        }
      }
     
    }


    /*****************************************************************************************/
    /***********************                DELETAR                  *************************/
    /*****************************************************************************************/
    $("a[id='nivDel']").tooltip()
    $("table.dataTable").on('click',"a.nivDel",function(e){

      var the_a_button = $(this);
      console.log(the_a_button.attr("href"));
       
      $("#modal-title").html("");
      $("#modal-body").html('<img src="/images/loading.gif" />');
      $("#modal-footer").html('');
       var $this = $(this);
       
       var id = $this.prev("input").val();
       $("#modal-title").text(translate("Apagar Nível"));
       $("#modal-body").html('<h5>'+translate("Deseja realmente apagar o grupo")+' '+the_a_button.attr("ref-name")+' ?</h5><span id="retorno"></span>');
       $("#modal-footer").html('<span id="retorno"></span><button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button><button name="del_conf" id="del_conf" class="btn btn-primary">'+translate("Confirmar")+'</button>');
       
       console.log($(".modal-body").html());
       //confirmar
       //apresentar o popup
       $.magnificPopup.open({
        items: {
          src: the_a_button.attr("href")
        },
        type: 'inline'
      });

       $("button[name='del_conf']").click(function(){
           $.ajax({
                url: "/backend/Ajax.php",
                type: "POST",
                data: {'url':'nivel/deletar/id/'+id+'/'},
                dataType : "json",
                success: function(info){


                  $.magnificPopup.close(); //close the popup
                 
                  //get result
                  if(info.error == true){
                    new PNotify({
                      title: translate('Erro'),
                      text: info.message,
                      type: 'error',
                      delay: 2500
                    });

                    setTimeout('window.location = "/backend/usuario/listar"', 1000);
                  }
                  else{
                    new PNotify({
                      title: translate('Success!'),
                      text: translate("Apagado com sucesso"),
                      type: 'success',
                      delay: 2500
                    });                    
                  }

                 }
           });
       });
    });
});