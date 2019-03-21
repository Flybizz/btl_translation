<?php     

  $url = (isset($_GET['url'])) ? $_GET['url'] : false;

  $control = explode("/", $url);

?>



<?php if($control[0] == "configuracao"): ?>

<script src="/../backend/public/js/Ajax/config_ajax.js"></script>

<script src="/../backend/public/js/Ajax/calendario_ajax.js"></script>



<?php endif; if($control[0] == "usuario"): ?>

<script src="/../backend/public/js/Ajax/usuario_ajax.js"></script>



<?php endif; if($control[0] == "vendedor"): ?>

<script src="/../backend/public/js/Ajax/vendedor_ajax.js"></script>



<?php endif; if($control[0] == "cliente"): ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBx7wcRGIH__sLSdZZjDU-wAHCQQJxWZj8"></script>

<script src="/../backend/public/js/gmap3.js"></script>

<script src="/../backend/public/js/Ajax/cliente_ajax.js"></script>





<?php endif; if($control[0] == "area"): ?>

<script src="/../backend/public/js/Ajax/area_ajax.js"></script>



<?php endif; if($control[0] == "cargo"): ?>

<script src="/../backend/public/js/Ajax/cargo_ajax.js"></script>



<?php endif; if($control[0] == "tipo"): ?>

<script src="/../backend/public/js/Ajax/tipo_ajax.js"></script>



<?php endif; if($control[0] == "distrito"): ?>

<script src="/../backend/public/js/Ajax/distrito_ajax.js"></script>



<?php endif; if($control[0] == "especialidade"): ?>

<script src="/../backend/public/js/Ajax/especialidade_ajax.js"></script>



<?php endif; if($control[0] == "produto"): ?>

<script src="/../backend/public/js/Ajax/produto_ajax.js"></script>



<?php endif; if($control[0] == "institucional"): ?>

<script src="/../backend/public/js/Ajax/institucional_ajax.js"></script>



<?php endif; if($control[0] == "menu"): ?>

<script src="/../backend/public/js/Ajax/menu_ajax.js"></script>



<?php endif; if($control[0] == "idioma"): ?>

<script src="/../backend/public/js/Ajax/idioma_ajax.js"></script>



<?php endif; if($control[0] == "nivel"): ?>

<script src="/../backend/public/js/Ajax/nivel_ajax.js"></script>



<?php endif; if($control[0] == "pagina"): ?>

<script src="/../backend/public/js/Ajax/pagina_ajax.js"></script>



<?php endif; if($control[0] == "homepage"): ?>

<script src="/../backend/public/js/Ajax/homepage_ajax.js"></script>



<?php endif; if($control[0] == "calendario" || $control[0] == "configuracao" || $control[0] == "cliente"): ?>



<script async defer src="https://apis.google.com/js/api.js"

onload="this.onload=function(){};handleClientLoad()"

onreadystatechange="if (this.readyState === 'complete') this.onload()">

</script>



<script src="/../backend/public/js/Ajax/calendario_ajax.js"></script>



<?php endif; if($control[0] == "formacao"): ?>

<script src="/../backend/public/js/Ajax/formacao_ajax.js"></script>



<?php endif; if($control[0] == "formando"): ?>

<script src="/../backend/public/js/Ajax/formando_ajax.js"></script>



<?php endif; if($control[0] == "lead"): ?>

<script src="/../backend/public/js/Ajax/lead_ajax.js"></script>



<?php endif; ?>

<?php if($control[0] == "index"): ?>
	<script src="/../backend/public/js/Ajax/index_ajax.js"></script>
<?php endif; ?>