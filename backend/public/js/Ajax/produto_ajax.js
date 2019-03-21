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
    if(router == "produto/registar"){
          
      let p_idioma = $("input[id='idioma']").val();

      /*  $("select[id=subcategoria]").change(function(){
        let sel_scategoria = $(this).val();
        let p_categoria = $("select[id='categoria'] option:selected").val();

        $("select[id=ssubcategoria]").html("<option value='0'>Selecione<option>");
        //console.log(sel_scategoria);
        chamaSubCategoria(p_categoria,sel_scategoria,p_idioma)
      });

      function chamaSubCategoria(cat,sub,idioma){

          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'produto/chamaSubCategoria/level/2/id/'+sub+'/sub1/'+cat+'/lang/'+idioma+'/'},
              success: function(ok2){
                console.log(ok2);
                $("select[id='ssubcategoria']").html(ok2);
              }
          });
      }

      $("select[id=categoria]").change(function(){

        let sel_categoria = $(this).val();

        $("select[id=subcategoria]").html("<option value='0'>Selecione<option>");
        $("select[id=ssubcategoria]").html("<option value='0'>Selecione<option>");

        $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            data: {'url':'produto/chamaSubCategoria/level/1/id/'+sel_categoria+'/lang/'+p_idioma+'/'},
            success: function(ok){
              console.log(ok);

              $("select[id='subcategoria']").html(ok);
              $("select[id=ssubcategoria]").html("<option value='0'>Selecione<option>");

            }
        });
      });
      */

      var timestamp = $("#timestamp").val();
      var unique_salt00 = $("#unique_salt").val();
      $("input[id='img']").uploadifive({
        'auto'             : false,
        'fileType'         : 'image/*',
        'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_produto.php',
        'formData'         : {
        'timestamp' : timestamp,
        'token'     : unique_salt00
                            },
        'queueID'          : 'queue',
        'fileSizeLimit'    : 1024,
        'queueSizeLimit'   : 2,
        'UploadLimit'      : 2,
        'buttonClass'  : 'btn btn-primary',
        'buttonText'   : translate('Anexar'),
        'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_produto.php',
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

      $("#bt_produto_cadastrar").click(function(){
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
          var idioma = $("input[id='idioma']").val().replace( /[\/]/g, "" );
          var titulo = $("input[id='titulo']").val().replace( /[\/]/g, "" );         
          var chamada = $("textarea[id='chamada']").val().replace( /[\/]/g, "" );
          var texto = CKEDITOR.instances['texto'].getData().replace( /[\/]/g, "|" );
          var destaque = $('input[name=destaque]:checked').val();        
          var ordem = $('input[id=ordem]').val().replace( "", 0 );
          var preco = $('input[id=preco]').val().replace( "", 0 );
      
          var control = $("select[id='controller'] option:selected").val();
/*           var categoria = $("select[id='categoria'] option:selected").val();
          var subcategoria = $("select[id='subcategoria'] option:selected").val();
          var sub2 = $("select[id='ssubcategoria'] option:selected").val(); */

          var ref = "";
          var count = 0;
          if(titulo == ""){ref += "<li>Titulo</li>"; count += 1;}
          if(ref != 0){
            new PNotify({
              title: translate('OPS!'),
              text: translate('<h5>Dados Obrigatórios:</h5>')+ref,
              type: 'danger'
            });
          }else{
            $("#ref").html("");
                var titulo = titulo.replace(/'/g, "\\'");
                $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  /* data: {'url':'produto/inserir/img/'+img+'/categoria/'+categoria+'/subcategoria/'+subcategoria+'/sub2/'+sub2+'/controller/'+control+'/titulo/'+titulo+'/subtitulo/'+subtitulo+'/texto/'+texto+'/chamada/'+chamada+'/destaque/'+destaque+'/novidade/'+novidade+'/ordem/'+ordem+'/preco/'+preco+'/marca/'+marca+'/seo_titulo/'+seo_titulo+'/seo_descricao/'+seo_descricao+'/seo_slug/'+seo_slug+'/seo_key/'+seo_key+'/'}, */
                  data: {'url':'produto/inserir/img/'+img+'/controller/'+control+'/titulo/'+titulo+'/texto/'+texto+'/chamada/'+chamada+'/destaque/'+destaque+'/ordem/'+ordem+'/preco/'+preco+'/'},
                  success: function(ok){
                    //console.log(ok);
                    new PNotify({
                      title: translate('Success!'),
                      text: ok,
                      type: 'success'
                    });
                    document.location.href = '/backend/produto/listar/lang/'+idioma;
                  }
              });
          }
      }//ajax_enviar_dados

    }
    /*****************************************************************************************/
    /***********************                ALTERAR                  *************************/
    /*****************************************************************************************/
    if(router == "produto/alterar"){

            
            let p_idioma = $("input[id='idioma']").val();
            /* var p_categoria = $("select[id='categoria'] option:selected").val();
            var p_subcategoria = $("select[id='subcategoria'] option:selected").val(); 

            $("select[id=subcategoria]").change(function(){
              let sel_scategoria = $(this).val();
              $("select[id=ssubcategoria]").html("<option value='0'>Selecione<option>");
              chamaSubCategoria(p_categoria,sel_scategoria,p_idioma)
            });

            function chamaSubCategoria(cat,sub,idioma){

                $.ajax({
                    url: "/backend/Ajax.php",
                    type: "POST",
                    data: {'url':'produto/chamaSubCategoria/level/2/id/'+sub+'/sub1/'+cat+'/lang/'+idioma+'/'},
                    success: function(ok2){
                      console.log(ok2);
                      $("select[id='ssubcategoria']").html(ok2);
                    }
                });

            }


            $("select[id=categoria]").change(function(){

              let sel_categoria = $(this).val();

              $("select[id=subcategoria]").html("<option value='0'>Selecione<option>");
              $("select[id=ssubcategoria]").html("<option value='0'>Selecione<option>");

              $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'produto/chamaSubCategoria/level/1/id/'+sel_categoria+'/lang/'+p_idioma+'/'},
                  success: function(ok){
                    console.log(ok);

                    $("select[id='subcategoria']").html(ok);
                    $("select[id=ssubcategoria]").html("<option value='0'>Selecione<option>");

                  }
              });

            }); */

            var timestamp = $("#timestamp").val();
            var unique_salt00 = $("#unique_salt").val();
            $("input[id='img']").uploadifive({
              'auto'             : true,
              'fileType'         : 'image/*',
              'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_produto.php',
              'formData'         : {
              'timestamp' : timestamp,
              'token'     : unique_salt00
                                   },
              'queueID'          : 'queue',
              'fileSizeLimit'    : 1024,
              'queueSizeLimit'   : 1,
              'UploadLimit'      : 1,
              'buttonClass'  : 'btn btn-primary',
              'buttonText'   :  translate('Selecionar'),
              'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_produto.php',
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

            $("#bt_produto_alterar").click(function(){
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
              var id_dest = $("input[id='code']").val().replace( /[\/]/g, "" );
              var idioma = $("input[id='idioma']").val().replace( /[\/]/g, "" );
              var titulo = $("input[id='titulo']").val().replace( /[\/]/g, "" );              
              var chamada = $("textarea[id='chamada']").val().replace( /[\/]/g, "" );
              var texto = CKEDITOR.instances['texto'].getData().replace( /[\/]/g, "|" );
              var destaque = $('input[name=destaque]:checked').val();              
              var ordem = $("input[id='ordem']").val().replace( "", 0 );
              var preco = $('input[id=preco]').val().replace( "", 0 );
              
              var control = $("select[id='controller'] option:selected").val();
              /* var categoria = $("select[id='categoria'] option:selected").val();
              var subcategoria = $("select[id='subcategoria'] option:selected").val();
              var sub2 = $("select[id='ssubcategoria'] option:selected").val(); */
              var ref = "";
              var count = 0;

              if(titulo == ""){ref += "<li>Titulo</li>"; count += 1;}

              if(ref != 0){
                new PNotify({
                  title: translate('OPS!'),
                  text: '<h5>'+translate("Dados Obrigatórios")+':</h5>'+ref,
                  type: 'danger'
                });

              }else{

                $("#ref").html("");
                var titulo = titulo.replace(/'/g, "\\'");

                $.ajax({
                    url: "/backend/Ajax.php",
                    type: "POST",
                    /* data: {'url':'produto/alteracao/id/'+id_dest+'/img/'+img+'/idioma/'+idioma+'/categoria/'+categoria+'/subcategoria/'+subcategoria+'/sub2/'+sub2+'/controller/'+control+'/titulo/'+titulo+'/subtitulo/'+subtitulo+'/texto/'+texto+'/chamada/'+chamada+'/destaque/'+destaque+'/novidade/'+novidade+'/ordem/'+ordem+'/preco/'+preco+'/marca/'+marca+'/seo_titulo/'+seo_titulo+'/seo_descricao/'+seo_descricao+'/seo_slug/'+seo_slug+'/seo_key/'+seo_key+'/'}, */
                    data: {'url':'produto/alteracao/id/'+id_dest+'/img/'+img+'/idioma/'+idioma+'/controller/'+control+'/titulo/'+titulo+'/texto/'+texto+'/chamada/'+chamada+'/destaque/'+destaque+'/ordem/'+ordem+'/preco/'+preco+'/'},
                    success: function(alterar){
                      console.log(alterar);
                      new PNotify({
                        title: translate('Sucesso!'),
                        text: alterar,
                        type: 'success'
                      });
                      setTimeout('location.reload()', 0);
                    }
                });
              }
            }


            $("a[id='img_Del']").click(function(){
              var ref = $("input[id='code']").val().replace( /[\/]/g, "" );
              $.ajax({
                    url: "/backend/Ajax.php",
                    type: "POST",
                    data: {'url':'produto/img_deletar/id/'+ref+'/'},
                    success: function(info){
                          console.log(info)
                          new PNotify({
                            title: translate('Sucesso!'),
                            text: info,
                            type: 'success'
                          });
                          setTimeout('location.reload()', 0);
                    }
              });
            });

    }
    /*****************************************************************************************/
    /***********************                DELETAR                  *************************/
    /*****************************************************************************************/
    if(router == "produto/listar"){

      $("table").on('click', '[btndelete]', function(){

        $("#modal-title").html("");
        $("#modal-body").html('<img src="/images/loading.gif" />');
        $("#modal-footer").html('');
        var id = $(this).attr("ref-id");
        var name = $(this).attr("ref-name");
        console.log(id);

        $("#modal-title").text("Apagar Produto");
        $("#modal-body").html('<h5>Deseja realmente apagar o produto <strong> '+name+' </strong> ?</h5><span id="retorno"></span>');
        $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button name="del_conf" id="del_conf" class="btn btn-primary modal-confirm">Confirmar</button> <button class="btn btn-default modal-dismiss">Cancelar</button></div></div>');
        //confirmar
        $("button[name='del_conf']").click(function(){
            $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'produto/deletar/id/'+id+'/'},
                  success: function(info){
                        $("#retorno").html(info);
                        var ident = $('input[name="id_retorno"]').val();
                        $("#"+ident).hide('slow');
                        $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button class="btn btn-default modal-dismiss">Fechar</button></div></div>')
                  }
            });
        });

        deleteModal(this)

      });

      function deleteModal(input){

        $(input).magnificPopup({
          type: 'inline',
          fixedContentPos: false,
          fixedBgPos: true,
          overflowY: 'auto',
          closeBtnInside: true,
          preloader: false,
          midClick: true,
          removalDelay: 300,
          mainClass: 'my-mfp-zoom-in',
          modal: true
        });
      }

    }


    /*****************************************************************************************/
    /***********************                GALERIA                  *************************/
    /*****************************************************************************************/
    if(router == "produto/galeria"){

      /*
      Thumbnail: Select
      */
      $(".mg-option input:checkbox").on('change', function( ev ){
        var id = $(this).attr("id");
        var wrapper = $(this).parents('.thumbnail');
        if($(this).is(':checked')) {
          wrapper.addClass('thumbnail-selected');
        } else {
          wrapper.removeClass('thumbnail-selected');
        }
         $(".mg-option input:checkbox").each(function(el){
          var check = $(this).attr("id");
          if(id == check){
            $("#"+check).prop('checked', true);
          }else{
            $("#"+check).prop('checked', false);
          }
        })
        $("#box-edit-galeria").html("<img src='/images/loading.gif'>").show("slow");
        $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            data: {'url':'produto/galeria_info/id/'+id+'/'},
            success: function(rtn){
              console.log(rtn);
              $("#box-edit-galeria").show("slow");
              $("#box-edit-galeria").html(rtn);
              $("a[id='edit_info_galeria']").click(function(){
                var img_id = $("input[id='gal_id']").val();
                var img_titulo = $("input[id='gal_titulo']").val();
                var img_alternativo = $("input[id='gal_alternativo']").val();
                var img_destaque = $('input[name=gal_destaque]:checked').val();
                $.ajax({
                    url: "/backend/Ajax.php",
                    type: "POST",
                    data: {'url':'produto/galeria_info_inserir/id/'+img_id+'/titulo/'+img_titulo+'/alternativo/'+img_alternativo+'/destaque/'+img_destaque+'/'},
                    success: function(rtn2){
                      console.log(rtn2);
                      $("#box-edit-galeria").hide("slow");
                      new PNotify({
                        title: translate('Sucesso!'),
                        text: rtn2,
                        type: 'success',
                        hide: "4000"
                      });
                      setTimeout('location.reload()', 0);
                    }
                });

              });


            }
        });

      })


      /*
      Image Preview: Lightbox
      */

      $('.thumb-preview > a[href]').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
          verticalFit: true
        }
      });

      $('.thumb-preview .mg-zoom').on('click.lightbox', function( ev ) {
        ev.preventDefault();
        $(this).closest('.thumb-preview').find('a.thumb-image').triggerHandler('click');
      });

      /*
      Thumnail: Dropdown Options
      */

      $('.thumbnail .mg-toggle').parent()
        .on('show.bs.dropdown', function( ev ) {
          $(this).closest('.mg-thumb-options').css('overflow', 'visible');
        })
        .on('hidden.bs.dropdown', function( ev ) {
          $(this).closest('.mg-thumb-options').css('overflow', '');
        });

      $('.thumbnail').on('mouseenter', function() {
        var toggle = $(this).find('.mg-toggle');
        if ( toggle.parent().hasClass('open') ) {
          toggle.dropdown('toggle');
        }
      });

      var referencia_ord = $("input[id='referencia']").val();
      var idioma_ord = $("input[id='idioma']").val();

      /* ORDEM */
      var list = document.getElementById("galf_galeria");
      Sortable.create(list, {
          animation: 150,
          draggable: '.order',
          handle: '.order_arrows',
          onSort: function (/**Event*/evt) {
              var ordenar = "";
              // var old = evt.oldIndex;  // element's old index within parent
              // var novo = evt.newIndex;  // element's new index within parent
              //console.log("novo: "+novo+" / velho: "+old);
              $(".order").each(function (index) {
                  var order_id = $(this).attr("id");
                  ordenar += order_id + ",";
              });
              ordenar = ordenar.substring(0, (ordenar.length - 1));
              $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url': 'produto/ordenar/id/'+referencia_ord+'/idioma/'+idioma_ord+'/ordem/'+ordenar+'/'},
                  success: function (ok) {
                      console.log(ok);
                  }
              });
              //console.log( ordenar );
          }
      });

      var timestamp = $("#timestamp").val();
      var unique_salt00 = $("#unique_salt").val();
      $("input[id='img_galeria']").uploadifive({
        'auto'             : true,
        'fileType'         : ["image\/gif","image\/jpeg","image\/png","application\/msword","application\/pdf","application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
        /* 'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_produto_galeria.php',*/
        'formData'         : {
        'timestamp' : timestamp,
        'token'     : unique_salt00
                             },
        'queueID'          : 'queue',
        'fileSizeLimit'    : 2000,
        'queueSizeLimit'   : 72,
        'UploadLimit'      : 72,
        'buttonClass'  : 'btn btn-primary',
        'buttonText'   : 'UPLOAD',
        'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_produto_galeria.php',
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

      function ajax_enviar_dados2(img){
        var referencia = $("input[id='referencia']").val().replace( /[\/]/g, "" );
        var idioma = $("input[id='idioma']").val().replace( /[\/]/g, "" );
        var control = $("input[id='controller']").val().replace( /[\/]/g, "" );
        var ref = "";
        var count = 0;
        /* if(titulo == ""){ref += "<li>Titulo</li>"; count += 1;}*/
        if(count != 0){
          new PNotify({
            title: translate('OPS!'),
            text: '<h5>'+translate("Dados Obrigatórios")+':</h5>'+ref,
            type: 'danger',
            hide: true,
            buttons: {
              sticker: false
            }
          });
        }else{
          $("#ref").html("");
          //var titulo = titulo.replace(/'/g, "\\'");

          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'produto/galeria_inserir/referencia/'+referencia+'/img/'+img+'/idioma/'+idioma+'/controller/'+control+'/'},
              success: function(rtn){
                console.log(rtn);

                setTimeout(function(){
                  new PNotify({
                    title: translate('Sucesso!'),
                    text: rtn,
                    type: 'success'
                  });
                }, 2000);
                setTimeout(function(){
                   window.location.reload(1);
                }, 8000);
              }
          });
        }
      }


      $("a[id='img_Del_galeria']").click(function(){
        var ref = $(this).attr("code-id");
        $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'produto/galeria_deletar/id/'+ref+'/'},
              success: function(info){
                    console.log(info)
                    new PNotify({
                      title: translate('Sucesso!'),
                      text: info,
                      type: 'success'
                    });
                    setTimeout('location.reload()', 0);
              }
        });
      });

    }
});
