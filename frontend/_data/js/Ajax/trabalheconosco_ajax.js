jQuery(document).ready(function(){

  /*********************************      RAMO       ************************************/

  /***********************   CARGO  *************************/

  function cargo(){

    jQuery("select[id='rc_ramo']").change(function(){

      var id = jQuery(this).select('selected').val();

      jQuery.ajax({
        url: "/Ajax.php",
        type: "POST",
        data: {'url':'page/cargo/id/'+id},
        success: function(dados){

            jQuery("#rc_profissao").html(dados);

            cargo()
         }
       });
       
        // jQuery('#painel').modal();  
    });

  }

  cargo()

    jQuery("#rc_cpf").blur(function(){     

      var xvalor = jQuery(this).val().replace( /[\/.-]/g, "" );
                    
      jQuery.ajax({
            url: "/Ajax.php",
            type: "POST",
            data: {'url':'page/verificaTabalheConosco/cpf/'+xvalor+'/'},
            success: function(f_dados){ 

             jQuery("#rc_cpf").valida({
                  tipo:"cpf",
                  erro : function(txtErro){
                      jQuery("#msn2").html(txtErro);
                      jQuery("#msn2").css("color","#f69087");
                      jQuery("#validacpf").val(1);
                      jQuery("#cpf_sinal").html("<i class='fa fa-remove'></i>").css("color","#f69087");  
                  },
                  sucesso: function(){
                    var resp = "CPF correto";
                      jQuery("#msn2").html(resp);
                      jQuery("#msn2").css("color","#42dca3");
                     jQuery("#validacpf").val(0);
                  }
              });        
      
              jQuery("#rc_cpf").valida({
                    tipo:"vazio",
                    erro : function(txtErro){
                      jQuery("#msn2").html(txtErro);
                      jQuery("#validacpf").val(1);
                    },
                    sucesso: function(){}
              });
              
              if(f_dados == 1){
              
                  jQuery("#msn3").html(" ( JÁ EXISTE PROFISSIONAL CADASTRADO COM ESSE CPF !");
                  jQuery("#msn3").css("color","#f69087");
                  jQuery("#validacpf").val(2);
      
              }else if(f_dados == 0 || f_dados == 2){
        
                  jQuery("#msn3").html("");

              }                     
            }
        });//ajax
    });//blur 

    jQuery("#rc_email").blur(function(){

        jQuery("#rc_email").valida({
            tipo:"email",
            erro : function(txtErro){
              jQuery("#msnEmail").html(txtErro);
                jQuery("#msnEmail").css("color","#f69087");
            },
            sucesso: function(){
                jQuery("#msnEmail").html("Email valido");
                jQuery("#msnEmail").css("color","#42dca3");
            }
        });
    }); 
     
    jQuery("button[id=bt_trab_cadastrar]").click(function(){

        jQuery("#retorno").html("");

        var cpf1 = jQuery("input[id='rc_cpf']").val()        
           
        if(cpf1 == ""){

          jQuery("#validacpf").val(1);

        }

        var validacpf = jQuery("#validacpf").val();

        //alert("validacpf:"+validacpf+"\n"+"validasenha:"+validasenha+"\n"+"validalogin:"+validalogin);                    
       
        
        var nome = jQuery("input[id='rc_nome']").val().replace( /[\/]/g, "" );
        var cpf = jQuery("input[id='rc_cpf']").val().replace( /[\/.-]/g, "" );
        var rg = jQuery("input[id='rc_rg']").val().replace( /[\/.-]/g, "" );
        var email = jQuery("input[id='rc_email']").val().replace( /[\/]/g, "" );
        var endereco = jQuery("input[id='rc_endereco']").val().replace( /[\/]/g, "" );
        var bairro = jQuery("input[id='rc_bairro']").val().replace( /[\/]/g, "" );
        var cidade = jQuery("input[id='rc_cidade']").val().replace( /[\/]/g, "" );
        var uf = jQuery("input[id='rc_uf']").val();
        var cep = jQuery("input[id='rc_cep']").val().replace( /[\/]/g, "" );
        var telefone = jQuery("input[id='rc_telefone']").val().replace( /[\/]/g, "" );
        var celular = jQuery("input[id='rc_celular']").val().replace( /[\/]/g, "" );
        var ramo = jQuery("select[id='rc_ramo']").select("option:selected").val();
        var profissao = jQuery("select[id='rc_profissao']").select("option:selected").val();
        var pdescricao = jQuery("textarea[id='rc_pdescricao']").val();
        
        var ref = "";
        var count = 0;
        if(nome == ""){ref += "Nome<br>"; count += 1;}
        if(cpf == "" && validacpf != 0 || cpf != "" && validacpf != 0){ref += "cpf<br>"; count += 1;}
        if(email == ""){ref += "E-mail<br>"; count += 1;}
        if(endereco == ""){ref += "Endereco <br>"; count += 1;}
        if(bairro == ""){ref += "Bairro <br>"; count += 1;}
        if(cidade == ""){ref += "Cidade <br>"; count += 1;}
        if(uf == ""){ref += "UF<br>"; count += 1;}
        if(cep == ""){ref += "CEP <br>"; count += 1;}
        if(ramo == 0){ref += "Ramo <br>"; count += 1;}
        if(profissao == 0){ref += "Profissao <br>"; count += 1;}

        console.log(ref);

        if(ref != 0){

          jQuery("#ref").html('<p><div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4>Falta(m) dados Obrigatórios:</h4>'+ref+'</div></p>');  

        }else{
          jQuery("#ref").html("");
        }
        
        if(validacpf == 0 && ref == 0){

            jQuery.ajax({
                url: "/Ajax.php",
                type: "POST",
                data: {'url':'page/cadastrarTabalheConosco/nome/'+nome+'/cpf/'+cpf+'/rg/'+rg+'/email/'+email+'/endereco/'+endereco+'/bairro/'+bairro+'/cidade/'+cidade+'/uf/'+uf+'/cep/'+cep+'/telefone/'+telefone+'/celular/'+celular+'/ramo/'+ramo+'/profissao/'+profissao+'/pdescricao/'+pdescricao+'/'},
                success: function(ok){
                                   
                    /*jQuery.ajax({
                        url: "/../Ajax.php",
                        type: "POST",
                        beforeSend: function(){jQuery('#carregando').show();},
                        complete: function(){jQuery('#carregando').hide();},
                        data: {'url':'email/enviar/status/doador/login/'+login+'/senha/'+senha1+'/'},
                        success: function(ok1){

                          alert(ok1)
                        }
                    });*/ 

                   /* jQuery.ajax({
                        url: "/../Ajax.php",
                        type: "POST",
                        beforeSend: function(){jQuery('#carregando').show();},
                        complete: function(){jQuery('#carregando').hide();},
                        data: {'url':'email/enviar/status/recebedor/login/'+login+'/'},
                        success: function(ok){
                            
                        }
                    });  */
                    
                    jQuery("input").val("");
                    jQuery("textarea").val("");

                    jQuery("#retorno").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>"+ok+"</div>");     
                    //jQuery(window.document.location).attr('href',"/pedido/listar/");  
  
                    jQuery("#msn2").html("");
                    jQuery("#msn3").html("");
                    jQuery("#msnEmail").html("");

                }
            });
        

        }else if(validacpf == 1){
            valida = "CPF INVÁLIDO.";
        }else if(validacpf == 2){
            valida = "JÁ EXISTE PROFISSIONAL CADASTRADO COM ESSE CPF";
        }else{
            valida = "";
        }

        jQuery("#retorno").html('<p><div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+valida+'</div></p>');
            
    });


});