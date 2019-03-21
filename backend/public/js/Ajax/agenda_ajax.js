$(document).ready(function(){
/*********************************      agenda       ************************************/
/***********************   CADASTRAR  *************************/
$("#retorno_index").hide();
$("span[id='agendaCad']").click(function(){
  $.ajax({
    url: "/backend/Ajax.php",
    type: "POST",
    data: {'url':'agenda/cadastrar'},
    success: function(dados){
      console.log(dados);
        /* START DEFAULT LAYOUT */
        $("#retorno").html("");
        $("#editor_lista").hide();
        $("#editor_acao").html(dados);
        $("#editor_acao").show();
        $('.input-group.date').datetimepicker();
        
        $(".closed_acao").click(function(){
          $("#editor_acao").hide();
          $("#editor_lista").show();
        });
        /* END DEFAULT LAYOUT */
        // $("textarea[id=age_texto]").summeragee({
        //   height:'350px'
        // });
        
        //CKEDITOR.replace( 'age_texto' );
        $("textarea[id=age_chamada]").css({
          height:'200px'
        });
        var timestamp = $("#timestamp").val();
        var unique_salt00 = $("#unique_salt").val();
        $("input[id='age_img']").uploadifive({
          'auto'             : false,
          'fileType'         : 'image/*',
          'checkScript'      : '/backend/public/plugins/uploadfive/check-exists_agenda.php',
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
          'uploadScript'     : '/backend/public/plugins/uploadfive/uploadifive_agenda.php',
          'onUploadComplete' : function(file, data) { 
            //console.log(data); 
            
              if(data != ""){}
            }
        });
        $("#bt_age_cadastrar").click(function(){
          var img = $("span[class='filename']").html();
         
          if( img != undefined){
            img = img
          }else{
            img = "";
            
          }
            var titulo = $("input[id='age_titulo']").val().replace( /[\/]/g, "" );
            var chamada = $("textarea[id='age_chamada']").val().replace( /[\/]/g, "" );
            var texto = CKEDITOR.instances['age_texto'].getData().replace( /[\/]/g, "|" );
            //var texto = $("textarea['name='age_texto']").code().replace( /[\/]/g, "|" );
            var data = $("input[id='age_data']").val().replace( /[\/]/g, "-" );
            //var link = $("input[name='age_link']").val().replace( /[\/]/g, "" );
            var destaque = $('input[name=age_destaque]:checked').val();
            console.log(titulo+" / "+chamada+" / "+texto+" / "+data+" / "+destaque+" / "+img);
            var ref = "";
            var count = 0;
            if(titulo == ""){ref += "<li>Titulo</li>"; count += 1;}
            if(ref != 0){
              $("#ref").html('<div class="alert alert-dismissible" role="alert">'+
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
              '<h5><i class="fa fa-exclamation-triangle"></i> Dados Obrigatórios:</h5><ol>'+ref+'</ol></div>');
              $("#retorno").html("Error: Tem campos obrigatorios pendentes.");
            }else{
              $("#ref").html("");
              var titulo = titulo.replace(/'/g, "\\'");
              $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'agenda/inserir/img/'+img+'/titulo/'+titulo+'/texto/'+texto+'/chamada/'+chamada+'/data/'+data+'/destaque/'+destaque+'/'},
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
    /*****************************************************************************************/
    /***********************                ALTERAR                  *************************/
    /*****************************************************************************************/
    $("a[id='agendaAlt']").click(function(){
       var $this = $(this);
       var id = $this.next("input").val();
       $.ajax({
          url: "/backend/Ajax.php",
          type: "POST",
          data: {'url':'agenda/selecionar/id/'+id},
          success: function(dados){
            //console.log(dados);
             
            $("#retorno").html("");
            $("#editor_lista").hide();
            $("#editor_acao").html(dados);
            $("#editor_acao").show();
            $('.input-group.date').datetimepicker();
            
            $(".closed_acao").click(function(){
              $("#editor_acao").hide();
              $("#editor_lista").show();
            });
            var timestamp = $("#timestamp").val();
            var unique_salt00 = $("#unique_salt").val();
            $("input[id='age_img']").uploadifive({
              'auto'             : false,
              'fileType'         : 'image/*',
              'checkScript'      : '/backend/public/plugins/uploadfive/check-exists_agenda.php',
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
              'uploadScript'     : '/backend/public/plugins/uploadfive/uploadifive_agenda.php',
              'onUploadComplete' : function(file, data) { 
                //console.log(data); 
                
                  if(data != ""){}
                }
            });
            $("#bt_age_alterar").click(function(){
              var id_age = id;
              var img = $("span[class='filename']").html();
              var imgalt = "";
              if( img != undefined){
                img = img
                imgalt = "img";
              }else{
                img = $("input[id='age_img']").val().replace( /[\/]/g, "" );
                imgalt = "imgalt";
              }
                var titulo = $("input[id='age_titulo']").val().replace( /[\/]/g, "" );
                var chamada = $("textarea[id='age_chamada']").val().replace( /[\/]/g, "" );
                var texto = CKEDITOR.instances['age_texto'].getData().replace( /[\/]/g, "|" );
                //var texto = $("textarea['name='age_texto']").code().replace( /[\/]/g, "|" );
                var data = $("input[id='age_data']").val().replace( /[\/]/g, "-" );
                //var link = $("input[name='age_link']").val().replace( /[\/]/g, "" );
                var destaque = $('input[name=age_destaque]:checked').val();
                //console.log(titulo+" / "+chamada+" / "+texto+" / "+data+" / "+destaque+" / "+img);
                var ref = "";
                var count = 0;
                if(titulo == ""){ref += "<li>Titulo</li>"; count += 1;}
                if(ref != 0){
                  $("#ref").html('<div class="alert alert-dismissible" role="alert">'+
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                  '<h5><i class="fa fa-exclamation-triangle"></i> Dados Obrigatórios:</h5><ol>'+ref+'</ol></div>');
                  $("#retorno").html("Error: Tem campos obrigatorios pendentes.");
                }else{
                  $("#ref").html("");
                  var titulo = titulo.replace(/'/g, "\\'");
                 
                  $.ajax({
                      url: "/backend/Ajax.php",
                      type: "POST",
                      data: {'url':'agenda/alteracao/id/'+id_age+'/'+imgalt+'/'+img+'/titulo/'+titulo+'/texto/'+texto+'/chamada/'+chamada+'/data/'+data+'/destaque/'+destaque+'/'},
                      success: function(alterar){
                        //console.log(alterar);
                        $("#retorno_index").html(alterar);
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
    $("a[id='agendaDel']").tooltip()
    $("a[id='agendaDel']").click(function(){
       
      $(".modal-title").html("");
      $(".modal-body").html('<img src="/../backend/public/img/loading.gif" />');
      $(".modal-footer").html('');
       var $this = $(this);
       
       var id = $this.prev("input").val();
       $(".modal-title").text("Apagar agenda");
       $(".modal-body").html('<h5>Deseja realmente apagar a agenda numero '+id+' ?</h5><span id="retorno"></span>');
       $(".modal-footer").html('<span id="retorno"></span><button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button><button name="del_conf" id="del_conf" class="btn btn-primary">Confirmar</button>');
       //confirmar
       $("button[name='del_conf']").click(function(){
           $.ajax({
                url: "/backend/Ajax.php",
                type: "POST",
                data: {'url':'agenda/deletar/id/'+id+'/'},
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
});