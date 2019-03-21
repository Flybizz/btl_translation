jQuery(document).ready(function(){

  /*********************************      CONTATO       ************************************/

    $("input[name='email']").keyup(function(){

        $("input[name='email']").valida({
            tipo:"email",
            erro : function(txtErro){

              //$("#msnEmail").html(txtErro);
                $("input[name='email']").css("background-color","#f69087");
                $("#valida").val(1);
            },
            sucesso: function(){
                //$("#msnEmail").html("Email valido");
                $("input[name='email']").css("background-color","#42dca3");
                $("#valida").val(0);
            }
        });
    }); 
    

    jQuery("button[id=btn_contato]").click(function(){

        jQuery("#retorno").html("");

        //alert("validacpf:"+validacpf+"\n"+"validasenha:"+validasenha+"\n"+"validalogin:"+validalogin);                    
      
        
        var nome = jQuery("input[name='name']").val().replace( /[\/]/g, "" );

        var email = jQuery("input[name='email']").val().replace( /[\/]/g, "" );

        var valida = jQuery("input[name='valida']").val();

        var cidade = jQuery("input[name='cidade']").val().replace( /[\/]/g, "" );

        var estado = jQuery("input[name='uf']").val().replace( /[\/]/g, "" );

        var celular = jQuery("input[name='phone']").val().replace( /[\/]/g, "" );

        var descricao = jQuery("textarea[id='mensagem']").val();

        //console.log("nome:"+nome+"\n"+"email:"+email+"\n"+"cidade:"+cidade+"\n"+"estado:"+estado+"\n"+"celular:"+celular+"\n"+"descricao:"+descricao);  

        
        var ref = "";

        var count = 0;

        if(nome == ""){ref += "- Nome<br>"; count += 1;}

        if(email == ""){ref += "- E-mail<br>"; count += 1;}

        if(valida == 1){ref += "- E-mail Incorreto .....@.....com<br>"; count += 1;}

        if(celular == ""){ref += "- Telefone <br>"; count += 1;}

        if(descricao == ""){ref += "- Mensagem <br>"; count += 1;}

        if(cidade == ""){ref += "- Cidade <br>"; count += 1;}

        if(estado == ""){ref += "- Estado <br>"; count += 1;}

        
        

        if(ref != 0){

          jQuery("#ref").html('<p><div class="alert alert-danger" style=""><button type="button" class="close" data-dismiss="alert">×</button><h6>Todos os dados são obrigatórios:</h6>'+ref+'</div></p>');  

        }else{

          jQuery("#ref").html("");

        }
        

        if(ref == 0){

            jQuery.ajax({

                url: "/backend/Ajax.php",

                type: "POST",

                data: {'url':'page/contatoinsert/tipo/contato/nome/'+nome+'/email/'+email+'/celular/'+celular+'/descricao/'+descricao+'/cidade/'+cidade+'/estado/'+estado+'/'},

                success: function(ok){

                    //console.log(ok);
                                   

                    jQuery.ajax({

                        url: "/backend/Ajax.php",

                        type: "POST",

                        data: {'url':'email/enviar/status/contatocliente/nome/'+nome+'/email/'+email+'/celular/'+celular+'/descricao/'+descricao+'/cidade/'+cidade+'/estado/'+estado+'/'},

                        success: function(ok1){

                            //console.log(ok1);

                        }

                    }); 

                    jQuery.ajax({

                        url: "/backend/Ajax.php",

                        type: "POST",

                        data: {'url':'email/enviar/status/contatoadm/nome/'+nome+'/email/'+email+'/celular/'+celular+'/descricao/'+descricao+'/cidade/'+cidade+'/estado/'+estado+'/'},

                        success: function(ok2){

                            //console.log(ok2);


                            jQuery("input[type=text]").val("");

                            jQuery("textarea").val("");

                            jQuery("#retorno").html('<div class="alert alert-success" style=""><button type="button" class="close" data-dismiss="alert">×</button> <span> Olá! Recebemos a sua mensagem em breve entraremos em contato.</span></div>');

                            //$(window.document.location).attr('href',"/pedido/listar/");  

                            jQuery("#msnEmail").html("");


                        }

                    });  

                                        

                }

            });

        

        }else{

            valida = "";

        }

    });

});