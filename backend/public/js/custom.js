/* Add here all your JS customizations */

/*
Modal Dismiss
*/
$(document).on('click', '.modal-dismiss', function (e) {
    e.preventDefault();
    $.magnificPopup.close();
});

/*
Modal Confirm
*/
var stack_bottomleft = {"dir1": "right", "dir2": "up", "push": "top"};
$(document).on('click', '.modal-confirm', function (e) {
    e.preventDefault();
    $.magnificPopup.close();

    new PNotify({
        title: 'Sucesso!',
        text: 'Item apagado com sucesso.',
        type: 'success'        
    });
});

let el_html = document.querySelector("html");

if (screen.width <= 768) {
	el_html.attributes.class.value = "sidebar-left-collapsed js flexbox flexboxlegacy touch csstransforms csstransforms3d no-overflowscrolling scroll webkit chrome linux js mobile touch mobile-device custom-scroll";
}else if(screen.width > 768 && screen.width <= 1024){
	el_html.attributes.class.value = "fixed sidebar-left-collapsed js flexbox flexboxlegacy no-touch csstransforms csstransforms3d no-overflowscrolling webkit chrome win js no-mobile-device custom-scroll sidebar-left-collapsed";
}else if(screen.width > 1024){
	el_html.attributes.class.value = "fixed js flexbox flexboxlegacy no-touch csstransforms csstransforms3d no-overflowscrolling webkit chrome win js no-mobile-device custom-scroll";
}

$("input[name=nav_search]").keyup(function(){
	let cp = $(this).val();
	//$("ul[class='nav nav-main'] li").hide();
	$("ul[class='nav nav-main'] li").hide();

	if(cp.length < 1){					
		$("ul[class='nav nav-main'] li").show();
		$("ul[class='nav nav-main'] li").removeClass("nav-expanded")
	}else{

		$("ul[class='nav nav-main'] li[data-search*='"+cp+"']").show();		
		$("ul[class='nav nav-main'] li[data-search*='"+cp+"']").parents("li").addClass("nav-expanded").show(); //garante o expand do li acima
		$("ul[class='nav nav-main'] li[data-search*='"+cp+"']").show();
		$("ul[class='nav nav-main'] li[data-search*='"+cp.toUpperCase()+"']").show();
	}
});

/* 
(function($) {

	'use strict';
		

	var updateOutput = function (e) {
		var list = e.length ? e : $(e.target),
			output = list.data('output');

		if (window.JSON) {
			output.val(window.JSON.stringify(list.nestable('serialize')));
		} else {
			output.val('JSON browser support required for this demo.');
		}
	};

	$('#nestable').nestable({
		group: 1
	}).on('change', updateOutput);

	
	$(function() {
		updateOutput($('#nestable').data('output', $('#nestable-output')));
	});

}).apply(this, [jQuery]); */