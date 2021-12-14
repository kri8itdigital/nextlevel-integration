<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.kri8it.com
 * @since      1.0.0
 *
 * @package    Nextlevel_Integration
 * @subpackage Nextlevel_Integration/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nextlevel_Integration
 * @subpackage Nextlevel_Integration/public
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class Nextlevel_Integration_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nextlevel_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nextlevel_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nextlevel-integration-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nextlevel_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nextlevel_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		wp_enqueue_script('jquery/datepicker/js', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array( 'jquery' ), false, false);

		wp_enqueue_style('jquery/datepicker/css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', null, false, false);

		wp_enqueue_script('bootstrap/js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', array(), false, false);

		wp_enqueue_style( 'bootstrap/css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', null, false, false);


		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nextlevel-integration-public.js', array( 'jquery', 'jquery/datepicker/js' ), $this->version, false );

		if(get_option('WPLANG') == ''):
			$_LANGUAGE = 'en_US';
		else:
			$_LANGUAGE = get_option('WPLANG');
		endif;

		$_ARRAY_OF_ARGS = array(
			'ajax_url' 				=> get_bloginfo('url').'/wp-admin/admin-ajax.php',
			'booking_lead_time' 	=> get_field('booking_lead_days','option'),
			'booking_minimum' 		=> get_field('booking_minimum_length','option'),
			'booking_maximum' 		=> get_field('booking_maximum_length','option'),
			//'language'				=> str_replace("_", "-", $_LANGUAGE),
			//'timezone'				=> get_option('timezone_string')
		);

		if(isset(WC()->session)):
			if(WC()->session->get('carpro_search_start')):

				$_SEARCH_ST = WC()->session->get('carpro_search_start');
				$_SEARCH_ET = date('Y-m-d H:i:s', strtotime("+15 minutes", strtotime($_SEARCH_ST)));
				$_ARRAY_OF_ARGS['search_now_dt']    = strtotime(wp_date('Y-m-d H:i:s'));
				$_ARRAY_OF_ARGS['search_start_dt']	= $_SEARCH_ST;
				$_ARRAY_OF_ARGS['search_start_num'] = strtotime($_SEARCH_ST);
				$_ARRAY_OF_ARGS['search_end_dt']	= $_SEARCH_ET;
				$_ARRAY_OF_ARGS['search_end_num'] 	= strtotime($_SEARCH_ET);
			endif;
		endif;


		wp_localize_script( $this->plugin_name, 'nextlevel_params', $_ARRAY_OF_ARGS );

	}










	/* AFTER THEME: REMOVE GENERIC ACTIONS */
	public function after_setup_theme(){

		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
		remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );
	}










	/* WOOCOMMERCE TEMPLATE ACTION  */
	public function woocommerce_before_shop_loop_item(){
		echo '<div class="container"><div class="row">';
	}










	/* WOOCOMMERCE TEMPLATE ACTION */
	public function woocommerce_before_shop_loop_item_title_start(){

		echo '<div class="col-4">';

	}










	/* WOOCOMMERCE TEMPLATE ACTION */
	public function woocommerce_before_shop_loop_item_title_image(){
		global $post;

		echo '<img src="'.get_field('image', $post).'" alt="'.$post->post_title.'" />';
	}










	/* WOOCOMMERCE TEMPLATE ACTION */
	public function woocommerce_before_shop_loop_item_title_end(){
		echo '</div>';
		echo '<div class="col-8">';


	}










	/* WOOCOMMERCE TEMPLATE ACTION */
	public function woocommerce_after_shop_loop_item_title(){

		if(!is_shop() && !is_product_category() && !is_singular('product')):
			NEXTLEVEL_HELPERS::VEHICLE_RATE_OPTIONS();
		endif;

	}










	/* WOOCOMMERCE TEMPLATE ACTION */
	public function woocommerce_after_shop_loop_item(){

		echo '</div></div></div>';

	}










	/* GENERIC: RETURN FALSE */
	public function generic_false_function(){
		return false;
	}










	/* SHORTCODE SETUP */
	public function shortcodes(){


		add_shortcode('nextlevel_search_form', array($this, 'nextlevel_search_form'));

		add_shortcode('nextlevel_search_results', array($this, 'nextlevel_search_results'));

	}










	/* SEARCH FORM SHORT CODE */
	public function nextlevel_search_form($_ATTS){

		$_BRANCHES = NEXTLEVEL_HELPERS::BRANCH_SELECT();
		//$_FIRST_BRANCH = reset($_BRANCHES);
		//$_TIMES = NEXTLEVEL_HELPERS::BRANCH_TIMES($_FIRST_BRANCH, $_DATE);

		ob_start();
		?>

			<div id="nextlevel_search_form_container">
				
				<form id="nextlevel_search_form">
					
					<div class="container">
						<div class="row"> 
							<div class="col-6">
								<select name="OutBranch" id="nextlevelOutBranch">
									
									<?php foreach($_BRANCHES as $_PROVINCE => $_BRANCH): ?>
										<optgroup label="<?php echo $_PROVINCE; ?>">
										<?php foreach($_BRANCH as $_CODE => $_TITLE): ?>
											<option value="<?php echo $_CODE; ?>"><?php echo $_TITLE; ?></option>
										<?php endforeach; ?>
										</optgroup>
									<?php endforeach; ?>

								</select>
							</div>

							<div class="col-3">

								<input type="text" name="OutDate" id="nextlevelOutDate" />
							
							</div>

							<div class="col-3">

								<select name="OutTime" id="nextlevelOutTime"></select>

							</div>

							<div class="col-6">
								<select name="InBranch" id="nextlevelInBranch">
									
									<?php foreach($_BRANCHES as $_PROVINCE => $_BRANCH): ?>
										<optgroup label="<?php echo $_PROVINCE; ?>">
										<?php foreach($_BRANCH as $_CODE => $_TITLE): ?>
											<option value="<?php echo $_CODE; ?>"><?php echo $_TITLE; ?></option>
										<?php endforeach; ?>
										</optgroup>
									<?php endforeach; ?>

								</select>
							</div>

							<div class="col-3">

								<input type="text" name="InDate" id="nextlevelInDate" />
							
							</div>

							<div class="col-3">

								<select name="InTime" id="nextlevelInTime"></select>
							
							</div>
						</div>
						<div class="row"> 
							<a id="nextlevelPerformSearch">Find A Vehicle</a>
						</div>
					</div>


				</form>

			</div>

		<?php
		return ob_get_clean();

	}










	/* SEARCH FORM SHORT CODE */
	public function nextlevel_search_results($_ATTS){

		ob_start();

		if(isset(WC()->session) && WC()->session->get('carpro_includes') && count(WC()->session->get('carpro_includes')) > 0):

			$_PRODUCTS = get_posts(
				array(
					'post_type' => 'product',
					'posts_per_page' => '-1',
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'vehicle_code',
							'value' => WC()->session->get('carpro_includes'),
							'compare' => 'IN'
						)
					)
				)
			);

			if(count($_PRODUCTS) > 0):

				global $post;

				woocommerce_product_loop_start();				
				foreach($_PRODUCTS as $post):

					$_SKU = get_post_meta($post->ID, '_sku', true);

					foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

						if($_CODE == $_SKU && isset($_DATA['vehicle']['rates']) && count($_DATA['vehicle']['rates']) > 0):

							setup_postdata($post);
							wc_get_template_part( 'content', 'product' );

						endif;

					endforeach;
				endforeach;
				woocommerce_product_loop_end();


			else:

				echo '<p>No Availability found for selection</p>';

			endif;

		else:

				echo '<p>No Availability found for selection</p>';

		endif;


		return ob_get_clean();

	}










	/* FILTER PRICE BASED ON AVAILABILITY */
	public function woocommerce_get_price_html($_PRICE, $_PRODUCT){

		if(!is_shop() && !is_product_category() && !is_singular('product')):
			$_SKU = $_PRODUCT->get_sku();
		
			if(isset(WC()->session) && WC()->session->get('carpro_selected_code') == $_SKU):

				return $_PRICE;

			elseif(isset(WC()->session) && WC()->session->get('carpro_availability')):

				/* INITIAL PRICE */			

				foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

					if(trim($_SKU) == trim($_CODE)):

						return $_PRICE;

					endif;

				endforeach;

			endif;
		endif;

		return false;

	}








	/* FILTER/SHOW PRICE BASED ON SEARCH CRITERIA */
	public function woocommerce_product_get_price($_PRICE, $_PRODUCT){

		$_SKU = $_PRODUCT->get_sku();

		if(!is_shop() && !is_product_category() && !is_singular('product')):
	
			if(isset(WC()->session) && WC()->session->get('carpro_selected_sku') == $_SKU):

				$_RATE = WC()->session->get('carpro_selected_rate');
				$_PRICE = $_RATE['total'];
				return $_PRICE;

			elseif(isset(WC()->session) && WC()->session->get('carpro_availability')):

				/* INITIAL PRICE */			

				foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

					if(trim($_SKU) == trim($_CODE)):

						$_FIRST_RATE = reset($_DATA['vehicle']['rates']);
						$_THE_RATE = reset($_FIRST_RATE['rates']);

						$_PRICE = $_THE_RATE['total'];
						return $_PRICE;

					endif;

				endforeach;

			endif;

		endif;

		return false;

	}








	/* PURCHASABLE FUNCTION */
	public function woocommerce_is_purchasable($_PURCHASE, $_PRODUCT){

		$_SKU = $_PRODUCT->get_sku();

		if(!is_shop() && !is_product_category() && !is_singular('product')):
			if(isset(WC()->session) && WC()->session->get('carpro_selected_sku') == $_SKU):
				return true;

			elseif(isset(WC()->session) && WC()->session->get('carpro_availability')):

				foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

					if(trim($_SKU) == trim($_CODE)):

						return true;

					endif;

				endforeach;

			endif;

		endif;

		return false;


	}










	/* ADD EXTRAS AND NOTES AT END OF CHECKOUT */
	public function woocommerce_before_order_notes($_CHECKOUT){

		$_CART_ITEMS = WC()->cart->get_cart();
		$_FIRST = reset($_CART_ITEMS);
		$_SKU = $_FIRST['data']->get_sku();


		echo '<div id="checkout_express"><h3>' . __('For an express collection, please complete below') . '</h2>';

			woocommerce_form_field( 'license_number', array(
		    	'type'          => 'text',
		    	'class'         => array('carpro-license-number form-row-wide'),
		    	'label'         => __('License Number'),
		    ), $_CHECKOUT->get_value( 'license_number' ));

			woocommerce_form_field( 'license_expiry', array(
		    	'type'          => 'date',
		    	'class'         => array('carpro-license-expiry form-row-wide'),
		    	'label'         => __('License Expiry'),
		    ), $_CHECKOUT->get_value( 'license_expiry' ));

		echo '</div>';


		if(isset(WC()->session) && WC()->session->get('carpro_available_extras_once')):
			$_ONCE = WC()->session->get('carpro_available_extras_once');
			echo '<div id="checkout_extras_once_off"><h3>' . __('Once Off Extras') . '</h2>';

				foreach($_ONCE as $_KEY => $_DATA):

					if(isset($_DATA['perday'])):
						$_VALUE_TO_USE = $_DATA['perday'];
					else:
						$_VALUE_TO_USE = $_DATA['total'];
					endif;

					 woocommerce_form_field( 'carpro_extra_'.$_KEY, array(
				        'type'          => 'checkbox',
				        'class'         => array('carpro-extra-checkbox form-row-wide'),
				        'label'         => __($_DATA['title'].' <small>Charged at '.wc_price($_VALUE_TO_USE).'</small>'),
				        ), NEXTLEVEL_HELPERS::ISEXTRASELECTED($_KEY));

				endforeach;

			echo '</div>';

		endif;


		if(isset(WC()->session) && WC()->session->get('carpro_available_extras_daily')):
			$_DAILY = WC()->session->get('carpro_available_extras_daily');
			echo '<div id="checkout_extras_daily"><h3>' . __('Per Day Extras') . '</h2>';

			foreach($_DAILY as $_KEY => $_DATA):

					if(isset($_DATA['perday'])):
						$_VALUE_TO_USE = $_DATA['perday'];
					else:
						$_VALUE_TO_USE = $_DATA['total'];
					endif;

					 woocommerce_form_field( 'carpro_extra_'.$_KEY, array(
				        'type'          => 'checkbox',
				        'class'         => array('carpro-extra-checkbox form-row-wide'),
				        'label'         => __($_DATA['title'].' <small>Charged at '.wc_price($_VALUE_TO_USE).' per day</small>'),
				        ), NEXTLEVEL_HELPERS::ISEXTRASELECTED($_KEY));

				endforeach;
			echo '</div>';
		endif;


		echo '<div id="checkout_travel_information"><h3>' . __('Travel Information (Optional)') . '</h2>';

			woocommerce_form_field( 'arrival_flight_number', array(
		    	'type'          => 'text',
		    	'class'         => array('arrival-flight-number form-row-wide'),
		    	'label'         => __('Arrival Flight Number'),
		    ), $_CHECKOUT->get_value( 'arrival_flight_number' ));

		echo '</div>';


		echo '<div id="checkout_payment"><h3>' . __('Payment Type') . '</h2>';

			$_BILLING_ARRAY = array(
				'full' => 'Pay In Full',
				'50deposit' => 'Pay 50% Deposit',
				'25deposit' => 'Pay 25% Deposit'
			);


			woocommerce_form_field( 'payment_type', array(
		    	'type'          => 'select',
		    	'required'  	=> true,
		    	'options'		=> $_BILLING_ARRAY,
		    	'class'         => array('payment-type form-row-wide'),
		    	'label'         => __('Payment Type'),
		    ), WC()->session->get('carpro_deposit_type'));

		echo '</div>';

	}










	/* EDIT THE ADD TO CART LINK FOR OUR OWN DEVICES */
	public function woocommerce_loop_add_to_cart_link($_LINK, $_PRODUCT, $_ARGS){
		
		$_ID = $_PRODUCT->get_id();
		$_SKU = $_PRODUCT->get_sku();

		if(!is_shop() && !is_product_category() && !is_singular('product')):
			$_CODE = NEXTLEVEL_HELPERS::VEHICLE_CODE($_SKU);
			$_RATE = NEXTLEVEL_HELPERS::VEHICLE_FIRST_RATE($_SKU);

			if($_RATE):

				$_LINK = '<a class="nextlevel_add_to_cart" data-sku="'.$_SKU.'" data-id="'.$_ID.'" data-vehicle="'.$_CODE.'" data-km="" data-code="'.$_RATE['code'].'">Book Vehicle</a>';
			endif;
		endif;

		return $_LINK;


	}










	/* FOOTER FUNCTION FOR LOADER */
	public function wp_footer(){
		?>

		<style type="text/css">
			#nextlevelLoader{
				background-color: <?php the_field('overlay_background_colour', 'option'); ?>;
				background-image:url( <?php the_field('overlay_loading_gif', 'option'); ?>);				
			}
		</style>

		<div id="nextlevelLoader"></div>

		<?php
	}










	/* BEFORE CHECKOUT FORM - CURRENTLY DEBUG */
	public function woocommerce_before_checkout_form(){

		if(get_field('nextlevel_debug_session', 'option')):
			echo '<pre>';
			print_r(WC()->session);
			echo '</pre>';
		endif;

	}










	/* SHOW ITEM META FOR PRODUCT - SELECTED KM ETC */
	public function woocommerce_get_item_data($item_data, $cart_item){

		$_DAYS = WC()->session->get('carpro_days');

		if(WC()->session->get('carpro_out_branch')):
			$item_data['out-branch'] = array('name' => 'Out Branch', 'display'=> WC()->session->get('carpro_out_branch'));
		endif;

		if(WC()->session->get('carpro_out_date')):
			$item_data['out-date'] = array('name' => 'Out Date', 'display'=> WC()->session->get('carpro_out_date'));
		endif;

		if(WC()->session->get('carpro_out_time')):
			$item_data['out-time'] = array('name' => 'Out Time', 'display'=> WC()->session->get('carpro_out_time'));
		endif;

		if(WC()->session->get('carpro_in_branch')):
			$item_data['in-branch'] = array('name' => 'In Branch', 'display'=> WC()->session->get('carpro_in_branch'));
		endif;

		if(WC()->session->get('carpro_in_date')):
			$item_data['in-date'] = array('name' => 'In Date', 'display'=> WC()->session->get('carpro_in_date'));
		endif;

		if(WC()->session->get('carpro_in_time')):
			$item_data['in-time'] = array('name' => 'In Time', 'display'=> WC()->session->get('carpro_in_time'));
		endif;

		if(WC()->session->get('carpro_selected_vehicle')):
			$item_data['selected-vehicle'] = array('name' => 'Selected Vehicle', 'display'=> WC()->session->get('carpro_selected_vehicle'));
		endif;

		if(WC()->session->get('carpro_selected_km')):
			$item_data['km-option'] = array('name' => 'KM Option', 'display'=> WC()->session->get('carpro_selected_km'));
		endif;

		if(WC()->session->get('carpro_selected_rate')):
			$_RATE = WC()->session->get('carpro_selected_rate');

			$item_data['cover-option'] = array('name' => 'Cover Option', 'display'=> $_RATE['title']);
			$item_data['cover-deposit'] = array('name' => 'Cover Deposit', 'display'=> wc_price($_RATE['deposit']));
			$item_data['cover-liability'] = array('name' => 'Cover Liability', 'display'=> wc_price($_RATE['liability']));
		endif;

		return $item_data;
	}















	/* ADDS EXTRAS ETC TO CHECKOUT */
	public function woocommerce_cart_calculate_fees(){

		if ( is_admin() && ! defined( 'DOING_AJAX' ) || ! is_checkout() ):
			return;
		endif;

		// Only trigger this logic once.
		if ( did_action( 'woocommerce_cart_calculate_fees' ) >= 2 ):
			return;
		endif;

		$_FEE_TOTAL = 0;

		if ( isset( $_POST['post_data'] ) ):

			/* FIRST WE NEED TO GROUP TOGETHER ALL THE SELECTED EXTRAS */
			$_ONCE = false;
			$_DAILY = false;

			$_SELECTED_ONCE = array();
			$_SELECTED_DAILY = array();

			if(isset(WC()->session) && WC()->session->get('carpro_available_extras_once')):
				$_ONCE = WC()->session->get('carpro_available_extras_once');
			endif; 

			if(isset(WC()->session) && WC()->session->get('carpro_available_extras_daily')):
				$_DAILY = WC()->session->get('carpro_available_extras_daily');
			endif; 

			parse_str( $_POST['post_data'], $_CO_FIELDS );

			foreach($_CO_FIELDS as $_KEY => $_VALUE):

				if(strstr($_KEY, 'carpro_extra') && (int)$_VALUE == 1):

					$_EXTRA_CODE = str_replace("carpro_extra_", "", $_KEY);
					
					if($_ONCE):

						foreach($_ONCE as $_CC => $_VALUES):

							if(trim($_CC) == trim($_EXTRA_CODE)):
								$_SELECTED_ONCE[$_CC] = $_VALUES;
							endif;

						endforeach;

						if(count($_SELECTED_ONCE) > 0):
							WC()->session->set('carpro_selected_once_extras', $_SELECTED_ONCE);
						endif;

					endif;

					if($_DAILY):						

						foreach($_DAILY as $_CC => $_VALUES):

							if(trim($_CC) == trim($_EXTRA_CODE)):

								$_SELECTED_DAILY[$_CC] = $_VALUES;

							endif;

						endforeach;

						if(count($_SELECTED_DAILY) > 0):
							WC()->session->set('carpro_selected_daily_extras', $_SELECTED_DAILY);
						endif;
					endif;

				endif;

			endforeach;	

		endif;

		if(WC()->session->get('carpro_selected_once_extras') && count(WC()->session->get('carpro_selected_once_extras')) > 0):

			foreach(WC()->session->get('carpro_selected_once_extras') as $_CC => $_VALUES):

				if(isset($_VALUES['perday'])):
					$_AMT = $_VALUES['perday'];
				else:
					$_AMT = $_VALUES['total'];
				endif;

				WC()->cart->add_fee( $_VALUES['title'], $_AMT, true, '' );
				$_FEE_TOTAL += $_AMT;
			endforeach;

		endif;

		if(isset(WC()->session) && WC()->session->get('carpro_selected_daily_extras') && 
			count(WC()->session->get('carpro_selected_daily_extras')) > 0):
			$_DAYS = WC()->session->get('carpro_days');

			foreach(WC()->session->get('carpro_selected_daily_extras') as $_CC => $_VALUES):

				if(isset($_VALUES['perday'])):
					$_AMT = (float)$_VALUES['perday']*(float)$_DAYS;
				else:
					$_AMT = (float)$_VALUES['total']*(float)$_DAYS;
				endif;

				WC()->cart->add_fee( $_VALUES['title'], $_AMT, true, '' );
				$_FEE_TOTAL += $_AMT;
			endforeach;

		endif;


		if(isset(WC()->session) && WC()->session->get('carpro_fees')):

			$_FEES = WC()->session->get('carpro_fees');

			foreach($_FEES as $_CODE => $_DATA):
				WC()->cart->add_fee( $_DATA['title'], $_DATA['amt'], true, '' );
				$_FEE_TOTAL += $_DATA['amt'];
			endforeach;

		endif;

		$_CART_TOTAL = WC()->cart->cart_contents_total;
		$_CART_TOTAL += $_FEE_TOTAL;
		$_ADD = false;
		$_DEP = false;
		
		if ( isset( $_POST['post_data'] ) ):
			parse_str( $_POST['post_data'], $_CO_FIELDS );

			if(isset($_CO_FIELDS['payment_type'])):		

				switch($_CO_FIELDS['payment_type']):
					case '50deposit':
						$_DEP = '50%';
						$_ADD = true;
					break;

					case '25deposit':
						$_DEP = '25%';
						$_ADD = true;
					break;

					default:

						$_DEP = 0;

					break;

				endswitch;
				WC()->session->set('carpro_deposit_type', $_CO_FIELDS['payment_type']);
				WC()->session->set('carpro_deposit_percentage', $_DEP);
			endif; 

		else:

			if(WC()->session->get('carpro_deposit_percentage')):
				$_DEP = WC()->session->get('carpro_deposit_percentage');

				if($_DEP != 0):
					$_ADD = true;
				endif;

			endif;

		endif;

		if($_DEP != 0 && $_ADD):

			$_AMT = 0;

			switch($_DEP):

				case "50%":
					$_TITLE = '50% Deposit';
					$_PERCENT = 50;
					$_AMT = (($_PERCENT / 100) * $_CART_TOTAL);
					$_DAMT = $_CART_TOTAL-$_AMT;
					$_AMT = number_format($_AMT, 2, ".", "");
				break;

				case "25%":
					$_TITLE = '25% Deposit';
					$_PERCENT = 75;
					$_AMT = (($_PERCENT / 100) * $_CART_TOTAL);
					$_DAMT = $_CART_TOTAL-$_AMT;
					$_AMT = number_format($_AMT, 2, ".", "");
				break;

				default:
					$_AMT = 0;
					$_OUT = 0;
					$_DAMT = 0;
				break;

			endswitch;

			WC()->session->set('carpro_deposit_amount', $_DAMT);

			if($_AMT > 0):
				WC()->cart->add_fee( $_TITLE, $_AMT*-1, true, '' );
			endif;

		endif;
		
		
		
	}






	public function woocommerce_checkout_create_order_line_item($item, $cart_item_key, $values, $order){

		if(WC()->session->get('carpro_out_branch')):
			$item->update_meta_data( 'Out Branch', WC()->session->get('carpro_out_branch'));
		endif;

		if(WC()->session->get('carpro_out_date')):
			$item->update_meta_data( 'Out Date', WC()->session->get('carpro_out_date'));
		endif;

		if(WC()->session->get('carpro_out_time')):
			$item->update_meta_data( 'Out Time', WC()->session->get('carpro_out_time'));
		endif;

		if(WC()->session->get('carpro_in_branch')):
			$item->update_meta_data( 'In Branch', WC()->session->get('carpro_in_branch'));
		endif;

		if(WC()->session->get('carpro_in_date')):
			$item->update_meta_data( 'In Date', WC()->session->get('carpro_in_date'));
		endif;

		if(WC()->session->get('carpro_in_time')):
			$item->update_meta_data( 'In Time', WC()->session->get('carpro_in_time'));
		endif;

		if(WC()->session->get('carpro_selected_vehicle')):
		 	$item->update_meta_data( 'Selected Vehicle', WC()->session->get('carpro_selected_vehicle') );
		endif;

		if(WC()->session->get('carpro_selected_km')):
		 	$item->update_meta_data( 'KM Option', WC()->session->get('carpro_selected_km') );
		endif;

		if(WC()->session->get('carpro_selected_rate')):
			$_RATE = WC()->session->get('carpro_selected_rate');
			$item->update_meta_data( 'Cover Option', $_RATE['title'] );
			$item->update_meta_data( 'Deposit', wc_price($_RATE['deposit']) );
			$item->update_meta_data( 'Liability', wc_price($_RATE['liability']) );
		endif;

	}










	/* WOOCOMMERCE TEMPLATE OVERRIDE */
	public function woocommerce_locate_template($template, $template_name, $template_path){

		$_PLUGIN_PATH = trailingslashit(trailingslashit(ABSPATH).'wp-content/plugins/nextlevel-integration/woocommerce');

		$_NEW_FILE = $_PLUGIN_PATH.$template_name;

		if(file_exists($_NEW_FILE)):
			$template = $_NEW_FILE;
		endif;

		return $template;
	}







	public function woocommerce_order_status_completed($_ORDER_ID){
		NEXTLEVEL::DORESERVATION($_ORDER_ID);
		NEXTLEVEL_HELPERS::CLEAR_CARPRO();

	}







	public function woocommerce_thankyou($_ORDER_ID){
		NEXTLEVEL_HELPERS::CLEAR_CARPRO();
	}





	public function woocommerce_checkout_fields($_FIELDS){


		$_FIELDS['billing']['billing_id_passport'] = array(
		    'label'     => __('ID/Passport Number', 'woocommerce'),
		    'type'		=> 'text',
		    'required'  => true,
		    'class'     => array('form-row-wide', 'billing_id_number'),
		    'clear'     => true,
		    'priority'  => 25
		);


		return $_FIELDS;



	}





	public function woocommerce_return_to_shop_redirect($_URL){

		$_URL = get_field('search_results_page', 'option');

		return $_URL;

	}





}
