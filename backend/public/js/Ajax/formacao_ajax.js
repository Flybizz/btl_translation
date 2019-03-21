$(document).ready(function(){
   
   /*********************************      FORMAÇÕES       ************************************/
   /*GET ROUTER*/
   let router = $("router").attr("base");
   let base = router.split("/");
   let controller = router[0];
   let action = router[1];
   
   $("a[data-remove='remove']").click(function(){
      $(this).parent().parent().fadeOut(function(){$(this).remove()})
   })
   $(".btn-repeat").click(function(){
      let template = $(this).prev().clone();
      let random = Math.random().toString(36).substring(2, 5);
      template.find("input[name^='formando[0][nome]']").attr("name", "formando[" + random + "][nome]").val("");
      template.find("input[name^='formando[0][nif]']").attr("name", "formando[" + random + "][nif]").val("");
      template.find("input[name^='formando[0][email]']").attr("name", "formando[" + random + "][email]").val("");
      //inserir antes do botão
      template.insertBefore($(this));
      $("a[data-remove='remove']").click(function(){
         $(this).parent().parent().fadeOut(function(){$(this).remove()})
      })
   })
   
   /*****************************************************************************************/ 
   /***********************                REGISTAR                  *************************/
   /*****************************************************************************************/
   $("#retorno_index").hide();
   if(router == "formacao/registar"){
      
      var timestamp = $("#timestamp").val();
      var unique_salt00 = $("#unique_salt").val();
      
      $("a[name='bt_form_cadastrar'").click(function(){
         event.preventDefault();
         ajax_enviar_dados();
      });//ser_cadastrar
      
      function ajax_enviar_dados(){
         if(false){
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
            var form_texto = CKEDITOR.instances['form_observacao'].getData().replace( /[\/]/g, "|" );
            let client_id = $("input[name=form_cliente_ref]").val();
            $.ajax({
               url: "/backend/Ajax.php",
               type: "POST",
               //data: {'url':'formacao/inserir/img/'+img+'/controller/'+control+'/nome/'+nome+'/funcao/'+funcao+'/texto/'+texto+'/status/'+status+'/morada/'+morada+'/localidade/'+localidade+'/distrito/'+distrito+'/cp/'+cp+'/telefone/'+telefone+'/telemovel/'+telemovel+'/email/'+email+'/website/'+website+'/login/'+login+'/senha/'+senha+'/nivel/'+nivel+'/'},
               data: {
                  'url':'formacao/inserir',
                  'form_data' : $("form").serialize() + form_texto
               },
               success: function(response){
                  console.log(response); 
                  new PNotify({
                     title: 'Sucesso!',
                     text: "Dados gravados com sucesso.",
                     type: 'success'
                  });
                  
                  setTimeout(function(){
                     document.location.href = `/backend/cliente/perfil/ref/${client_id}/tab/formacao`;
                  }, 3000)
                  
               }
            });
         }
      }//ajax_enviar_dados 
      
   }
   
   
   /*****************************************************************************************/ 
   /***********************                ALTERAR                  *************************/
   /*****************************************************************************************/
   if(router == "formacao/alterar"){
            
      $("a[name='bt_form_cadastrar'").click(function(){
         event.preventDefault();
         ajax_enviar_dados();
      });//ser_cadastrar
      
      function ajax_enviar_dados(){
         var form_texto = CKEDITOR.instances['form_observacao'].getData().replace( /[\/]/g, "|" );
         if(false){
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
            $.ajax({
               url: "/backend/Ajax.php",
               type: "POST",
               //data: {'url':'formacao/inserir/img/'+img+'/controller/'+control+'/nome/'+nome+'/funcao/'+funcao+'/texto/'+texto+'/status/'+status+'/morada/'+morada+'/localidade/'+localidade+'/distrito/'+distrito+'/cp/'+cp+'/telefone/'+telefone+'/telemovel/'+telemovel+'/email/'+email+'/website/'+website+'/login/'+login+'/senha/'+senha+'/nivel/'+nivel+'/'},
               data: {
                  url:'formacao/alteracao',
                  //'form_data' : $("form").serialize() + form_texto
                  form_data : $("form").serialize(),
                  obs: form_texto
               },
               success: function(response){
                  new PNotify({
                     title: 'Success!',
                     text: "Dados gravados com sucesso.",
                     type: 'success'
                  });
               }
            });
         }
      }//ajax_enviar_dados 
      
   }
      
});