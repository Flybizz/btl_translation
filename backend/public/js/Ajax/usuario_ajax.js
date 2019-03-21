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
    if(router == "usuario/registar"){
   
      $('#usu_senha').pstrength();
      $('#usu_confsenha').blur(function(){
          var senha = $('#usu_senha').val();
          var confsenha = $('#usu_confsenha').val();
       if(confsenha != senha){
          $('#msgSenha').html("Digite novamente, senha incorreta.") 
          $('#msgSenha').css('color','#ff0000')
          $("#validasenha").val(1);
        }else if(confsenha == ""){
          $('#msgSenha').html("<i class='icon-remove'></i>")
        }else{
          $('#msgSenha').html("Senha válida.")  
          $('#msgSenha').css('color','#007F00')
          $("#validasenha").val(0);
        }
      });

      var timestamp = $("#timestamp").val();
      var unique_salt00 = $("#unique_salt").val();
      $("input[id='usu_img']").uploadifive({
        'auto'             : false,
        'fileType'         : 'image/*',
        'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_usuario.php',
        'formData'         : {
        'timestamp' : timestamp,
        'token'     : unique_salt00
                            },
        'queueID'          : 'queue',
        'fileSizeLimit'    : 1024,
        'queueSizeLimit'   : 1,
        'UploadLimit'      : 1,
        'buttonClass'  : 'btn btn-primary',
        'buttonText'   : 'Anexar',
        'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_usuario.php',
          'onProgress'   : function(file, e) {
              if (e.lengthComputable) {
                  percent = Math.round((e.loaded / e.total) * 100);
              }
              file.queueItem.find('.fileinfo').html(' - ' + percent + '%');
              file.queueItem.find('.progress-bar').css('width', percent + '%');
          },
          'onUploadComplete' : function(file, data) { 
            console.log(data);               
              if(data != ""){
                ajax_enviar_dados(data)
              }//ver se existe imagens
            }
      });   


      $("a[name='bt_usu_cadastrar'").click(function(){
        var img = $("span[class='filename']").html();         
        if( img != undefined){
          img = img
        }else{
          img = "";            
        } 
        if(img == ""){
          ajax_enviar_dados(img)
        }//verifica se tem img          
      });//ser_cadastrar

      function ajax_enviar_dados(img){

          var nome = $("input[id='usu_nome']").val().replace( /[\/]/g, "" );
          var funcao = $("input[id='usu_funcao']").val().replace( /[\/]/g, "" );
          var login = $("input[id='usu_login']").val().replace( /[\/]/g, "" );
          var senha = $("input[id='usu_confsenha']").val();
          var nivel = $("select[id='usu_nivel'] option:selected").val();
          
          var texto = CKEDITOR.instances['usu_texto'].getData().replace( /[\/]/g, "|" );      
          var status = $('input[name=usu_status]:checked').val();
          
          var morada = $("input[id='usu_morada']").val().replace( /[\/]/g, "" );
          var localidade = $("input[id='usu_localidade']").val().replace( /[\/]/g, "" );
          var distrito = $("input[id='usu_distrito']").val().replace( /[\/]/g, "" );
          var cp = $("input[id='usu_cp']").val().replace( /[\/]/g, "" );
          
          var telefone = $("input[id='usu_telefone']").val().replace( /[\/]/g, "" );
          var telemovel = $("input[id='usu_telemovel']").val().replace( /[\/]/g, "" );
          var email = $("input[id='usu_email']").val().replace( /[\/]/g, "" );
          var website = $("input[id='usu_website']").val().replace( /[\/]/g, "" );                

          var control = $("select[id='usu_controller'] option:selected").val();

          var ref = "";
          var count = 0;
          if(nome == 0){ref += "<li>Nome</li>"; count += 1;}
          if(login == ""){ref += "<li>Login</li>"; count += 1;}
          if(senha == ""){ref += "<li>Senha</li>"; count += 1;}
          if(nivel == ""){ref += "<li>Nivel</li>"; count += 1;}
          if(ref != 0){
            new PNotify({
              title: 'OPS!',
              text: '<h5>Dados Obrigatórios:</h5>'+ref,
              type: 'danger',
              hide: true,
              buttons: {
                sticker: false
              }
            });  
          }else{
            $("#ref").html("");
                var nome = nome.replace(/'/g, "\\'");
                $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'usuario/inserir/img/'+img+'/controller/'+control+'/nome/'+nome+'/funcao/'+funcao+'/texto/'+texto+'/status/'+status+'/morada/'+morada+'/localidade/'+localidade+'/distrito/'+distrito+'/cp/'+cp+'/telefone/'+telefone+'/telemovel/'+telemovel+'/email/'+email+'/website/'+website+'/login/'+login+'/senha/'+senha+'/nivel/'+nivel+'/' },
                  success: function(ok){
                    console.log(ok); 
                    new PNotify({
                      title: 'Success!',
                      text: ok,
                      type: 'success',
                      hide: false,
                      buttons: {
                        sticker: false
                      }
                    });      
                    document.location.href = '/backend/usuario/listar';
                  }
              });
          }
      }//ajax_enviar_dados 
       
    }


    /*****************************************************************************************/ 
    /***********************                ALTERAR                  *************************/
    /*****************************************************************************************/
    if(router == "usuario/alterar"){

      $('#usu_senha').pstrength();
      $('#usu_confsenha').blur(function(){
          var senha = $('#usu_senha').val();
          var confsenha = $('#usu_confsenha').val();
       if(confsenha != senha){
          $('#msgSenha').html("Digite novamente, senha incorreta.") 
          $('#msgSenha').css('color','#ff0000')
          $("#validasenha").val(1);
        }else if(confsenha == ""){
          $('#msgSenha').html("<i class='icon-remove'></i>")
        }else{
          $('#msgSenha').html("Senha válida.")  
          $('#msgSenha').css('color','#007F00')
          $("#validasenha").val(0);
        }
      });
     
      var timestamp = $("#timestamp").val();
      var unique_salt00 = $("#unique_salt").val();
      $("input[id='usu_img']").uploadifive({
        'auto'             : true,
        'fileType'         : 'image/*',
        'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_usuario.php',
        'formData'         : {
        'timestamp' : timestamp,
        'token'     : unique_salt00
                             },
        'queueID'          : 'queue',
        'fileSizeLimit'    : 1024,
        'queueSizeLimit'   : 1,
        'UploadLimit'      : 1,
        'buttonClass'  : 'btn btn-primary',
        'buttonText'   : 'Selecionar',
        'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_usuario.php',
        'onProgress'   : function(file, e) {
          if (e.lengthComputable) {
              percent = Math.round((e.loaded / e.total) * 100);
          }
          file.queueItem.find('.fileinfo').html(' - ' + percent + '%');
          file.queueItem.find('.progress-bar').css('width', percent + '%');
        },
        'onUploadComplete' : function(file, data) { 
          console.log(data);            
            if(data != ""){
              ajax_enviar_dados2(data)
            }
          }
      });
      $("a[name='bt_usu_alterar'").click(function(){              
        var img = $("span[class='filename']").html();         
        if( img != undefined){
          img = img
        }else{
          img = "";            
        } 
        if(img == ""){
          ajax_enviar_dados2(img)
        }//verifica se tem img 
      })            
      function ajax_enviar_dados2(img){ 
        var calendario = $("input[id='usu_calendar_id']").val().replace( /[\/]/g, "" );
        var id_inst = $("input[id='usu_code']").val().replace( /[\/]/g, "" );
        var idioma = $("input[id='usu_idioma']").val().replace( /[\/]/g, "" );
        var nome = $("input[id='usu_nome']").val().replace( /[\/]/g, "" );
        var funcao = $("input[id='usu_funcao']").val().replace( /[\/]/g, "" );
        var login = $("input[id='usu_login']").val().replace( /[\/]/g, "" );
        var senha = $("input[id='usu_confsenha']").val();
        var nivel = $("select[id='usu_nivel'] option:selected").val();        
        var texto = CKEDITOR.instances['usu_texto'].getData().replace( /[\/]/g, "|" );      
        var status = $('input[name=usu_status]:checked').val();        
        var morada = $("input[id='usu_morada']").val().replace( /[\/]/g, "" );
        var localidade = $("input[id='usu_localidade']").val().replace( /[\/]/g, "" );
        var distrito = $("input[id='usu_distrito']").val().replace( /[\/]/g, "" );
        var cp = $("input[id='usu_cp']").val().replace( /[\/]/g, "" );        
        var telefone = $("input[id='usu_telefone']").val().replace( /[\/]/g, "" );
        var telemovel = $("input[id='usu_telemovel']").val().replace( /[\/]/g, "" );
        var email = $("input[id='usu_email']").val().replace( /[\/]/g, "" );
        var website = $("input[id='usu_website']").val().replace( /[\/]/g, "" );
        var control = $("select[id='usu_controller'] option:selected").val();
        var ref = "";
        var count = 0;

        if(nome == 0){ref += "<li>Nome</li>"; count += 1;}
        if(login == ""){ref += "<li>Login</li>"; count += 1;}
        if(nivel == ""){ref += "<li>Nivel</li>"; count += 1;}
        if(ref != 0){
          new PNotify({
            title: 'OPS!',
            text: '<h5>Dados Obrigatórios:</h5>'+ref,
            type: 'danger',
            hide: true,
            buttons: {
              sticker: false
            }
          });  
        }else{
          $("#ref").html("");
          var nome = nome.replace(/'/g, "\\'");
          
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'usuario/alteracao/id/'+id_inst+'/img/'+img+'/controller/'+control+'/idioma/'+idioma+'/nome/'+nome+'/funcao/'+funcao+'/texto/'+texto+'/status/'+status+'/morada/'+morada+'/localidade/'+localidade+'/distrito/'+distrito+'/cp/'+cp+'/telefone/'+telefone+'/telemovel/'+telemovel+'/email/'+email+'/website/'+website+'/login/'+login+'/senha/'+senha+'/nivel/'+nivel+'/calendar_id/'+calendario+'/'},
              success: function(alterar){
                console.log(alterar);
                new PNotify({
                  title: 'Sucesso!',
                  text: alterar,
                  type: 'success',
                }); 
                setTimeout('location.reload()', 0);
              }
          });
        }
      } 
      
      
      $("a[id='img_Del']").click(function(){
        var id_img = $("input[id='usu_code']").val().replace( /[\/]/g, "" );
        $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'usuario/img_deletar/id/'+id_img+'/'},
              success: function(info){
                    console.log(info)  
                    setTimeout('location.reload()', 0);                            
              }
        });
      });
               
    }
    /*****************************************************************************************/
    /***********************                DELETAR                  *************************/
    /*****************************************************************************************/
    if(router == "usuario/listar"){
      
      $("table.dataTable").on('click',"a.usuDel",function(e){
        
        var the_a_button = $(this);
              
        $("#modal-title").html("");
        $("#modal-body").html('<img src="/images/loading.gif" />');
        $("#modal-footer").html('');
        var id = $(this).attr("ref-id");
        var name = $(this).attr("ref-name");
        
        $("#modal-title").text("Apagar Utilizador(a)");
        $("#modal-body").html('<h5>Deseja realmente apagar o utilizador(a) <strong>'+name+'</strong> ?</h5><span id="retorno"></span>');
        $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button name="del_conf" id="del_conf" class="btn btn-primary modal-confirm">Confirmar</button> <button class="btn btn-default modal-dismiss">Cancelar</button></div></div>');

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
                  data: {'url':'usuario/deletar/id/'+id+'/'},
                  success: function(info){
                        $("#retorno").html(info);
                        var ident = $('input[name="id_retorno"]').val();
                        $("#"+ident).hide('slow');
                        $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button class="btn btn-default modal-dismiss">Fechar</button></div></div>')
                  }
            });
        });
      });
    }

});