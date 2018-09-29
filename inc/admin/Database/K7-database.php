<?php

if( ! defined( 'ABSPATH')) exit;

class K7_Database
{


    private $table_name;
    private $primary_key;
    private $version;

    public function __construct()
    {

        global $wpdb;

        $this->table_name = $wpdb->prefix . 'form_submissions';
        $this->primary_key = 'id';
        $this->version = '1.0';


        $this->init();


    }

    public function create_table($table = null)
    {

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


        $sql = "CREATE TABLE " . $wpdb->prefix . 'form_submissions' . " (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        data longtext NOT NULL,
        PRIMARY KEY  (id)
        ) CHARACTER SET utf8 COLLATE $charset_collate;";

        dbDelta($sql);



        update_option($this->table_name . '_db_version', $this->version);
    }

    public function insert_data( string $table, array $data, array $dataType)
    {
        global $wpdb;

        return $wpdb->insert($table, $data, $dataType);


    }

    public function select_data($table)
    {
        global $wpdb;


        $obj = $wpdb->get_results("SELECT *  FROM  $table ");

        return $obj;
    }

    public function update_data()
    {

    }

    public function delete_data()
    {
        global $wpdb;


    }

    public function on_delete_blog()
    {
        global $wpdb;
        $tabelas [] = $wpdb->query("DROP table IF EXISTS " . $wpdb->prefix . "form_submissions ");
        return $tabelas;
    }


    public function init()
    {
        add_action('drop_database', array($this, 'on_delete_blog'));
        add_action('init', array($this, 'create_table'));
        add_filter(' wpmu_drop_tables ', array($this, ' on_delete_blog '));

        register_uninstall_hook(__FILE__, 'drop_database');
        register_activation_hook(__FILE__, 'create_table');

    }

}
new K7_Database();