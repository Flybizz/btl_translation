(function($) {
  'use strict';
  $(document).ready(function() {
  /*********************************      INDEX       ************************************/

    function score(){
      $(".score span").click(function(){
        let star = $(this).attr("star")
        let count = $(".score").attr("count") 
        for(let n = 1; n <= count; n++){                                             
          if(n <= star){            
            console.log(n+" <= "+star+" = checked")
            $("[star_"+n+"]").addClass("checked")
          }else{
            console.log(n+" > "+star+" = no checked" )
            $("[star_"+n+"]").removeClass("checked")
          }
        }        
      })
    }

    score()

    function verificaData(){

      var dtstart = document.getElementById("datastart").value;
      var dts = dtstart.split("/");
      var rdts = dts[1]+"/"+dts[0]+"/"+dts[2];

      var dtend = document.getElementById("dataend").value;
      var dte = dtend.split("/");
      var rdte = dte[1]+"/"+dte[0]+"/"+dte[2];

      var dataInicio = new Date(rdts);
      var dataFim = new Date(rdte);
      
      var diffMilissegundos = dataFim - dataInicio;
      var diffSegundos = diffMilissegundos / 1000;
      var diffMinutos = diffSegundos / 60;
      var diffHoras = diffMinutos / 60;
      var diffDias = diffHoras / 24;
      var diffMeses = diffDias / 30;

      if(diffMilissegundos < 0){
        return false; 
      }else{
        return true;
      }

    }
  
           
    $("#cp_pesquisa").keyup(function(){

      $("ul[id='list_cliente']").html(`
        <div class="loader loader--style7">
          <img src='/images/loading.svg'>
        </div>
      `);
      
      let dg = $(this).val();   
      
      let arr_area = $('select[id="area"] option:selected');
      let data_area = $.map(arr_area, function (obj) {
        return obj.value;
      });
      let area = data_area.join(",");

      let arr_produto = $('select[id="produto"] option:selected');
      let data_produto = $.map(arr_produto, function (obj) {
        return obj.value;
      });
      let produto = data_produto.join(",");

      let arr_distrito = $('select[id="distrito"] option:selected');
      let data_distrito = $.map(arr_distrito, function (obj) {
        return obj.value;
      });
      let distrito = data_distrito.join(",");

      let arr_tipo = $('select[id="tipo"] option:selected');
      let data_tipo = $.map(arr_tipo, function (obj) {
        return obj.value;
      });      
      let tipo = data_tipo.join(",");     

      let vendedor = $("select[id='vendedor'] option:selected").val(); 
      let datastart = $("input[id='datastart']").val().replace( /[\/]/g, "-" );
      let dataend = $("input[id='dataend']").val().replace( /[\/]/g, "-" );
      var request = null;

      if( dg.length >= 3){

          //$("div[class='card-progress']").show();
          $.ajax({
              
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url': `cliente/resultado/campo/${dg}/vendedor/${vendedor}/area/${area}/produto/${produto}/distrito/${distrito}/tipo/${tipo}/datastart/${datastart}/dataend/${dataend}/`},
              success: function(info){

                $("ul[id='list_cliente']").html(info);
                          
                 $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url': `cliente/total/campo/${dg}/vendedor/${vendedor}/area/${area}/produto/${produto}/distrito/${distrito}/tipo/${tipo}/datastart/${datastart}/dataend/${dataend}/`},
                  success: function(total){
                    
                    $("span[id='pesq_total']").html(total);
               
                  }

                });
                                
              }

          });

        }else if( dg.length === 0){
          $("span[id='pesq_total']").html(0);
          $("ul[id='list_cliente']").html(`
            <li class="m-1 p-4 bg-light" >        
                <div class="col-md-12">
                    <h4 class="title fs-16 text-dark text-center">OPS! Campo de pesquisa está vazio.</h4>
                </div>        
            </li>
          `);
        }else{
          $("span[id='pesq_total']").html(0);
          $("ul[id='list_cliente']").html(`
            <li class="m-1 p-4 bg-light" >        
                <div class="col-md-12">
                    <h4 class="title fs-16 text-dark text-center">OPS! Digite mais de 3 caracteres.</h4>
                </div>        
            </li>
          `);
        }
        
    });

    $("button[id='buscar']").click(function(){

      $("ul[id='list_cliente']").html(`
        <div class="loader loader--style7">
          <img src='/images/loading.svg'>
        </div>
      `);
            
      let arr_area = $('select[id="area"] option:selected');
      let data_area = $.map(arr_area, function (obj) {
          return obj.value;
      });
      let area = data_area.join(",");

      let arr_produto = $('select[id="produto"] option:selected');
      let data_produto = $.map(arr_produto, function (obj) {
          return obj.value;
      });
      let produto = data_produto.join(",");

      let arr_distrito = $('select[id="distrito"] option:selected');
      let data_distrito = $.map(arr_distrito, function (obj) {
          return obj.value;
      });
      let distrito = data_distrito.join(",");

      let arr_tipo = $('select[id="tipo"] option:selected');
      let data_tipo = $.map(arr_tipo, function (obj) {
          return obj.value;
      });
      let tipo = data_tipo.join(",");

      let vendedor = $("select[id='vendedor'] option:selected").val();

      let datastart = $("input[id='datastart']").val().replace( /[\/]/g, "-" );
      let dataend = $("input[id='dataend']").val().replace( /[\/]/g, "-" );
               
      let cp_busca = $("input[id='cp_pesquisa']").val().replace( /[\/]/g, "" );      
      let vrfDate = verificaData()
      
      if(vrfDate != false){

          $.ajax({
              url: "/backend/Ajax.php",
              type: "POST",
              data: {'url':`cliente/resultado/campo/${cp_busca}/vendedor/${vendedor}/area/${area}/produto/${produto}/distrito/${distrito}/tipo/${tipo}/datastart/${datastart}/dataend/${dataend}/`},
              success: function(info){

                $("ul[id='list_cliente']").html(info);

                $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':`cliente/total/campo/${cp_busca}/vendedor/${vendedor}/area/${area}/produto/${produto}/distrito/${distrito}/tipo/${tipo}/datastart/${datastart}/dataend/${dataend}/`},
                  success: function(total){                    
                    $("span[id='pesq_total']").html(total|0);               
                  }
                });
                                
              }

          });
      }else{

        new PNotify({
          title: 'Alert',
          text: 'Data final anterior a data inicial.',
          type: 'danger',
          hide: true
        })        
      }    
    });
  
    $("button[id='pdf']").click(function(){       
                
      let arr_area = $('select[id="area"] option:selected');
      let data_area = $.map(arr_area, function (obj) {
          return obj.value;
      });
      let area = data_area.join(",");

      let arr_produto = $('select[id="produto"] option:selected');
      let data_produto = $.map(arr_produto, function (obj) {
          return obj.value;
      });
      let produto = data_produto.join(",");

      let arr_distrito = $('select[id="distrito"] option:selected');
      let data_distrito = $.map(arr_distrito, function (obj) {
          return obj.value;
      });
      let distrito = data_distrito.join(",");

      let arr_tipo = $('select[id="tipo"] option:selected');
      let data_tipo = $.map(arr_tipo, function (obj) {
          return obj.value;
      });
      let tipo = data_tipo.join(",");

      let vendedor = $("select[id='vendedor'] option:selected").val();

      let datastart = $("input[id='datastart']").val().replace( /[\/]/g, "-" );
      let dataend = $("input[id='dataend']").val().replace( /[\/]/g, "-" );
               
      let cp_busca = $("input[id='cp_pesquisa']").val().replace( /[\/]/g, "" );     

      $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            data: {'url':`cliente/pdf/campo/${cp_busca}/vendedor/${vendedor}/area/${area}/produto/${produto}/distrito/${distrito}/tipo/${tipo}/datastart/${datastart}/dataend/${dataend}/`},
            success: function(info){             
              console.log(info);
              
              window.open(
                   '/backend/public/pdf/relatorio_cliente.pdf',
                   'Batch Print',
                   'width=960,height=960,location=_newtab'
              );
            }
          });
    });
    
    $("button[id='addCliente']").click(function(){

      location.replace("/backend/cliente/registar/lang/pt");

    });

    
    $("button[id='addCliente']").click(function(){

      location.replace("/backend/cliente/registar/lang/pt");

    });

    $("button[id='clearForm']").click(function(){

      $("ul[id='list_cliente']").html(`
        <div class="loader loader--style7">
          <img src='/images/loading.svg'>
        </div>
      `);

      $("select[id='area']").multiselect('deselectAll', false);
      $("select[id='area']").multiselect('updateButtonText');

      $("select[id='produto']").multiselect('deselectAll', false);
      $("select[id='produto']").multiselect('updateButtonText');

      $("select[id='tipo']").multiselect('deselectAll', false);
      $("select[id='tipo']").multiselect('updateButtonText');

      $("select[id='campo']").multiselect('deselectAll', false);
      $("select[id='campo']").multiselect('updateButtonText');

      $("select[id='qualificacao']").multiselect('deselectAll', false);
      $("select[id='qualificacao']").multiselect('updateButtonText');

      $("select[id='distrito']").multiselect('deselectAll', false);
      $("select[id='distrito']").multiselect('updateButtonText');
    
      $("input[id='cp_pesquisa']").val("");
      $("input[id='datastart']").val("");
      $("input[id='dataend']").val("");

      $("select[id='vendedor']").val(0).trigger('change');

      $("span[id='pesq_total']").html("");
      $("ul[id='list_cliente']").html(`
        <li class="m-1 p-4 bg-warning" >        
            <div class="col-md-12">
                <h4 class="title fs-16 text-dark text-center">OPS! Nenhum resultado de pesquisa.</h4>
            </div>        
        </li>
      `);

    });

    $(document).on('click','#xls',function(){
      
      let arr_area = $('select[id="area"] option:selected');
      let data_area = $.map(arr_area, function (obj) {
          return obj.value;
      });
      let area = data_area.join(",");

      let arr_produto = $('select[id="produto"] option:selected');
      let data_produto = $.map(arr_produto, function (obj) {
          return obj.value;
      });
      let produto = data_produto.join(",");

      let arr_distrito = $('select[id="distrito"] option:selected');
      let data_distrito = $.map(arr_distrito, function (obj) {
          return obj.value;
      });
      let distrito = data_distrito.join(",");

      let arr_tipo = $('select[id="tipo"] option:selected');
      let data_tipo = $.map(arr_tipo, function (obj) {
          return obj.value;
      });
      let tipo = data_tipo.join(",");

      let vendedor = $("select[id='vendedor'] option:selected").val();

      let datastart = $("input[id='datastart']").val().replace( /[\/]/g, "-" );
      let dataend = $("input[id='dataend']").val().replace( /[\/]/g, "-" );
               
      let cp_busca = $("input[id='cp_pesquisa']").val().replace( /[\/]/g, "" );   

      let arr_campos = $('select[id="campo"] option:selected');
      let data_campo = $.map(arr_campos, function (obj) {
        return obj.value;
      });
      let coluna = data_campo.join(",");
           
      $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            data: {'url':`excel/dwexcel/campo/${cp_busca}/coluna/${coluna}/vendedor/${vendedor}/area/${area}/produto/${produto}/distrito/${distrito}/tipo/${tipo}/datastart/${datastart}/dataend/${dataend}/`},
            success: function(xls){   
              console.log(xls);
                       
              tableToExcel(xls,"relatorio");
            }

      });          
    });        

    var tableToExcel = (function() {
      var uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
        , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
      return function(table, name) {

        if (!table.nodeType){
          var ctx = {worksheet: name || 'Worksheet', table: table}
          // window.location.href = uri + base64(format(template, ctx))
          var dt = new Date();
          var day = dt.getDate();
          var month = dt.getMonth() + 1;
          var year = dt.getFullYear();
          var postfix = day + "." + month + "." + year;
          var result = uri + base64(format(template, ctx));
  
          var cle = document.createEvent("MouseEvent");
          cle.initEvent("click", true, true);
          var elem = document.getElementById('dlink');                   
          elem.href = result;
          elem.download = name + '-' + postfix + '.xls';
          elem.dispatchEvent(cle);

          return true;
        }
      }
    })()

    function dbemail() {

      $("a[id='cli_dbemail']").magnificPopup({
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

      $("a[id='cli_dbemail']").click(function(){

        $("#modal-title").html("");
        $("#modal-body").html('<img src="/images/loading.gif" />');
        $("#modal-footer").html('');       

        $("#modal-title").text("GERAR BANCO DE DADOS - EMAIL");
        $("#modal-body").html("");
        $("#modal-footer").html(`
          <div class="row">
            <div class="col-md-12 text-right">
              <button name="bdclient_conf" class="btn btn-primary">Confirmar</button>
              <button class="btn btn-default modal-dismiss">Cancelar</button>
            </div>
          </div>
        `);
         
         //confirmar
         $("button[name='bdclient_conf']").click(function(){

            let arr_area = $('select[id="area"] option:selected');
            let data_area = $.map(arr_area, function (obj) {
                return obj.value;
            });
            let area = data_area.join(",");
      
            let arr_produto = $('select[id="produto"] option:selected');
            let data_produto = $.map(arr_produto, function (obj) {
                return obj.value;
            });
            let produto = data_produto.join(",");
      
            let arr_distrito = $('select[id="distrito"] option:selected');
            let data_distrito = $.map(arr_distrito, function (obj) {
                return obj.value;
            });
            let distrito = data_distrito.join(",");
      
            let arr_tipo = $('select[id="tipo"] option:selected');
            let data_tipo = $.map(arr_tipo, function (obj) {
                return obj.value;
            });
            let tipo = data_tipo.join(",");
      
            let vendedor = $("select[id='vendedor'] option:selected").val();
      
            let datastart = $("input[id='datastart']").val().replace( /[\/]/g, "-" );
            let dataend = $("input[id='dataend']").val().replace( /[\/]/g, "-" );
                    
            let cp_busca = $("input[id='cp_pesquisa']").val().replace( /[\/]/g, "" );   
      
            let arr_campos = $('select[id="campo"] option:selected');
            let data_campo = $.map(arr_campos, function (obj) {
              return obj.value;
            });
            let coluna = data_campo.join(",");

            $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':`cliente/bdemail/campo/${cp_busca}/coluna/${coluna}/vendedor/${vendedor}/area/${area}/produto/${produto}/distrito/${distrito}/tipo/${tipo}/datastart/${datastart}/dataend/${dataend}/`},
                  success: function(info){
                     $("#retorno").html(info);
                     $("#modal-body").html(info);
                                         
                     $("#modal-footer").html(`<div class="row"><div class="col-md-12 text-right"> <button id="copy_email" class="btn btn-primary">Copiar</button> <button class="btn btn-default modal-dismiss">Cancelar</button></div></div>`)

                     $("#copy_email").click(function(){
                          $("#result_email").select();
                          document.execCommand('copy');
                      });
                  }

            });          

            
         });
      });
    }

    dbemail()

      /* RECUPERACAO PASSWORD */
      $("#recuperar_password").click(function(e){

          e.preventDefault();
          let email = $("#rec_email").val()
          let token = $("#token").val()
  
          $.ajax({
          url: "/backend/Ajax.php",
          type: "POST",
          data: {'url':'email/usuarioNewPasswordConfirm/email/'+email+'/token/'+token+''},
          success: function(dados){
              console.log(dados)
              let return_success = $("input[id='return_success']").val();
              $("form[id=result]").html(`                
                <div class='text-center alert alert-info'>                  
                  <h6 style="font-size: 1.5em; font-weight: 600; letter-spacing: normal; line-height: normal;">${return_success}</h6>
                </div>
              `)
          }
          })
  
      });
  
      $('#usu_password').pstrength();
      $('#confirm_password').blur(function(e){
  
          var senha = $('#usu_password').val();
          var confsenha = $('#confirm_password').val();
  
          var pw_error = $("#password_error").val();
          var pw_success = $("#password_success").val();
  
          if(confsenha != senha){
  
          $('#msgSenha').html(pw_error);
          $('#msgSenha').css('color','#ff0000');
          $("#validasenha").val(1);
  
          }else if(confsenha == ""){
  
          $('#msgSenha').html("<i class='icon-remove'></i>")
  
          }else{
  
          $('#msgSenha').html(pw_success)
          $('#msgSenha').css('color','#007F00')
          $("#validasenha").val(0);
  
          }
  
      });
  
      $("#bt_redefinir").click(function(event){
  
          event.preventDefault();
  
          var senha1 = $('#usu_password').val();
          var confsenha1 = $('#confirm_password').val();
          var validasenha = $("#validasenha").val();
          var validaemail = $("#validaemail").val();
  
          if(confsenha1 == ""){
              $("#validasenha").val(1);
          }
          if(confsenha1 != "" && validasenha == 1){
              $("#validasenha").val(1);
          }
          if(confsenha1 != "" && validasenha == 0){
              $("#validasenha").val(0);
          }
  
          var usuario = $("input[id='usu_id']").val();
          var senha = md5($("input[id='confirm_password']").val());
          var senha1 = $("input[id='confirm_password']").val();
  
          var ref = "";
          var count = 0;
          var valida = "";
  
          if(senha == ""){ref += "Password<br>"; count += 1;}
  
          if(count != 0){
  
              $("#ref").html("<div class='alert alert-danger' style='font-size:12px;'><button type='button' class='close' data-dismiss='alert'>×</button><h4 style='font-size:12px;'>Falta dados Obrigatórios:</h4>"+ref+"</div> <div class='clearfix'></div>");
  
          }else{
              $("#ref").html("");
          }
  
          if(validasenha == 0 && count == 0){
  
          $.ajax({
                  url: "/backend/Ajax.php",
                  type: "POST",
                  data: {'url':'usuario/update/usuario/'+usuario+'/senha/'+senha+'/'},
                  success: function(rs){
  
                  console.log(rs);
  
                  var usuario2 = $("input[id='usu_id']").val();
  
                  if(rs == 1){
                      $.ajax({
                          url: "/backend/Ajax.php",
                          type: "POST",
                          data: {'url':'email/usuarioNewPasswordSuccess/usuario/'+usuario2+'/'},
                          success: function(dados){
                              console.log(dados) 
                              let return_success = $("input[id='return_success']").val();
                              $("form[id='return']").html("<div class='text-center'><h6 style='font-size: 1.5em;'>"+return_success+"</h6></div><br><br>")                            
                          }
                      })
                  }else{
                    let return_success = $("input[id='return_success']").val();
                    $("form[id='return']").html("<div class='text-center'><h6 style='font-size: 1.5em;'>"+return_success+"</h6></div><br><br>")
                  }
                  }
              });
  
          }else if(validasenha == 1){
              valida = "SENHA INCORRETA.";
          }
  
          if(valida != ""){
              $("#retorno").html("<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>×</button><h6 style='font-size:11px;'>"+valida+"</h6></div>");
  
          }
  
  
      });


     
  });
})(window.jQuery);