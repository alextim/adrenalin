<?php
final class AT_CF_Address_Book_List_Table extends WP_List_Table {
	private $table_name;
    /**
        * [REQUIRED] You must declare constructor and give some basic params
        */
    function __construct() {
        global $status, $page;
		
		global $wpdb;
        $this->table_name = $wpdb->prefix . AT_CF_ADDRESS_BOOK_TABLE;
		
        parent::__construct(array(
            'singular' => 'email',
            'plural' => 'emails',
			'ajax' => false,
        ));
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
	
    function column_cb($item) : string {
        return '<input type="checkbox" name="id[]" value="' .  $item['id'] . '" />';
    }
    function get_columns() : array {
        return [
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
		    'ip'               => 'IP',
			'visited'          => 'Visited',
			'is_subscribed'    => 'Subscribed',
            'first_name'       => 'First Name',
            'middle_name'      => 'Middle Name',
            'last_name'        => 'Last Name',
            'email'            => 'E-Mail',
            'birthdate'        => 'Birthdate',
            'sex'              => 'Sex',
            'street_address_1' => 'Street 1',
            'street_address_2' => 'Street 2',
			'city'             => 'City',
			'country'          => 'Country',
			'postal_index'     => 'Index',
			'passport'         => 'Passport',
			'sport_level'      => 'Level',
        ];
    }

    function get_sortable_columns() : array {
        return [
            'email'      => ['email',      false],
            'first_name' => ['first_name', true],
        ];
    }

    function get_bulk_actions() {
        return [
            'delete' => 'Delete',
        ];
    }
	
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

    function prepare_items() {
        global $wpdb;

        $per_page = 20; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = [$columns, $hidden, $sortable];

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var('SELECT COUNT(id) FROM ' . $this->table_name);

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'first_name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], ['asc', 'desc'])) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
		$sql = 'SELECT * FROM ' . $this->table_name . ' ORDER BY ' . $orderby . ' ' . $order . ' LIMIT %d OFFSET %d';
        $this->items = $wpdb->get_results($wpdb->prepare($sql, $per_page, $paged), ARRAY_A);

        // [REQUIRED] configure pagination
        $this->set_pagination_args([
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ]);
    }
}