(function( $ ) {
	//'use strict';

  var $_TIMER;

  var $_NOW;

  $(document).ready(function(){

    console.log(nextlevel_params);

      if(nextlevel_params.search_start_num && nextlevel_params.search_end_num){
        $_TIMER = setInterval(nextlevelOrderTimeout, 1000);
      }




      $('#nextlevelPerformSearch').on('click', function(){


        var ajax_data = {
          action: 'nextlevel_ajax_do_search',
          data: $('#nextlevel_search_form').serialize()
        };

        

        jQuery.ajax({
            url: nextlevel_params.ajax_url,
            type:'POST',
            data: ajax_data,
            beforeSend:function(){
              jQuery('#nextlevelLoader').addClass('SHOWING');
            },
            success: function (url) {
              window.location.href = url;
            }
        });

        


      });




      $('.nextlevel_add_to_cart').on('click', function(){

        $_VEHICLE   = $(this).data('vehicle');
        $_ID        = $(this).data('id');
        $_KM        = $(this).data('km');
        $_CODE      = $(this).data('code');
        $_SKU      = $(this).data('sku');


        var ajax_data = {
          action: 'nextlevel_ajax_do_add_to_cart',
          vehicle: $_VEHICLE,
          id: $_ID,
          km: $_KM,
          code: $_CODE,
          sku: $_SKU
        };

        jQuery.ajax({
            url: nextlevel_params.ajax_url,
            type:'POST',
            data: ajax_data,
            beforeSend:function(){
              jQuery('#nextlevelLoader').addClass('SHOWING');
            },
            success: function (url) {
              window.location.href = url;
            }
        });


      });




      $('.carpro-extra-checkbox').find('.input-checkbox').on('change', function(){

         $(document.body).trigger("update_checkout")

      });

      $('#payment_type').on('change', function(){

         $(document.body).trigger("update_checkout")

      });



      

      $('.nextlevelVehicleRateChoice').on('change', function(){


        var $_PRODUCT = $(this).closest('li.product');
        var $_NEW_PRICE = $(this).parent().next().html();
        var $_PRICE = $_PRODUCT.find('span.price');
        var $_BUTTON = $_PRODUCT.find('.nextlevel_add_to_cart');

        $_BUTTON.attr('data-km', $(this).data('km'));
        $_BUTTON.attr('data-code', $(this).data('code'));
        
        $_PRICE.html($_NEW_PRICE).trigger('update');

      });



      $('#nextlevel_search_form').submit(function(event){

        event.preventDefault();
        //* DO AJAX SEARCH / LOAD AND REDIRECT */


        var ajax_data = {
          action: 'nextlevel_ajax_do_search',
          data: $('#nextlevel_search_form').serialize()
        };

        jQuery.ajax({
            url: nextlevel_params.ajax_url,
            type:'POST',
            data: ajax_data,
            beforeSend:function(){
              jQuery('#nextlevelLoader').addClass('SHOWING');
            },
            success: function (url) {
              window.location.href = url;
            }
        });

      });




      /* DATE PICKERS FOR SEARCH FORM */
      var DROPOFFARGS = {
            minDate: parseInt(nextlevel_params.booking_lead_time) + parseInt(nextlevel_params.booking_minimum),
            startDate: parseInt(nextlevel_params.booking_lead_time) + parseInt(nextlevel_params.booking_minimum),
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            onSelect: function(date){
              $_BRANCH = $('#nextlevelInBranch').val();
              nextlevelBranchTimes('InTime', $_BRANCH, date);
            }
        };

      $('#nextlevelInDate').datepicker( DROPOFFARGS );




  		var PICKUPARGS = {
          minDate: parseInt(nextlevel_params.booking_lead_time),
          defaultDate: 0,
          dateFormat: 'yy-mm-dd',
          changeMonth: true,
          changeYear: true,
          onSelect: function(date){
            $min = nextlevel_params.booking_minimum;
            $max = nextlevel_params.booking_maximum;
            
            var selectedDate = new Date(date);
            var msecsInADay = 86400000;

            $_BRANCH = $('#nextlevelOutBranch').val();
            nextlevelBranchTimes('OutTime', $_BRANCH, date);

            if($min > 0){
              var minDate = new Date(selectedDate.getTime() + (msecsInADay*$min));
              $val = $.datepicker.formatDate( 'yy-mm-dd', minDate );

              $('#nextlevelInDate').datepicker( "option", "minDate", minDate );
              $_BRANCH = $('#nextlevelInBranch').val();
              nextlevelBranchTimes('InTime', $_BRANCH, date);

            }

            if(nextlevel_params.booking_maximum > 0){
              $total = parseInt($min) + parseInt($max);
              var maxDate = new Date(selectedDate.getTime() + (msecsInADay*$total));
              $('#nextlevelInDate').datepicker( "option", "maxDate", maxDate );
            }
            
            $('#nextlevelInDate').val($val).trigger('change');
            
          }
        };

      $('#nextlevelOutDate').datepicker( PICKUPARGS );



      $('#nextlevelOutDate').datepicker('setDate', new Date());
      $('#nextlevelInDate').datepicker('setDate', new Date());
      nextlevelBranchTimes('OutTime', $('#nextlevelOutBranch').val(), $('#nextlevelOutDate').val());
      nextlevelBranchTimes('InTime', $('#nextlevelInBranch').val(), $('#nextlevelInDate').val());

      $('#nextlevelOutBranch').on('change', function(){
        nextlevelBranchTimes('OutTime', $(this).val(), $('#nextlevelOutDate').val());
      });

      $('#nextlevelInBranch').on('change', function(){
        nextlevelBranchTimes('InTime', $(this).val(), $('#nextlevelInDate').val());
      });


  });






  function nextlevelBranchTimes($_SELECT, $_BRANCH, $_DATE){


     var ajax_data = {
        action: 'nextlevel_ajax_branch_times',
        branch: $_BRANCH,
        date: $_DATE
      };

      jQuery.ajax({
          url: nextlevel_params.ajax_url,
          type:'POST',
          data: ajax_data,
          beforeSend:function(){
            jQuery('#nextlevelLoader').addClass('SHOWING');
          },
          success: function (items) {
            $('#nextlevel'+$_SELECT).html(items);
            jQuery('#nextlevelLoader').removeClass('SHOWING');
          }
      });

  }




  function nextlevelOrderTimeout(){

    $_NOW = nextlevel_params.search_now_dt +1;

    if($_NOW > nextlevel_params.search_end_num){

      alert('Sorry, your booking time has expired');
      clearInterval($_TIMER);

      var ajax_data = {
        action: 'nextlevel_ajax_reset_search',
      };

       jQuery.ajax({
          url: nextlevel_params.ajax_url,
          type:'POST',
          data: ajax_data,
          beforeSend:function(){
            
          },
          success: function (url) {
            window.location.href = url;
          }
      });

      
    }

    /*

    $_NOW = new Date();
    $_NOW.toLocaleString(nextlevel_params.language, {timeZone: nextlevel_params.timezone});

    $_TIMESTAMP = Math.round($_NOW.getTime()/1000);

    console.log($_TIMESTAMP+' -- '+nextlevel_params.search_end_num);

    */


  }


})( jQuery );
