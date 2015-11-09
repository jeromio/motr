<?php
/**
 * Plugin Name: WooCommerce Create Customer on Order
 * Description: Save time and simplify your life by having the ability to create a new Customer directly on the WooCommerce Order screen
 * Author: cxThemes
 * Author URI: http://codecanyon.net/user/cxThemes
 * Plugin URI: link to codecanyon
 * Version: 1.10
 * Text Domain: create-customer-order
 * Domain Path: /languages/
 *
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-ADD-USER-ORDER
 * @author    cxThemes
 * @category  WooCommerce
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Define Constants
 **/
define( 'CREATE_CUSTOMER_ORDER_VERSION', '1.10' );
define( 'CREATE_CUSTOMER_ORDER_REQUIRED_WOOCOMMERCE_VERSION', 2.2 );

/**
 * Update Check
 */
require 'plugin-updates/plugin-update-checker.php';
new PluginUpdateChecker(
	'http://cxthemes.com/plugins/woocommerce-create-customer-order/create-customer-order.json',
	__FILE__,
	'create-customer-order'
);

/**
 * Required woocommerce functions if they are missing.
 **/
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Check if WooCommerce is active, and is required WooCommerce version.
 */
if ( ! is_woocommerce_active() || version_compare( get_option( 'woocommerce_db_version' ), CREATE_CUSTOMER_ORDER_REQUIRED_WOOCOMMERCE_VERSION, '<' ) ) {
	add_action( 'admin_notices', 'WC_Create_Customer_Order::woocommerce_inactive_notice' );
	return;
}

/**
 * Intantiate plugin.
 *
 */
$GLOBALS['wc_create_customer_order'] = new WC_Create_Customer_Order();

/**
 *
 * Main Class.
 *
 */
class WC_Create_Customer_Order {
	
	
	/* Plugin id */
	private $id = 'woocommerce_create_customer_order';
	

	/**
	 * Construct and initialize the main plugin class
	 */
	public function __construct() {
		
		add_action( 'init',    array( $this, 'load_translation' ) );
		
		if ( is_admin() ) {
			
			add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'create_customer_on_order_page' ) );
			
			add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'update_customer_on_order_page' ) );
			
			add_action( 'admin_print_styles', array( $this, 'admin_scripts' ) );
			
			add_action( 'wp_ajax_woocommerce_order_create_user', array( $this, 'woocommerce_create_customer_on_order' ) );
			
		}
			
		add_action( 'loop_start', array( $this, 'condition_filter_title' ) );
		
		add_filter( 'woocommerce_reset_password_message', array( $this, 'change_lost_password_message' ) );
		
		add_action( 'woocommerce_customer_reset_password', array( $this, 'update_customer_password_state' ) );
		
		add_action( 'woocommerce_process_shop_order_meta', array( $this, 'save_address_from_order_to_customer') );

	}
	
	function condition_filter_title($array){
	    global $wp_query;
	    if($array === $wp_query){
	        add_filter( 'the_title', array( $this, 'woocommerce_new_customer_change_title' ) );
	    }else{
	        remove_filter( 'the_title', array( $this, 'woocommerce_new_customer_change_title' ) );
	    }
	}

	/**
	 * Localization
	 */
	public function load_translation() {
		
		// localisation
		load_plugin_textdomain( 'create-customer-order', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		
	}
	
	/**
	 * Add create customer form to Order Page
	 */
	public function create_customer_on_order_page() {
		
		global $woocommerce, $wp_roles; ?>
		
		<div class='create_user form-field form-field-wide'>
			<p>
				<button class='button create_user_form'>
					<?php _e( 'Create Customer', 'create-customer-order' ); ?>
					<span class='create_user_icon'>&nbsp;</span>
				</button>
			</p>
			<div class='toggle-create-user'>
				<p>
					<label for='create_user_first_name'>
						<?php _e( 'First Name', 'create-customer-order' ); ?>
					</label>
					<input type='text' name='create_user_first_name' id='create_user_first_name' value='' />
				</p>
				<p>
					<label for='create_user_last_name'>
						<?php _e( 'Last Name', 'create-customer-order' ); ?>
					</label>
					<input type='text' name='create_user_last_name' id='create_user_last_name' value='' />
				</p>
				<p>
					<label for='create_user_email_address'>
						<?php _e( 'Email Address', 'create-customer-order' ); ?>
						<span class='required-field'>*</span>
					</label>
					<input type='text' name='create_user_email_address' id='create_user_email_address' value='' />
				</p>
				<p>
					<label for='create_user_role'><?php _e( 'Role', 'create-customer-order' ); ?></label>
					<select name='create_user_role' id='create_user_role'>
						<?php
						if ( isset( $wp_roles ) ) {
							foreach ( $wp_roles->get_names() as $role => $label ) {
								?>
								<option value="<?php echo $role; ?>" <?php if ( "customer" == $role ) { echo 'selected="selected"'; } ?> >
									<?php echo $label; ?>
								</option>
								<?php
							}
						}
						else{
							?>
							<option value="customer" selected="selected">
								<?php _e( 'Customer', 'create-customer-order' ); ?>
							</option>
							<?php
						}
						?>
					</select>
				</p>
				<p>
					<button class='button submit_user_form_cancel'>
						<?php _e( 'Cancel', 'create-customer-order' ); ?>
					</button>
					<button class='button submit_user_form'>
						<?php _e( 'Create Customer', 'create-customer-order' ); ?>
					</button>
				</p>
			</div>
		</div>
		
		<?php
		// Insert Add Customer
		wc_enqueue_js( "jQuery('.create_user.form-field').insertAfter( jQuery('#customer_user').parents('.form-field:eq(0)') );" );
		
	}
	
	/**
	 * Add Save to customer checkboxes above Billing and Shipping Details on Order page
	 */
	public function update_customer_on_order_page() {
		
		global $woocommerce;
		
		// Insert Add Customer
		wc_enqueue_js( "jQuery('.button.load_customer_billing').parents('.order_data_column').find('h4').append( \"<label class='save-billing-address'>" .__( "Save to Customer", 'create-customer-order' ). "<span class='save-billing-address-check'><input type='checkbox' name='save-billing-address-input' id='save-billing-address-input' value='true' /></span></label>\" );     ");   
		wc_enqueue_js( "jQuery('.button.billing-same-as-shipping').parents('.order_data_column').find('h4').append( \"<label class='save-shipping-address'>" .__( "Save to Customer", 'create-customer-order' ). "<span class='save-shipping-address-check'><input type='checkbox' name='save-shipping-address-input' id='save-shipping-address-input' value='true' /></span></label>\" );     ");
	}


	/**
	 * Include admin scripts
	 */
	public function admin_scripts() {
		
		global $woocommerce, $wp_scripts;
		
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		wp_register_style( 'woocommerce-create-customer-order', plugins_url( basename( plugin_dir_path( __FILE__ ) ) . '/css/styles.css', basename( __FILE__ ) ), '', CREATE_CUSTOMER_ORDER_VERSION, 'screen' );
		wp_enqueue_style( 'woocommerce-create-customer-order' );
		
		wp_register_script( 'woocommerce-create-customer-order', plugins_url( basename( plugin_dir_path( __FILE__ ) ) . '/js/create-user-on-order.js', basename( __FILE__ ) ), array('jquery'), CREATE_CUSTOMER_ORDER_VERSION );
		wp_enqueue_script( 'woocommerce-create-customer-order' );
		
		$woocommerce_create_customer_order_params = array(
			'plugin_url' 					=> $woocommerce->plugin_url(),
			'ajax_url' 						=> admin_url('admin-ajax.php'),
			'create_customer_nonce' 		=> wp_create_nonce("create-customer"),
			'home_url'						=> get_home_url(),
			'msg_email_exists'				=> __( 'Email Address already exists', 'create-customer-order'),
			'msg_email_empty'				=> __( 'Please enter an email address', 'create-customer-order'),
			'msg_email_exists_username'		=> __( 'This email address already exists as another users Username', 'create-customer-order'),
			'msg_success'					=> __( 'User created and linked to this order', 'create-customer-order'),
			'msg_email_valid'				=> __( 'Please enter a valid email address', 'create-customer-order'),
			'msg_successful'				=> __( 'Success', 'create-customer-order'),
			'msg_error'						=> __( 'Error', 'create-customer-order')
		);
		
		wp_localize_script( 'woocommerce-create-customer-order', 'woocommerce_create_customer_order_params', $woocommerce_create_customer_order_params );
		
	}
	
	
	/**
	* Create customer via ajax on Order page
	*
	* @access public
	* @return void
	*/
	public function woocommerce_create_customer_on_order() {
		global $woocommerce, $wpdb;

		check_ajax_referer( 'create-customer', 'security' );

		$email_address = $_POST['email_address'];
		$first_name = sanitize_text_field( $_POST['first_name'] );
		$last_name = sanitize_text_field( $_POST['last_name'] );
		$last_name = sanitize_text_field( $_POST['last_name'] );
		$user_role = sanitize_text_field( $_POST['user_role'] );

		$error = false;

		if ( !empty($email_address) ) {
			
			if ( !email_exists( $email_address ) ) {
				
				if ( !username_exists( $email_address ) ) {
					
					$password = wp_generate_password();
					$user_id = wp_create_user( $email_address, $password, $email_address );
					
					$display_name = $first_name . " " . $last_name;
					
					if ( ( $first_name == "" ) && ( $last_name == "" ) ) {
						$display_name = substr($email_address, 0, strpos($email_address, '@'));
					}
					
					// Create the user
					wp_update_user( array (
						'ID' => $user_id,
						'first_name' => $first_name,
						'last_name' => $last_name,
						'role' => $user_role,
						'display_name'=> $display_name,
						'nickname' => $display_name
					) ) ;
					
					update_user_meta( $user_id, "create_customer_on_order_password", true );
					
					$allow = apply_filters('allow_password_reset', true, $user_id);
					$user_login = $email_address;
					$key = $wpdb->get_var( $wpdb->prepare( "SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login ) );
					
					if ( empty( $key ) ) {
						
						// Generate something random for a key...
						$key = wp_generate_password( 20, false );

						//do_action( 'retrieve_password_key', $user_login, $key );

						// Now insert the key, hashed, into the DB.
						if ( empty( $wp_hasher ) ) {
							require_once ABSPATH . 'wp-includes/class-phpass.php';
							$wp_hasher = new PasswordHash( 8, true );
						}

						$hashed = $wp_hasher->HashPassword( $key );

						// Now insert the new md5 key into the db
						$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
						
						$lost_password_link = esc_url( add_query_arg(
							array( 'key' => $key, 'login' => rawurlencode( $user_login ) ),
							wc_get_endpoint_url( 'lost-password', '', get_permalink( wc_get_page_id( 'myaccount' ) ) )
						) );
						
						$this->send_register_email($email_address, $lost_password_link);
					}
					
				} else {
					$error = "username";
				}
				
			} else {
				$error = "email";
			}
			
		} else {
			$error = "empty";
		}
		
		if ( !$error ) {
			
			if ($user_id) {
				echo json_encode( array( "user_id" => $user_id, "username" => $email_address ) );
			} else {
				echo json_encode( array( "error_message" => $error ) );
			}
			
		} else {
			
			echo json_encode( array( "error_message" => $error ) );
			
		}

		// Quit out
		die();
	}
	
	/**
	 * Add new message to Lost Password page for users who have been created through Add Customer on Order
	 */
	public function change_lost_password_message($msg) {
		
		global $woocommerce;
		
   		$email_address = esc_attr( $_GET['login'] );
		$user = get_user_by( "login", $email_address );
		
		$password_not_changed = get_user_meta( $user->ID, "create_customer_on_order_password", true );
		
		if ( $password_not_changed ) {
			
			$msg = __( 'As this is your first time logging in. Please set your password below.', 'create-customer-order');
			
		}
		
		return $msg;
		
	}
	
	/**
	 * Add new Title to Lost Password page for customers who have been created through Add Customer on Order
	 */
	public function woocommerce_new_customer_change_title($page_title) {
		global $woocommerce;
		
		$is_lost_pass_page = false;
		
		// Check if is lost pass page
		if ( function_exists( 'wc_get_endpoint_url' ) ) { // Above WC 2.1
			if ( is_wc_endpoint_url( 'lost-password' ) ) $is_lost_pass_page = true;
		} else {
			if ( is_page( woocommerce_get_page_id( 'lost_password' ) ) ) $is_lost_pass_page = true;
		}
		
		// Only do this is is lost pass page, and that we have a login to check against
		if( $is_lost_pass_page && isset( $_GET['login'] ) ){
			
			$email_address = esc_attr( $_GET['login'] );
			$user = get_user_by( "login", $email_address );

			$password_not_changed = get_user_meta( $user->ID, "create_customer_on_order_password", true );

			if ( $password_not_changed ) {
				$page_title = __( 'Create your Password', 'create-customer-order' );
			}
		}
			
		return $page_title;
		
	}
	
	/**
	 * After customer submits and resets password the account the customer is redirect to my accounts page and account set to standard behaviour
	 */
	public function update_customer_password_state($user) {
		
		global $woocommerce;
		
		$email_address = esc_attr( $_POST['reset_login'] );
		$user_from_email = get_user_by( "login", $email_address );
		
		$password_not_changed = get_user_meta( $user_from_email->ID, "create_customer_on_order_password", true );

		if ( ($user->ID == $user_from_email->ID) && ( $password_not_changed ) ) {
			
			delete_user_meta( $user->ID, "create_customer_on_order_password" );
			wc_add_notice( __( 'You have successfully activated your account. Please login with your email address and new password', 'create-customer-order' ) );
			
			?>
			<script type='text/javascript'>
				window.location = '<?php echo get_permalink( woocommerce_get_page_id ( "myaccount" ) ); ?>';
			</script>
			<?php
			
			die;
		}
	}
	
	/**
	 * Send custom register email with lost password reset link
	 */
	public function send_register_email($to, $link) {
		
		// Email Message
		$email_message = __("Welcome to %s,

We have created an account for you on the site. Your login username is your email address: %s

Please click %s to set your new password and log into your account.

Copy and paste this link into your browser if you are having trouble with the above link: %s

Thank-you
%s", 'create-customer-order');
		$email_message = nl2br( sprintf(
			$email_message,
			get_bloginfo("name"),
			$to,
			"<a href='".$link."'>".__( "here", 'create-customer-order' )."</a>",
			$link,
			get_bloginfo("name")
		) );
		apply_filters("woocommerce_create_customer_order_email_msg", $email_message);
		
		// Email Heading
		$email_heading = __("Your account has been created", 'create-customer-order');
		
		apply_filters("woocommerce_create_customer_order_email_title", $email_heading);
		
		// Email Body
		$email_body = $this->get_email_header_html($email_heading);
		$email_body .= $email_message;
		$email_body .= $this->get_email_footer_html();
		
		// Email Subject
		$email_subject = __("Your account on %s", 'create-customer-order');
		$email_subject = sprintf( $email_subject, get_bloginfo("name") );
		apply_filters("woocommerce_create_customer_order_email_subject", $email_subject);
		
		// Email Headers
		$headers[] = 'From: '.get_option( 'woocommerce_email_from_name' ).' <'.get_option( 'woocommerce_email_from_address' ).'>';
		add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
		
		// Send Email
		$status = wp_mail( $to, $email_subject, $email_body, $headers );
		
		remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
	
	}
	
	function get_email_header_html($email_heading) {
		ob_start();
		woocommerce_get_template( 'emails/email-header.php', array( 'email_heading' => $email_heading ) );
		return ob_get_clean();
	}
	
	function get_email_footer_html() {
		ob_start();
		woocommerce_get_template( 'emails/email-footer.php' );
		return ob_get_clean();
	}

	/**
	 * WP Mail Filter - Set email body as HTML
	 */
	public function set_html_content_type() {
		return 'text/html';
	}
	
	/**
	 * Save Billing and Shipping details to the customer when checkboxes are checked on Order page
	 */
	public function save_address_from_order_to_customer( $post_id, $post=null ) {
		$user_id = absint( $_POST['customer_user'] );
		
		$save_to_billing_address = $_POST['save-billing-address-input'];
		$save_to_shipping_address = $_POST['save-shipping-address-input'];
		
		if ($save_to_billing_address == 'true') {
			update_user_meta( $user_id, 'billing_first_name', woocommerce_clean( $_POST['_billing_first_name'] ) );
			update_user_meta( $user_id, 'billing_last_name', woocommerce_clean( $_POST['_billing_last_name'] ) );
			update_user_meta( $user_id, 'billing_company', woocommerce_clean( $_POST['_billing_company'] ) );
			update_user_meta( $user_id, 'billing_address_1', woocommerce_clean( $_POST['_billing_address_1'] ) );
			update_user_meta( $user_id, 'billing_address_2', woocommerce_clean( $_POST['_billing_address_2'] ) );
			update_user_meta( $user_id, 'billing_city', woocommerce_clean( $_POST['_billing_city'] ) );
			update_user_meta( $user_id, 'billing_postcode', woocommerce_clean( $_POST['_billing_postcode'] ) );
			update_user_meta( $user_id, 'billing_country', woocommerce_clean( $_POST['_billing_country'] ) );
			update_user_meta( $user_id, 'billing_state', woocommerce_clean( $_POST['_billing_state'] ) );
			update_user_meta( $user_id, 'billing_email', woocommerce_clean( $_POST['_billing_email'] ) );
			update_user_meta( $user_id, 'billing_phone', woocommerce_clean( $_POST['_billing_phone'] ) );
		}
		
		if ($save_to_shipping_address == 'true') {
			update_user_meta( $user_id, 'shipping_first_name', woocommerce_clean( $_POST['_shipping_first_name'] ) );
			update_user_meta( $user_id, 'shipping_last_name', woocommerce_clean( $_POST['_shipping_last_name'] ) );
			update_user_meta( $user_id, 'shipping_company', woocommerce_clean( $_POST['_shipping_company'] ) );
			update_user_meta( $user_id, 'shipping_address_1', woocommerce_clean( $_POST['_shipping_address_1'] ) );
			update_user_meta( $user_id, 'shipping_address_2', woocommerce_clean( $_POST['_shipping_address_2'] ) );
			update_user_meta( $user_id, 'shipping_city', woocommerce_clean( $_POST['_shipping_city'] ) );
			update_user_meta( $user_id, 'shipping_postcode', woocommerce_clean( $_POST['_shipping_postcode'] ) );
			update_user_meta( $user_id, 'shipping_country', woocommerce_clean( $_POST['_shipping_country'] ) );
			update_user_meta( $user_id, 'shipping_state', woocommerce_clean( $_POST['_shipping_state'] ) );
		}
	}
	
	/**
	 * Display Notifications on specific criteria.
	 *
	 * @since	2.14
	 */
	public static function woocommerce_inactive_notice() {
		if ( current_user_can( 'activate_plugins' ) ) :
			if ( ! is_woocommerce_active() ) :
				?>
				<div id="message" class="error">
					<p>
						<?php
						printf(
							__( '%sCreate Customer on Order for WooCommerce is inactive%s %sWooCommerce%s must be active for Create Customer on Order to work. Please install & activate WooCommerce.', 'create-customer-order' ),
							'<strong>',
							'</strong><br>',
							'<a href="http://wordpress.org/extend/plugins/woocommerce/" target="_blank" >',
							'</a>'
						);
						?>
					</p>
				</div>
				<?php
			elseif ( version_compare( get_option( 'woocommerce_db_version' ), CREATE_CUSTOMER_ORDER_REQUIRED_WOOCOMMERCE_VERSION, '<' ) ) :
				?>
				<div id="message" class="error">
					<!--<p style="float: right; color: #9A9A9A; font-size: 13px; font-style: italic;">For more information <a href="http://cxthemes.com/plugins/update-notice.html" target="_blank" style="color: inheret;">click here</a></p>-->
					<p>
						<?php
						printf(
							__( '%sCreate Customer on Order for WooCommerce is inactive%s This version of Create Customer on Order requires WooCommerce %s or newer. For more information about our WooCommerce version support %sclick here%s.', 'create-customer-order' ),
							'<strong>',
							'</strong><br>',
							CREATE_CUSTOMER_ORDER_REQUIRED_WOOCOMMERCE_VERSION,
							'<a href="https://helpcx.zendesk.com/hc/en-us/articles/202241041/" target="_blank" style="color: inheret;" >',
							'</a>'
						);
						?>
					</p>
					<div style="clear:both;"></div>
				</div>
				<?php
			endif;
		endif;
	}


} // class WC_Create_Customer_Order