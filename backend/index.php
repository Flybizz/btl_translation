<?php require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/Autoloader.php';
    $config = unserialize (CONFIG_DB);   
?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title><?php echo $config[0]['D001_Empresa'] ?></title>
		<meta name="keywords" content="" />
		<meta name="description" content="<?php echo $config[0]['D001_Empresa'] ?>">
		<meta name="author" content="Flybizz - Digital Marketing Agency">

    	<link rel="icon" type="image/x-icon" href="/images/config/<?php echo $config[0]['D001_Favicon'] ?>"/>

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
			

		<!-- /../backend/public/vendor CSS -->
		<link rel="stylesheet" href="/../backend/public/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="/../backend/public/vendor/animate/animate.css">

		<link rel="stylesheet" href="/../backend/public/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="/../backend/public/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="/../backend/public/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

		<!-- Specific Page /../backend/public/vendor CSS -->
		<link rel="stylesheet" href="/../backend/public/vendor/jquery-ui/jquery-ui.css" />
		<link rel="stylesheet" href="/../backend/public/vendor/jquery-ui/jquery-ui.theme.css" />
		<link rel="stylesheet" href="/../backend/public/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="/../backend/public/vendor/morris/morris.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="/../backend/public/vendor/select2/css/select2.css" />
		<link rel="stylesheet" href="/../backend/public/vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
		<link rel="stylesheet" href="/../backend/public/vendor/datatables/media/css/dataTables.bootstrap4.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="/../backend/public/vendor/fullcalendar/fullcalendar.css" />
		<link rel="stylesheet" href="/../backend/public/vendor/fullcalendar/fullcalendar.print.css" media="print" />

		<link rel="stylesheet" href="/../backend/public/vendor/switchery/css/switchery.min.css" />		

		<link rel="stylesheet" href="/../backend/public/vendor/pnotify/pnotify.custom.css" />

		<link href="/../backend/public/vendor/uploadfive/uploadifive.css" rel="stylesheet">
		<!-- <link href="/../backend/public/css/jquery.minicolors.css" rel="stylesheet" type="text/css"/> -->
	    <link rel='stylesheet' href='/../backend/public/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css' type='text/css' media='all' />
	    <link rel="stylesheet" href="/../backend/public/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css" />
		
		<link rel="stylesheet" href="/../backend/public/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="/../backend/public/css/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="/../backend/public/css/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="/../backend/public/css/custom.css">

		<link href="/../backend/public/css/flags/css/flag-icon.css" rel="stylesheet">

		<!-- Head Libs -->
		<script src="/../backend/public/vendor/modernizr/modernizr.js"></script>
       
    <?php
      $cor_fundo = $config[0]['D001_Tema'];
      $cor_texto = "#FFFFFF";

      function hex2rgb($hex)
      {
          $hex = str_replace("#", "", $hex);
          if (strlen($hex) == 3) {
              $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
              $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
              $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
          } else {
              $r = hexdec(substr($hex, 0, 2));
              $g = hexdec(substr($hex, 2, 2));
              $b = hexdec(substr($hex, 4, 2));
          }
          $rgb = array($r, $g, $b);
          //return implode(",", $rgb); // returns the rgb values separated by commas
          return $rgb; // returns an array with the rgb values
      }
      if (!empty($config[0]['D001_Tema'])):
          $tema = hex2rgb($config[0]['D001_Tema']);
          $t1_dark1 = $tema[0] - 15;
          $t1_dark2 = $tema[1] - 15;
          $t1_dark3 = $tema[2] - 15;
          if ($tema[0] >= 180):
              $tema_text = "rgba(25,25,25,0.9)";
          else:
              $tema_text = "rgba(250,250,250,0.9)";
          endif;
      else:
          $tema = array(0 => 250, 1 => 250, 2 => 250);
      endif;

      $tema2 = hex2rgb($config[0]['D001_Tema2']);
      $t2_dark1 = $tema2[0] - 15;
      $t2_dark2 = $tema2[1] - 15;
      $t2_dark3 = $tema2[2] - 15;
      ?>

		

	</head>
	<body>
		<section class="body">

      	<?php if ( isset($_SESSION['usuarioLogado2']) ): ?>
      		<?php  require_once 'backend/lib/App/View/header.phtml'; ?>
			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">

          			<?php  require_once 'backend/lib/App/View/nav.phtml'; ?>
				
				</aside>
				<!-- end: sidebar -->

				<section role="main" class="content-body">

				<?php
					if (! empty( $_REQUEST['url'] )):
						require_once("pagina.segura.php"); //SEGURANÇA
						define("CONTROLLERS", "../backend/lib/App/Controller/");
						define("MODELS", "../backend/lib/App/Model/");
						define("VIEWS", "../backend/lib/App/View/");
						
						require_once 'backend/lib/App/Controller.php';
						require_once 'backend/lib/App/Model.php';
						require_once 'backend/lib/App/System.php';
						$start = new App_System();
						$start->run();
					endif;
				?>

					<!-- Modal Animation -->
					<div id="modalAnim" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
						<section class="card">
							<header class="card-header">
								<h2 id="modal-title" class="card-title"></h2>
							</header>
							<div class="card-body">
								<div class="modal-wrapper">								
									<div class="modal-text">
										<p id="modal-body" class="mb-0"></p>
									</div>
								</div>
							</div>
							<footer id="modal-footer" class="card-footer">
							</footer>
						</section>
					</div>

					<div class="flybizz-beta">
							<a target="_blank" href="https://www.flybizz.net"><img src="/images/flybizz.png" alt="Flybizz - Digital Marketing Agency" /></a>
							<span><i class="fa fa-code"></i> Versão 2.2.0</span>
					</div>
		  					
				</section>
			</div>
			<aside id="sidebar-right" class="sidebar-right">
				<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/lib/App/View/partials/sidebar.phtml" ?>
			</aside>
		<?php 

		else:      
			
			if(isset($_GET["url"]) && $_GET["url"] == "recuperar"):
				require_once $_SERVER["DOCUMENT_ROOT"]."/backend/lib/App/View/recuperarPassword.phtml";			
			elseif(isset($_GET["url"]) && $_GET["url"] == "redefinir"):

				$controller = new App_Controller();

				if(isset($_GET["token"])):
					if(!empty($_GET["token"])):
	
					  $model = new App_Model_usuarioModel();
					  $usuario = $model->usuarioSelecionarToken($_GET["token"]);
					  $dados["usuario"] = $usuario;
	
					  // Cria um objeto com a data atual UTC
					  $dt = new DateTime(gmdate('Y/m/d H:i:s'), new DateTimeZone('UTC'));
					  // Altera a timezone
					  $dt->setTimezone(new DateTimeZone('Europe/Lisbon'));
	
					  if(!empty($usuario)):
	
						$token = date('Y-m-d H:i:s', $usuario[0]["usu_time"]);
	
						$dt1 = new DateTime( $token );
						$dt2 = $dt;
						$intervalo = $dt1->diff( $dt2 );
	
						if($intervalo->d >= 1 && $intervalo->invert == 0):
						  	$dados["session"] = false;
						  	$dados["token"] = true;
						elseif($intervalo->d < 1 && $intervalo->invert == 0):
							$dados["token"] = true;
						  	$dados["session"] = true;
						endif;
					  else:
						$dados["session"] = false;
						$dados["token"] = false;
					  endif;
					else:
					  $dados["session"] = false;
					  $dados["token"] = false;
					endif;
				  else:
					$dados["session"] = false;
					$dados["token"] = false;
				  endif;

				  echo $controller->load_view(getcwd()."/lib/App/View/redefinirPassword", $dados);

			
			else:
				require_once $_SERVER["DOCUMENT_ROOT"]."/backend/lib/App/View/login.phtml";			  
			
			endif;
			
		endif;
		?>

		

		</section>

		

		<!-- /../backend/public/vendor -->
		<script src="/../backend/public/vendor/jquery/jquery.js"></script>
		<script src="/../backend/public/js/jquery.pstrength-min.1.2.js"></script>
		<script src="/../backend/public/js/md5.js"></script>
				
		<script src="/../backend/public/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="/../backend/public/vendor/popper/umd/popper.min.js"></script>
		<script src="/../backend/public/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="/../backend/public/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="/../backend/public/vendor/common/common.js"></script>
		<script src="/../backend/public/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="/../backend/public/vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="/../backend/public/vendor/jquery-placeholder/jquery-placeholder.js"></script>
		
		<script src="/../backend/public/vendor/bootstrap-timepicker/bootstrap-timepicker.js"></script>
		
		<!-- Specific Page /../backend/public/vendor -->
		<script src="/../backend/public/vendor/jquery-ui/jquery-ui.js"></script>
		<script src="/../backend/public/vendor/jqueryui-touch-punch/jqueryui-touch-punch.js"></script>
		<script src="/../backend/public/vendor/jquery-appear/jquery-appear.js"></script>
		<script src="/../backend/public/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
		<script src="/../backend/public/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.js"></script>
		<script src="/../backend/public/vendor/flot/jquery.flot.js"></script>
		<script src="/../backend/public/vendor/flot.tooltip/flot.tooltip.js"></script>
		<script src="/../backend/public/vendor/flot/jquery.flot.pie.js"></script>
		<script src="/../backend/public/vendor/flot/jquery.flot.categories.js"></script>
		<script src="/../backend/public/vendor/flot/jquery.flot.resize.js"></script>
		<script src="/../backend/public/vendor/jquery-sparkline/jquery-sparkline.js"></script>
		<script src="/../backend/public/vendor/raphael/raphael.js"></script>
		<script src="/../backend/public/vendor/morris/morris.js"></script>
		<script src="/../backend/public/vendor/gauge/gauge.js"></script>
		<script src="/../backend/public/vendor/snap.svg/snap.svg.js"></script>
		<script src="/../backend/public/vendor/liquid-meter/liquid.meter.js"></script>
		<script src="/../backend/public/vendor/jqvmap/jquery.vmap.js"></script>
		<script src="/../backend/public/vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>
		<script src="/../backend/public/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
		<script src="/../backend/public/vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
		<script src="/../backend/public/vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
		<script src="/../backend/public/vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
		<script src="/../backend/public/vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
		<script src="/../backend/public/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
		<script src="/../backend/public/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>

		<!-- Specific Page Vendor -->
		<script src="/../backend/public/vendor/select2/js/select2.js"></script>		
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<script src="/../backend/public/vendor/datatables/media/js/dataTables.select.min.js"></script>
		<script src="/../backend/public/vendor/datatables/media/js/dataTables.select.min.js"></script>
		<script src="/../backend/public/vendor/datatables/media/js/dataTables.bootstrap4.min.js"></script>				
		<script src="/../backend/public/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/dataTables.buttons.min.js"></script>
		<script src="/../backend/public/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.bootstrap4.min.js"></script>
		<script src="/../backend/public/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.html5.min.js"></script>
		<script src="/../backend/public/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.print.min.js"></script>
		<script src="/../backend/public/vendor/datatables/extras/TableTools/JSZip-2.5.0//jszip.min.js"></script>
		<script src="/../backend/public/vendor/datatables/extras/TableTools/pdfmake-0.1.32/pdfmake.min.js"></script>
		<script src="/../backend/public/vendor/datatables/extras/TableTools/pdfmake-0.1.32/vfs_fonts.js"></script>		
		<script src="/../backend/public/vendor/jquery-nestable/jquery-nestable.js"></script>

		<script src="/../backend/public/vendor/ios7-switch/ios7-switch.js"></script>
		<!--script src="/../backend/public/vendor/switchery/js/switchery.min.js"></script-->
		
		<script src="/../backend/public/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

		<script src="/../backend/public/vendor/pnotify/pnotify.custom.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="/../backend/public/js/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="/../backend/public/js/custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="/../backend/public/js/theme.init.js"></script>

		<!-- Examples -->
		<script src="/../backend/public/js/examples/examples.datatables.default.js"></script>
		<script src="/../backend/public/js/examples/examples.datatables.row.with.details.js"></script>
		<script src="/../backend/public/js/examples/examples.datatables.tabletools.js"></script>

		<!-- Specific Page Vendor -->
		<!-- <script src="/../backend/public/vendor/jquery-ui/jquery-ui.js"></script>
		<script src="/../backend/public/vendor/jqueryui-touch-punch/jqueryui-touch-punch.js"></script> -->
		<script src="/../backend/public/vendor/moment/moment.js"></script>
		
		<script src="/../backend/public/vendor/fullcalendar/fullcalendar.js"></script>
		<script src="/../backend/public/vendor/fullcalendar/locale/pt.js"></script>

		<!-- Examples -->
    <?php if( isset($_REQUEST['url'])): if($_REQUEST['url'] == "index/"):  ?>
    <script src="/../backend/public/js/examples/examples.dashboard.js"></script>
<!--     <script src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['geochart']}]}"></script>
    <script src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script> -->
    <script src="/../backend/public/js/Ajax/index_ajax.js"></script>
	<?php endif;
		if($_REQUEST['url'] == "recuperar"):
	?>
		<script src="/../backend/public/js/Ajax/index_ajax.js"></script>	
		<?php endif;
		if($_REQUEST['url'] == "redefinir"):
	?>
		<script src="/../backend/public/js/Ajax/index_ajax.js"></script>		
	<?php endif; endif; ?>

    <script src="/../backend/public/vendor/ckeditor/ckeditor.js"></script> 
    <script src="/../backend/public/vendor/uploadfive/jquery.uploadifive.min.js"></script>
    
    <script src="/../backend/public/js/wv_validacao.js"></script>
    
    <script src="/../backend/public/js/jquery.nicescroll.js"></script>
<!--     <script src="/../backend/public/js/jquery.minicolors.js"></script> -->

    <script src="/../backend/public/js/Sortable.js"></script>
    <script src="/../backend/public/js/Sortable.min.js"></script>

    <script src="/../backend/public/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
	

	<script src="/../backend/public/js/slugify.js"></script>   
	
	<script src="/../backend/public/js/examples/examples.calendar.js"></script>

	<script src="/../backend/public/js/common.js"></script>

	<script>var CONFIG = <?php echo json_encode($config); ?></script>

	<?php require_once "backend/function.php"; ?>
	  

	</body>
</html>