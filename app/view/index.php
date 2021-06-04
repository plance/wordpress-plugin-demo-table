<?php defined('ABSPATH') || exit;

/**
 * Table
 */
class Plg_Table_View_Admin_Data_Index extends WP_List_Table
{
	public $per_page;
	
    /**
     * Prepare columns of table for showing
     */
    public function prepare_items()
    {
		global $wpdb;
		
		/** Determine the total number of records in the database */
		$total_items = $wpdb -> get_var("
			SELECT COUNT(`id`)
			FROM `".$wpdb -> prefix."plg_table_demo`
			{$this -> _getSqlWhere()}
		");
		
		/** Sets */
		$per_page = $this -> get_items_per_page($this -> per_page, 10);
		
		/** Set the data for pagination */
        $this -> set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page
        ));

		/** Get the data to form a table */
        $data = $this -> table_data();
		
		$this -> _column_headers = $this -> get_column_info();
		
		/** Set the table data */
        $this -> items = $data;
    }
 
    /**
     * Title of columns of the table
     * @return array
     */
    public function get_columns()
    {
        return array(
            'id'		 => __('ID', 'lance'),
            'field_one'	 => __('Field one', 'lance'),
            'field_two'	 => __('Field two', 'lance'),
            'date_create'=> __('Date create', 'lance'),
        );
    }
 
    /**
     * An array of column names for which sorting is performed
     * @return array
     */
    public function get_sortable_columns()
    {
        return array(
			'id'		 => array('id', false),
			'field_one'	 => array('field_one', false),
			'field_two'	 => array('field_two', false),
			'date_create'=> array('date_create', false),
		);
    }
 
    /**
     * Table data
     * @return array
     */
    private function table_data()
    {
		global $wpdb;
		
		/** Sets */
		$per_page = $this -> get_pagination_arg('per_page');
		$order_ar = $this -> get_sortable_columns();
		
		$get_orderby = filter_input(INPUT_GET, 'orderby');
		$order = filter_input(INPUT_GET, 'order') == 'asc' ? 'asc' : 'desc';
		$orderby = key_exists($get_orderby, $order_ar) ? $get_orderby: 'date_create';

		$sql = "SELECT *
			FROM `".$wpdb -> prefix."plg_table_demo`
			{$this -> _getSqlWhere()}
			ORDER BY `{$orderby}` {$order}
			LIMIT ".(($this -> get_pagenum() - 1) * $per_page).", {$per_page}
		";

		return $wpdb -> get_results($sql, ARRAY_A);
    }
 
	/**
	 * Displays if there is no data
	 */
	public function no_items()
	{
	  echo __('Data not found', 'lance');
	}
	
    /**
     * Returns the contents of the column
     * @param  array $item item data array
     * @param  string $column_name the name of the current column
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch($column_name)
		{
			case 'id':
			case 'field_two':
				return $item[$column_name] ? esc_attr($item[$column_name]) : '-';
        }
    }
	
	/**
	 * Returns data from a custom column
	 * @param string $item
	 * @return string
	 */
	public function column_field_one($item)
	{
		return esc_attr($item['field_one']).$this -> row_actions(array(
			'edit' => '<a href="'.add_query_arg(array('action' => 'edit', 'id' => $item['id'])).'">'.__('edit', 'lance').'</a>',
			'delete' => '<a href="'.add_query_arg(array('action' => 'delete', 'id' => $item['id'])).'" onclick="return confirm(\''.__('Delete?', 'lance').'\')">'.__('delete', 'lance').'</a>',
		));
	}

	/**
	 * Returns data from a custom column
	 * @param string $item
	 * @return string
	 */	
	public function column_date_create($item)
	{
		return date(get_option('date_format', 'd.m.Y').' '.get_option('time_format', 'H:i'), $item['date_create']);
	}
	
	/********************************************************************************************************************/
	/************************************************* PRIVATE METHODS **************************************************/
	/********************************************************************************************************************/
	
	/**
	 * Get "where" for sql
	 * @global wpdb $wpdb
	 * @return string
	 */
	private function _getSqlWhere()
	{
		global $wpdb;
		
		$where = '';
		$get_s = filter_input(INPUT_GET, 's');
		
		if($get_s)
		{
			$where = 'WHERE '.join(' OR ', array(
				"`field_one` LIKE  '%".$wpdb -> _real_escape($get_s)."%'",
				"`field_two` LIKE  '%".$wpdb -> _real_escape($get_s)."%'",
			));
		}
		
		return $where;
	}
}