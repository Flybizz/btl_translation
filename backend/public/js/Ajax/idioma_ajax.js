$(document).ready(function(){
/*********************************      idioma       ************************************/
    /*GET ROUTER*/
    let router = $("router").attr("base");
    let base = router.split("/");
    let controller = router[0];
    let action = router[1];  
    
    /*****************************************************************************************/ 
    /***********************                REGISTAR                  *************************/
    /*****************************************************************************************/

    if(router == "idioma/registar"){

      $("#bt_idioma_cadastrar").click(function(){
        var nome = $("input[id='idioma_nome']").val().replace( /[\/]/g, "" );
        var sigla = $("input[id='idioma_sigla']").val().replace( /[\/]/g, "" );
        var icon = $("input[id='idioma_icon']").val().replace( /[\/]/g, "" );
        var ordem = $("input[id='idioma_ordem']").val().replace( /[\/]/g, "" );
        var destaque = $('input[name=idioma_destaque]:checked').val();
        var status = $('input[name=idioma_status]:checked').val();
        //console.log(titulo+" / "+chamada+" / "+texto+" / "+data+" / "+categoria+" / "+galeria+" / "+destaque+" / "+img);
        var ref = "";
        var count = 0;
        if(nome == ""){ref += "<li>Nome</li>"; count += 1;}
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
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'idioma/inserir/nome/'+nome+'/sigla/'+sigla+'/icon/'+icon+'/ordem/'+ordem+'/destaque/'+destaque+'/status/'+status+'/'},
              success: function(ok){
                //console.log(ok)
                new PNotify({
                  title: 'Success!',
                  text: ok,
                  type: 'success',
                  hide: false,
                  buttons: {
                    sticker: false
                  }
                });
                document.location.href = '/backend/idioma/listar';
              }
          });
        }
      });

    }
    /*****************************************************************************************/
    /***********************                ALTERAR                  *************************/
    /*****************************************************************************************/

    if(router == "idioma/alterar"){

      $("#bt_idioma_alterar").click(function(){
        var id_idioma = $("input[id='idioma_code']").val().replace( /[\/]/g, "" );
        var nome = $("input[id='idioma_nome']").val().replace( /[\/]/g, "" );
        var sigla = $("input[id='idioma_sigla']").val().replace( /[\/]/g, "|" );
        var icon = $("input[id='idioma_icon']").val().replace( /[\/]/g, "|" );
        var ordem = $("input[id='idioma_ordem']").val().replace( /[\/]/g, "" );
        var destaque = $('input[name=idioma_destaque]:checked').val();
        var status = $('input[name=idioma_status]:checked').val();
        var ref = "";
        var count = 0;
        if(nome == ""){ref += "<li>Nome</li>"; count += 1;}
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
        
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'idioma/alteracao/id/'+id_idioma+'/nome/'+nome+'/sigla/'+sigla+'/icon/'+icon+'/ordem/'+ordem+'/destaque/'+destaque+'/status/'+status+'/'},
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
                
              }
          });
        }
            
      });

    }  
    /*****************************************************************************************/
    /***********************                SUBMENU                  *************************/
    /*****************************************************************************************/

    $("a[id='sidiomaCad']").click(function(){
      var idsub = $(this).next("input").val();
      $.ajax({
        url: "/backend/Ajax.php",
        type: "POST",
        data: {'url':'idioma/scadastrar/'},
        success: function(dados){
            /* STidioma DEFAULT LAYOUT */
            $("#retorno").html("");
            $("#editor_lista").hide();
            $("#editor_acao").html(dados);
            $("#editor_acao").show();
            // $('.input-group.date').datepicker({ format: 'dd/mm/yyyy' });
            
            $(".closed_acao").click(function(){
              $("#editor_acao").hide();
              $("#editor_lista").show();
            });
            $("#bt_sidioma_cadastrar").click(function(){
                var nome = $("input[id='sidioma_nome']").val().replace( /[\/]/g, "" );
                var link = $("input[id='sidioma_link']").val().replace( /[\/]/g, "|" );
                var ordem = $("input[id='sidioma_ordem']").val().replace( /[\/]/g, "" );
                var tipo = $('input[name=sidioma_tipo]:checked').val();
                //console.log(titulo+" / "+chamada+" / "+texto+" / "+data+" / "+categoria+" / "+galeria+" / "+destaque+" / "+img);
                var ref = "";
                var count = 0;
                if(nome == ""){ref += "<li>Nome</li>"; count += 1;}
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
                  $.ajax({
                      url: "/backend/Ajax.php",
                      type: "POST",
                      data: {'url':'idioma/sinserir/id/'+idsub+'/nome/'+nome+'/link/'+link+'/ordem/'+ordem+'/tipo/'+tipo+'/'},
                      success: function(ok){
                        $("#retorno_index").html(ok);
                        $("#retorno_index").show();
                        $("#editor_acao").html('');
                        $("#editor_lista").show();
                        setTimeout('location.reload()', 0);
                      }
                  });
                }
            });
          }
      });
    
      // $('#painel').modal();  
    });

    /*******************  SUBMENU ALTERAR **********************/
    $("a[id='sidiomaAlt']").click(function(){
        var idsub = $(this).next("input").val();
        //alert(idsub);
        $.ajax({
          url: "/backend/Ajax.php",
          type: "POST",
          data: {'url':'idioma/salterar/id/'+idsub},
          success: function(dados){
              $("#retorno").html("");
              $("#editor_lista").hide();
              $("#editor_acao").html(dados);
              $("#editor_acao").show();
              
              $(".closed_acao").click(function(){
                $("#editor_acao").hide();
                $("#editor_lista").show();
              });
              $("#bt_sidioma_alterar").click(function(){
                  var nome = $("input[id='sidioma_nome']").val().replace( /[\/]/g, "" );
                  var link = $("input[id='sidioma_link']").val().replace( /[\/]/g, "|" );
                  var ordem = $("input[id='sidioma_ordem']").val().replace( /[\/]/g, "" );
                  var tipo = $('input[name=sidioma_tipo]:checked').val();
                  //console.log(titulo+" / "+chamada+" / "+texto+" / "+data+" / "+categoria+" / "+galeria+" / "+destaque+" / "+img);
                  var ref = "";
                  var count = 0;
                  if(nome == ""){ref += "<li>Nome</li>"; count += 1;}
                  if(ref != 0){
                    $("#ref").html('<div class="alert alert-dismissible" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                    '<h5><i class="fa fa-exclamation-triangle"></i> Dados Obrigatórios:</h5><ol>'+ref+'</ol></div>');
                    $("#retorno").html("Error: Tem campos obrigatorios pendentes.");
                  }else{
                    $("#ref").html("");
                    $.ajax({
                        url: "/backend/Ajax.php",
                        type: "POST",
                        data: {'url':'idioma/alteracao/id/'+idsub+'/nome/'+nome+'/link/'+link+'/ordem/'+ordem+'/tipo/'+tipo+'/'},
                        success: function(ok){
                          //console.log(ok);
                          $("#retorno_index").html(ok);
                          $("#retorno_index").show();
                          $("#editor_acao").html('');
                          $("#editor_lista").show();
                          setTimeout('location.reload()', 0);
                        }
                    });
                  }
              });
            }
        });
      
        // $('#painel').modal();  
    });
      
    /*****************************************************************************************/
    /***********************                DELETAR                  *************************/
    /*****************************************************************************************/    
    
    if(router == "idioma/listar"){

      $("a[class='idiomaDel']").magnificPopup({
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

      $("a[class='idiomaDel']").click(function(){
              
        $("#modal-title").html("");
        $("#modal-body").html('<img src="/images/loading.gif" />');
        $("#modal-footer").html('');
        var $this = $(this);
        
        var id = $this.prev("input").val();
        $("#modal-title").text("Apagar idioma");
        $("#modal-body").html('<h5>Deseja realmente apagar o idioma número '+id+' ?</h5><span id="retorno"></span>');
        $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button name="del_conf" id="del_conf" class="btn btn-primary modal-confirm">Confirmar</button> <button class="btn btn-default modal-dismiss">Cancelar</button></div></div>');
        //confirmar
        $("button[name='del_conf']").click(function(){
            $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'idioma/deletar/id/'+id+'/'},
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