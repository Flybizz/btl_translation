$(document).ready(function(){

      //$('.carregando').hide();
              
            // Ao sair do input "Blur"
            $("#cli_cpf").blur(function(){     

              var xvalor = $("#cli_cpf").val().replace( /[\/.-]/g, "" );

             $.ajax({
                  url: "/../Ajax.php",
                  type: "POST",
                  data: {'url':'cliente/verifica/cpf/'+xvalor+'/'},
                  success: function(f_dados){                   

                    $("#cli_cpf").valida({
                        tipo:"cpf",
                        erro : function(txtErro){
                          $("#msn2").html(txtErro);
                          $("#msn2").css("color","#FF0000");
                          $("#validacnpj").val(1);
                        },
                        sucesso: function(){
                          $("#msn2").html("CNPJ válido");
                          $("#msn2").css("color","#009F00");
                          $("#validacpf").val(0);
                        }
                    });        
              
                    $("#cli_cpf").valida({
                          tipo:"vazio",
                          erro : function(txtErro){
                            $("#msn2").html(txtErro);
                            $("#validacpf").val(1);
                          },
                          sucesso: function(){}
                    });

                    if(f_dados == 1){
                      
                      $("#msn3").html(" ( JÁ EXISTE CLIENTE CADASTRADO COM ESSE CPf! )");
                      $("#validacpf").val(2);
              
                    }else if(f_dados == 0 || f_dados == 2){
                
                      $("#msn3").html("");

                    }                     
                  }
                });
               }); 
                
                $("input[required]").blur(function(){

                  var input = $(this).val();
                  
                  if(input == ""){
                    $(this).next("span").html("<i class='icon-remove'></i>");

                  }
                  else if(input != ""){
                    $(this).next("span").html("<i class='icon-ok'></i>")
                  }  
                });

                

              /* CADASTRO ACESSO */ 
              $('#usuario_senha').pstrength();

                $('#usuario_confsenha').blur(function(){
                  
                    var senha = $('#usuario_senha').val();
                    var confsenha = $('#usuario_confsenha').val();
                          
                 if(confsenha != senha){
                            
                    $('#msgSenha').html("Digite novamente, senha incorreta.") 
                    $('#msgSenha').css('color','#ff0000')
                    $("#validasenha").val(1);
                            
                  }else if(confsenha == ""){
                          
                    $('#msgSenha').html("<i class='icon-remove'></i>")
                              
                  }else{
                            
                    $('#msgSenha').html("Senha válida.")  
                    $('#msgSenha').css('color','#007F00')
                    $("#validasenha").val(0);
                             
                  }
                        
                }); 
               

               $("#cli_email").blur(function(){
    
                  $("#cli_email").valida({
                      tipo:"email",
                      erro : function(txtErro){
                        $("#msnEmail").html(txtErro);
                        $("#msnEmail").css("color","#FF0000");
                      },
                      sucesso: function(){
                        $("#msnEmail").html("Email válido");
                        $("#msnEmail").css("color","#009F00");
                  }
                });
              }); 
             
              $("#bt_cli_cadastrar").click(function(){

                      var senha1 = $('#usuario_senha').val();
                      var confsenha1 = $('#usuario_confsenha').val();
                      var cpf1 = $("input[name='cli_cpf']").val()
                      var validacpf = $("#validacpf").val();
                      var validasenha = $("#validasenha").val();  
                       
                      if(confsenha1 == "" && cnpj1 == ""){

                        $("#validacpf").val(1);

                      }
                      if(confsenha1 == "" && validacpf == 2){

                        $("#validacpf").val(2);

                      }
                      if(confsenha1 == "" && validacpf == 1){

                        $("#validacpf").val(1);

                      }                      
                      if(confsenha1 != "" && cpf1 == ""){

                        $("#validacpf").val(1);

                      }
                      if(confsenha1 == "" && validacpf == 0){

                        $("#validasenha").val(1);

                      }
                      if(confsenha1 != "" && validasenha == 1){

                        $("#validasenha").val(1);

                      }
                      if(confsenha1 != "" && validasenha == 0 && validacpf == 0){

                        $("#validasenha").val(0);
                        $("#validacpf").val(0);

                      }

                      //alert("validacnpj:"+validacnpj+"\n"+"validasenha:"+validasenha);                    
               
                    var razao = $("input[name='cli_razao']").val().replace( /[\/]/g, "" );
                    var fantasia = $("input[name='cli_fantasia']").val().replace( /[\/]/g, "" );
                    var cnpj = $("input[name='cli_cnpj']").val().replace( /[\/.-]/g, "" );
                    var insc = $("input[name='cli_insc']").val().replace( /[\/]/g, "" );
                    var contato = $("input[name='cli_contato']").val().replace( /[\/]/g, "" );
                    var email = $("input[name='cli_email']").val().replace( /[\/]/g, "" );
                    var endereco = $("input[name='cli_endereco']").val().replace( /[\/]/g, "" );
                    var bairro = $("input[name='cli_bairro']").val().replace( /[\/]/g, "" );
                    var cidade = $("input[name='cli_cidade']").val().replace( /[\/]/g, "" );
                    var uf = $("input[name='cli_uf']").val();
                    var cep = $("input[name='cli_cep']").val().replace( /[\/]/g, "" );
                    var telefone = $("input[name='cli_telefone']").val().replace( /[\/]/g, "" );
                    var telefone2 = $("input[name='cli_telefone2']").val().replace( /[\/]/g, "" );
                    var celular = $("input[name='cli_celular']").val().replace( /[\/]/g, "" );
                    var login = $("input[name='usuario_login']").val();
                    var senha = MD5($("input[name='usuario_confsenha']").val());
                    var senha1 = $("input[name='usuario_confsenha']").val();


                    /*alert(razao+"\n"+fantasia+"\n"+cnpj+"\n"+insc+"\n"+contato+"\n"+email+"\n"+endereco+"\n"+bairro+"\n"+cidade+"\n"
                      +uf+"\n"+cep+"\n"+telefone+"\n"+telefone2+"\n"+celular+"\n"+fax);*/
                    var ref = "";
                    var count = 0;
                    if(razao == ""){ref += "Razão Social<br>"; count += 1;}
                    if(fantasia == ""){ref += "fantasia<br>"; count += 1;}
                    if(cnpj == ""){ref += "cnpj<br>"; count += 1;}
                    if(contato == ""){ref += "contato<br>"; count += 1;}
                    if(email == ""){ref += "email<br>"; count += 1;}
                    if(endereco == ""){ref += "endereco <br>"; count += 1;}
                    if(bairro == ""){ref += "bairro <br>"; count += 1;}
                    if(cidade == ""){ref += "cidade <br>"; count += 1;}
                    if(uf == ""){ref += "uf<br>"; count += 1;}
                    if(cep == ""){ref += "cep <br>"; count += 1;}
                    if(telefone == ""){ref += "telefone <br>"; count += 1;}
                    if(login == ""){ref += "login <br>"; count += 1;} 
                    if(senha == ""){ref += "senha"; count += 1;} 

                    if(ref != 0){

                      $("#ref").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button><h4>Falta dados Obrigatórios:</h4>"+ref+"</div>");  

                    }else{
                      $("#ref").html("");
                    }

                    if(validacnpj == 0 && validasenha == 0 && ref == 0){  
                   
                      $.ajax({
                          url: "/../Ajax.php",
                          type: "POST",
                          data: {'url':'cliente/inserir/razao/'+razao+'/fantasia/'+fantasia+'/cnpj/'+cnpj+'/insc/'+insc+'/contato/'+contato+'/email/'+email+'/endereco/'+endereco+'/bairro/'+bairro+'/cidade/'+cidade+'/uf/'+uf+'/cep/'+cep+'/telefone/'+telefone+'/telefone2/'+telefone2+'/celular/'+celular+'/login/'+login+'/senha/'+senha+'/'},
                          success: function(ok){


                                 $.ajax({
                                      url: "/../Ajax.php",
                                      type: "POST",
                                      data: {'url':'email/enviar/status/cliente/razao/'+razao+'/fantasia/'+fantasia+'/cnpj/'+cnpj+'/insc/'+insc+'/contato/'+contato+'/email/'+email+'/endereco/'+endereco+'/bairro/'+bairro+'/cidade/'+cidade+'/uf/'+uf+'/cep/'+cep+'/telefone/'+telefone+'/telefone2/'+telefone2+'/celular/'+celular+'/login/'+login+'/senha/'+senha1+'/'},
                                      success: function(ok){
                                      }
                                  }); 

                                   $.ajax({
                                      url: "/../Ajax.php",
                                      type: "POST",
                                      data: {'url':'email/enviar/status/admcliente/razao/'+razao+'/fantasia/'+fantasia+'/cnpj/'+cnpj+'/insc/'+insc+'/contato/'+contato+'/email/'+email+'/endereco/'+endereco+'/bairro/'+bairro+'/cidade/'+cidade+'/uf/'+uf+'/cep/'+cep+'/telefone/'+telefone+'/telefone2/'+telefone2+'/celular/'+celular+'/login/'+login+'/senha/'+senha1+'/'},
                                      success: function(ok){

                                          $("input").val("");

                                          $("#retorno").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>"+ok+"</div>");     
                                          //$(window.document.location).attr('href',"/pedido/listar/");  
										  
										  $(".obg").html("");
										  $("#msn2").html("");
										  $("#msn3").html("");
										  
										  
										  										  
                                      }
                                  }); 



                                                              
                          }
                      });
                    

                    }else if(validacnpj == 1){
                        valida = "CNPJ INVALIDO.";
                    }else if(validacnpj == 2){
                        valida = "JÁ EXISTE CLIENTE CADASTRADO COM ESSE CNPJ.";
                    }else if(validasenha == 1){
                        valida = "SENHA INCORRETA.";
                    }

                    $("#retorno").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button><h5>"+valida+"</h5></div>");
                    
              });


                $("#btn_altacesso").click(function(){

                  $("#box_alt_acesso").show("slow");

                  $("#btn_altacesso_ok").click(function(){

                        $('.carregando').show()

                        var id_usu = $(this).prev("input").val();

                        var senha1 = $('#usuario_senha').val();
                        var confsenha1 = $('#usuario_confsenha').val();
                        var validasenha = $("#validasenha").val();                           
                       
                        if(confsenha1 != "" && validasenha == 1){

                          $("#validasenha").val(1);

                        }
                        if(confsenha1 != "" && validasenha == 0){

                          $("#validasenha").val(0);

                        }

                                                   
                      var login2 = $("input[name='usuario_login']").val();
                      var senha2 = MD5($("input[name='usuario_confsenha']").val());
                      var senha1 = $("input[name='usuario_confsenha']").val();

                      var ref = "";
                      var count = 0;
                    
                      if(login2 == ""){ref += "login <br>"; count += 1;} 
                      if(senha2 == ""){ref += "senha"; count += 1;} 


                      if(ref != 0){

                        $("#retorno").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button><h4>Falta dados Obrigatórios:</h4>"+ref+"</div>");  

                      }else{
                        $("#retorno").html("");
                      }

                      if(validasenha == 0 && ref == 0){  
                     
                        $.ajax({
                            url: "/../Ajax.php",
                            type: "POST",
                            data: {'url':'usuario/alteracao/id/'+id_usu+'/login/'+login2+'/senha/'+senha2+'/'},
                            //beforeSend: function(){$('.carregando').show()},
                            //complete: function(){$('.carregando').hide()},
                            success: function(ok){

                                  $.ajax({
                                        url: "/../Ajax.php",
                                        type: "POST",
                                        data: {'url':'email/enviar/status/acessocliente/id/'+id_usu+'/login/'+login2+'/senha/'+senha1+'/'},
                                        //beforeSend: function(){$('.carregando').show()},
                                        //complete: function(){$('.carregando').hide()},
                                        success: function(ok2){
                                        }
                                  }); 

                                  $.ajax({
                                        url: "/../Ajax.php",
                                        type: "POST",
                                        data: {'url':'email/enviar/status/admacessocliente/id/'+id_usu+'/login/'+login2+'/senha/'+senha1+'/'},
                                        //beforeSend: function(){$('.carregando').show()},
                                        //complete: function(){$('.carregando').hide()},
                                        success: function(ok){
                                            $("#retorno_cli").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>"+ok+"</div>");     
                                            $("#box_alt_acesso").hide("slow"); 
											
											$("input").each(function(){
												$(this).val("");
											});
											
                                        }
                                  }); 

                                                                
                            }
                        });                     
                     
                      }else if(validasenha == 1){
                          valida = "SENHA INCORRETA.";
                      }

                      $("#retorno").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button><h5>"+valida+"</h5></div>");
                    

                    });   

                  });  


/*ALTERAÇÃO*/

// Ao sair do input "Blur"
            /*$("#cli_cnpj").blur(function(){     

              var xvalor = $("#cli_cnpj").val().replace( /[\/.-]/g, "" );

             $.ajax({
                  url: "/../Ajax.php",
                  type: "POST",
                  data: {'url':'cliente/verifica/cnpj/'+xvalor+'/'},
                  success: function(f_dados){                   

                    $("#cli_cnpj").valida({
                        tipo:"cnpj",
                        erro : function(txtErro){
                          $("#msn2").html(txtErro);
                          $("#msn2").css("color","#FF0000");
                          $("#validacnpj").val(1);
                        },
                        sucesso: function(){
                          $("#msn2").html("CNPJ válido");
                          $("#msn2").css("color","#009F00");
                          $("#validacnpj").val(0);
                        }
                    });        
              
                    $("#cli_cnpj").valida({
                          tipo:"vazio",
                          erro : function(txtErro){
                            $("#msn2").html(txtErro);
                            $("#validacnpj").val(1);
                          },
                          sucesso: function(){}
                    });

                    if(f_dados == 1){
                      
                      $("#msn3").html(" ( JÁ EXISTE CLIENTE CADASTRADO COM ESSE CNPJ! )");
                      $("#validacnpj").val(2);
              
                    }else if(f_dados == 0 || f_dados == 2){
                
                      $("#msn3").html("");

                    }                     
                  }
                });
               }); */
            	
                // $("input[required]").blur(function(){

                //   var input = $(this).val();
                  
                //   if(input == ""){
                //     $(this).next("span").html("<i class='icon-remove'></i>");

                //   }
                //   else if(input != ""){
                //     $(this).next("span").html("<i class='icon-ok'></i>")
                //   }  
                // });

                

              /* CADASTRO ACESSO */ 
             /* $('#usuario_senha').pstrength();

                $('#usuario_confsenha').blur(function(){
                  
                    var senha = $('#usuario_senha').val();
                    var confsenha = $('#usuario_confsenha').val();
                          
                 if(confsenha != senha){
                            
                    $('#msgSenha').html("Digite novamente, senha incorreta.") 
                    $('#msgSenha').css('color','#ff0000')
                    $("#validasenha").val(1);
                            
                  }else if(confsenha == ""){
                          
                    $('#msgSenha').html("<i class='icon-remove'></i>")
                              
                  }else{
                            
                    $('#msgSenha').html("Senha válida.")  
                    $('#msgSenha').css('color','#007F00')
                    $("#validasenha").val(0);
                             
                  }
                        
                }); */
               

               $("#cli_email_alt").blur(function(){
    
                  $("#cli_email_alt").valida({
                      tipo:"email",
                      erro : function(txtErro){
                        $("#msnEmail_alt").html(txtErro);
                        $("#msnEmail_alt").css("color","#FF0000");
                      },
                      sucesso: function(){
                        $("#msnEmail_alt").html("Email válido");
                        $("#msnEmail_alt").css("color","#009F00");
                  }
                });
              }); 
             
          $("a[id=btn_alterarCliente]").click(function(){			  			                        
               					
					          var id = $(this).next("input").val();
                    var razao = $("input[name='cli_razao_alt']").val().replace( /[\/]/g, "" );
                    var fantasia = $("input[name='cli_fantasia_alt']").val().replace( /[\/]/g, "" );
                    var cnpj = $("input[name='cli_cnpj_alt']").val().replace( /[\/.-]/g, "" );
                    var insc = $("input[name='cli_insc_alt']").val().replace( /[\/]/g, "" );
                    var contato = $("input[name='cli_contato_alt']").val().replace( /[\/]/g, "" );
                    var email = $("input[name='cli_email_alt']").val().replace( /[\/]/g, "" );
                    var endereco = $("input[name='cli_endereco_alt']").val().replace( /[\/]/g, "" );
                    var bairro = $("input[name='cli_bairro_alt']").val().replace( /[\/]/g, "" );
                    var cidade = $("input[name='cli_cidade_alt']").val().replace( /[\/]/g, "" );
                    var uf = $("input[name='cli_uf_alt']").val();
                    var cep = $("input[name='cli_cep_alt']").val().replace( /[\/]/g, "" );
                    var telefone = $("input[name='cli_telefone_alt']").val().replace( /[\/]/g, "" );
                    var telefone2 = $("input[name='cli_telefone2_alt']").val().replace( /[\/]/g, "" );
                    var celular = $("input[name='cli_celular_alt']").val().replace( /[\/]/g, "" );
                                 					  
                    var ref = "";
                    var count = 0;
                    if(razao == ""){ref += "Razão Social<br>"; count += 1;}
                    if(fantasia == ""){ref += "fantasia<br>"; count += 1;}
                    if(cnpj == ""){ref += "cnpj<br>"; count += 1;}
                    if(contato == ""){ref += "contato<br>"; count += 1;}
                    if(email == ""){ref += "email<br>"; count += 1;}
                    if(endereco == ""){ref += "endereco <br>"; count += 1;}
                    if(bairro == ""){ref += "bairro <br>"; count += 1;}
                    if(cidade == ""){ref += "cidade <br>"; count += 1;}
                    if(uf == ""){ref += "uf<br>"; count += 1;}
                    if(cep == ""){ref += "cep <br>"; count += 1;}
                    if(telefone == ""){ref += "telefone <br>"; count += 1;}
                   
                    if(ref != 0){

                      $("#ref").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button><h4>Falta dados Obrigatórios:</h4>"+ref+"</div>");  

                    }else{
                      $("#ref").html("");
					  
					                
                      $.ajax({
                          url: "/backend/Ajax.php",
                          type: "POST",
                          data: {'url':'cliente/alteracao2/id/'+id+'/razao/'+razao+'/fantasia/'+fantasia+'/cnpj/'+cnpj+'/insc/'+insc+'/contato/'+contato+'/email/'+email+'/endereco/'+endereco+'/bairro/'+bairro+'/cidade/'+cidade+'/uf/'+uf+'/cep/'+cep+'/telefone/'+telefone+'/telefone2/'+telefone2+'/celular/'+celular+'/'},
                          success: function(ok){


              								//$("#ref").html(ok);
              								
              								//$(window.document.location).attr('href',"/cliente/listar/"); 
						  
                                //  $.ajax({
                                //       url: "/../Ajax.php",
                                //       type: "POST",
                                //       data: {'url':'email/enviar/status/clientealt/razao/'+razao+'/fantasia/'+fantasia+'/cnpj/'+cnpj+'/insc/'+insc+'/contato/'+contato+'/email/'+email+'/endereco/'+endereco+'/bairro/'+bairro+'/cidade/'+cidade+'/uf/'+uf+'/cep/'+cep+'/telefone/'+telefone+'/telefone2/'+telefone2+'/celular/'+celular+'/'},
                                //       success: function(ok){
                                //       }
                                //   }); 

                                //    $.ajax({
                                //       url: "/../Ajax.php",
                                //       type: "POST",
                                //       data: {'url':'email/enviar/status/admclientealt/razao/'+razao+'/fantasia/'+fantasia+'/cnpj/'+cnpj+'/insc/'+insc+'/contato/'+contato+'/email/'+email+'/endereco/'+endereco+'/bairro/'+bairro+'/cidade/'+cidade+'/uf/'+uf+'/cep/'+cep+'/telefone/'+telefone+'/telefone2/'+telefone2+'/celular/'+celular+'/'},
                                //       success: function(ok){

                                //           //$("input").val("");

                                //           //$("#retorno").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>"+ok+"</div>");     
                                //           $(window.document.location).attr('href',"/cliente/listar/");  
										  
                    										  // //$(".obg").html("");
                    										  // //$("#msn2").html("");
                    										  // //$("#msn3").html("");
										  
										  
										  										  
                                //       }
                                //   }); 


                                $("#retorno").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>"+ok+"</div>");
                                                              
                          }
                      });
					  
					         }
                 
                    //$("#retorno").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button><h5>"+valida+"</h5></div>");
                    
          });				  
                       
  
});