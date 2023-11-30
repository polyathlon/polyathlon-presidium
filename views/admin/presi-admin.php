<div class="presi-background">
</div>
<div id="presi-wrap" class="presi-wrap presi-glazzed-wrap">

<?php include_once( PRESI_ADMIN_VIEWS_DIR_PATH.'/presi-header-banner.php' ); ?>

<div class="presi-wrap-main">

    <script>
        PRESI_AJAX_URL = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
        PRESI_IMAGES_URL = '<?php echo PRESI_IMAGES_URL ?>';
    </script>

    <?php

    abstract class PRESITabType{
        const Dashboard = 'dashboard';
        const Settings = 'settings';
        const Help = 'help';
        const Terms = 'terms';
    }

    $presi_tabs = array(
        PRESITabType::Dashboard => 'All Schedules',
        PRESITabType::Settings => 'General Settings',
        PRESITabType::Help => 'User Manual',
    );

    $presi_adminPage = isset( $_REQUEST['page']) ? filter_var($_REQUEST['page'], FILTER_SANITIZE_STRING) : null;
    $presi_currentTab = isset ( $_GET['tab'] ) ? filter_var($_GET['tab'], FILTER_SANITIZE_STRING) : PRESITabType::Dashboard;
    $presi_action = isset ( $_GET['action'] ) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : null;
    $presi_gridType = isset ( $_GET['type'] ) ? filter_var($_GET['type'], FILTER_SANITIZE_STRING) : null;

    include_once(PRESI_ADMIN_VIEWS_DIR_PATH."/presi-admin-modal-spinner.php");
    include_once(PRESI_ADMIN_VIEWS_DIR_PATH."/presi-admin-header.php");

    if($presi_action == 'create' || $presi_action == 'edit'){
        if($presi_gridType == PRESITableType::PORTFOLIO) {
            include_once(PRESI_ADMIN_VIEWS_DIR_PATH."/presi-admin-portfolio.php");
        }else{
            include_once(PRESI_ADMIN_VIEWS_DIR_PATH."/presi-admin-position.php");
        }

    }else if ($presi_action == 'options'){
        include_once(PRESI_ADMIN_VIEWS_DIR_PATH."/presi-admin-options.php");
    }else{
        //Tabs are not fully developed yet, that's why we have disabled them in this version
        //presi_renderAdminTabs($presi_currentTab, $presi_adminPage, $presi_tabs);

        if($presi_currentTab == PRESITabType::Dashboard){
            include_once(PRESI_ADMIN_VIEWS_DIR_PATH."/presi-admin-table.php");
        }else if($presi_currentTab == PRESITabType::Settings){
            include_once(PRESI_ADMIN_VIEWS_DIR_PATH."/presi-admin-settings.php");
        }else if($presi_currentTab == PRESITabType::Help){
            include_once(PRESI_ADMIN_VIEWS_DIR_PATH."/presi-admin-help.php");
        }
    }

    function presi_renderAdminTabs( $current, $page, $tabs = array()){
        //Hardcoded style for removing dynamically added bottom-border
        echo '<h2 class="nav-tab-wrapper presi-admin-nav-tab-wrapper" style="border: 0px">';

        foreach ($tabs as $tab => $name) {
            $class = ($tab == $current) ? 'nav-tab-active' : '';
            echo "<a class='nav-tab $class' href='?page=$page&tab=$tab'>$name</a>";
        }
        echo '</h2>';
    }

    ?>
    <div style="clear:both;"></div>
</div>
</div>
