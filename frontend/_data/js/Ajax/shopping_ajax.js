$(document).ready(function(){
  
  $("#btn_add").tooltip();
  //$(".tam_estoque").tooltip();
  //$(".tam_qtd").hide();

  // $('.box_tam .btn').each(function() {

  //     var qt = $(this).next('input').val();

  //     if(qt != 0){
  //       $(this).attr("class","btn tam_estoque active")
  //       $(this).css("background-color","rgba(121,157,44,1)");
  //       $(this).css("color","rgba(250,250,250,1)");
  //     }

  // });

  /*status*/ 
  // $(".tam_estoque").click(function() {

  //     var tam = $(this).prev("input").prev("input").val();
      
  //     var tamanho = $(this).prev("input").val($(this).val());
  //     var tag = '#tamqtd_'+tam;
  //     var estoque = $('#estoque_'+tam).val();

  //     var teste = $(this).attr("class");

  //     var t = teste.indexOf('active')
      
  //     if(t == -1){      

  //       $(this).css("background-color","rgba(121,157,44,1)");
  //       $(this).css("color","rgba(250,250,250,1)");

  //       $(tag).val(1);

  //       $(tag).blur(function() {

  //         var q = $(this).val();

  //         var qt = Number(q);
  //         var es = Number(estoque);
          
  //         /*if(qt > es){
  //           $(this).val(estoque);
  //         }*/

  //       });

  //       $("#btn_add").tooltip('destroy');

  //   }else{
  //     $(this).css("background-color","rgba(121,157,44,0)");
  //     $(this).css("color","rgba(24,24,24,0.7)");
  //     $(tag).val('');
  //   }


  // }); 


  // $(".tam_qtd").click(function(){

  //     var radio = $(this).prev("button");
      
  //     var valor = $(this).val();

  //     radio.attr("class","btn tam_estoque active")

  //     radio.css("background-color","rgba(121,157,44,1)");
  //     radio.css("color","rgba(250,250,250,1)");

  //     if(valor == ""){
  //       $(this).val(1);
  //     }

  //     $("#btn_add").tooltip('destroy');

  //     //alert(valor);
  // })

    
  $("#btn_add").click(function(){

        var ref = $(this).prev("input").val();

        //var data = [];

        // $('.box_tam .btn.active').each(function() {

        //   var tamanho = $(this).val();

        //   var qtd = $(this).next('input').val();

        //   var arr = "'"+ref+"-"+tamanho+"-"+qtd+"'";

        //   data.push(arr);

        // });

        //alert(ref)

        // var arr = ref;

        // data.push(arr);

        // var dt = data.join(",");
        
        $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'shopping/add/ref/'+ref},
              success: function(ok){

                  var item = ok.split("-");

                  $("#sessao_pedido > a > span").html("("+item[1]+")");  
                  //alert(ok);
				          $(window.document.location).attr('href',"/carrinho"); 
				  
           }
        });
        

  });  

                       
  // $(".btn_inserir").click(function(){

  //       var ref = $(this).prev("input").prev("input").val();
  //       //var tamanho = $(this).prev("input").val();

  //       //alert(tamanho);
          
  //         $.ajax({
  //             url: "/backend/Ajax.php",
  //             type: "POST",
  //             data: {'url':'shopping/inserir/ref/'+ref},
  //             success: function(ok){
  //               //alert(ok);
  //               $(window.document.location).attr('href',"/shopping/carrinho/"); 
  //             }
  //         });

  // });  

  // $(".btn_retirar").click(function(){

  //       var ref = $(this).prev("input").prev("input").val();
  //       //var tamanho = $(this).prev("input").val();
          
  //         $.ajax({
  //             url: "/backend/Ajax.php",
  //             type: "POST",
  //             data: {'url':'shopping/retirar/ref/'+ref},
  //             success: function(ok){
  //                 //$("#conteudo").html(ok);
  //                 $(window.document.location).attr('href',"/shopping/carrinho/");
  //             }
  //         });

  // });  

  $(".btn_excluir").click(function(){

        var ref = $(this).prev("input").val();
        // var qtd = $(this).prev("input").prev("input").prev("input").val();
        // var tamanho = $(this).prev("input").val();

        //alert(ref);
          
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'shopping/excluir/ref/'+ref},
              success: function(ok){
                  $(window.document.location).attr('href',"/carrinho"); 
                  //alert(ok)
              }
          });

        //alert(ref+" / "+qtd+" / "+tamanho);

  });  


  $("input[id='add_cupom']").blur(function(event) {
   
    var code = $(this).val();

    if(code != ""){

      $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            beforeSend: function(){
              $("#alert_cupom").html("<img src='images/loading.gif'/>");
            },
            data: {'url':'shopping/cupom/code/'+code+'/'},
            success: function(return_cupom){

              //$("#alert_cupom").html("<h4>"+return_cupom+"</h4>");

                //alert(return_cupom)

                location.reload(true) 

            } 
      })

    }else{

      $("#alert_cupom").html("<img src='images/loading.gif'/>");

      $("#alert_cupom").html("");

    }


  });


  //FECHER PEDIDO

  $("a[id=btn_fecharpedido]").click(function(){

      //$(".carregando").show();
     
      var subtotal = $("input[name='ped_subtotal']").val();
      // var item = $("input[name='ped_item']").val();
      var usuario = $("input[name='ped_usuario']").val();
      //var dados = $("input[name='ped_dados']").val();
      //var session = $("input[id='session_produto']").val();
      

      //alert(subtotal+' / '+item+' / '+usuario);

      
    if(subtotal != 0){

      $.ajax({
          url: "/backend/Ajax.php",
          type: "POST",
          data: {'url':'pedido/inserir/usuario/'+usuario+'/'},
          success: function(retorno){

            //$("#div_carrinho_send").html(retorno);


            var exp = retorno.split("|");

            var code = exp[1];
            var ref  = exp[0];

            //alert(code+" / "+ref);


            ///var code = code;
            var isOpenLightbox = PagSeguroLightbox({
                code: code
            }, {
                success : function(transactionCode) {
                    alert("success - " + transactionCode);
                    $.ajax({
                      url: "/backend/Ajax.php",
                      type: "POST",
                      data: {'url':'pedido/transaction/code/'+transactionCode+'/ref/'+ref+'/'},
                      success: function(retorno2){
                        //alert(retorno2);
                        $(window.document.location).attr('href',"/pedidoListar");  
                      }  

                    })



                },
                abort : function() {
                    $(window.document.location).attr('href',"/pedidoListar");
                }
            });
            // Redirecionando o cliente caso o navegador não tenha suporte ao Lightbox
            if (!isOpenLightbox){
                location.href="https://pagseguro.uol.com.br/v2/checkout/payment.html?code="+code;
            }
             

              //alert(ok);

              // $.ajax({
              //     url: "/backend/Ajax.php",
              //     type: "POST",
            //       beforeSend: function(){
            //         $("#div_carrinho").hide();
            //         $("#div_carrinho_send").show();
            //         $("#div_carrinho_send").html('<img src="/lib/App1/Public/frontend/img/enviando.gif" class="img-responsive center-block">');
            //       },
            //       complete: function(){                          
            //         $("#div_carrinho_send").html('<i class="fa fa-check-circle fa-5x"></i> Seu pedido foi enviado com sucesso!!');
            //       },
              //     data: {'url':'email/enviar/status/pedido/numero/'+ok},
              //     success: function(ok3){
                    
            //         //$(window.document.location).attr({'href':"#pgpedido",'class':'page-scroll'}); 
            //         $("#sessao_pedido > a > span").html(0); 
            //       }
              //   }); 

              // $.ajax({
              //     url: "/backend/Ajax.php",
              //     type: "POST",
              //     data: {'url':'email/enviar/status/admpedido/numero/'+ok},
              //     success: function(ok4){
              //         //$(window.document.location).attr('href',"/pedido/listar/");  
              //     }
              // });


              //$(window.document.location).attr('href',"/pedidoListar");  
          }
      });

    }//if subtotal


  });    
        
});