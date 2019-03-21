$(document).ready(function(){
    
  $("#btn_fecharpedido").click(function(){

        $(".carregando").show();

        var subtotal = $("input[name='ped_subtotal']").val();
        var item = $("input[name='ped_item']").val();
        var usuario = $("input[name='ped_usuario']").val();
        //var dados = $("input[name='ped_dados']").val();

        //alert(subtotal+' / '+item+' / '+usuario);
          
          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':'pedido/inserir/usuario/'+usuario+'/item/'+item+'/subtotal/'+subtotal+'/'},
              success: function(ok){

                //alert(ok);

                 // $.ajax({
                 //      url: "/../Ajax.php",
                 //      type: "POST",
                 //      data: {'url':'email/enviar/status/pedido/numero/'+ok},
                 //      success: function(ok){
                 //      }
                 //  }); 

                 //   $.ajax({
                 //      url: "/../Ajax.php",
                 //      type: "POST",
                 //      data: {'url':'email/enviar/status/admpedido/numero/'+ok},
                 //      success: function(ok){
                 //          $(window.document.location).attr('href',"/pedido/listar/");  
                 //      }
                 //  });
              }
          }); 

  });  

    //$("a[class='pedTitle']").tooltip();

    $("a[id='pView']").click(function(){

        var id = $(this).prev("input").val();

        $.ajax({
          url: "/backend/Ajax.php",
          type: "POST",
          data: {'url':'pedido/pedview/id/'+id},
          success: function(dados){
             
            $("#retorno").html("");  


            $(".titlePainel").text("Visualizar Pedidos");

            $(".contentPainel").html(dados);

            $(".footerPainel").html('<span id="retorno"></span><button name="bt_ped_imprimir" id="bt_ped_imprimir" class="btn">Imprimir</button>')
          
            //alert(id);

            $("button[name='bt_ped_imprimir']").click(function(){                    
                //$(".contentPrint").printElement();
                $(".contentPrint").printElement();
                //alert("teste");
                return false;                    
            })              
       
          
           }
       });
       
        $('#responsive').modal();  

       //$("#painel").show('fast');
            
    });

 
                       
    /***********************   CADASTRAR  *************************/

  /*$("a[id='cliCad']").click(function(){

      
      $.ajax({
          url: "Ajax.php",
          type: "POST",
          data: {'url':'cliente/cadastrar/'},
          success: function(dados){
            
            $(".titlePainel").text("Cadastrar Cliente");

            $(".contentPainel").html(dados);

            $(".footerPainel").html('<span id="retorno"></span><button name="bt_cli_cadastrar" id="bt_cli_cadastrar" class="btn btn-primary">Cadastrar</button>')

                                      
              $('#bt_cli_cadastrar').click(function(){
                      

                    var usuario = $("select[name='cli_repres']").val();
                    var razao = $("input[name='cli_razao']").val();
                    var fantasia = $("input[name='cli_fantasia']").val();
                    var cnpj = $("input[name='cli_cnpj']").val();
                    var insc = $("input[name='cli_insc']").val();
                    var contato = $("input[name='cli_contato']").val();
                    var email = $("input[name='cli_email']").val();
                    var endereco = $("input[name='cli_endereco']").val();
                    var bairro = $("input[name='cli_bairro']").val();
                    var cidade = $("input[name='cli_cidade']").val();
                    var uf = $("input[name='cli_uf']").val();
                    var cep = $("input[name='cli_cep']").val();
                    var telefone = $("input[name='cli_telefone']").val();
                    var telefone2 = $("input[name='cli_telefone2']").val();
                    var celular = $("input[name='cli_celular']").val();
                    var fax = $("input[name='cli_fax']").val();
                    var frete = $("input[name='cli_frete']").val();
                    var obs = $("textarea[name='cli_obs']").val();
                
                    var info = 'cliente/inserir/usuario/'+usuario+'/razao/'+razao+'/fantasia/'+fantasia+'/cnpj/'+cnpj+'/insc/'+insc+'/contato/'+contato+'/email/'+email+'/endereco/'+endereco+'/bairro/'+bairro+'/cidade/'+cidade+'/uf/'+uf+'/cep/'+cep+'/telefone/'+telefone+'/telefone2/'+telefone2+'/celular/'+celular+'/fax/'+fax+'/frete/'+frete+'/obs/'+obs+'/';
                  
                                                      
                    $.ajax({
                      url: "Ajax.php",
                      type: "POST",
                      data: {'url': info},
                      success: function(res){
                        $('#retorno').html(res);
                      }                                  
                    }); 
                  
              });                       
            }
          
        });

       $('#painel').modal();  

    });//fim cli_cad*/

 
/*

    /*****************************************************************************************/
    /***********************                ALTERAR                  *************************/
    /*****************************************************************************************/
/*    $("i[id='cAlt']").tooltip();
    $("a[id='cliAlt']").click(function(){
       var $this = $(this);

       var id = $this.next("input").val();

       $.ajax({
          url: "../Ajax.php",
          type: "POST",
          data: {'url':'cliente/selecionar/id/'+id},
          success: function(dados){
             
            $("#retorno").html("");  
             
             //$("#conteudo").css("opacity",".3");


            $(".titlePainel").text("Alterar Cliente");

            $(".contentPainel").html(dados);

            $(".footerPainel").html('<span id="retorno"></span><button name="bt_cli_alterar" id="bt_cli_alterar" class="btn btn-primary">Alterar</button>')
          
            $("#bt_cli_alterar").click(function(){


                var id_cliente = id;
         
                //var alterar = $(this);
                var razao = $("input[name='cli_razao']").val();
                var fantasia = $("input[name='cli_fantasia']").val();
                var cnpj = $("input[name='cli_cnpj']").val();
                var insc = $("input[name='cli_insc']").val();
                var contato = $("input[name='cli_contato']").val();
                var email = $("input[name='cli_email']").val();
                var endereco = $("input[name='cli_endereco']").val();
                var bairro = $("input[name='cli_bairro']").val();
                var cidade = $("input[name='cli_cidade']").val();
                var uf = $("input[name='cli_uf']").val();
                var cep = $("input[name='cli_cep']").val();
                var telefone = $("input[name='cli_telefone']").val();
                var telefone2 = $("input[name='cli_telefone2']").val();
                var celular = $("input[name='cli_celular']").val();
                var fax = $("input[name='cli_fax']").val();
                var frete = $("input[name='cli_frete']").val();
                var obs = $("textarea[name='cli_obs']").val();
               
                $.ajax({
                    url: "../Ajax.php",
                    type: "POST",
                    data: {'url':'cliente/alteracao/id/'+id_cliente+'/razao/'+razao+'/fantasia/'+fantasia+'/cnpj/'+cnpj+'/insc/'+insc+'/contato/'+contato+'/email/'+email+'/endereco/'+endereco+'/bairro/'+bairro+'/cidade/'+cidade+'/uf/'+uf+'/cep/'+cep+'/telefone/'+telefone+'/telefone2/'+telefone2+'/celular/'+celular+'/fax/'+fax+'/frete/'+frete+'/obs/'+obs+'/'},
                    success: function(alterar){
                           $("#retorno").html(alterar);                 
                    }
                });
                   
             });
          
           }
       });
       
        $('#painel').modal();  

       //$("#painel").show('fast');
            
    });


    /*****************************************************************************************/
    /***********************                DELETAR                  *************************/
    /*****************************************************************************************/
    
 /*   $("i[id='cDel']").tooltip();

    $("a[id='cliDel']").click(function(){
       var $this = $(this);
       
       var id = $this.prev("input").val();

       $(".titleBox").text("Deletar Cliente");
       
       $(".contentBox").html('<h5>Deseja realmente deletar o cliente numero '+id+' ?</h5><span id="retorno"></span>');
       
       $(".footerBox").html('<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button><button name="del_conf" id="del_conf" class="btn btn-primary">Confirmar</button>')                            
       
       $('#box').modal();                 
              
       //confirmar
       $("button[name='del_conf']").click(function(){
          
           $.ajax({
                url: "../Ajax.php",
                type: "POST",
                data: {'url':'cliente/deletar/id/'+id+'/'},
                success: function(info){
                        
                       //$(".contentBox").html('<div id="del_retorno"></div>');
                                               
                       $("#retorno").html(info);

                       $(".footerBox").html('<button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>') 
                       
                       var ident = $('input[name="id_retorno"]').val();
                                
                       $("#"+ident).hide('slow');                        
                       
                 }
           });      
          
       });
              
    });
    
    
    /*****************************************************************************************/
    /***********************                LIBERADO                 *************************/
    /*****************************************************************************************/
 /*     $("div[id='cli_lib']").on('switch-change', function (e, data) {
          var $el = $(data.el),
              id = $(this).next("input").val(),
              status = data.value;
      
          $.ajax({
             url: "../Ajax.php",
             type: "POST",
             data: {'url':'cliente/bloquear/id/'+id+'/status/'+status+'/'},
             success: function(info){

              //alert(info);

                  $(".contentBox").html('<div id="lib_retorno"></div>');
                  $("#lib_retorno").html(info);
                  
                  //$("#box").css("display","block");
                  
                  var ident = $('input[name="id_retorno"]').val();
                  
                  $("#"+ident).hide('slow');           

             }
          });       
         
      });  

      $("div[id='cli_bloq']").on('switch-change', function (e, data) {
          var $el = $(data.el),
              id = $(this).next("input").val(),
              status = data.value;
      
          $.ajax({
             url: "../Ajax.php",
             type: "POST",
             data: {'url':'cliente/bloquear/id/'+id+'/status/'+status+'/'},
             success: function(info){

                  $(".contentBox").html('<div id="lib_retorno"></div>');
                  $("#lib_retorno").html(info);
                  
                  //$("#box").css("display","block");
                  
                  var ident = $('input[name="id_retorno"]').val();
                  
                  $("#"+ident).hide('slow');           

             }
          });       
         
      });   
    
    /*$("select[name='cli_lib']").change(function(){
        var $this = $(this);
        
        var status = $this.select('option:selected').val();
        var id = $this.next('input').val();
        
        $.ajax({
           url: "../Ajax.php",
           type: "POST",
           data: {'url':'cliente/bloquear/id/'+id+'/status/'+status+'/'},
           success: function(info){
                
                $(".contentBox").html('<div id="lib_retorno"></div>');
                $("#lib_retorno").html(info);
                
                //$("#box").css("display","block");
                
                var ident = $('input[name="id_retorno"]').val();
                                
                $("#"+ident).hide('slow');                  
                                              
                
           }
        });        
        
    });    */
      
    
    /*****************************************************************************************/
    /***********************                BLOQUEADO                *************************/
    /*****************************************************************************************/
    
   /* $("select[name='cli_bloq']").change(function(){
        var $this = $(this);
        
        var status = $this.select('option:selected').val();
        var id = $this.next('input').val();
        
        $.ajax({
           url: "../Ajax.php",
           type: "POST",
           data: {'url':'cliente/bloquear/id/'+id+'/status/'+status+'/'},
           success: function(info){
                
                $(".contentBox").html('<div id="bloq_retorno"></div>');
                                
                $("#bloq_retorno").html(info);
                                                
                //$("#box").css("display","block");
                
                var ident = $('input[name="id_retorno"]').val();
                                
                $("#"+ident).hide('slow');                
                
           }
        });        
        
    }); */ 
});