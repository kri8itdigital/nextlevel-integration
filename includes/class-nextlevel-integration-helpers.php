<?php


class NEXTLEVEL_HELPERS{









	/* GET ORDER TOTAL */
	public static function GET_ORDER_TOTAL($_ORDER){


		$_TOTAL = number_format($_ORDER->get_subtotal(), 2, ".", "");

		$_FEES = $_ORDER->get_fees();

		$_FEE_AMT = 0;

		foreach($_FEES as $_FEE):

			$_AMT = $_FEE->get_total();

			if($_AMT > 0):
				$_FEE_AMT += $_AMT;
			endif;

		endforeach;

		return $_TOTAL+$_FEE_AMT;


	}









	/* IS VEHICLE ENABLED - SO THAT WE CAN SHOW IT */
	public static function IS_AVAILABLE($_CODE){

		$_ARGS = array(
			'post_type' => 'product',
			'posts_per_page' => '1',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'vehicle_code',
					'value' => $_CODE
				),
				array(
					'key' => 'enabled',
					'value' => 1
				),
			) 
		);

		$_AVAILABLE = array();


		$_VEHICLES = get_posts($_ARGS);

		if(count($_VEHICLES) == 1):
			$_VEHICLES = reset($_VEHICLES);
			return $_VEHICLES;
		endif;

		return $_AVAILABLE;


		
	}









	/* FETCH BRANCHES */
	public static function BRANCH_SELECT(){
	
		$_ARGS = array(
			'post_type' => 'branch',
			'posts_per_page' => '-1',
			'meta_key' => 'enabled',
			'meta_value' => 1
		);	

		$_BRANCHES = get_posts($_ARGS);

		$_SELECTION = array();

		foreach($_BRANCHES as $_BRANCH):

			$_CODE = get_field('carpro_branch_code', $_BRANCH);
			$_TITLE = $_BRANCH->post_title;
			$_PROVINCE = get_field('province', $_BRANCH);

			$_SELECTION[$_PROVINCE][$_CODE] = $_TITLE;

		endforeach;

		return $_SELECTION;

	}









	/* BRANCH TIMES */
	public static function BRANCH_TIMES_SELECT($_CODE, $_DATE, $_DAY){

		$_BRANCH = self::GET_BRANCH_FROM_CODE($_CODE);

		if(self::IS_PUBLIC_HOLIDAY($_DATE)):

			$_TIMES = get_field('public_holidays', $_BRANCH);

		else:

			$_TIMES = get_field($_DAY, $_BRANCH);

		endif;

		/* CLOSED / BY APPOINTEMENT */
		if(strtolower($_TIMES) == 'closed'):
			return false;
		elseif(strtolower($_TIMES) == 'by appointment'):
			return false;
		else:

			$_TIMES = explode('-', $_TIMES);

			$_START_TIME    = $_TIMES[0];
			$_END_TIME 	    = $_TIMES[1];
			$_START_TIME    = strtotime ($_START_TIME); //change to strtotime
			$_END_TIME      = strtotime ($_END_TIME);

			//30 minutes
			$_ADD = 1800;

			$_SELECTION = array();

			while($_START_TIME <= $_END_TIME):

				$_SELECTION[] = date ("h:i", $_START_TIME);
	   			$_START_TIME += $_ADD; 

			endwhile;

			return $_SELECTION;

		endif;

	}









	/* IS PARTICULAR DAY A PUBLIC HOLIDAY */
	public static function IS_PUBLIC_HOLIDAY($_DATE){


		$_PH = get_posts(
			array(
				'post_type' => 'publicholiday',
				'posts_per_page' => 1,
				'meta_key' => 'date',
				'meta_value' => wp_date('Y-m-d', strtotime($_DATE))
			)
		);

		if(count($_PH) >= 1):
			return true;
		endif;

		return false;

	}









	/* GET BRANCH FROM CODE */
	public static function GET_BRANCH_FROM_CODE($_CODE){

		$_BRANCH = get_posts(
			array(
				'post_type' => 'branch',
				'posts_per_page' => 1,
				'meta_key' => 'carpro_branch_code',
				'meta_value' => $_CODE
			)
		);

		if(count($_BRANCH) == 1):
			return reset($_BRANCH);
		endif;

		return false;

	}









	/* GET FIRST RATE FOR THRIFTY */
	public static function VEHICLE_FIRST_RATE($_SKU){

		if(WC()->session->get('carpro_availability')):
			foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

				if(trim($_SKU) == trim($_CODE)):

					$_FIRST_RATE = reset($_DATA['vehicle']['rates']);
					$_THE_RATE = reset($_FIRST_RATE['rates']);
					return $_THE_RATE;

				endif;

			endforeach;
		endif;
	}









	/* GET VEHICLE INTERNATIONAL CODE */
	public static function VEHICLE_CODE($_SKU){

		if(WC()->session->get('carpro_availability')):
			foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

				if(trim($_SKU) == trim($_CODE)):

					return $_DATA['vehicle']['international'];

				endif;

			endforeach;
		endif;
	}









	/* BUILD RATE OPTIONS */
	public static function VEHICLE_RATE_OPTIONS(){
		global $product;

		$_VEHICLE_CODE = $product->get_sku();

		if(isset(WC()->session) && WC()->session->get('carpro_availability')):


			foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

				if(trim($_VEHICLE_CODE) == trim($_CODE)):

					$_COUNTER = 0;

					?> <form class="nextlevelVehicleForm"> <?php
					$_RATES = $_DATA['vehicle']['rates'];
					
					foreach($_RATES as $_KM => $_INFO):

						$_KM_RATES = $_INFO['rates'];
						//echo '<pre>'; print_r($_KM_RATES); echo '</pre>';
						?>
						<div class="container nextlevelVehicleRateBlock">
							<div class="row">
								<div class="col-md-4"><h4><?php echo $_KM; ?></h4></div>
							
							
							<?php foreach($_KM_RATES as $_KMR): ?>
								<div class="col-md-4">
									<div class="nextlevelInputItem">
									<input name="<?php echo $_VEHICLE_CODE; ?>_rate_choice" class="nextlevelVehicleRateChoice" <?php if($_COUNTER == 0): ?> checked <?php endif; ?> 
									data-id="<?php echo $product->get_id(); ?>" 
									data-code="<?php echo $_KMR['code']; ?>" 
									data-total="<?php echo $_KMR['total']; ?>" 
									data-km="<?php echo $_KM; ?>" 
									type="radio" /> <?php echo $_KMR['title']; ?><br/>
									<small>Liability: </small><?php echo $_KMR['liability']; ?>
									</div>
									<div class="nextlevelPriceItem hidden"><?php echo wc_price($_KMR['total']);?></div>
								</div>

								<?php $_COUNTER++; ?>
							<?php endforeach; ?>

							</div>
						</div>
						<?php

					endforeach;

					?> </form> <?php

				endif;

			endforeach;

		endif;

	}









	/* CLEAR ALL THE CARPRO STUFF FROM THE SESSION */
	public static function CLEAR_CARPRO(){

		WC()->session->__unset('carpro_search_start');
		WC()->session->__unset('carpro_availability');
		WC()->session->__unset('carpro_selected_vehicle');
		WC()->session->__unset('carpro_selected_sku');
		WC()->session->__unset('carpro_selected_km');
		WC()->session->__unset('carpro_selected_code');
		WC()->session->__unset('carpro_selected_rate');
		WC()->session->__unset('carpro_available_extras');
		WC()->session->__unset('carpro_fees');
		WC()->session->__unset('carpro_available_extras_daily');
		WC()->session->__unset('carpro_available_extras_once');
		WC()->session->__unset('carpro_includes');
		WC()->session->__unset('carpro_days');
		WC()->session->__unset('carpro_selected_once_extras');
		WC()->session->__unset('carpro_selected_daily_extras');
		WC()->session->__unset('carpro_out_branch');
		WC()->session->__unset('carpro_out_date');
		WC()->session->__unset('carpro_out_time');
		WC()->session->__unset('carpro_in_branch');
		WC()->session->__unset('carpro_in_date');
		WC()->session->__unset('carpro_in_time');
		WC()->session->__unset('carpro_nothing_found');
		WC()->session->__unset('carpro_deposit_percentage');
		WC()->session->__unset('carpro_deposit_amount');
		WC()->session->__unset('carpro_deposit_type');

	}









	/* CHECK SESSION IF AN EXTRA HAS BEEN SELECTED */
	public static function ISEXTRASELECTED($_KEY){

		$_SELECTED = 0;

		if(WC()->session->get('carpro_selected_once_extras')):

			$_EXTRAS = WC()->session->get('carpro_selected_once_extras');
			foreach($_EXTRAS as $_CC => $_DATA):

				if(strtoupper($_KEY) == strtoupper($_CC)):
					$_SELECTED = 1;
				endif;

			endforeach;

		endif;

		if(WC()->session->get('carpro_selected_daily_extras')):
			$_EXTRAS = WC()->session->get('carpro_selected_daily_extras');
			foreach($_EXTRAS as $_CC => $_DATA):

				if(strtoupper($_KEY) == strtoupper($_CC)):
					$_SELECTED = 1;
				endif;

			endforeach;
		endif;


		return $_SELECTED;

	}



}



?>