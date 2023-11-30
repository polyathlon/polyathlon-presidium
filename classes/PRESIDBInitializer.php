<?php

class PRESIDBInitializer {
    public $tablePortfolios;
    public $tablePositions;
    public $tableOptions;

    function __construct(){
        $this->tablePortfolios = PRESI_TABLE_PORTFOLIOS;
        $this->tablePositions = PRESI_TABLE_POSITIONS;
        $this->tableOptions = PRESI_TABLE_OPTIONS;
    }

    public function configure(){
        //NOTE: before any configuration check what should we do later. Should we initialize with demo data or not, or something else.
        $needsConfiguration = $this->needsConfiguration();
        $needInitialization = $this->needsInitialization();

        if($needsConfiguration){
            $this->setupTables();
        }

        if($needInitialization){
            //$this->initializeTables();
        }
    }

    public function needsConfiguration(){
        global $wpdb;

        $sql = "SHOW TABLES FROM `{$wpdb->dbname}`  WHERE";
        $sql .=" `Tables_in_{$wpdb->dbname}` LIKE '%{$this->tablePortfolios}%' OR";
        $sql .=" `Tables_in_{$wpdb->dbname}` LIKE '%{$this->tablePositions}%'";

        $res = $wpdb->get_results($sql,ARRAY_A);

        //If any table is missing needs setup
        return count($res) < 12;
    }

    public function needsInitialization(){
        global $wpdb;

        $sql = "SHOW TABLES FROM `{$wpdb->dbname}`  WHERE";
        $sql .=" `Tables_in_{$wpdb->dbname}` LIKE '%{$this->tablePortfolios}%' OR";
        $sql .=" `Tables_in_{$wpdb->dbname}` LIKE '%{$this->tablePositions}%'";

        $res = $wpdb->get_results($sql,ARRAY_A);

        //If there is no tables yet, needs initialization
        return count($res) == 0;
    }


    public function checkForChanges() {
        // global $wpdb;
        // $table = $wpdb->get_results( $wpdb->prepare(
        //     "SELECT COUNT(1) FROM information_schema.tables WHERE table_schema=%s AND table_name=%s;",
        //     $wpdb->dbname, $this->tableProjects
        // ) );
        // if ( !empty( $table ) ) {
        //     $column = $wpdb->get_results($wpdb->prepare(
        //         "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
        //         $wpdb->dbname, $this->tableProjects, 'details'
        //     ));
        //     if (empty($column)) {
        //         $sql = "ALTER TABLE `{$this->tableProjects}` ADD `details` text";
        //         $wpdb->query($sql);
        //     }
        // }
    }

    private function setupTables(){
        global $wpdb;

        $charset_collate = '';

        if ( ! empty( $wpdb->charset ) ) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }

        if ( ! empty( $wpdb->collate ) ) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        $sql = "SET FOREIGN_KEY_CHECKS=0";
        $wpdb->query( $sql );

        //Create Portfolio table
        $sql = "CREATE TABLE IF NOT EXISTS {$this->tablePortfolios} (
                    `portfolio_id` int(11) NOT NULL AUTO_INCREMENT,
                    `position_id` int(11) NOT NULL DEFAULT '0',
                    `title` varchar(50) NOT NULL,
                    `image` text NOT NULL,
                    `e_mail` varchar(250),
                    `phone` varchar(30),
                    `address` varchar(250),
                    `other` text,
                    PRIMARY KEY(`portfolio_id`),
                    CONSTRAINT `position_ref` FOREIGN KEY (`position_id`)
                    REFERENCES {$this->tablePositions}(`position_id`)
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION
                )ENGINE=InnoDB $charset_collate;
        ";
        $wpdb->query( $sql );

        //Create Position table
        $sql = "CREATE TABLE IF NOT EXISTS {$this->tablePositions} (
                `position_id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(250) NOT NULL,
                PRIMARY KEY(`position_id`)
              )ENGINE=InnoDB $charset_collate;
        ";
        $wpdb->query( $sql );

        $sql = "SET FOREIGN_KEY_CHECKS=1";
        $wpdb->query( $sql );
    }

    private function initializeTables(){
        // global $wpdb;

        // //Insert demo schedule
        // $wpdb->insert(
        //     $this->tableSchedules,
        //     array(
        //         'title' => '',
        //         'corder' => '',
        //         'options' => PRESIHelper::getScheduleDefaultOptions()
        //     )
        // );
        // $pid = $wpdb->insert_id;

        // //Add demo project
        // $wpdb->insert(
        //     $this->tableProjects,
        //     array(
        //         'pid' => $pid,
        //         'title' => '',
        //         'description' => "",
        //     )
        // );
    }
}