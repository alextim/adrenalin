<?php
final class AT_Contact_Form_Admin_Tables {
	private $user_capability = 'edit_pages';
	
	
	public function __construct() {
		add_action( 'admin_menu', [&$this, 'add_plugin_page']);	
		add_action( 'admin_init', [&$this, 'maybe_download' ]);
	}
	
	
	function add_plugin_page() {
		add_menu_page('AT Contact Form', 'AT Contact Form',       $this->user_capability, 'atcf-main',   [&$this, 'message_list_page_handler'], 'dashicons-email-alt');
	    add_submenu_page('atcf-main', 'Messages', 'Messages',     $this->user_capability, 'atcf-main',   [&$this, 'message_list_page_handler']);
	    add_submenu_page('atcf-main', 'E-mails',  'Address Book', $this->user_capability, 'atcf-emails', [&$this, 'address_book_list_page_handler']);
	}	
	
	
	function maybe_download(){
	
		 /* Listen for form submission */
		if( empty($_POST['action']) || 'export-address-book' !== $_POST['action'] )
			return;
	 
		// Check permissions and nonces 
		if( !current_user_can($this->user_capability) )
			wp_die('');
	 
		check_admin_referer( 'atcf-export-address-book','_wplnonce');
	 
		// Trigger download
        global $wpdb;
		$sql = 'SELECT email, first_name, last_name FROM ' . $wpdb->prefix . AT_CF_ADDRESS_BOOK_TABLE;

		$rows = $wpdb->get_results($sql); 

		if (!$rows) {
			$err = mysqli_error();
			if ( empty($err) ) {
				die('Sorry! No data to export!');
			} else {
				die('Invalid query: ' . $err );
			}
        }
	
		$output = '';

		$field_separator = ',';
		$field_delimiter = ''; //'"';
		$eol = "\n";
		
		$output .= $field_delimiter . 'E-mail'     . $field_delimiter . $field_separator;
		$output .= $field_delimiter . 'First Name' . $field_delimiter . $field_separator;
		$output .= $field_delimiter . 'Last Name'  . $field_delimiter;

		foreach ($rows as $row) {
			$output .= $eol;
			$output .= $field_delimiter . $row->email      . $field_delimiter . $field_separator;
			$output .= $field_delimiter . $row->first_name . $field_delimiter . $field_separator;
			$output .= $field_delimiter . $row->last_name  . $field_delimiter;

		}
		$output .= $eol;
	 
		$filename = 'email-list-' . date( 'Y-m-d h-i-s' ) . '.csv';
	 
		/* Print header */
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );
	 
		echo $output;
		exit;
	}
	
	
	function message_list_page_handler() {
		$table = new AT_CF_Message_List_Table();
		$table->prepare_items();
		
		$message = '';
		
		if ('delete' === $table->current_action()) {
			$message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'custom_table_example'), count($_REQUEST['id'])) . '</p></div>';
		}
		?>
		<div class="wrap">
			<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>

			<?php echo $message; ?>

			<form id="messages-table" method="GET">
			
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
				<?php 
				
				
				$table->display() 
				?>
			</form>

		</div>
	<?php
	}

	
	function address_book_list_page_handler() {
		$table = new AT_CF_Address_Book_List_Table();
		$table->prepare_items();
	?>
		<div class="wrap">

		<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>

		<form id="address-book-table" method="GET">
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
			<?php $table->display() ?>
		</form>
		
		<form id="address-book-export" action="" method="POST">
			<input type="hidden" name="action" value="export-address-book" />
			<?php wp_nonce_field('atcf-export-address-book','_wplnonce') ;?>
            <?php submit_button( __('Export CSV', 'atcf-log'), 'button' ); ?>
 		</form>
		
	</div>
	<?php
	}
}