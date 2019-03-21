jQuery(document).ready(function(){



  // Instantiate EasyZoom instances

  var easyzoom = jQuery('.easyzoom').easyZoom();



  // Setup thumbnails example

  var api1 = easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');



  jQuery('.thumbnails').on('click', 'a', function(e) {

    var varthis = jQuery(this);



    e.preventDefault();



    // Use EasyZoom's `swap` method

    api1.swap(varthis.data('standard'), varthis.attr('href'));

  });



  // Setup toggles example

  // var api2 = easyzoom.filter('.easyzoom--with-toggle').data('easyZoom');



  // jQuery('.toggle').on('click', function() {

  //   var varthis = jQuery(this);



  //   if ($this.data("active") === true) {

  //     varthis.text("Switch on").data("active", false);

  //     api2.teardown();

  //   } else {

  //     varthis.text("Switch off").data("active", true);

  //     api2._init();

  //   }

  // });



})