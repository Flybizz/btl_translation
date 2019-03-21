<?php 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/lib/App/Autoloader.php';
    $config = unserialize(CONFIG_DB);

    if (isset($_REQUEST['url'])):
        $control = explode("/", $_REQUEST['url']);
    else:
        $control = array(0 => '');
    endif;
?>
<!DOCTYPE html>
<html lang="pt" class="">    
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes"/>

        <?php 
        if($control[0] == ""): ?>
        <title><?php echo $config[0]['D001_Titulo']; ?></title>    
        <meta name="title" content="<?php echo $config[0]['D001_Titulo']; ?>"/> 
        <meta name="description" content="<?php echo $config[0]['D001_Descricao']; ?>"/>
        <?php else: 
            $seo = include $_SERVER['DOCUMENT_ROOT'] . '/backend/lib/App/Site/seo.phtml';
         ?>
        <title><?php echo $seo['title']; ?></title> 
        <meta name="title" content="<?php echo $seo['title']; ?>"/> 
        <meta name="description" content="<?php echo $seo['description']; ?>"/>
        <?php endif; ?> 

        <meta name="copyright" content="(c) 2018 <?php echo $config[0]['D001_Empresa']; ?>"/>
        <meta name="revisit-after" content="7 days"/>
        <meta name="robots" content="INDEX,FOLLOW"/>
        
        <meta name="rating" content="general"/>
        <meta name="language" content="portuguese"/>
        <meta name="audience" content="all"/>
  
        <meta property="og:url"           content="https://<?php echo $config[0]['D001_Site']; ?>" />
        <meta property="og:type"          content="website" />
        <meta property="og:title"         content="<?php echo $config[0]['D001_Titulo']; ?>" />
        <meta property="og:description"   content="<?php echo $config[0]['D001_Descricao']; ?>" />
        <meta property="og:image"         content="https://<?php echo $config[0]['D001_Site']; ?>/images/facebook.jpg" />
        <meta property="og:image:alt"     content="<?php echo $config[0]['D001_Titulo']; ?>" /> 
     
        <meta property="og:site_name"     content="<?php echo $config[0]['D001_Titulo']; ?>"/>
        <meta property="og:locale"        content="pt_PT"/>
        <meta property="fb:app_id"        content="153696808687191" />                 
        <link rel="shortcut icon" href="/images/config/<?php echo $config[0]['D001_Favicon'] ?>" type="image/x-icon">    
        <link href="/frontend/_data/css/icofont.css" rel="stylesheet" type="text/css">   
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Libre+Baskerville:400i" rel="stylesheet" type="text/css">
        
        <?php
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
        
        if (isset($_REQUEST["url"])) {
            $url = explode("/", $_GET["url"]);
            if ($url[0] != "page"):
                $colortema = "color: " . $config[0]['D001_Tema'] . ";";
                $backtema = "background-color: #ffffff;";
            else:
                $colortema = "color: #555555;";
                $backtema = "background-color: " . $config[0]['D001_Tema'] . " !important;";
            endif;
        }
        ?>
        
        <?php if (!empty($control[0]) && $control[0] == "landpage"): ?>          
            <link href="/frontend/_data/css/theme.css" rel="stylesheet" type="text/css"/>
        <?php else: ?>
            <!-- Vendor CSS -->
            <link rel="stylesheet" href="/frontend/vendor/bootstrap/css/bootstrap.min.css">
            <link rel="stylesheet" href="/frontend/vendor/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" href="/frontend/vendor/animate/animate.min.css">
            <link rel="stylesheet" href="/frontend/vendor/simple-line-icons/css/simple-line-icons.min.css">
            <link rel="stylesheet" href="/frontend/vendor/owl.carousel/assets/owl.carousel.min.css">
            <link rel="stylesheet" href="/frontend/vendor/owl.carousel/assets/owl.theme.default.min.css">
            <link rel="stylesheet" href="/frontend/vendor/magnific-popup/magnific-popup.min.css">
            <!-- Theme CSS -->
            <link rel="stylesheet" href="/frontend/css/theme.css">
            <link rel="stylesheet" href="/frontend/css/theme-elements.css">
            <link rel="stylesheet" href="/frontend/css/theme-blog.css">
            <!-- <link rel="stylesheet" href="/frontend/css/theme-shop.css"> -->

            <!-- Current Page CSS -->
            <link rel="stylesheet" href="/frontend/vendor/rs-plugin/css/settings.css">
            <link rel="stylesheet" href="/frontend/vendor/rs-plugin/css/layers.css">
            <link rel="stylesheet" href="/frontend/vendor/rs-plugin/css/navigation.css">
            <link rel="stylesheet" href="/frontend/vendor/circle-flip-slideshow/css/component.css">
           
           <!--  <link rel="stylesheet" href="/frontend/css/demos/demo-photography-2.css"> -->

            <link rel="stylesheet" href="/frontend/css/skins/default.css"> 

            <!-- <link rel="stylesheet" href="/frontend/css/skins/skin-photography.css"> -->
            <?php endif; ?>
                  
        <link rel="stylesheet" href="/frontend/css/custom.css">
        <script src="/frontend/vendor/modernizr/modernizr.min.js"></script>
    </head>
    <body>
        <div class="body">
            <div role="main" class="main calc-height initial-height">
                <?php
                define("CONTROLLERS", "backend/lib/App/Controller/");
                define("MODELS", "backend/lib/App/Model/");
                define("SITES", "backend/lib/App/Site/");
                //define("VIEWS", "lib/App/View/");
                require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/lib/App/Controller.php';
                require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/lib/App/Model.php';
                require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/lib/App/System.php';
                require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/lib/App/Logar.php';          
                $start = new App_System();
                $start->run();  
                ?>
        </div>

        <!-- Vendor -->
        <script src="/frontend/vendor/jquery/jquery.min.js"></script>
        <script src="/frontend/vendor/jquery.appear/jquery.appear.min.js"></script>
        <script src="/frontend/vendor/jquery.easing/jquery.easing.min.js"></script>        
        <script src="/frontend/vendor/jquery-cookie/jquery-cookie.min.js"></script>
        
        <script src="/frontend/vendor/popper/umd/popper.min.js"></script>
        <script src="/frontend/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="/frontend/vendor/common/common.min.js"></script>
        <script src="/frontend/vendor/jquery.validation/jquery.validation.min.js"></script>
        <script src="/frontend/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
        <script src="/frontend/vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
        <script src="/frontend/vendor/isotope/jquery.isotope.min.js"></script>
        <script src="/frontend/vendor/owl.carousel/owl.carousel.min.js"></script>
        <script src="/frontend/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
        <script src="/frontend/vendor/vide/vide.min.js"></script>
        <script src="/frontend/vendor/jquery-mousewheel/jquery.mousewheel.min.js"></script>
        
        <!-- Theme Base, Components and Settings -->
        <script src="/frontend/js/theme.js"></script>
        
        <!-- Current Page Vendor and Views -->
        <script src="/frontend/vendor/rs-plugin/js/jquery.themepunch.tools.min.js"></script>      
        <script src="/frontend/vendor/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>

        
        <!-- <script src="/frontend/js/demos/demo-photography.js"></script> -->
        <!-- <script src="/frontend/js/demos/demo-real-estate.js"></script> -->
        <!-- Examples -->
        <script src="/frontend/js/examples/examples.portfolio.js"></script>

        
        <!-- Theme Custom -->
        <script src="/frontend/js/custom.js"></script>
        
        <!-- Theme Initialization Files -->
        <script src="/frontend/js/theme.init.js"></script>
      
        <!-- END: Scripts -->
        <script src="/frontend/_data/js/wv_validacao.js"></script>
        <script src="/frontend/_data/js/Ajax/contato_ajax.js"></script>
        <script src="/frontend/_data/js/Ajax/orcamento_ajax.js"></script>
        <script src="/frontend/_data/js/Ajax/custom_ajax.js"></script>
        <script src="/frontend/_data/js/Ajax/imovel_ajax.js"></script>
        <script src="https://apis.google.com/js/platform.js" async defer></script> 


        <!-- Examples -->
		
        <script>
            $("#cred").tooltip()
        </script>
    </body>
</html>
