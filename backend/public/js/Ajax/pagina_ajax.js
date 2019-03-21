$(document).ready(function(){
    /*********************************      NOTÍCIA       ************************************/
    /*GET ROUTER*/
    let router = $("router").attr("base");
    let base = router.split("/");
    let controller = router[0];
    let action = router[1];  
    
    /*****************************************************************************************/ 
    /***********************                REGISTAR                  *************************/
    /*****************************************************************************************/

    $("#retorno_index").hide();

    if(router == "pagina/registar"){
          
      /*******************************************************************************
      * START META SEO
      *********************************************************************************/
      /*META SEO TITULO*/
      $("input[id='pag_titulo']").blur(function(){

        let dados_titulo = $(this).val(); 
        $("input[id='pag_titulo_seo']").val(dados_titulo);
        let seo_title = $("input[id='pag_titulo_seo']").val();
        rg_seo_titulo(seo_title);
        rg_seo_key(seo_title,"titulo");

        /* GERAR SLUG */
        $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            data: {'url':'seo/seoSlug/', frase: dados_titulo},
            success: function(rs_slug){
                /* console.log(rs_slug); */
                $("input[id='pag_slug_seo']").val(rs_slug);
                rg_seo_slug(rs_slug);
                rg_seo_key(rs_slug,"slug");
            }
        });

        
      });    

      $("input[id='pag_titulo_seo']").blur(function(){
        let dados_titulo_seo = $(this).val(); 
        rg_seo_titulo(dados_titulo_seo);
        rg_seo_key(dados_titulo_seo,"titulo");
      });

      function rg_seo_titulo(titulo_seo){
        /* regra title */
        if(titulo_seo.length >= 50 && titulo_seo.length <= 60 ){
          $("li[id='seo_title']").html("<i class='icofont icofont-check-circled'></i> - A meta título está em bom tamanho.").css("color","#7ad03a");
        }else if(titulo_seo.length >= 40 && titulo_seo.length <= 49 ){
          $("li[id='seo_title']").html("<i class='icofont icofont-exclamation-circle'></i> - A meta título ainda não está em um bom tamanho.").css("color","#FFDD00");
        }else{
          $("li[id='seo_title']").html("<i class='icofont icofont-close-circled'></i> - A meta título não está em bom tamanho.").css("color","#FF0000");
        }
      }

      /*META SEO SLUG*/
      $("input[id='pag_slug_seo']").blur(function(){
        let dados_slug_seo = $(this).val(); 
        rg_seo_slug(dados_slug_seo);
        rg_seo_key(dados_slug_seo,"slug");
      });

      function rg_seo_slug(slug_seo){
        /* regra title */
        if(slug_seo.length >= 3){
          $("li[id='seo_slug']").html("<i class='icofont icofont-check-circled'></i> - A meta slug está em bom tamanho.").css("color","#7ad03a");          
        }else{
          $("li[id='seo_slug']").html("<i class='icofont icofont-close-circled'></i> - A meta slug não está em bom tamanho.").css("color","#FF0000");
        }
      }

      /*META SEO DESCRICAO*/
      $("textarea[id='pag_chamada']").blur(function(){
        let dados_chamada = $(this).val(); 
        $("textarea[id='pag_descricao_seo']").val(dados_chamada);
        let seo_chamada = $("textarea[id='pag_descricao_seo']").val();
        rg_seo_descricao(seo_chamada);
        rg_seo_key(seo_chamada,"descricao");
      });    

      $("textarea[id='pag_descricao_seo']").blur(function(){
        let dados_descricao_seo = $(this).val(); 
        rg_seo_descricao(dados_descricao_seo);
        rg_seo_key(dados_descricao_seo,"descricao");
      });

      function rg_seo_descricao(descricao_seo){
        /* regra title */
        if(descricao_seo.length >= 145 && descricao_seo.length <= 156 ){
          $("li[id='seo_description']").html("<i class='icofont icofont-check-circled'></i> - A meta descrição está em bom tamanho.").css("color","#7ad03a");
        }else if(descricao_seo.length >= 120 && descricao_seo.length <= 144 ){
          $("li[id='seo_description']").html("<i class='icofont icofont-exclamation-circle'></i> - A meta descrição ainda não está em um bom tamanho.").css("color","#ee7c1b");
        }else{
          $("li[id='seo_description']").html("<i class='icofont icofont-close-circled'></i> - A meta descrição não está em um bom tamanho.").css("color","#FF0000");
        }
      }

      /*palavra-chave*/
      $("input[id='pag_key_seo']").blur(function(){
        let dados_key = $(this).val();
        let seo_title = $("input[id='pag_titulo_seo']").val();
        let seo_description = $("textarea[id='pag_descricao_seo']").val();
        let seo_slug = $("input[id='pag_slug_seo']").val();
        rg_seo_key(seo_title,"titulo");
        rg_seo_key(seo_description,"descricao");
        rg_seo_key(seo_slug,"slug");
        rg_seo_chave(dados_key);
      });  

      function rg_seo_chave(key){
        /* regra title */
        if(key.length >= 3 ){
          $("li[id='seo_key']").html("<i class='icofont icofont-check-circled'></i> - A palavra-chave principal foi definida para esta página.").css("color","#7ad03a");          
        }else{  
          $("li[id='seo_key']").html("<i class='icofont icofont-exclamation-circle'></i> - Não há palavra-chave principal definida para esta página.").css("color","#ee7c1b");
        }
      }

      function rg_seo_key(dados,campo){
        let seo_key = $("input[id='pag_key_seo']").val().replace( /[\/]/g, "|" );   
        if(campo == "titulo"){
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'seo/verifica/', frase: dados, key: seo_key},
              success: function(rs){
                  if(rs != ""){          
                    $("li[id='seo_key_title']").html('<i class="icofont icofont-check-circled"></i> - A palavra-chave principal "'+seo_key+'" aparece no TÍTULO desta página.').css("color","#7ad03a");
                  }else{           
                    $("li[id='seo_key_title']").html('<i class="icofont icofont-exclamation-circle"></i> - A palavra-chave principal "'+seo_key+'" não aparece no TÍTULO desta página.').css("color","#ee7c1b");
                  }
              }
          });           
          
        }

        if(campo == "descricao"){ 
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'seo/verifica/', frase: dados, key: seo_key},
              success: function(rs){
                  if(rs != ""){         
                    $("li[id='seo_key_description']").html('<i class="icofont icofont-check-circled"></i> - A palavra-chave principal "'+seo_key+'" aparece na DESCRIÇÃO desta página.').css("color","#7ad03a");
                  }else{          
                    $("li[id='seo_key_description']").html('<i class="icofont icofont-exclamation-circle"></i> - A palavra-chave principal "'+seo_key+'" não aparece na DESCRIÇÃO desta página.').css("color","#ee7c1b");
                  }
              }
          }); 
        }

        if(campo == "slug"){
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'seo/verificaSlug/frase/'+dados+'/key/'+seo_key+'/'},
              success: function(rs_slug){
                  /* console.log(rs_slug); */
                  if(rs_slug != "" && seo_key != ""){
                    $("li[id='seo_key_slug']").html('<i class="icofont icofont-check-circled"></i> - A palavra-chave principal "'+seo_key+'" aparece no SLUG desta página.').css("color","#7ad03a");
                  }else{        
                    $("li[id='seo_key_slug']").html('<i class="icofont icofont-exclamation-circle"></i> - A palavra-chave principal "'+seo_key+'" não aparece no SLUG desta página.').css("color","#ee7c1b");
                  }
              }
          });    
        
        }          

      }

      /*******************************************************************************
      * END META SEO
      *********************************************************************************/

      var timestamp = $("#timestamp").val();
      var unique_salt00 = $("#unique_salt").val();
      $("input[id='pag_img']").uploadifive({
        'auto'             : false,
        'fileType'         : 'image/*',
        'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_pagina.php',
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
        'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_pagina.php',
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
      $("#bt_pag_cadastrar").click(function(){
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
          var titulo = $("input[id='pag_titulo']").val().replace( /[\/]/g, "" );
          var subtitulo = $("input[id='pag_subtitulo']").val().replace( /[\/]/g, "" );
          var chamada = $("textarea[id='pag_chamada']").val().replace( /[\/]/g, "" );
          var texto = CKEDITOR.instances['pag_texto'].getData().replace( /[\/]/g, "|" );      
          var destaque = $('input[name=pag_destaque]:checked').val();
          var tipovideo = $("select[id='pag_tipovideo'] option:selected").val();
          var codevideo = $("input[id='pag_codevideo']").val().replace( /[\/]/g, "" );

          var seo_titulo = $("input[id='pag_titulo_seo']").val().replace( /[\/]/g, "|" );
          var seo_descricao = $("textarea[id='pag_descricao_seo']").val().replace( /[\/]/g, "|" );
          var seo_slug = $("input[id='pag_slug_seo']").val().replace( /[\/]/g, "|" );
          var seo_key = $("input[id='pag_key_seo']").val().replace( /[\/]/g, "|" );

          var control = $("select[id='pag_controller'] option:selected").val();

          var ref = "";
          var count = 0;
          if(titulo == ""){ref += "<li>Titulo</li>"; count += 1;}
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
                var titulo = titulo.replace(/'/g, "\\'");
                $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'pagina/inserir/img/'+img+'/controller/'+control+'/titulo/'+titulo+'/subtitulo/'+subtitulo+'/texto/'+texto+'/chamada/'+chamada+'/destaque/'+destaque+'/tipovideo/'+tipovideo+'/codevideo/'+codevideo+'/seo_titulo/'+seo_titulo+'/seo_descricao/'+seo_descricao+'/seo_slug/'+seo_slug+'/seo_key/'+seo_key+'/'},
                  success: function(ok){
                    console.log(ok); 
                    new PNotify({
                      title: 'Success!',
                      text: ok,
                      type: 'success'
                    });      
                    document.location.href = '/backend/pagina/listar';
                  }
              });
          }
      }//ajax_enviar_dados 
       

    }

    /*****************************************************************************************/ 
    /***********************                ALTERAR                  *************************/
    /*****************************************************************************************/

    if(router == "pagina/alterar"){

            /*******************************************************************************
            * START META SEO
            *********************************************************************************/
            /*META SEO TITULO*/
            $("input[id='pag_titulo_alt']").blur(function(){

              let dados_titulo = $(this).val(); 
              $("input[id='pag_titulo_seo']").val(dados_titulo);
              let seo_title = $("input[id='pag_titulo_seo']").val();
              rg_seo_titulo(seo_title);
              rg_seo_key(seo_title,"titulo");

              /* GERAR SLUG */
              $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'seo/seoSlug/', frase: dados_titulo},
                  success: function(rs_slug){
                      console.log(rs_slug);
                      $("input[id='pag_slug_seo']").val(rs_slug);
                      rg_seo_slug(rs_slug);
                      rg_seo_key(rs_slug,"slug");
                  }
              });
              
            });    

            $("input[id='pag_titulo_seo']").blur(function(){
              let dados_titulo_seo = $(this).val(); 
              rg_seo_titulo(dados_titulo_seo);
              rg_seo_key(dados_titulo_seo,"titulo");
            });

            function rg_seo_titulo(titulo_seo){
              /* regra title */
              if(titulo_seo.length >= 50 && titulo_seo.length <= 60 ){
                $("li[id='seo_title']").html("<i class='icofont icofont-check-circled'></i> - A meta título está em bom tamanho.").css("color","#7ad03a");
              }else if(titulo_seo.length >= 40 && titulo_seo.length <= 49 ){
                $("li[id='seo_title']").html("<i class='icofont icofont-exclamation-circle'></i> - A meta título ainda não está em um bom tamanho.").css("color","#FFDD00");
              }else{
                $("li[id='seo_title']").html("<i class='icofont icofont-close-circled'></i> - A meta título não está em bom tamanho.").css("color","#FF0000");
              }
            }

            /*META SEO SLUG*/
            $("input[id='pag_slug_seo']").blur(function(){
              let dados_slug_seo = $(this).val(); 
              rg_seo_slug(dados_slug_seo);
              rg_seo_key(dados_slug_seo,"slug");
            });

            function rg_seo_slug(slug_seo){
              /* regra title */
              if(slug_seo.length >= 3){
                $("li[id='seo_slug']").html("<i class='icofont icofont-check-circled'></i> - A meta slug está em bom tamanho.").css("color","#7ad03a");          
              }else{
                $("li[id='seo_slug']").html("<i class='icofont icofont-close-circled'></i> - A meta slug não está em bom tamanho.").css("color","#FF0000");
              }
            }

            /*META SEO DESCRICAO*/
            $("textarea[id='pag_chamada_alt']").blur(function(){
              let dados_chamada = $(this).val(); 
              $("textarea[id='pag_descricao_seo']").val(dados_chamada);
              let seo_chamada = $("textarea[id='pag_descricao_seo']").val();
              rg_seo_descricao(seo_chamada);
              rg_seo_key(seo_chamada,"descricao");
            });    

            $("textarea[id='pag_descricao_seo']").blur(function(){
              let dados_descricao_seo = $(this).val(); 
              rg_seo_descricao(dados_descricao_seo);
              rg_seo_key(dados_descricao_seo,"descricao");
            });

            function rg_seo_descricao(descricao_seo){
              /* regra title */
              if(descricao_seo.length >= 145 && descricao_seo.length <= 156 ){
                $("li[id='seo_description']").html("<i class='icofont icofont-check-circled'></i> - A meta descrição está em bom tamanho.").css("color","#7ad03a");
              }else if(descricao_seo.length >= 120 && descricao_seo.length <= 144 ){
                $("li[id='seo_description']").html("<i class='icofont icofont-exclamation-circle'></i> - A meta descrição ainda não está em um bom tamanho.").css("color","#ee7c1b");
              }else{
                $("li[id='seo_description']").html("<i class='icofont icofont-close-circled'></i> - A meta descrição não está em um bom tamanho.").css("color","#FF0000");
              }
            }

            /*palavra-chave*/
            $("input[id='pag_key_seo']").blur(function(){
              let dados_key = $(this).val();
              let seo_title = $("input[id='pag_titulo_seo']").val();
              let seo_description = $("textarea[id='pag_descricao_seo']").val();
              let seo_slug = $("input[id='pag_slug_seo']").val();
              rg_seo_key(seo_title,"titulo");
              rg_seo_key(seo_description,"descricao");
              rg_seo_key(seo_slug,"slug");
              rg_seo_chave(dados_key);
            });  

            function rg_seo_chave(key){
              /* regra title */
              if(key.length >= 3 ){
                $("li[id='seo_key']").html("<i class='icofont icofont-check-circled'></i> - A palavra-chave principal foi definida para esta página.").css("color","#7ad03a");          
              }else{  
                $("li[id='seo_key']").html("<i class='icofont icofont-exclamation-circle'></i> - Não há palavra-chave principal definida para esta página.").css("color","#ee7c1b");
              }
            }

            function rg_seo_key(dados,campo){
              let seo_key = $("input[id='pag_key_seo']").val().replace( /[\/]/g, "|" );   
              if(campo == "titulo"){
                $.ajax({
                    url: "/backend/Ajax.php",
                    type: "POST",
                    data: {'url':'seo/verifica/', frase: dados, key: seo_key},
                    success: function(rs){
                        if(rs != ""){          
                          $("li[id='seo_key_title']").html('<i class="icofont icofont-check-circled"></i> - A palavra-chave principal "'+seo_key+'" aparece no TÍTULO desta página.').css("color","#7ad03a");
                        }else{           
                           $("li[id='seo_key_title']").html('<i class="icofont icofont-exclamation-circle"></i> - A palavra-chave principal "'+seo_key+'" não aparece no TÍTULO desta página.').css("color","#ee7c1b");
                        }
                    }
                });           
                
              }

              if(campo == "descricao"){ 
                $.ajax({
                    url: "/backend/Ajax.php",
                    type: "POST",
                    data: {'url':'seo/verifica/', frase: dados, key: seo_key},
                    success: function(rs){
                        if(rs != ""){         
                          $("li[id='seo_key_description']").html('<i class="icofont icofont-check-circled"></i> - A palavra-chave principal "'+seo_key+'" aparece na DESCRIÇÃO desta página.').css("color","#7ad03a");
                        }else{          
                           $("li[id='seo_key_description']").html('<i class="icofont icofont-exclamation-circle"></i> - A palavra-chave principal "'+seo_key+'" não aparece na DESCRIÇÃO desta página.').css("color","#ee7c1b");
                        }
                    }
                }); 
              }

              if(campo == "slug"){
                $.ajax({
                    url: "/backend/Ajax.php",
                    type: "POST",
                    data: {'url':'seo/verificaSlug/frase/'+dados+'/key/'+seo_key+'/'},
                    success: function(rs_slug){
                        console.log(rs_slug);
                        if(rs_slug != "" && seo_key != ""){
                          $("li[id='seo_key_slug']").html('<i class="icofont icofont-check-circled"></i> - A palavra-chave principal "'+seo_key+'" aparece no SLUG desta página.').css("color","#7ad03a");
                        }else{        
                           $("li[id='seo_key_slug']").html('<i class="icofont icofont-exclamation-circle"></i> - A palavra-chave principal "'+seo_key+'" não aparece no SLUG desta página.').css("color","#ee7c1b");
                        }
                    }
                });    
              
              }          

            }

            /*******************************************************************************
            * END META SEO
            *********************************************************************************/

            var timestamp = $("#timestamp").val();
            var unique_salt00 = $("#unique_salt").val();
            $("input[id='pag_img']").uploadifive({
              'auto'             : true,
              'fileType'         : 'image/*',
              'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_pagina.php',
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
              'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_pagina.php',
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

            $("#bt_pag_alterar").click(function(){              
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
              var id_pag = $("input[id='pag_code_alt']").val().replace( /[\/]/g, "" );
              var idioma = $("input[id='pag_idioma_alt']").val().replace( /[\/]/g, "" );
              var titulo = $("input[id='pag_titulo_alt']").val().replace( /[\/]/g, "" );
              var subtitulo = $("input[id='pag_subtitulo_alt']").val().replace( /[\/]/g, "" );
              var chamada = $("textarea[id='pag_chamada_alt']").val().replace( /[\/]/g, "" );
              var texto = CKEDITOR.instances['pag_texto_alt'].getData().replace( /[\/]/g, "|" );            
              var destaque = $('input[name=pag_destaque_alt]:checked').val();
              var tipovideo = $("select[id='pag_tipovideo_alt'] option:selected").val();
              var codevideo = $("input[id='pag_codevideo_alt']").val().replace( /[\/]/g, "" );

              var seo_titulo = $("input[id='pag_titulo_seo']").val().replace( /[\/]/g, "|" );
              var seo_descricao = $("textarea[id='pag_descricao_seo']").val().replace( /[\/]/g, "|" );
              var seo_slug = $("input[id='pag_slug_seo']").val().replace( /[\/]/g, "|" );
              var seo_key = $("input[id='pag_key_seo']").val().replace( /[\/]/g, "|" );

              var control = $("select[id='pag_controller_alt'] option:selected").val();

              var ref = "";
              var count = 0;
              if(titulo == ""){ref += "<li>Titulo</li>"; count += 1;}
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
                var titulo = titulo.replace(/'/g, "\\'");
                
                $.ajax({
                    url: "/backend/Ajax.php",
                    type: "POST",
                    data: {'url':'pagina/alteracao/id/'+id_pag+'/img/'+img+'/idioma/'+idioma+'/controller/'+control+'/titulo/'+titulo+'/subtitulo/'+subtitulo+'/texto/'+texto+'/chamada/'+chamada+'/destaque/'+destaque+'/tipovideo/'+tipovideo+'/codevideo/'+codevideo+'/seo_titulo/'+seo_titulo+'/seo_descricao/'+seo_descricao+'/seo_slug/'+seo_slug+'/seo_key/'+seo_key+'/'},
                    success: function(alterar){
                      console.log(alterar);
                      new PNotify({
                        title: 'Sucesso!',
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
            
            
            $("a[id='img_Del']").click(function(){
              var id_img = $("input[id='pag_code_alt']").val().replace( /[\/]/g, "" );
              $.ajax({
                    url: "/backend/Ajax.php",
                    type: "POST",
                    data: {'url':'pagina/img_deletar/id/'+id_img+'/'},
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
    if(router == "pagina/listar"){

      $("a[class='pagDel']").magnificPopup({
        type: 'inline',
        fixedContentPos: false,
        fixedBgPos: true,    
        overflowY: 'auto',    
        closeBtnInside: true,
        preloader: false,        
        midClick: false,
        removalDelay: 300,
        mainClass: 'my-mfp-zoom-in',
        modal: true
      });

      $("a[class='pagDel']").click(function(){
              
        $("#modal-title").html("");
        $("#modal-body").html('<img src="/images/loading.gif" />');
        $("#modal-footer").html('');
        var id = $(this).attr("ref-id");

        $("#modal-title").text("Apagar Página");
        $("#modal-body").html('<h5>Deseja realmente apagar o página número '+id+' ?</h5><span id="retorno"></span>');
        $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button name="del_conf" id="del_conf" class="btn btn-primary modal-confirm">Confirmar</button> <button class="btn btn-default modal-dismiss">Cancelar</button></div></div>');

        console.log(id);
                
        //confirmar
        $("button[name='del_conf']").click(function(){
            $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'pagina/deletar/id/'+id+'/'},
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