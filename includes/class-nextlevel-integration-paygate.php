<?php


class NEXTLEVEL_PAYGATE{


	var $_INITIATE 		= null;
	var $_PROCESS 		= null;
	var $_QUERY 		= null;
	var $_PAYGATE_ID 	= null;
	var $_PAYGATE_KEY 	= null;
	var $_CUR 			= null;
	var $_LNG 			= null;


	var $_BOOKING_ID    = null





	var $_S 		= null;
	var $_O 		= null;
	var $_I 		= null;
	var $_E 		= null;
	var $_D 		= null;
	var $_CUR		= null;










	/*
	 CONSTRUCTOR
	 */
	public function __construct($BOOKING){

		$this->_INITIATE 		= get_field('paygate_initiate_url','option');
		$this->_PROCESS 		= get_field('paygate_process_url','option');
		$this->_QUERY 			= get_field('paygate_query_url','option');
		$this->_CUR 			= get_field('paygate_currency_code','option');
		$this->_LNG 			= get_field('paygate_long_country_code','option');
		$_MODE 					= get_field('paygate_mode', 'option');
		$this->_PAYGATE_ID 		= get_field('paygate_'.$_MODE.'_id','option');
		$this->_PAYGATE_KEY 	= get_field('paygate_'.$_MODE.'_encryption_key','option');
		$this->_BOOKING_ID 		= $BOOKING;

		$this->load_booking();


	}









	/*
	 LOAD THE VARIABLES
	 */
	public function load_booking(){

		$this->_D = GFAPI::get_entry($this->_E);

		$this->_S = $this->_D[4];

		$this->_O = get_user_by('id', $this->_S);


	}









	/*
	 WRAPPER FOR HANDLING PAYMENT
	 */
	public function do_payment(){

		$RESULT = $this->do_initiate_booking();

		$this->do_redirect($RESULT);

	}









	/*
	 GENERATE TRANSACTION
	 */
	public function do_initiate_booking(){


		$RETURN = get_field('booking_payment_return_url', 'option');
		$RETURN_URL = get_permalink($RETURN);	

		$SITEURL = get_bloginfo('siteurl');

		$encryptionKey = $this->_PAYGATE_KEY;

		$DateTime = new DateTime();

		$DATA = array(
		    'PAYGATE_ID'        => $this->_PAYGATE_ID,
		    'REFERENCE'         => $this->_D[18],
		    'AMOUNT'            => number_format( $this->_D[19], 2, '', '' ),
		    'CURRENCY'          => $this->_CUR,
		    'RETURN_URL'        => trailingslashit($RETURN_URL).'?gateway=paygate&ref='.$this->_D[18],
		    'TRANSACTION_DATE'  => $DateTime->format('Y-m-d H:i:s'),
		    'LOCALE'            => 'en-za',
		    'COUNTRY'           => $this->_LNG,
		    'EMAIL'             => $this->_D[13],
		    'NOTIFY_URL'		=> trailingslashit($SITEURL).'?grx_return=paygate',
		    'USER1' 			=> $this->_E,
		    'USER2' 			=> $this->_D[18]
		);

		$checksum = md5(implode('', $DATA) . $encryptionKey);

		$DATA['CHECKSUM'] = $checksum;

		if(get_field('payment_gateways_in_debug_mode', 'option') && current_user_can('administrator')):
			echo '<pre>';
			print_r($DATA);
			echo '</pre>';
		endif;

		$fieldsString = http_build_query($DATA);

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $this->_Initiate);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_NOBODY, false);
		curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);

		//execute post
		$RESPONSE = curl_exec($ch);

		//close connection
		curl_close($ch);
		parse_str( $RESPONSE, $RESULT );
        unset( $RESULT['CHECKSUM'] );

        if(get_field('payment_gateways_in_debug_mode', 'option') && current_user_can('administrator')):
	        echo '<pre>';
			print_r($RESPONSE);
			echo '</pre>';

			echo '<pre>';
			print_r($RESULT);
			echo '</pre>';
		endif;

        $CHECKSUM = array(
		    'PAYGATE_ID'     => $this->_I['paygate_id'],
		    'PAY_REQUEST_ID' => $RESULT['PAY_REQUEST_ID'],
		    'REFERENCE'      => $this->_D[18]
		);

        $RESULT['CHECKSUM'] = md5(implode('', $CHECKSUM) . $encryptionKey);


		return $RESULT;


	}









	/*
	 BUILD PAYMENT FORM
	 */
	public function do_redirect($RESULT){

		?>

		<form id="paygatePaymentForm" action="https://secure.paygate.co.za/payweb3/process.trans" method="POST" >
		    <input type="hidden" name="PAY_REQUEST_ID" value="<?php echo $RESULT['PAY_REQUEST_ID']; ?>">
		    <input type="hidden" name="CHECKSUM" value="<?php echo $RESULT['CHECKSUM']; ?>">
		    <?php if(get_field('payment_gateways_in_debug_mode', 'option') && current_user_can('administrator')): ?>

				<input type="submit" value="Pay Now" />
				
			<?php else: ?>

				<script type="text/javascript">
					$(document).ready(function(){
						$("#paygatePaymentForm").submit();
					});
				</script>

			<?php endif; ?>
		</form>

		<?php

	}









	/*
	 NOTIFY HANDLER
	 */
	public static function check_status(){


		if(isset($_GET['grx_return']) && $_GET['grx_return'] == 'paygate' && isset($_POST)):

				
			if(!is_array($_POST)):
				parse_str( $DATA, $_POST );
			else:
				$DATA = $_POST;
			endif;

			if(get_field('payment_gateways_in_debug_mode', 'option') && current_user_can('administrator')):
				ob_start();
				echo '<pre>';
				print_r($DATA);
				echo '</pre>';
				$TEXT = ob_get_clean();
				mail('hilton@kri8it.com', 'paygate debug', $TEXT);
			endif;

			if(isset($DATA['REFERENCE']) && isset($DATA['TRANSACTION_STATUS'])):
			

				$_BOOKING = new GRX__BOOKING($DATA['REFERENCE']);

				if(intval($DATA['TRANSACTION_STATUS']) == 1):
					$_BOOKING->payment_received();

					if(isset($DATA['TRANSACTION_ID']) && isset($DATA['PAY_METHOD'])):
						update_field('cc_gateway', 'PAYGATE', $_BOOKING->O_BOOKING);
						update_field('cc_payment_details', $DATA['TRANSACTION_ID'], $_BOOKING->O_BOOKING);
					endif;

				else:

					if(isset($DATA['RESULT_CODE']) && isset($DATA['RESULT_DESC'])):
						update_field('cc_gateway', 'PAYGATE', $_BOOKING->O_BOOKING);
						update_field('cc_error_code', $DATA['RESULT_CODE'], $_BOOKING->O_BOOKING);
						update_field('cc_error_message', $DATA['RESULT_DESC'], $_BOOKING->O_BOOKING);

					endif;

				endif;

			endif;


		endif;


	}


}

add_action('init', array('GRX__Paygate', 'check_status'));

}


?>