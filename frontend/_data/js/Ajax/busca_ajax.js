jQuery(document).ready(function(){

    jQuery("#searchword").keypress(function(e){

      if(e.which == 13){

        var busca = jQuery(this).val().replace( /[\/]/g, "" );

        window.location.href = '/page/busca/ps/'+busca+'/p/0';
        
      }      

    });

    jQuery("#searchwordbtn").click(function(e){

        var busca = jQuery(this).next("input").val().replace( /[\/]/g, "" );

        window.location.href = '/page/busca/ps/'+busca+'/p/0';

    });

});