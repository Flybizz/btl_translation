jQuery(document).ready(function(){



  /*********************************      OorcAMENTO       ************************************/

    jQuery("#orc_email").blur(function(){

        jQuery("#orc_email").valida({

            tipo:"email",

            erro : function(txtErro){

                //jQuery("#orc_email").html(txtErro);

                jQuery("#orc_email").css("background-color","#f69087");
                $("#valida").val(1);

            },

            sucesso: function(){

                //jQuery("#orc_email").html("Email valido");

                jQuery("#orc_email").css("background-color","#42dca3");
                $("#valida").val(0);

            }

        });

    }); 

     
   jQuery("button[id=bt_orc_cadastrar]").click(function(){

       jQuery("#retorno").html("");

        //alert("validacpf:"+validacpf+"\n"+"validasenha:"+validasenha+"\n"+"validalogin:"+validalogin);                    

        var nome = jQuery("input[id='orc_nome']").val().replace( /[\/]/g, "" );

        var email = jQuery("input[id='orc_email']").val().replace( /[\/]/g, "" );

        var valida = jQuery("input[id='valida']").val();

        // var endereco = jQuery("input[id='orc_endereco']").val().replace( /[\/]/g, "" );

        // var bairro = jQuery("input[id='orc_bairro']").val().replace( /[\/]/g, "" );

        // var cidade = jQuery("input[id='orc_cidade']").val().replace( /[\/]/g, "" );

        // var uf = jQuery("input[id='orc_uf']").val();

        // var cep = jQuery("input[id='orc_cep']").val().replace( /[\/]/g, "" );

        var celular = jQuery("input[id='orc_celular']").val().replace( /[\/]/g, "" );

        var servico = jQuery("select[id='orc_pacote']").select("option:selected").val();

        var descricao = jQuery("textarea[id='orc_descricao']").val();

        var dtchegada = jQuery("input[id='orc_dtchegada']").val().replace( /[\/]/g, "" );

        var dtpartida = jQuery("input[id='orc_dtpartida']").val().replace( /[\/]/g, "" );

        var noite = jQuery("select[id='orc_noite']").select("option:selected").val();

        var quarto = jQuery("select[id='orc_quarto']").select("option:selected").val();

        var adulto = jQuery("select[id='orc_adulto']").select("option:selected").val();

        var crianca = jQuery("select[id='orc_crianca']").select("option:selected").val();

        
        var ref = "";

        var count = 0;

        if(nome == ""){ref += "- Nome<br>"; count += 1;}

        if(email == ""){ref += "- Email<br>"; count += 1;}

        if(valida == 1){ref += "- E-mail Incorreto .....@.....com<br>"; count += 1;}

        if(dtchegada == ""){ref += "- Data de Chegada<br>"; count += 1;}

        if(dtpartida == ""){ref += "- Data de Partida<br>"; count += 1;}

        if(quarto == ""){ref += "- Quantos Quartos<br>"; count += 1;}

        if(adulto == ""){ref += "- Quantos Adulto<br>"; count += 1;}

        if(crianca == ""){ref += "- Quantas Crianças<br>"; count += 1;}

        // if(endereco == ""){ref += "Endereco <br>"; count += 1;}

        // if(bairro == ""){ref += "Bairro <br>"; count += 1;}

        // if(cidade == ""){ref += "Cidade <br>"; count += 1;}

        // if(uf == ""){ref += "UF<br>"; count += 1;}

        // if(cep == ""){ref += "CEP <br>"; count += 1;}

        if(celular == 0){ref += "- Telefone <br>"; count += 1;}

        // if(servico == 0){ref += "Serviço <br>"; count += 1;}

        if(descricao == 0){ref += "- Descrição <br>"; count += 1;}

        //console.log(ref);

        if(ref != 0){

          jQuery("#ref").html('<p><div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4>Falta(m) dados Obrigatórios:</h4>'+ref+'</div></p>');  

        }else{

          jQuery("#ref").html("");

        }

        if(ref == 0){

            jQuery.ajax({

                url: "/backend/Ajax.php",

                type: "POST",

                data: {'url':'page/orcamentoinsert/nome/'+nome+'/email/'+email+'/celular/'+celular+'/servico/'+servico+'/descricao/'+descricao+'/dtchegada/'+dtchegada+'/dtpartida/'+dtpartida+'/noite/'+noite+'/quarto/'+quarto+'/adulto/'+adulto+'/crianca/'+crianca+'/'},

                success: function(ok){

                    jQuery.ajax({

                        url: "/backend/Ajax.php",

                        type: "POST",

                        data: {'url':'email/enviar/status/orcamentocliente/nome/'+nome+'/email/'+email+'/celular/'+celular+'/servico/'+servico+'/descricao/'+descricao+'/dtchegada/'+dtchegada+'/dtpartida/'+dtpartida+'/noite/'+noite+'/quarto/'+quarto+'/adulto/'+adulto+'/crianca/'+crianca+'/'},

                        success: function(ok1){

                           //alert("CLIENTE = "+ok1)

                           console.log(ok1)

                        }

                    }); 


                    jQuery.ajax({

                        url: "/backend/Ajax.php",

                        type: "POST",

                        data: {'url':'email/enviar/status/orcamentoadm/nome/'+nome+'/email/'+email+'/celular/'+celular+'/servico/'+servico+'/descricao/'+descricao+'/dtchegada/'+dtchegada+'/dtpartida/'+dtpartida+'/noite/'+noite+'/quarto/'+quarto+'/adulto/'+adulto+'/crianca/'+crianca+'/'},

                        success: function(ok2){

                            console.log(ok2)
                            
                            jQuery("input").val("");

                            jQuery("textarea").val("");

                            jQuery("select").val(0);

                            jQuery("#retorno").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>"+ok2+"</div>");     

                            jQuery("#msnEmail").html("");

                            jQuery("#ref").html("");
                        }

                    });  

                    

                    



                }

            });

        

        }else{

            valida = "";

        }



    });





});