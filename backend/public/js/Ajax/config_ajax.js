$(document).ready(function () {
    /*********************************      NOTÍCIA       ************************************/
    /*GET ROUTER*/
    let router = $("router").attr("base");
    let base = router.split("/");
    let controller = router[0];
    let action = router[1];

/*     function colorindo() {
        $('.configcolor').minicolors({
            opacity: true,
            change: function (value, opacity) {
                console.log(value + ' - ' + opacity);
            },
            theme: 'bootstrap'
        });
    } */

    $("#retorno_index").hide();

    if(router == "configuracao/empresa"){
      
        var timestamp = $("#timestamp").val();
        var unique_salt00 = $("#unique_salt").val();

        $("input[id='cfg_favicon']").uploadifive({
        'auto'             : true,
        'fileType'         : 'image/*',
        'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_config.php',       
        'queueID'          : 'queue',
        'fileSizeLimit'    : 1024,
        'queueSizeLimit'   : 1,
        'UploadLimit'      : 1,
        'buttonClass'  : 'btn btn-primary',
        'buttonText'   : translate('Anexar'),
        'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_config.php',
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
                ajax_enviar_dados(data,"","","","")
              }//ver se existe imagens
            }
        }); 

        $("input[id='cfg_logo']").uploadifive({
            'auto'             : true,
            'fileType'         : 'image/*',
            'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_config.php',       
            'queueID'          : 'queue2',
            'fileSizeLimit'    : 1024,
            'queueSizeLimit'   : 1,
            'UploadLimit'      : 1,
            'buttonClass'  : 'btn btn-primary',
            'buttonText'   : translate('Anexar'),
            'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_config.php',
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
                    ajax_enviar_dados("",data,"","","")
                }
            }
        }); 

        $("input[id='cfg_logo2']").uploadifive({
        'auto'             : true,
        'fileType'         : 'image/*',
        'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_config.php',       
        'queueID'          : 'queue3',
        'fileSizeLimit'    : 1024,
        'queueSizeLimit'   : 1,
        'UploadLimit'      : 1,
        'buttonClass'  : 'btn btn-primary',
        'buttonText'   : translate('Anexar'),
        'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_config.php',
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
                    ajax_enviar_dados("","",data,"","")
                }
            }
        }); 

        $("input[id='cfg_logosocial']").uploadifive({
        'auto'             : true,
        'fileType'         : 'image/*',
        'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_config.php',       
        'queueID'          : 'queue4',
        'fileSizeLimit'    : 1024,
        'queueSizeLimit'   : 1,
        'UploadLimit'      : 1,
        'buttonClass'  : 'btn btn-primary',
        'buttonText'   : translate('Anexar'),
        'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_config.php',
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
                    ajax_enviar_dados("","","",data,"")
                }
            }
        }); 

        $("input[id='cfg_pino']").uploadifive({
        'auto'             : true,
        'fileType'         : 'image/*',
        'checkScript'      : '/backend/public/vendor/uploadfive/check-exists_config.php',       
        'queueID'          : 'queue5',
        'fileSizeLimit'    : 1024,
        'queueSizeLimit'   : 1,
        'UploadLimit'      : 1,
        'buttonClass'  : 'btn btn-primary',
        'buttonText'   : translate('Anexar'),
        'uploadScript'     : '/backend/public/vendor/uploadfive/uploadifive_config.php',
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
                    ajax_enviar_dados("","","","",data)
                }
            }
        }); 

        $("#bt_cfg").click(function () {

            var config_favicon = $("#config_favicon").val();
            var config_logo = $("#config_logo").val();
            var config_logoalt = $("#config_logoalt").val();
            var config_logosocial = $("#config_logosocial").val();
            var config_pino = $("#config_pino").val();
 
            ajax_enviar_dados(config_favicon,config_logo,config_logoalt,config_logosocial,config_pino);
 
        });//ser_cadastrar

        function ajax_enviar_dados(favicon,logo,logoalt,logosocial,pino) {
            var empresa = $("input[id='cfg_empresa']").val().replace(/[\/]/g, "|");
            var cp = $("input[id='cfg_cp']").val().replace(/[\/]/g, "|");
            var morada = $("input[id='cfg_morada']").val().replace(/[\/]/g, "|");
            //var numero = $("input[id='cfg_numero']").val().replace(/[\/]/g, "|");
            //var andar = $("input[id='cfg_andar']").val().replace(/[\/]/g, "|");
            var localidade = $("input[id='cfg_localidade']").val().replace(/[\/]/g, "|");                    
            var distrito = $("input[id='cfg_distrito']").val().replace(/[\/]/g, "|");
            var telefone = $("input[id='cfg_telefone']").val().replace(/[\/]/g, "|");
            var email = $("input[id='cfg_email']").val().replace(/[\/]/g, "|");
            var site = $("input[id='cfg_site']").val().replace(/[\/]/g, "|");
            var obs = CKEDITOR.instances['cfg_obs'].getData().replace(/[\/]/g, "|");
            $.ajax({
                url: "/backend/Ajax.php",
                type: "POST",
                data: {'url': 'configuracao/alteracao/id/1/status/1/favicon/' + favicon + '/logo/' + logo + '/logoalt/' + logoalt + '/logosocial/' + logosocial + '/pino/' + pino + '/empresa/' + empresa + '/cp/' + cp + '/morada/' + morada + '/localidade/' + localidade + '/distrito/' + distrito + '/telefone/' + telefone + '/email/' + email + '/site/' + site + '/obs/' + obs + '/'},
                success: function (alterar) {
                    new PNotify({
                        title: translate('Sucesso!'),
                        text: alterar,
                        type: 'success'
                    }); 
                    //setTimeout('location.reload()', 0);
                }
            });
        }


        $("a[name='img_Del']").click(function(){
          var img = $(this).attr("ref");
          $.ajax({
                url: "/backend/Ajax.php",
                type: "POST",
                data: {'url':'configuracao/del_img/img/'+img+'/'},
                success: function(info){
                      console.log(info)  
                      new PNotify({
                        title: translate('Sucesso!'),
                        text: info,
                        type: 'success'
                      }); 
                      //setTimeout('location.reload()', 0);                            
                }
          });
        });

    }

    if(router == "configuracao/avancado"){ 
     
        $('.custom-tag-input').tagsinput({
            tagClass: function (item) {
                return 'label label-inverse';
            }
        });
        // var clickCheckbox = document.querySelector('#cfg_instagramview')
        // , clickButton = document.querySelector('#bt_cfg_avancado');
        // clickButton.addEventListener('click', function() {
        //   //alert(clickCheckbox.checked);
        // });
        $("#bt_cfg").click(function () {

            var fontgoogle = $("textarea[id='cfg_fontgoogle']").val().replace(/[\/]/g, "|");
            var fontconfig = $("input[id='cfg_fontconfig']").val().replace(/[\/]/g, "|");
            var rss = $("input[id='cfg_rss']").val().replace(/[\/]/g, "|");
            var aovivo = $("input[id='cfg_aovivo']").val().replace(/[\/]/g, "|");
            var aovivoradio = $("input[id='cfg_aovivoradio']").val().replace(/[\/]/g, "|");
            var descricao = $("textarea[id='cfg_descricao']").val().replace(/[\/]/g, "|");
            var conteudo = $("input[id='cfg_conteudo']").tagsinput('items');
            var skype = $("input[id='cfg_skype']").val().replace(/[\/]/g, "|");
            var whatsapp = $("input[id='cfg_whatsapp']").val().replace(/[\/]/g, "|");
            var facebook = $("input[id='cfg_facebook']").val().replace(/[\/]/g, "|");
            var facebooktxt = $("textarea[id='cfg_facebooktxt']").val().replace(/[\/]/g, "|");
            var twitter = $("input[id='cfg_twitter']").val().replace(/[\/]/g, "|");
            var twittertxt = $("textarea[id='cfg_twittertxt']").val().replace(/[\/]/g, "|");
            var youtube = $("input[id='cfg_youtube']").val().replace(/[\/]/g, "|");
            var blog = $("input[id='cfg_blog']").val().replace(/[\/]/g, "|");
            var google = $("input[id='cfg_google']").val().replace(/[\/]/g, "|");
            var instagram = $("input[id='cfg_instagram']").val().replace(/[\/]/g, "|");
            var instagramtxt = $("textarea[id='cfg_instagramtxt']").val().replace(/[\/]/g, "|");
            var instagramview = document.querySelector('#cfg_instagramview').checked;
            var tema = $("input[id='cfg_tema']").val().replace(/[\/]/g, "|");
            var tema2 = $("input[id='cfg_tema2']").val().replace(/[\/]/g, "|");
            var latitude = $("input[id='cfg_latitude']").val().replace(/[\/]/g, "|");
            var longitude = $("input[id='cfg_longitude']").val().replace(/[\/]/g, "|");
            var googlemap = $("textarea[id='cfg_googlemap']").val().replace(/[\/]/g, "|");
            var keyvisita = $("input[id='cfg_keyvisita']").val().replace(/[\/]/g, "|");
            var nav = $('input[name=cfg_nav]:checked').val();
            $.ajax({
                url: "/backend/Ajax.php",
                type: "POST",
                data: {'url': 'configuracao/alteracao/id/1/status/2/fontgoogle/' + fontgoogle + '/fontconfig/' + fontconfig + '/rss/' + rss + '/aovivo/' + aovivo + '/aovivoradio/' + aovivoradio + '/descricao/' + descricao + '/conteudo/' + conteudo + '/skype/' + skype + '/whatsapp/' + whatsapp + '/facebook/' + facebook + '/facebooktxt/' + facebooktxt + '/twitter/' + twitter + '/twittertxt/' + twittertxt + '/youtube/' + youtube + '/blog/' + blog + '/google/' + google + '/instagram/' + instagram + '/instagramtxt/' + instagramtxt + '/instagramview/' + instagramview + '/latitude/' + latitude + '/longitude/' + longitude + '/googlemap/' + googlemap + '/keyvisita/' + keyvisita + '/nav/' + nav + '/tema/' + tema + '/tema2/' + tema2 + '/'},
                success: function (alterar) {
                    console.log(alterar)  
                    new PNotify({
                        title: translate('Sucesso!'),
                        text: alterar,
                        type: 'success'
                    }); 
                    setTimeout('location.reload()', 0);
                }
            });
        });
               
    }

    if(router == "configuracao/pageconstrucao"){ 

    
        $('.custom-tag-input').tagsinput({
            tagClass: function (item) {
                return 'label label-inverse';
            }
        });
   

        $("#bt_cfg").click(function () {                   
            var pctitulo = $("input[id='cfg_pc_titulo']").val().replace(/[\/]/g, "|");
            var pcdescricao = $("textarea[id='cfg_pc_descricao']").val().replace(/[\/]/g, "|");
            var pcstatus = document.querySelector('#cfg_pc_status').checked;                
            $.ajax({
                url: "/backend/Ajax.php",
                type: "POST",
                data: {'url': 'configuracao/alteracao/id/1/status/3/pcstatus/' + pcstatus + '/pctitulo/' + pctitulo + '/pcdescricao/' + pcdescricao + '/'},
                success: function (alterar) {
                    console.log(alterar)  
                    new PNotify({
                        title: translate('Sucesso!'),
                        text: alterar,
                        type: 'success'
                    }); 
                    setTimeout('location.reload()', 0);
                }
            });
        });

    }

    if(router == "configuracao/integracao"){ 

    
        $('.custom-tag-input').tagsinput({
            tagClass: function (item) {
                return 'label label-inverse';
            }
        });
   

        $("#bt_cfg").click(function () {
            
            var keyvisita = $("input[id='cfg_keyvisita']").val().replace(/[\/]/g, "|");
            var gaid = $("input[id='cfg_ga_id']").val().replace(/[\/]/g, "|");
            var gatagscript = $("textarea[id='cfg_ga_tagscript']").val().replace(/[\/]/g, "|");
            var gatagiframe = $("textarea[id='cfg_ga_tagiframe']").val().replace(/[\/]/g, "|");
            var fbpixel = $("textarea[id='cfg_fb_pixel']").val() ? $("textarea[id='cfg_fb_pixel']").val().replace(/[\/]/g, "|") : false;

            var smtp_host = $("input[id='cfg_d001_smtp_host']").val().replace(/[\/]/g, "|");
            var smtp_email = $("input[id='cfg_d001_smtp_email']").val().replace(/[\/]/g, "|");
            var smtp_password = $("input[id='cfg_d001_smtp_password']").val().replace(/[\/]/g, "|");
            var smtp_port = $("input[id='cfg_d001_smtp_port']").val().replace(/[\/]/g, "|");


            $.ajax({
                url: "/backend/Ajax.php",
                type: "POST",
                data: {'url': `configuracao/alteracao/id/1/status/4/keyvisita/${keyvisita}/gaid/${gaid}/gatagscript/${gatagscript}/gatagiframe/${gatagiframe}/fbpixel/${fbpixel}/smtp_host/${smtp_host}/smtp_email/${smtp_email}/smtp_password/${smtp_password}/smtp_port/${smtp_port}`},
                success: function (alterar) {
                    console.log(alterar)  
                    new PNotify({
                        title: translate('Sucesso!'),
                        text: alterar,
                        type: 'success'
                    }); 
                    setTimeout('location.reload()', 0);
                }
            });
        });

    }


    if(router == "configuracao/calendario"){   

        $("#bt_cfg").click(function () {

            var cfg_ga_calendar_id = $("input[id='cfg_ga_calendar_id']").val().replace(/[\/]/g, "|");
            var cfg_ga_api_key = $("input[id='cfg_ga_api_key']").val().replace(/[\/]/g, "|");
            var cfg_ga_client_id = $("input[id='cfg_ga_client_id']").val().replace(/[\/]/g, "|");

            $.ajax({
                url: "/backend/Ajax.php",
                type: "POST",
                data: {'url': `configuracao/alteracao/id/1/status/calendario/cfg_ga_calendar_id/${cfg_ga_calendar_id}/cfg_ga_api_key/${cfg_ga_api_key}/cfg_ga_client_id/${cfg_ga_client_id}` },
                success: function (alterar) {
                    console.log(alterar)  
                    new PNotify({
                        title: translate('Sucesso!'),
                        text: alterar,
                        type: 'success'
                    }); 
                    setTimeout('location.reload()', 0);
                }
            });
        });

    }
     
});