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
    if(router == "cliente/registar"){
       
      let row = 0; 
      $("button[id='add_contato']").click(function(e) {
        e.preventDefault();
        if(row < 4){
          row++;
        } 
                
        if(row <= 3 && row >= 0){
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'cliente/add/row/'+row},
              success: function(ok){
                $("div[id='new_contato']").append(ok);                    
                $("button[data-remove='remove"+row+"']").click(function() {
                  var id_remove = $(this).attr("data-id");
                  $("#"+id_remove+"").remove();
                  row--;
                  if(row < 3){
                    $("button[id='add_contato']").show("slow");
                  }else{
                    $("button[id='add_contato']").hide("slow");
                  }
                });
                if(row == 3){
                  $("button[id='add_contato']").hide("slow");
                }else{
                  $("button[id='add_contato']").show("slow");
                }               
              }
          });
        }
        
      });
     
      var timestamp = $("#timestamp").val();
      var unique_salt00 = $("#unique_salt").val();
      $("input[id='img']").uploadifive({
        'auto'             : false,
        'fileType'         : ["image\/gif","image\/jpeg","image\/png","application\/msword","application\/pdf","application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
        'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_cliente.php',
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
        'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_cliente.php',
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
              }
            }
        });    
        $("a[name=bt_cliente_cadastrar]").click(function(){
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
            
            let idioma = $("#idioma").val();
            let referencia = $("input[id='cli_referencia']").val().replace( /[\/]/g, "" );
            let saudacao = $("select[id='cli_saudacao'] option:selected").val();
            let nome = $("input[id='cli_nome']").val().replace( /[\/]/g, "" );
            let telefone = $("input[id='cli_telefone']").val().replace( /[\/]/g, "" );
            let email = $("input[id='cli_email']").val().replace( /[\/]/g, "" );
            let url = $("input[id='cli_url']").val().replace( /[\/]/g, "" );          
            let nif = $("input[id='cli_nif']").val().replace( "", 0 );
            
            let tipo = $("select[id='cli_tipo'] option:selected").val();
    
            let rua = $("input[id='cli_rua']").val().replace( /[\/]/g, "" );
            let numero = $("input[id='cli_numero']").val().replace( /[\/]/g, "" );
            let andar = $("input[id='cli_andar']").val().replace( /[\/]/g, "" );
            let cp = $("input[id='cli_cp']").val().replace( /[\/]/g, "" );
            let localidade = $("input[id='cli_localidade']").val().replace( /[\/]/g, "" );
            let distrito = $("select[id='cli_distrito'] option:selected").val();        
            let descricao = CKEDITOR.instances['cli_descricao'].getData().replace( /[\/]/g, "|" );
            let status = $('input[name=cli_status]:checked').val();
            let rgpd = $('input[name=cli_rgpd]:checked').val();
            let vendedor = $("select[id='cli_vendedor'] option:selected").val(); 
                                        
            let arr_area = $('#cli_area option:selected');
            let data = $.map(arr_area, function (obj) {
                return obj.value;
            });
            let area = data.join(",");
            let arr_produto = $('#cli_produto option:selected');
            let data_produto = $.map(arr_produto, function (obj) {
                return obj.value;
            });
            let produto = data_produto.join(",");
            let arr_associado = $('#cli_associado option:selected');
            let data2 = $.map(arr_associado, function (obj) {
                return obj.value;
            });
            let associado = data2.join(",");
            let arr = "";
            let item = 0;
            $("[data-tipo='contato']").each(function(index, el) {
                item++;
                arr += item+","+$("input[id=cont_nome_"+item+"]").val().replace( /[\/]/g,"")+
                ","+$("select[id=cont_cargo_"+item+"] option:selected").val().replace("",0)+
                ","+$("select[id=cont_especialidade_"+item+"] option:selected").val().replace("",0)+
                ","+$("input[id=cont_email_"+item+"]").val().replace( /[\/]/g,"")+
                ","+$("input[id=cont_telemovel_"+item+"]").val().replace( /[\/]/g,"")+
                ","+$("select[id=cont_lead_status_"+item+"] option:selected").val().replace("",0)+"*";
            })
            let str_contato = arr.slice(0,-1);
            //var valida = $("#valida").val();
            let ref = "";
            let count = 0;
            if(nome == ""){ref += "<li>Nome</li>"; count += 1;}
            if(ref != 0){
                new PNotify({
                title: 'OPS!',
                text: '<h5>Dados Obrigatórios:</h5>'+ref,
                type: 'danger'
            });
            }else{
            $("#ref").html("");
                nome = nome.replace(/'/g, "\\'");
                console.log(vendedor);
                
                $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'cliente/inserir/img/'+img+'/referencia/'+referencia+'/saudacao/'+saudacao+'/nome/'+nome+'/tipo/'+tipo+'/area/'+area+'/produto/'+produto+'/rua/'+rua+'/numero/'+numero+'/andar/'+andar+'/cp/'+cp+'/localidade/'+localidade+'/distrito/'+distrito+'/telefone/'+telefone+'/email/'+email+'/url/'+url+'/descricao/'+descricao+'/status/'+status+'/contato/'+str_contato+'/associado/'+associado+'/vendedor/'+vendedor+'/nif/'+nif+'/rgpd/'+rgpd+'/'},
                  success: function(ok){
                    console.log(ok);
                    
                     new PNotify({
                      title: 'Success!',
                      text: ok,
                      type: 'success'
                    });        
                    /*document.location.href = '/backend/cliente/listar/lang/'+idioma;*/
                  }
              });
          }
        }
       
    }
    /*****************************************************************************************/ 
    /***********************                ALTERAR                  *************************/
    /*****************************************************************************************/
    if(router == "cliente/alterar"){
      bind_lead_control();
            var qtd_contato = $("input[id='qtd_contato']").val();                    
            if(qtd_contato == 3){
              $("button[id='add_contato']").hide("slow");
            }else{
              $("button[id='add_contato']").show("slow");
            }
            var row = qtd_contato;
            $("a[data-remove='remove']").magnificPopup({
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
            $("a[data-remove='remove']").click(function(e) {
              e.preventDefault();
              var id_remove = $(this).attr("data-id");
         
              $("#modal-title").html("");
              $("#modal-body").html('<img src="/images/loading.gif" />');
              $("#modal-footer").html('');
              
              var id = $(this).attr("data-db");
              var name = $(this).attr("data-name");
              $("#modal-title").text("Apagar Contacto");
              $("#modal-body").html('<h5>Deseja realmente apagar o CONTACTO: '+name+' ?</h5><span id="retorno"></span>');
              $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button name="del_conf" id="del_conf" class="btn btn-primary modal-confirm">Confirmar</button> <button class="btn btn-default modal-dismiss">Cancelar</button></div></div>');
                 var id = $(this).attr("data-db");
                 $("button[name='del_conf']").click(function(){
                     $.ajax({
                        url: "/backend/Ajax.php",
                        type: "POST",
                        data: {'url':'contato/deletar/id/'+id+'/'},
                        success: function(info){
                            // $("#retorno").html(info);
                            $(".footerBox").html('<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>');
                            // var ident = $('input[name="id_retorno"]').val();
                            // $("#"+ident).hide('slow');
                            $(".modal-body").html("<p>"+info+"</p>")
                                            .css({
                                               'font-size' : '14px'
                                            });
                           $(".modal-footer").html('<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>');
                           $("#row"+id_remove).remove();
                            row--;
                            ///console.log("subtrair : "+row);
                            if(row < 3){
                              $("button[id='add_contato']").show("slow");
                            }else{
                              $("button[id='add_contato']").hide("slow");
                            }
                        }
                     });
                 });                
            });
            $("button[id='add_contato']").click(function(e) {
              e.preventDefault();
              row++;
              $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'cliente/add/row/'+row},
                  success: function(ok){
                    //alert(ok)
                    $("div[id='new_contato']").append(ok);
                    $("button[data-remove='remove"+row+"']").click(function() {
                      var id_remove = $(this).attr("data-id");
                      $("#"+id_remove+"").remove();
                      row--;
                      ///console.log("subtrair : "+row);
                      if(row < 3){
                        $("button[id='add_contato']").show("slow");
                      }else{
                        $("button[id='add_contato']").hide("slow");
                      }
                    });
                    if(row == 3){
                      $("button[id='add_contato']").hide("slow");
                    }else{
                      $("button[id='add_contato']").show("slow");
                    }
                    bind_lead_control();
                  }
              });
            }); 
            var timestamp = $("#timestamp").val();
            var unique_salt00 = $("#unique_salt").val();
            $("input[id='img']").uploadifive({
              'auto'             : true,
              'fileType'         : 'image/*',
              'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_cliente.php',
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
              'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_cliente.php',
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
            $("a[name=bt_cliente_alterar]").click(function(){              
                let img = $("span[class='filename']").html();         
                
                if( img != undefined){
                    img = img
                }else{
                    img = $("input[id='cli_imagem']").val().replace( /[\/]/g, "" );            
                } 
              
                ajax_enviar_dados2(img)
              
            })            
            function ajax_enviar_dados2(img){ 
                let id_cli = $("input[id='cli_code']").val().replace( /[\/]/g, "" );                
                let referencia = $("input[id='cli_referencia']").val().replace( /[\/]/g, "" );
                let saudacao = $("select[id='cli_saudacao'] option:selected").val();
                let nome = $("input[id='cli_nome']").val().replace( /[\/]/g, "" );
                let telefone = $("input[id='cli_telefone']").val().replace( /[\/]/g, "" );
                let email = $("input[id='cli_email']").val().replace( /[\/]/g, "" );
                let url = $("input[id='cli_url']").val().replace( /[\/]/g, "" );          
                let nif = $("input[id='cli_nif']").val().replace( "", 0 );
                
                let tipo = $("select[id='cli_tipo'] option:selected").val();
                let vendedor = $("select[id='cli_vendedor'] option:selected").val();
        
                let rua = $("input[id='cli_rua']").val().replace( /[\/]/g, "" );
                let numero = $("input[id='cli_numero']").val().replace( /[\/]/g, "" );
                let andar = $("input[id='cli_andar']").val().replace( /[\/]/g, "" );
                let cp = $("input[id='cli_cp']").val().replace( /[\/]/g, "" );
                let localidade = $("input[id='cli_localidade']").val().replace( /[\/]/g, "" );
                let distrito = $("select[id='cli_distrito'] option:selected").val();        
                let descricao = CKEDITOR.instances['cli_descricao'].getData().replace( /[\/]/g, "|" );
                let status = $('input[name=cli_status]:checked').val();
                let rgpd = $('input[name=cli_rgpd]:checked').val();
                let google_map = $("input[id='cli_google_map']").val().replace( /[\/]/g, "" );
                                            
                let arr_area = $('#cli_area option:selected');
                let data = $.map(arr_area, function (obj) {
                    return obj.value;
                });
                let area = data.join(",");
                let arr_produto = $('#cli_produto option:selected');
                let data_produto = $.map(arr_produto, function (obj) {
                    return obj.value;
                });
                let produto = data_produto.join(",");
                let arr_associado = $('#cli_associado option:selected');
                let data2 = $.map(arr_associado, function (obj) {
                    return obj.value;
                });
                let associado = data2.join(",");
 
                var arr = "";
                var item = 0;
                $("[data-tipo='contato']").each(function(index, el) {
                 item++;
                    arr += item+","+$("input[id=cont_nome_"+item+"]").val().replace( /[\/]/g,"")+
                    ","+$("select[id=cont_cargo_"+item+"] option:selected").val().replace( "",0)+
                    ","+$("select[id=cont_especialidade_"+item+"] option:selected").val().replace( "",0)+
                    ","+$("input[id=cont_email_"+item+"]").val().replace( /[\/]/g,"")+
                    ","+$("input[id=cont_telemovel_"+item+"]").val().replace( /[\/]/g,"")+
                    ","+$("select[id=cont_lead_status_"+item+"] option:selected").val()+
                    ","+$("input[id=cont_id_"+item+"]").val().replace( /[\/]/g,"")+"*";
                })
                var str_contato = arr.slice(0,-1);
                var ref = "";
                var count = 0;
                if(nome == ""){ref += "<li>Nome</li>"; count += 1;}
                if(ref != 0){
                    new PNotify({
                        title: 'OPS!',
                        text: '<h5>Dados Obrigatórios:</h5>'+ref,
                        type: 'danger'
                    });
                }else{
                    $("#ref").html("");
                    //var nome = nome.replace(/'/g, "\\'");
                         
                    $.ajax({
                        url: "/backend/Ajax.php",
                        type: "POST",
                        data: {'url':`cliente/alteracao/id/${id_cli}/img/${img}/referencia/${referencia}/saudacao/${saudacao}/nome/${nome}/tipo/${tipo}+/area/${area}/produto/${produto}/vendedor/${vendedor}/rua/${rua}/numero/${numero}/andar/${andar}/cp/${cp}/localidade/${localidade}/distrito/${distrito}/telefone/${telefone}/email/${email}/url/${url}/descricao/${descricao}/status/${status}/contato/${str_contato}/associado/${associado}/nif/${nif}/rgpd/${rgpd}/google_map/${google_map}/`},
                        success: function(alterar){
                            console.log(alterar);
                            if(alterar == "1"){
                              new PNotify({
                                title: 'Success!',
                                text: "Dados alterados com sucesso!",
                                type: 'success'
                              });
                            }
                            else{
                              new PNotify({
                                title: 'Erro',
                                text: "Ocorreu um erro na actualização dos dados",
                                type: 'success'
                              });
                            }
                            
                            setTimeout('location.reload()', 3000);
                        }
                    });
                }
         
            }             
            $("a[id='img_Del']").click(function(){
                var ref = $(this).attr("data-del-img");
                $.ajax({
                      url: "/backend/Ajax.php",
                      type: "POST",
                      data: {'url':'cliente/img_deletar/id/'+ref+'/'},
                      success: function(info){
                            console.log(info)
                            new PNotify({
                              title: 'Sucesso!',
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
    if(router == "cliente/listar"){
      $("table.dataTable").on('click',"a.cliDel", function(e){
        var the_a_button = $(this);
                   
        $("#modal-title").html("");
        $("#modal-body").html('<img src="/images/loading.gif" />');
        $("#modal-footer").html('');
        var id = $(this).prev("input").val();
        var name = $(this).attr("data-name");
        console.log(name)
        $("#modal-title").text("Apagar Cliente");
        $("#modal-body").html('<h5>Deseja realmente apagar o cliente: <strong>'+name+'</strong> ?</h5><span id="retorno"></span>');
        $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button name="del_conf" id="del_conf" class="btn btn-primary modal-confirm">Confirmar</button> <button class="btn btn-default modal-dismiss">Cancelar</button></div></div>');
         //apresentar o popup
         $.magnificPopup.open({
          items: {
            src: the_a_button.attr("href")
          },
          fixedContentPos: false,
          fixedBgPos: true,    
          overflowY: 'auto',    
          closeBtnInside: true,
          preloader: false,        
          midClick: false,
          removalDelay: 300,
          mainClass: 'my-mfp-zoom-in',
          modal: true,
          type: 'inline'
        });
        $("button[name='del_conf']").click(function(){
          $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            data: {'url':'cliente/deletar/id/'+id+'/'},
            success: function(info){
              $("#retorno").html(info);
              var ident = $('input[name="id_retorno"]').val();
              $("#"+ident).hide('slow');
              $("#modal-footer").html('<div class="row"><div class="col-md-12 text-right"> <button class="btn btn-default modal-dismiss">Fechar</button></div></div>')
            }
          });
        });
      });
      $("a[id='uploadCsv']").click(function(){
            
        $("#list_uploadcsv").html('<img src="/images/loading.gif" />');
     
            $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'excel/upcliente/'},
              success: function(info){                        
                $("#list_uploadcsv").html(info);
              }
            });
      });
    }
    /*********************************************************************
    * FOLLOUP
    ***********************************************************************/
   
    if(router == "cliente/perfil"){
      var lat = $("input[name=lat]").val();
      var long = $("input[name=long]").val();
      var place = $("input[name=place]").val();
      
      if(lat && long && place){
        $('.map')
        .gmap3({
          center:[lat, long],
          zoom:16
        })
        .marker([
          {position:[lat, long]},
          {address: place},
        ])
      }
      
      
      $("a#foll_gravar").click(function(){
          var cli_id = $("input[id='cli_code']").val().replace( /[\/]/g, "" );
          var foll_tipo = $("select[id='foll_tipo'] option:selected").val();
          var foll_titulo = $("input[id='foll_titulo']").val().replace( /[\/]/g, "|" ); 
          var foll_texto = $("textarea[id='foll_texto']").val().replace( /[\/]/g, "|" );     
          var ref = "";
          var count = 0;
          if(foll_titulo == ""){ref += "<li>Título</li>"; count += 1; console.log(foll_titulo)}
          if(foll_texto == ""){ref += "<li>Mensagem</li>"; count += 1; console.log(foll_texto)}
          //if(foll_tipo == 0){ref += "<li>Tipo de contacto</li>"; count += 1;}
          if(ref != 0){
            new PNotify({
              title: 'OPS!',
              text: '<h5>Dados Obrigatórios:</h5>'+ref,
              type: 'danger'
            });
          }else{
            $("#ref").html("");                
                $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'followup/registar/id/'+cli_id+'/tipo/'+foll_tipo+'/titulo/'+foll_titulo+'/texto/'+foll_texto+'/'},
                  success: function(ok){
                    new PNotify({
                      title: 'Success!',
                      text: ok,
                      type: 'success'
                    });        
                    //setTimeout('location.reload()', 0);
                    $("select[id='foll_tipo']").val(0);
                    $("input[id='foll_titulo']").val("");
                    $("textarea[id='foll_texto']").val("");     
                    var cli_referencia = $("input[id=cli_ref]").val();
                    getTimeline(cli_referencia)
                  }
              });
          }
      });
      $("#tar_gravar").click(function(){
          var cli_id = $("input[id='cli_code']").val().replace( /[\/]/g, "" );
          var tar_tipo = $("select[id='tar_tipo'] option:selected").val();
          var tar_titulo = $("input[id='tar_titulo']").val().replace( /[\/]/g, "" );
          var tar_dtstart = $("input[id='tar_dtstart']").val().replace( /[\/]/g, "-" );
          var tar_hrstart = $("input[id='tar_hrstart']").val().replace( /[\/]/g, "-" );
          var tar_dtend = $("input[id='tar_dtend']").val().replace( /[\/]/g, "-" );
          var tar_hrend = $("input[id='tar_hrend']").val().replace( /[\/]/g, "-" );
          var tar_texto = $("textarea[id='tar_texto']").val().replace( /[\/]/g, "|" );     
          var tar_prioridade = $("select[id='tar_prioridade'] option:selected").val();
          var ref = "";
          var count = 0;
          if(tar_titulo == ""){ref += "<li>Título</li>"; count += 1;}
          if(tar_dtstart == ""){ref += "<li>Data inicial</li>"; count += 1;}
          if(tar_dtend == ""){ref += "<li>Data final</li>"; count += 1;}
          if(tar_texto == ""){ref += "<li>Mensagem</li>"; count += 1;}
          if(tar_tipo == 0){ref += "<li>Segmento</li>"; count += 1;}
          if(ref != 0){
            new PNotify({
              title: 'OPS!',
              text: '<h5>Dados Obrigatórios:</h5>'+ref,
              type: 'danger'
            });
          }else{
            $("#ref").html("");                
                $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'tarefa/registar/id/'+cli_id+'/tipo/'+tar_tipo+'/titulo/'+tar_titulo+'/dtstart/'+tar_dtstart+'/hrstart/'+tar_hrstart+'/dtend/'+tar_dtend+'/hrend/'+tar_hrend+'/texto/'+tar_texto+'/prioridade/'+tar_prioridade+'/'},
                  success: function(ok){
                     new PNotify({
                      title: 'Sucesso!',
                      text: ok,
                      type: 'success'
                    });     
                    var today = new Date();
                    var dd = today.getDate();
                    var mm = today.getMonth()+1;
                    var yyyy = today.getFullYear();
                    if(dd<10) { dd = '0'+dd } 
                    if(mm<10) { mm = '0'+mm} 
                    today = dd + '/' + mm + '/' + yyyy;
                    $("select[id='tar_tipo']").val(0);
                    $("input[id='tar_titulo']").val("");
                    $("input[id='tar_dtstart']").val(today);
                    $("input[id='tar_dtend']").val("");
                    $("input[id='tar_hrstart']").val("");
                    $("input[id='tar_hrend']").val("");
                    $("textarea[id='tar_texto']").val("");     
                    $("select[id='tar_prioridade'] option:selected").val(1); 
                    //setTimeout('location.reload()', 0);
                    var cli_referencia = $("input[id=cli_ref]").val();
                    getTimeline(cli_referencia)
                  }
              });
          }
      });
      function getTimeline(ref){
        $("[data-timeline]").html('<img src="/images/loading.gif" />');
        $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            data: {'url':'cliente/timeline/ref/'+ref+'/'},
            success: function(timeline){
              
              $("[data-timeline]").html(timeline);
            
            }
        });
      }
      //apagar formações
      $("table.dataTable").on('click',"a.btn-list-delete", function(e){
           
        var the_a_button = $(this);
        
        $("#modal-title").html("");
        $("#modal-body").html('<img src="/images/loading.gif" />');
        $("#modal-footer").html('');
        var id = $(this).attr("data-id");
        var name = $(this).attr("data-name");
        
        $("#modal-title").text("Apagar formação");
        $("#modal-body").html('<h5>Deseja realmente apagar a formação(a) <strong>'+name+'</strong> ?</h5><span id="retorno"></span>');
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
              data: {'url':'formacao/deletar/id/'+id+'/'},
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
    /* PDF */
    if(router == "cliente/perfil"){
      $("a.btn-list-formando").on("click",function(){       
                      
        let ref_formacao = $(this).data("id");     
        $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {url:'formacao/pdf/', ref: ref_formacao},
              success: function(result){             
                console.log(result);                                
                window.open(
                    '/backend/public/pdf/view_formacao.pdf',
                    'Batch Print',
                    'width=960,height=960,location=_newtab'
                ); 
              }
            });
      });
    }
});
function bind_lead_control(){
  $("[data-tipo=lead]").bind("change", function(){
    var changed_element = $(this);
    //must take action on other elements
    if(changed_element.val() != ""){
      //other elements
      $("[data-tipo=lead]").not(changed_element).val(""); //reset others
    }
  })
}