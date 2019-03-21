<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/Autoloader.php';
$config = unserialize (CONFIG_DB); 

header("Content-type: text/css"); 


/* variaveis */ 
$cor_fundo = $config[0]['D001_Tema'];
$cor_texto = '#FFFFFF';


?>

.pace .pace-progress {
  background: <?php echo $cor_fundo; ?> !important;
  -webkit-background: <?php echo $cor_fundo; ?> !important;
  -moz-background: <?php echo $cor_fundo; ?> !important;
  height: 3px;
}

.bg-primary {
  background-color: <?php echo $cor_fundo; ?>;
}

.text-primary {
	color: <?php echo $cor_fundo; ?> !important;
}

.btn-primary, .btn-primary:focus {
	color: <?php echo $cor_texto; ?> ;
	background-color: <?php echo $cor_fundo; ?>;
	border-color: <?php echo $cor_fundo; ?>;
  opacity: 0.6;
}

.btn-primary.active, .btn-primary:active, .btn-primary.active:focus, .btn-primary:active:focus, .btn-primary:active:hover, .open .dropdown-toggle.btn-primary {
  background-color: <?php echo $cor_fundo; ?>;
  border-color: <?php echo $cor_fundo; ?>;
  opacity: 0.8;
  color: <?php echo $cor_texto; ?>;
}
.btn-primary.hover, .btn-primary:hover, .open .dropdown-toggle.btn-primary {
  background-color: <?php echo $cor_fundo; ?>;
  border-color: <?php echo $cor_fundo; ?>;
  opacity: 1;
  color: <?php echo $cor_texto; ?>;
}
.btn-primary.active:hover {
  background-color: <?php echo $cor_fundo; ?>;
  border-color: <?php echo $cor_fundo; ?>;
  opacity: 1;
}

.btn-primary.disabled, .btn-primary[disabled], fieldset[disabled] .btn-primary, .btn-primary.disabled:hover, .btn-primary[disabled]:hover, fieldset[disabled] .btn-primary:hover, .btn-primary.disabled:focus, .btn-primary[disabled]:focus, fieldset[disabled] .btn-primary:focus, .btn-primary.disabled:active, .btn-primary[disabled]:active, fieldset[disabled] .btn-primary:active, .btn-primary.disabled.active, .btn-primary[disabled].active, fieldset[disabled] .btn-primary.active {
  background-color: <?php echo $cor_fundo; ?>;
  border-color: <?php echo $cor_fundo; ?>;
}
.btn-primary .badge {
  color: <?php echo $cor_fundo; ?>;
  background-color: #ffffff;
}

.dropdown-default.open>.btn-primary+.dropdown-menu:after {
  background-color: <?php echo $cor_fundo; ?>;
}

.progress-bar-primary {
  background-color: <?php echo $cor_fundo; ?>;
  background-image: none;
}

.progress-circle.progress-circle-primary .pie .half-circle {
  border-color: <?php echo $cor_fundo; ?>;
}

.nav-tabs-primary.nav-tabs-simple>li:after {
  background-color: <?php echo $cor_fundo; ?>;
}

.nav-tabs-primary.nav-tabs-fillup>li>a:after {
  background: none repeat scroll 0 0 <?php echo $cor_fundo; ?>;
  border: 1px solid <?php echo $cor_fundo; ?>;
}

.irs-wrapper.primary .irs-diapason {
  background-color: <?php echo $cor_fundo; ?>;
}

.irs-wrapper.primary .irs-from, .irs-wrapper.primary .irs-to, .irs-wrapper.primary .irs-single {
  background: <?php echo $cor_fundo; ?>;
}

.irs-wrapper.primary .irs-slider.from:before {
  background-color: <?php echo $cor_fundo; ?>;
}
.irs-wrapper.primary .irs-slider.to:before {
  background-color: <?php echo $cor_fundo; ?>;
}
.irs-wrapper.primary .irs-slider.single:before {
  background-color: <?php echo $cor_fundo; ?>;
}
.irs-wrapper.primary .irs-from:after, .irs-wrapper.primary .irs-to:after, .irs-wrapper.primary .irs-single:after {
  border-top-color: <?php echo $cor_fundo; ?>;
}
.irs-wrapper.primary .irs-bar {
  background-color: <?php echo $cor_fundo; ?>;
}

.noUi-target.bg-primary .noUi-connect {
  background-color: <?php echo $cor_fundo; ?>;
}

.radio.radio-primary input[type=radio]:checked+label:before {
  border-color: <?php echo $cor_fundo; ?>;
}

.checkbox.check-primary input[type=checkbox]:checked+label:before {
  border-color: <?php echo $cor_fundo; ?>;
}

.input-group-addon.primary {
  background-color: <?php echo $cor_fundo; ?>;
  border: 1px solid <?php echo $cor_fundo; ?>;
  color: #ffffff ?>
}
.input-group-addon.primary .arrow {
  color: <?php echo $cor_fundo; ?>;
}

.datepicker thead tr .next, .datepicker thead tr .prev {
  color: <?php echo $cor_fundo; ?>;
  content: '';
  font-size: 0px;
}

.datepicker thead tr .next:before, .datepicker thead tr .prev:before {
  color: <?php echo $cor_fundo; ?>;
  font-family: 'FontAwesome';
  font-size: 10px;
}

.datepicker thead tr .dow {
  font-family: 'Montserrat';
  color: <?php echo $cor_fundo; ?>;
  text-transform: uppercase;
  font-size: 11px;
}

.datepicker table tr td.active {
  background-color: <?php echo $cor_fundo; ?>!important;
}

.datepicker table tr td span.active {
  background-color: <?php echo $cor_fundo; ?>!important;
}

.daterangepicker .calendar .prev, .daterangepicker .calendar .next, .daterangepicker .calendar th {
  color: <?php echo $cor_fundo; ?>;
  text-transform: uppercase;
  font-size: 11px;
}

.daterangepicker td.active, .daterangepicker td.active:hover {
  background-color: <?php echo $cor_fundo; ?>;
  border-color: <?php echo $cor_fundo; ?>;
}

.summernote-wrapper .note-popover .popover .popover-content .dropdown-menu li a i, .summernote-wrapper .note-toolbar .dropdown-menu li a i {
  color: <?php echo $cor_fundo; ?>;
}

.line-chart[data-line-color="primary"] .nvd3 line.nv-guideline, .line-chart[data-line-color="primary"] .nvd3 .nv-groups path.nv-line, .line-chart[data-line-color="primary"] .nvd3.nv-line .nvd3.nv-scatter .nv-groups .nv-point {
  stroke: <?php echo $cor_fundo; ?>;
}

.line-chart[data-area-color="primary"] .nvd3 .nv-groups path.nv-area {
  fill: <?php echo $cor_fundo; ?>;
}

.line-chart[data-point-color="primary"] .nvd3.nv-line .nvd3.nv-scatter .nv-groups .nv-point {
  fill: <?php echo $cor_fundo; ?>;
}

.login-wrapper {
  height: 100%;
  background-color: <?php echo $cor_fundo; ?>;
}

.timeline-point.primary {
  background-color: <?php echo $cor_fundo; ?>;
}

.b-primary {
  border-color: <?php echo $cor_fundo; ?>;
}
.b-complete {
  border-color: <?php echo $cor_fundo; ?>;
}


.closed_acao{
  font-size: 30px; 
  margin-top: 20px;
  cursor: pointer;
}

.closed_acao:hover{
  color: <?php echo $cor_fundo; ?>
}


/*PAGINATION*/

.pagination > li > a,
.pagination > li > span {
  position: relative;
  float: left;
  padding: 6px 12px;
  margin-left: -1px;
  line-height: 1.428571429;
  color: <?php echo $cor_fundo; ?>;
  text-decoration: none;
  background-color: #fff;
  border: 1px solid #ddd;
}

.pagination > li > a:hover,
.pagination > li > span:hover,
.pagination > li > a:focus,
.pagination > li > span:focus {
  color: #333333;
  background-color: #eee;
  border-color: #ddd;
}
.pagination > .active > a,
.pagination > .active > span,
.pagination > .active > a:hover,
.pagination > .active > span:hover,
.pagination > .active > a:focus,
.pagination > .active > span:focus {
  z-index: 2;
  color: #fff;
  cursor: default;
  background-color: <?php echo $cor_fundo; ?>;
  border-color: <?php echo $cor_fundo; ?>;
}