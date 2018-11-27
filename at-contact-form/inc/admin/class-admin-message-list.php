<?php
final class AT_CF_Message_List_Table extends WP_List_Table {
	private $table_name;
	private $src_post_id = -1; // by default no filter - All 
	
    /**
    * [REQUIRED] You must declare constructor and give some basic params
    */
	function __construct()  {
        global $status, $page;
		
		global $wpdb;
        $this->table_name = $wpdb->prefix . AT_CF_MESSAGE_TABLE;

        parent::__construct([
            'singular' => 'message',
            'plural' => 'messages',
			'ajax' => false,
        ]);
		
		//add_action( 'sensei_before_list_table', [ &$this, 'table_search_form' ], 5 );
    }
	
	private function print_posts_dropdown($selected) : bool {
		global $wpdb;
		$sql = 'SELECT DISTINCT src_post_id AS id FROM ' . $this->table_name; 
		$items = $wpdb->get_results($sql, ARRAY_A);
		if (!$items) {
			return false;
		}

		echo '<select name="src_post_id" id="src_post_id">';
			echo '<option value="-1" ' . selected( $selected, -1, false ) . '>All</option>';
		
		foreach ($items as $item) {
			$post_id = absint($item['id']);
			$title = '';
			if ($post_id !== 0) {
				$post = get_post($post_id);
				if ($post) {
					$title = substr(esc_html($post->post_title), 0,100);
				} 
			}
			if (empty($title)) {
				$title = 'Unknown post, ID = ' . $post_id;
			}
			echo '<option value="' .  $post_id . '" ' . selected( $selected, $post_id, false ) . '>' . $title . '</option>';
		}
		echo '</select>';
		return true;
	}
	
	private function get_src_post_id_filter() : int {
		$src_post_id = filter_input( INPUT_GET, 'src_post_id' );
		if ($src_post_id == '') {
			return -1;
		} else {
			return intval($src_post_id);
		}
	}
	
    protected function extra_tablenav( $which ) {
?>
        <div class="alignleft actions">
		
<?php
        if ( 'top' === $which && !is_singular() ) {
    
			$this->src_post_id = $this->get_src_post_id_filter();
			if ( $this->print_posts_dropdown( $this->src_post_id ) ) {
 
                 submit_button( __( 'Filter' ), '', 'filter_action', false, array( 'id' => 'post-query-submit' ) );
            }
        }
 
 ?>
        </div>
<?php
        /**
         * Fires immediately following the closing "actions" div in the tablenav for the posts
         * list table.
         *
         * @since 4.4.0
         *
         * @param string $which The location of the extra table nav markup: 'top' or 'bottom'.
         */
        do_action( 'manage_posts_extra_tablenav', $which );
    }
    /**
        * [REQUIRED] this is a default column renderer
        *
        * @param $item - row (key, value array)
        * @param $column_name - string (key)
        * @return HTML
        */
    function column_default($item, $column_name) {
        return $item[$column_name];
    }

    /**
        * [OPTIONAL] this is example, how to render column with actions,
        * when you hover row "Edit | Delete" links showed
        *
        * @param $item - row (key, value array)
        * @return HTML
        */
		/*
    function column_name($item)
    {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &person=2
        $actions = array(
            'edit' => sprintf('<a href="?page=persons_form&id=%s">%s</a>', $item['id'], __('Edit', 'custom_table_example')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'custom_table_example')),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }
*/
    /**
        * [REQUIRED] this is how checkbox column renders
        *
        * @param $item - row (key, value array)
        * @return HTML
        */
    function column_cb($item) : string {
        return '<input type="checkbox" name="id[]" value="' .  $item['id'] . '" />';
    }
	
    function column_src_post_id($item) : string {
		$post_id = absint($item['src_post_id']);
		if ($post_id !== 0) {
			$post = get_post($post_id);
			if ($post) {
				return sprintf('<a href="%s">%s<a/>', esc_url(get_post_permalink($post_id)), esc_html($post->post_title));
			} 
		}
		return '';
    }
	

    /**
        * [REQUIRED] This method return columns to display in table
        * you can skip columns that you do not want to show
        * like content, or description
        *
        * @return array
        */
    function get_columns() : array {
        return [
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'time'        => 'Time',
            'first_name'  => 'First Name',
            'last_name'   => 'Last Name',
            'email'       => 'E-Mail',
            'phone'       => 'Phone',
            'subject'     => 'Subject',
            'message'     => 'Message',
			'src_post_id' => 'Source',
        ];
    }

    /**
        * [OPTIONAL] This method return columns that may be used to sort table
        * all strings in array - is column names
        * notice that true on name column means that its default sort
        *
        * @return array
        */ 
    function get_sortable_columns() : array {
        return [
            'first_name'  => ['first_name',  false],
            'email'       => ['email',       false],
            'time'        => ['time',        true],
            'src_post_id' => ['src_post_id', false],
        ];
    }

    /**
        * [OPTIONAL] Return array of bult actions if has any
        *
        * @return array
        */
    function get_bulk_actions() : array {
        return [
            'delete' => 'Delete',
        ];
    }

    /**
        * [OPTIONAL] This method processes bulk actions
        * it can be outside of class
        * it can not use wp_redirect coz there is output already
        * in this example we are processing delete action
        * message about successful deletion will be shown on page in next part
        */
    function process_bulk_action()  {
        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : [];
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
				global $wpdb;
				$sql = 'DELETE FROM ' . $this->table_name . " WHERE id IN($ids)";
                $wpdb->query($sql);
            }
        }		
    }

    /**
        * [REQUIRED] This is the most important method
        *
        * It will get rows from database and prepare them to be showed in table
        */
    function prepare_items() {
        global $wpdb;

        $per_page = 20; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = [$columns, $hidden, $sortable];
		
		
		$src_post_id = $this->get_src_post_id_filter();
		$filter = (-1 == $src_post_id) ? '' :  sprintf(' WHERE src_post_id=%d', $src_post_id);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var('SELECT COUNT(id) FROM ' . $this->table_name . $filter);

        // prepare query params, as usual current page, order by and order direction
        $paged   =  isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'time';
        $order   = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], ['asc', 'desc'])) ? $_REQUEST['order'] : 'desc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
		$sql = sprintf('SELECT * FROM %s %s ORDER BY %s %s ', $this->table_name, $filter, $orderby, $order);
		$sql .= 'LIMIT %d OFFSET %d';

        $this->items = $wpdb->get_results($wpdb->prepare($sql, $per_page, $paged), ARRAY_A);

        // [REQUIRED] configure pagination
        $this->set_pagination_args([
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ]);
    }
}