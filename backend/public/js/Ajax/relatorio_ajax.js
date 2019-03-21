$(document).ready(function(){
      //$("#rpc_dtnascimento").mask("99/99/9999");
      $("a[id='gpdf']").tooltip();
      $("a[id='gpdf']").click(function(){
        var anoi = $("input[id='rpc_dtnascimento']").val().replace( /[\/]/g, "-" );
        var anof = $("input[id='rpc_dtnascimento2']").val().replace( /[\/]/g, "-" );  
        var status = $("select[id='rpc_status']").select("option:selected").val(); 
        var cid = $("input[id='rpc_cidade']").val();     
        $.ajax({
            url: "/backend/Ajax.php",
            type: "POST",
            data: {'url':'relatorio/pdf/status/'+status+'/datnas/'+anoi+' '+anof+'/cidres/'+cid+'/'},
            success: function(dados2){
                
                //alert(dados2);
                  
                window.open(
                     'http://cnsf.odo.br/backend/public/pdf/relatorio.pdf',
                     'Batch Print',
                     'width=600,height=600,location=_newtab'
                );
            }
        });       
      });
});//document