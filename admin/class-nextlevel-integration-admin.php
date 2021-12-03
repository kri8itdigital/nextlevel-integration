<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.kri8it.com
 * @since      1.0.0
 *
 * @package    Nextlevel_Integration
 * @subpackage Nextlevel_Integration/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nextlevel_Integration
 * @subpackage Nextlevel_Integration/admin
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class Nextlevel_Integration_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nextlevel-integration-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nextlevel-integration-admin.js', array( 'jquery' ), $this->version, false );

	}










	/* HTTP TIMEOUT */
	public function http_request_timeout(){
		return 600;
	}









	/* Add Additional Cron Timings */
	public function cron_schedules($schedules){

		
		$schedules['twohours'] = array(
	        'interval' => 7200,
	        'display'  => esc_html__( 'Every Two Hours' ),
	    );

		$schedules['fiveminutes'] = array(
	        'interval' => 300,
	        'display'  => esc_html__( 'Every Five Minutes' ),
	    );

		return $schedules;
		
	}










	/* Setup Cron Schedules */
	public function setup_cron_schedules(){

		$_INTERVAL = 'fiveminutes';

		if (! wp_next_scheduled( 'nextlevel_clean_logs_action')):

			wp_schedule_event(time(), 'daily', 'nextlevel_clean_logs_action');

		endif;

		if(get_field('nextlevel_sync_branches', 'option')):

			if (! wp_next_scheduled( 'nextlevel_sync_branch_action')):

				wp_schedule_event(time(), $_INTERVAL, 'nextlevel_sync_branch_action');

			endif;

		else:

			if (wp_next_scheduled( 'nextlevel_sync_branch_action')):

				wp_clear_scheduled_hook('nextlevel_sync_branch_action');
				
			endif;

		endif;


		if(get_field('nextlevel_sync_vehicles', 'option')):
		
			if (! wp_next_scheduled( 'nextlevel_sync_vehicle_action')):

				wp_schedule_event(time(), $_INTERVAL, 'nextlevel_sync_vehicle_action');

			endif;

		else:

			if (wp_next_scheduled( 'nextlevel_sync_vehicle_action')):

				wp_clear_scheduled_hook('nextlevel_sync_vehicle_action');
				
			endif;

		endif;
		
		if (! wp_next_scheduled( 'nextlevel_sync_publicholiday_action')):

			wp_schedule_event(time(), $_INTERVAL, 'nextlevel_sync_publicholiday_action');

		endif;

		
	}










	/* ACF Custom Fields */
	public function custom_fields(){

		if( function_exists('acf_add_options_page') ) {
  
		   $main_menu = acf_add_options_page(array(
		    'page_title'  => 'NEXTLEVEL',
		    'menu_title'  => 'NEXTLEVEL',
		    'icon_url' => 'dashicons-admin-site-alt'
		  ));

		}


		if( function_exists('acf_add_local_field_group') ):

			acf_add_local_field_group(array(
				'key' => 'group_5ea95862013be',
				'title' => 'BRANCH CONTROL',
				'fields' => array(
					array(
						'key' => 'field_618d05b1de916',
						'label' => 'Enabled',
						'name' => 'enabled',
						'type' => 'true_false',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'message' => '',
						'default_value' => 0,
						'ui' => 0,
						'ui_on_text' => '',
						'ui_off_text' => '',
					),
					array(
						'key' => 'field_5ea976de3428e',
						'label' => 'CarPro Branch Code',
						'name' => 'carpro_branch_code',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617133ca24268',
						'label' => 'Contact Person',
						'name' => 'contact_person',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617133d924269',
						'label' => 'Contact Email',
						'name' => 'contact_email',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617133fc2426a',
						'label' => 'Contact Number',
						'name' => 'contact_number',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617134062426b',
						'label' => 'Address',
						'name' => 'address',
						'type' => 'wysiwyg',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'tabs' => 'all',
						'toolbar' => 'full',
						'media_upload' => 0,
						'delay' => 0,
					),
					array(
						'key' => 'field_617134152426c',
						'label' => 'Province',
						'name' => 'province',
						'type' => 'select',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'Eastern Cape',
							'Free State',
							'Gauteng',
							'KwaZulu-Natal',
							'Limpopo',
							'Mpumalanga',
							'Northern Cape',
							'North West',
							'Western Cape'
						),
						'default_value' => false,
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'return_format' => 'value',
						'ajax' => 0,
						'placeholder' => '',
					),
					array(
						'key' => 'field_6171341e2426d',
						'label' => 'City',
						'name' => 'city',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617135592426e',
						'label' => 'Logitude',
						'name' => 'logitude',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617135902426f',
						'label' => 'Latitude',
						'name' => 'latitude',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_6171359e24270',
						'label' => 'Monday',
						'name' => 'monday',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617135a524271',
						'label' => 'Tuesday',
						'name' => 'tuesday',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617135ab24272',
						'label' => 'Wednesday',
						'name' => 'wednesday',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617135b024273',
						'label' => 'Thursday',
						'name' => 'thursday',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617135b424274',
						'label' => 'Friday',
						'name' => 'friday',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617135b824275',
						'label' => 'Saturday',
						'name' => 'saturday',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617135bd24276',
						'label' => 'Sunday',
						'name' => 'sunday',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_617135c324277',
						'label' => 'Public Holidays',
						'name' => 'public_holidays',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'branch',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => true,
				'description' => '',
			));

			acf_add_local_field_group(array(
				'key' => 'group_6179298a0fa12',
				'title' => 'PUBLIC HOLIDAY CONTROL',
				'fields' => array(
					array(
						'key' => 'field_61792995df916',
						'label' => 'Country Code',
						'name' => 'country_code',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_6179299cdf917',
						'label' => 'Date',
						'name' => 'date',
						'type' => 'date_picker',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'display_format' => 'Y-m-d',
						'return_format' => 'Y-m-d',
						'first_day' => 1,
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'publicholiday',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => true,
				'description' => '',
			));

			acf_add_local_field_group(array(
			'key' => 'group_5ea9586e7b388',
			'title' => 'VEHICLE CONTROL',
			'fields' => array(
				array(
					'key' => 'field_5ea97c3d5d5d2',
					'label' => 'Vehicle Code',
					'name' => 'vehicle_code',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5ea97c485d5d3',
					'label' => 'Enabled',
					'name' => 'enabled',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'field_619cc251579c3',
					'label' => 'Image',
					'name' => 'image',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5ea97c595d5d4',
					'label' => 'On Promotion',
					'name' => 'on_promotion',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'field_5ea97c6c5d5d6',
					'label' => 'Transmission',
					'name' => 'transmission',
					'type' => 'select',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'manual' => 'Manual',
						'automatic' => 'Automatic',
					),
					'default_value' => false,
					'allow_null' => 0,
					'multiple' => 0,
					'ui' => 0,
					'return_format' => 'value',
					'ajax' => 0,
					'placeholder' => '',
				),
				array(
					'key' => 'field_5ea97caa5d5d9',
					'label' => 'Fuel',
					'name' => 'fuel',
					'type' => 'select',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'petrol' => 'Petrol',
						'diesel' => 'Diesel',
					),
					'default_value' => false,
					'allow_null' => 0,
					'multiple' => 0,
					'ui' => 0,
					'return_format' => 'value',
					'ajax' => 0,
					'placeholder' => '',
				),
				array(
					'key' => 'field_5ea97c635d5d5',
					'label' => 'Air Conditioning',
					'name' => 'air_conditioning',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'field_5ea97c925d5d7',
					'label' => 'Airbags',
					'name' => 'airbags',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'field_5ea97c9f5d5d8',
					'label' => 'Cross Border Allowed',
					'name' => 'cross_border_allowed',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'field_619ca637139f6',
					'label' => '4 X 2',
					'name' => '4_x_2',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'field_619ca640139f7',
					'label' => '4 X 4',
					'name' => '4_x_4',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'field_5ea97cc35d5da',
					'label' => 'Doors',
					'name' => 'doors',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5ea97ccc5d5db',
					'label' => 'Seats',
					'name' => 'seats',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'product',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		));

		endif;				
		
	}










	/* POST TYPES */
	public function post_types(){

		if(get_field('nextlevel_sync_branches', 'option')):
		
		$labels = array(
		    'name' => _x( 'Branches', 'post type general name', 'thriftylocal' ),
		    'singular_name' => _x( 'Branch', 'post type singular name', 'thriftylocal' ),
		    'add_new' => _x( 'Add New', 'slider', 'thriftylocal' ),
		    'add_new_item' => __( 'Add Branch', 'thriftylocal' ),
		    'edit_item' => __( 'Edit Branch', 'thriftylocal' ),
		    'new_item' => __( 'New Branch', 'thriftylocal' ),
		    'view_item' => __( 'View Branch', 'thriftylocal' ),
		    'search_items' => __( 'Search Branches', 'thriftylocal' ),
		    'not_found' =>  __( 'No Branches found', 'thriftylocal' ),
		    'not_found_in_trash' => __( 'No Branches found in Trash', 'thriftylocal' ), 
		    'parent_item_colon' => ''
		  );
		  
		  $rewrite = 'branches';
		  
		  $args = array(
		    'labels' => $labels,
		    'public' => true,
		    'publicly_queryable' => true,
		    'show_ui' => true, 
		    'query_var' => true,
		    'rewrite' => array( 'slug' => $rewrite ),
		    'capability_type' => 'post',
		    'hierarchical' => false,
		    'menu_position' => null, 
		    'menu_icon' => 'dashicons-admin-site-alt',
		    'has_archive' => true, 
		    'show_in_rest' => true,
		    'supports' => array('title', 'thumbnail'),
    		'taxonomies' => array( 'branch-category'), 
		  );
		      
		  register_post_type( 'branch', $args );

		endif;


		$labels = array(
		    'name' => _x( 'Public Holidays', 'post type general name', 'thriftylocal' ),
		    'singular_name' => _x( 'Public Holiday', 'post type singular name', 'thriftylocal' ),
		    'add_new' => _x( 'Add New', 'slider', 'thriftylocal' ),
		    'add_new_item' => __( 'Add Public Holiday', 'thriftylocal' ),
		    'edit_item' => __( 'Edit Public Holiday', 'thriftylocal' ),
		    'new_item' => __( 'New Public Holiday', 'thriftylocal' ),
		    'view_item' => __( 'View Public Holiday', 'thriftylocal' ),
		    'search_items' => __( 'Search Public Holidays', 'thriftylocal' ),
		    'not_found' =>  __( 'No Public Holidays found', 'thriftylocal' ),
		    'not_found_in_trash' => __( 'No Public Holidays found in Trash', 'thriftylocal' ), 
		    'parent_item_colon' => ''
		  );
		  
		  $rewrite = 'publicholiday';
		  
		  $args = array(
		    'labels' => $labels,
		    'public' => false,
		    'publicly_queryable' => false,
		    'show_ui' => true, 
		    'query_var' => false,
		    'rewrite' => array( 'slug' => $rewrite ),
		    'capability_type' => 'post',
		    'hierarchical' => false,
		    'menu_position' => null, 
		    'menu_icon' => 'dashicons-star-filled',
		    'has_archive' => false, 
		    'show_in_rest' => true,
		    'supports' => array('title') 
		  );
		      
		  register_post_type( 'publicholiday', $args );


		  
		
	}










	/* SYNC BRANCH ACTION */
	public function nextlevel_sync_branch_action(){
		NEXTLEVEL::DOBRANCHES();		
	}










	/* SYNC VEHICLE ACTION */
	public function nextlevel_sync_vehicle_action(){

		NEXTLEVEL::DOVEHICLES();
		
	}










	/* SYNC PUBLIC HOLIDAY */
	public function nextlevel_sync_publicholiday_action(){

		NEXTLEVEL::DOPUBLICHOLIDAYS();
		
	}










	/* CLEAN LOGS */
	public function nextlevel_clean_logs_action(){

		LOG::clean();

	}










	/* PUBLIC HOLIDAY COLUMN HEADER MANAGEMENT */
	public function manage_publicholiday_posts_columns($columns) {
	    unset($columns['date']);
	    $columns['ph_date'] = 'Date';
	    $columns['ph_country'] = 'Country';
	    return $columns;
	}










	/* PUBLIC HOLIDAY EXTRA COLUMN DATA MANAGEMENT */
	public function manage_publicholiday_posts_custom_column($column, $post_id) {

		$_THE_PH = get_post($post_id);

		switch($column):

			case "ph_date":
				the_field('date', $_THE_PH);
			break;

			case "ph_country":	
				the_field('country_code', $_THE_PH);
			break;

		endswitch;

	}





	function manage_shop_order_posts_columns($columns) {
		
		$n_columns = array();
		foreach($columns as $key => $value):

			if($key=='order_date'):
		      $n_columns['order_reservation'] = 'Reservation';
		      $n_columns['order_vehicle_details'] = 'Vehicle Details';
		      $n_columns['order_out_details'] = 'Out Details';
		      $n_columns['order_in_details'] = 'In Details';
		    endif;

		    if($key=='order_total'):
		      $n_columns['order_sum'] = 'Rental Sum';
		    endif;

			$n_columns[$key] = $value;

		endforeach;

		 return $n_columns;

	}




	
	function manage_shop_order_posts_custom_column($column, $post_id) {

		$_THE_ORDER_POST = get_post($post_id);

		switch($column):

			case 'order_reservation':
				if(get_field('carpro_reservation_number', $_THE_ORDER_POST)):
					the_field('carpro_reservation_number', $_THE_ORDER_POST);
				elseif(get_post_meta($post_id, 'carpro_error', true)):
					echo '<span style="color:red">'.get_post_meta($post_id, 'carpro_error', true).'</span>';
				else:
					echo '-';
				endif;
			break;

			case 'order_vehicle_details':
				echo get_field('carpro_selected_vehicle', $_THE_ORDER_POST).' | '.get_field('carpro_selected_code', $_THE_ORDER_POST).' | '.get_field('carpro_selected_km', $_THE_ORDER_POST);
			break;

		    case 'order_out_details':
				echo get_field('carpro_out_branch', $_THE_ORDER_POST).' | '.get_field('carpro_out_date', $_THE_ORDER_POST).' | '.get_field('carpro_out_time', $_THE_ORDER_POST);
			break;

		    case 'order_in_details':
				echo get_field('carpro_in_branch', $_THE_ORDER_POST).' | '.get_field('carpro_in_date', $_THE_ORDER_POST).' | '.get_field('carpro_in_time', $_THE_ORDER_POST);
			break;

		    case 'order_sum':

		    	if(get_field('rental_amount', $_THE_ORDER_POST)):
		    		$_AMT = get_field('rental_amount', $_THE_ORDER_POST);
					echo wc_price($_AMT);
		    	else:
		    		echo '-';
		    	endif;
			break;

		endswitch;

	}










	/* EXTRA MENU ITEMS */
	public function admin_menu(){
		add_menu_page( 
			'NEXTLEVEL Logs' , 
			'NEXTLEVEL Logs', 
			'edit_posts', 
			'nextlevel-logs', 
			array($this, 'nextlevel_log_menu'), 
			'dashicons-privacy'
		);
	}










	/* LOG MENU ITEM FUNCTION */
	public function nextlevel_log_menu(){
		$_LOGS = LOG::fetch();

		?>


		<script type="text/javascript">
			jQuery(document).ready(function(){
			jQuery('#nextlevelLogSelect').on('change', function(){
				jQuery('#NEXTLEVELLogForm').submit();
			});
			});
		</script>

		<div class="wrap">
			<div class="top-header">
			<h2 class="">NEXTLEVEL Logs</h2>

			<form id="NEXTLEVELLogForm" method="get">
			<input type="hidden" name="page" value="nextlevel-logs" />
			<select name="nextlevel-log-item" id="nextlevelLogSelect">
				<option value="">Please Select</option>
				<?php foreach($_LOGS as $_LOG): ?>

					<?php $_DATE = str_replace('.txt', '', $_LOG); ?>

					<option <?php selected($_DATE, $_GET['nextlevel-log-item']); ?> value="<?php echo $_DATE; ?>"><?php echo wp_date('F d, Y', strtotime($_DATE)); ?></option>

				<?php endforeach; ?>
			</select>
			</form>
			</div>

			<?php if($_GET['nextlevel-log-item']): ?>

			<div class="bottom-content">
				
				<?php $_FILE = LOG::link($_GET['nextlevel-log-item'].'.txt', true); ?>

				<iframe id="nextlevelLogIframe" src="<?php echo $_FILE; ?>" />

			</div>

			<?php endif; ?>
		</div>

		<?php
	}










	/* AJAX: BRANCH TIMES */
	public function nextlevel_ajax_branch_times(){

		$_BRANCH = $_POST['branch'];
		$_DATE = $_POST['date'];

		$_DAY = strtolower(date('l', strtotime($_DATE)));

		$_TIMES = NEXTLEVEL_HELPERS::BRANCH_TIMES_SELECT($_BRANCH, $_DATE, $_DAY);

		if($_TIMES):

			$_OUTPUT = '';

			foreach($_TIMES as $_TIME):
				$_OUTPUT.= '<option value="'.$_TIME.'">'.$_TIME.'</option>';
			endforeach;

			echo $_OUTPUT;

		else:


		endif;


	}










	/* AJAX: AVAILABILITY */
	public function nextlevel_ajax_do_search(){

		parse_str($_POST['data'],$_POSTED);

		if(!isset($_POSTED['InBranch'])):
			$_POSTED['InBranch'] = $_POSTED['OutBranch'];
		endif;

		$_PARAMS = array(
		  "OutBranch" => $_POSTED['OutBranch'],
		  "InBranch" => $_POSTED['InBranch'],
		  "OutDate" => wp_date('d/m/Y', strtotime($_POSTED['OutDate'])),
		  "OutTime" => $_POSTED['OutTime'],
		  "InDate" => wp_date('d/m/Y', strtotime($_POSTED['InDate'])),
		  "InTime" => $_POSTED['InTime']
		);

		NEXTLEVEL::DOAVAILABLILITY($_PARAMS);

		//$_URL = get_permalink( woocommerce_get_page_id( 'shop' ) );
		$_URL = get_field('search_results_page', 'option');

		echo $_URL;

		exit;


	}










	/* AJAX: ADD TO CART */
	public function nextlevel_ajax_do_add_to_cart(){

		WC()->session->__unset('carpro_selected_vehicle');
		WC()->session->__unset('carpro_selected_sku');
		WC()->session->__unset('carpro_selected_km');
		WC()->session->__unset('carpro_selected_code');

		WC()->cart->empty_cart();

		$_VEHICLE = $_POST['vehicle'];
		$_ID = $_POST['id'];
		$_KM = $_POST['km'];
		$_CODE = $_POST['code'];
		$_SKU = $_POST['sku'];

		WC()->cart->add_to_cart($_ID, 1);

		WC()->session->set('carpro_selected_vehicle', $_VEHICLE);
		WC()->session->set('carpro_selected_sku', $_SKU);
		WC()->session->set('carpro_selected_km', $_KM);
		WC()->session->set('carpro_selected_code', $_CODE);

		foreach(WC()->session->get('carpro_availability') as $_VEH => $_DATA):

			if(trim($_VEHICLE) == trim($_DATA['vehicle']['international'])):

				$_RATES 	= $_DATA['vehicle']['rates'];
				$_FEES 		= $_DATA['vehicle']['fees'];
				$_EXTRA_D	= $_DATA['vehicle']['extras']['daily'];
				$_EXTRA_O 	= $_DATA['vehicle']['extras']['once'];

				foreach($_RATES as $_KM_ID => $_KM_RATES):

					if($_KM == $_KM_ID):

						$_THE_RATES = $_KM_RATES['rates'];
						
						foreach($_THE_RATES as $_KM_RATE):

							if($_KM_RATE['code'] == $_CODE):

								WC()->session->set('carpro_selected_rate', $_KM_RATE);
								$_EXTRAS = $_KM_RATES['extras'];
								$_EXTRA_D	= array_merge($_EXTRA_D, $_EXTRAS['daily']);
								$_EXTRA_O 	= array_merge($_EXTRA_O, $_EXTRAS['once']);
								WC()->session->set('carpro_available_extras_daily', $_EXTRA_D);
								WC()->session->set('carpro_available_extras_once', $_EXTRA_O);
								WC()->session->set('carpro_fees', $_FEES);

							endif;

						endforeach;

					endif;

				endforeach;

			endif;

		endforeach;

		echo wc_get_checkout_url();
		exit;

	}



















	/* AJAX: RESET SEARCH */
	public function nextlevel_ajax_reset_search(){

		NEXTLEVEL_HELPERS::CLEAR_CARPRO();

		WC()->cart->empty_cart();

		echo get_site_url();
		exit;
	}










	/* SAVE EXTRA INFORMATION TO ORDER */
	public function woocommerce_checkout_update_order_meta($_ORDER_ID){

		$_RATE = WC()->session->get('carpro_selected_rate');
		$_DAILY = WC()->session->get('carpro_selected_daily_extras');
		$_ONCE = WC()->session->get('carpro_selected_once_extras');

		update_post_meta($_ORDER_ID, 'carpro_selected_vehicle', WC()->session->get('carpro_selected_vehicle'));
		update_post_meta($_ORDER_ID, 'carpro_out_branch', WC()->session->get('carpro_out_branch'));
		update_post_meta($_ORDER_ID, 'carpro_out_date', WC()->session->get('carpro_out_date'));
		update_post_meta($_ORDER_ID, 'carpro_out_time', WC()->session->get('carpro_out_time'));
		update_post_meta($_ORDER_ID, 'carpro_in_branch', WC()->session->get('carpro_in_branch'));
		update_post_meta($_ORDER_ID, 'carpro_in_date', WC()->session->get('carpro_in_date'));
		update_post_meta($_ORDER_ID, 'carpro_in_time', WC()->session->get('carpro_in_time'));
		update_post_meta($_ORDER_ID, 'carpro_selected_km', WC()->session->get('carpro_selected_km'));
		update_post_meta($_ORDER_ID, 'carpro_selected_code', WC()->session->get('carpro_selected_code'));
		update_post_meta($_ORDER_ID, 'carpro_selected_rate', WC()->session->get('carpro_selected_rate'));
		update_post_meta($_ORDER_ID, 'carpro_selected_once_extras', WC()->session->get('carpro_selected_once_extras'));
		update_post_meta($_ORDER_ID, 'carpro_selected_daily_extras', WC()->session->get('carpro_selected_daily_extras'));
		update_post_meta($_ORDER_ID, 'carpro_fees', WC()->session->get('carpro_fees'));

		update_post_meta($_ORDER_ID, 'carpro_deposit_percentage', WC()->session->get('carpro_deposit_percentage'));
		update_post_meta($_ORDER_ID, 'carpro_deposit_amount', WC()->session->get('carpro_deposit_amount'));

		if(isset($_POST['license_number'])):
			update_post_meta($_ORDER_ID, 'license_number', $_POST['license_number']);
		endif;

		if(isset($_POST['license_expiry'])):
			update_post_meta($_ORDER_ID, 'license_expiry', $_POST['license_expiry']);
		endif;

		if(isset($_POST['arrival_flight_number'])):
			update_post_meta($_ORDER_ID, 'arrival_flight_number', $_POST['arrival_flight_number']);
		endif;
		
		update_post_meta($_ORDER_ID, 'carpro_selected_rate_no', $_RATE['rateno']);
		update_post_meta($_ORDER_ID, 'carpro_selected_rate_srno', $_RATE['ratesrno']);

		$_E_D = array();
		if(count($_DAILY) > 0):

			foreach($_DAILY as $_CODE => $_DATA):
				$_E_D[] = $_CODE;
			endforeach;

		endif;

		if(count($_E_D) > 0):
			update_post_meta($_ORDER_ID, 'carpro_selected_daily_extra_codes', implode(', ', $_E_D));
		endif;

		$_E_O = array();
		if(count($_ONCE) > 0):

			foreach($_ONCE as $_CODE => $_DATA):
				$_E_O[] = $_CODE;
			endforeach;

		endif;

		if(count($_E_O) > 0):
			update_post_meta($_ORDER_ID, 'carpro_selected_once_extra_codes', implode(', ', $_E_O));
		endif;


	}










	/*  */
	public function woocommerce_email($_EMAIL_CLASS){

		remove_action( 
			'woocommerce_order_status_pending_to_processing_notification', 
			array( $_EMAIL_CLASS->emails['WC_Email_Customer_Processing_Order'], 
				'trigger' 
			) 
		);

		remove_action( 
			'woocommerce_order_status_pending_to_on-hold_notification', 
			array( $_EMAIL_CLASS->emails['WC_Email_Customer_Processing_Order'], 
				'trigger' 
			) 
		);

	}










	/*  */
	public function woocommerce_payment_complete_order_status($_STATUS){

		return 'completed';

	}










	/*  */
	public function woocommerce_order_status_completed($_ORDER_ID){

		NEXTLEVEL::DORESERVATION($_ORDER_ID);
		NEXTLEVEL_HELPERS::CLEAR_CARPRO();

	}










	/*  */
	public function woocommerce_order_number($_ID){


		if(get_post_meta($_ID, 'carpro_reservation_number', true)):
			$_ID = get_post_meta($_ID, 'carpro_reservation_number', true);
		endif;


		return $_ID;


	}



	public function woocommerce_email_order_details($_ORDER){

		if(get_post_meta($_ORDER->get_id(), 'carpro_error', true)):

			$_CAR_PRO_ERROR = get_post_meta($_ORDER->get_id(), 'carpro_error', true);

			$_EMAIL = WC()->mailer()->get_emails()['WC_Email_New_Order']->recipient;

			echo '<p>Our apologies, there seems to have been an issue with our reservation system. kindly EMAIL <a href="mailto:'.$_EMAIL.'">'.$_EMAIL.'</a> with <strong>REFERENCE: '.$_ORDER->get_id().'</strong> and <strong>ERROR: '.$_CAR_PRO_ERROR.'</strong></p>';

		endif;	



	}


}
