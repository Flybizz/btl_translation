$(document).ready(function(){


  /*********************************      USUARIOS       ************************************/
    /*GET ROUTER*/
    let router = $("router").attr("base");
    let base = router.split("/");
    let controller = router[0];
    let action = router[1];  
    
    /*****************************************************************************************/ 
    /***********************                REGISTAR                  *************************/
    /*****************************************************************************************/
    $("#retorno_index").hide();
    if(router == "vendedor/registar"){
   
      $("a[name='bt_vendedor_alterar'").click(function(){              
        ajax_enviar_dados2()
      })            
      function ajax_enviar_dados2(){

        var nome = $("input[id='titulo']").val().replace( /[\/]/g, "" );
        var telefone = $("input[id='telefone']").val().replace( /[\/]/g, "" );
        var telemovel = $("input[id='telemovel']").val().replace( /[\/]/g, "" );
        var email = $("input[id='email']").val().replace( /[\/]/g, "" );
        var area = $("select[id='area'] option:selected").val();
        var status = $('input[name=vendedor_status]:checked').val();

        if(false){
        }else{
          
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'vendedor/inserir/nome/'+nome+'/status/'+status+'/telefone/'+telefone+'/telemovel/'+telemovel+'/email/'+email+'/area/'+area+'/'},
              success: function(alterar){
                console.log(alterar);
                new PNotify({
                  title: translate('Sucesso!'),
                  text: alterar,
                  type: 'success',
                  hide: false,
                  buttons: {
                    sticker: false
                  }
                }); 
                location.replace("/backend/vendedor/listar");
              }
          });
        }
      } 
       
    }


    /*****************************************************************************************/ 
    /***********************                ALTERAR                  *************************/
    /*****************************************************************************************/
    if(router == "vendedor/alterar"){

     
      $("a[name='bt_vendedor_alterar'").click(function(){              
        ajax_enviar_dados2()
      })            
      function ajax_enviar_dados2(){

        var id_inst = $("input[id='code']").val().replace( /[\/]/g, "" );
        var nome = $("input[id='titulo']").val().replace( /[\/]/g, "" );
        var telefone = $("input[id='telefone']").val().replace( /[\/]/g, "" );
        var telemovel = $("input[id='telemovel']").val().replace( /[\/]/g, "" );
        var email = $("input[id='email']").val().replace( /[\/]/g, "" );
        var area = $("select[id='area'] option:selected").val();
        var status = $('input[name=vendedor_status]:checked').val();

        if(false){
        }else{
          $("#ref").html("");
          var nome = nome.replace(/'/g, "\\'");
          
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'vendedor/alteracao/id/'+id_inst+'/nome/'+nome+'/status/'+status+'/telefone/'+telefone+'/telemovel/'+telemovel+'/email/'+email+'/area/'+area+'/'},
              success: function(alterar){
                console.log(alterar);
                new PNotify({
                  title: translate('Sucesso!'),
                  text: alterar,
                  type: 'success',
                  hide: false,
                  buttons: {
                    sticker: false
                  }
                }); 
                setTimeout('location.reload()', 0);
              }
          });
        }
      } 
      
               
    }
    /*****************************************************************************************/
    /***********************                DELETAR                  *************************/
    /*****************************************************************************************/
    if(router == "vendedor/listar"){

      
      $("table.dataTable").on('click',"a.vendedorDel",function(e){
        
        var the_a_button = $(this);
              
        $("#modal-title").html("");
        $("#modal-body").html('<img src="/images/loading.gif" />');
        $("#modal-footer").html('');
        var id = $(this).attr("ref-id");
        var name = $(this).attr("ref-name");
        
        $("#modal-title").text(translate("Apagar Utilizador(a)"));
        $("#modal-body").html('<h5>'+translate("Deseja realmente apagar o registo(a)")+' <strong>'+name+'</strong> ?</h5><span id="retorno"></span>');
        $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button name="del_conf" id="del_conf" class="btn btn-primary modal-confirm">Confirmar</button> <button class="btn btn-default modal-dismiss">'+translate("Cancelar")+'</button></div></div>');

        //apresentar o popup
        $.magnificPopup.open({
          items: {
            src: the_a_button.attr("href")
          },
          type: 'inline'
        });

        //confirmar
        $("button[name='del_conf']").click(function(){
            $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'vendedor/deletar/id/'+id+'/'},
                  success: function(info){
                        $("#retorno").html(info);
                        var ident = $('input[name="id_retorno"]').val();
                        $("#"+ident).hide('slow');
                        $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button class="btn btn-default modal-dismiss">'+translate("Fechar")+'</button></div></div>')
                  }
            });
        });
      });
    }

});