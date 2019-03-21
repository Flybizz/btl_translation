/*
Name: 			Tables / Advanced - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	2.0.0
*/

(function($) {

	'use strict';

	var datatableInit = function() {
		var $table = $('#datatable-tabletools');

		var table = $table.dataTable({
			/*"retrieve": true,
			"pagingType": "full_numbers",*/
			pageLength: 20,
			lengthMenu: [20, 50, 100],
			dom: '<lfi<t>p>',
			autoWidth: false,
			//"columns": columnsTable, <-- options problem
			columnDefs: [
				{
				   targets: 0,
				   checkboxes: {
					  selectRow: true,
					  selectCallback: function(nodes, selected){
						 // If "Show all" is not selected
						 if($('select[name=check_lead]').val() !== 'all'){
							// Redraw table to include/exclude selected row
							table.draw(false);                  
						 }            
					  }
				   },
				}
			 ],
			select: 'os',
			order: [[0, 'asc']],
			sDom: '<"text-right mb-md"T><"row"<"col-lg-6"l><"col-lg-6"f>><"table-responsive"t>p',
			buttons: [ 'print', 'excel', 'pdf' ]
		});

		$('<div />').addClass('dt-buttons mb-2 pb-1 text-right').prependTo('#datatable-tabletools_wrapper');

		table.DataTable().buttons().container().prependTo( '#datatable-tabletools_wrapper .dt-buttons' );

		$('#datatable-tabletools_wrapper').find('.btn-secondary').removeClass('btn-secondary').addClass('btn-default');
	};

	$(function() {
		datatableInit();
	});

}).apply(this, [jQuery]);
