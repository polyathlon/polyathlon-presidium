<?php

//Helper functions
function presi_ajax_return( $response ){
    echo  json_encode( $response );
    die();
}

function wp_ajax_presi_get_portfolio(){
    global $wpdb;
    $response = new stdClass();

    if(!isset($_GET['id'])){
        $response->status = 'error';
        $response->errormsg = 'Invalid portfolio identifier!';
        presi_ajax_return($response);
    }

    $pid = (int)$_GET['id'];
    $query = $wpdb->prepare("SELECT * FROM ".PRESI_TABLE_PORTFOLIOS." WHERE ".PRESI_TABLE_PORTFOLIOS_ID." = %d", $pid);
    $res = $wpdb->get_results( $query , OBJECT );

    if(count($res)){
        $portfolio = $res[0];

        // $query = "SELECT * FROM ".PRESI_TABLE_POSITIONS;
        // $res = $wpdb->get_results( $query , OBJECT );

        // $positions = array();

        // foreach( $res as $position ){
        //     $positions[$position->position_id] = $position;
        // }

        // $portfolio->positions = $positions;

        $response->status = 'success';
        $response->presidium = $portfolio;
    }else{
        $response->status = 'error';
        $response->errormsg = 'Unknown portfolio identifier!';
    }

    presi_ajax_return($response);
}

function wp_ajax_presi_save_portfolio() {
    global $wpdb;
    $response = new stdClass();

    if(!isset($_POST['data'])){
        $response->status = 'error';
        $response->errormsg = 'Invalid portfolio passed!';
        presi_ajax_return( $response );
    }

    //Convert to stdClass object
    $portfolio = json_decode( stripslashes( $_POST['data']), true );

    $pid = isset($portfolio[PRESI_TABLE_PORTFOLIOS_ID]) ? (int)$portfolio[PRESI_TABLE_PORTFOLIOS_ID] : 0;

    //Insert if portfolio is draft yet
    if(isset($portfolio['isDraft']) && (int)$portfolio['isDraft']){
        $position_id = isset($portfolio['position_id']) ? filter_var($portfolio['position_id'], FILTER_SANITIZE_STRING) : "";
        $title = isset($portfolio['title']) ? filter_var($portfolio['title'], FILTER_SANITIZE_STRING) : "";
        $image = isset($portfolio['image']) ? filter_var($portfolio['image'], FILTER_SANITIZE_STRING) : "";
        $e_mail = isset($portfolio['e_mail']) ? filter_var($portfolio['e_mail'], FILTER_SANITIZE_STRING) : "";
        $phone = isset($portfolio['phone']) ? filter_var($portfolio['phone'], FILTER_SANITIZE_STRING) : "";
        $address = isset($portfolio['address']) ? filter_var($portfolio['address'], FILTER_SANITIZE_STRING) : "";
        $other = isset($portfolio['other']) ? filter_var($portfolio['other'], FILTER_SANITIZE_STRING) : "";

        $wpdb->insert(
            PRESI_TABLE_PORTFOLIOS,
            array(
                'position_id' => $position_id,
                'title' => $title,
                'image' => $image,
                'e_mail' => $e_mail,
                'phone' => $phone,
                'address' => $address,
                'other' => $other,
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );

        //Get real identifier and use it instead of draft identifier for tmp usage
        $pid = $wpdb->insert_id;
    }

    $position_id = isset($portfolio['position_id']) ? filter_var($portfolio['position_id'], FILTER_SANITIZE_STRING) : "";
    $title = isset($portfolio['title']) ? filter_var($portfolio['title'], FILTER_SANITIZE_STRING) : "";
    $image = isset($portfolio['image']) ? filter_var($portfolio['image'], FILTER_SANITIZE_STRING) : "";
    $e_mail = isset($portfolio['e_mail']) ? filter_var($portfolio['e_mail'], FILTER_SANITIZE_STRING) : "";
    $phone = isset($portfolio['phone']) ? filter_var($portfolio['phone'], FILTER_SANITIZE_STRING) : "";
    $address = isset($portfolio['address']) ? filter_var($portfolio['address'], FILTER_SANITIZE_STRING) : "";
    $other = isset($portfolio['other']) ? filter_var($portfolio['other'], FILTER_SANITIZE_STRING) : "";

    $wpdb->update(
        PRESI_TABLE_PORTFOLIOS,
        array(
            'position_id' => $position_id,
            'title' => $title,
            'image' => $image,
            'e_mail' => $e_mail,
            'phone' => $phone,
            'address' => $address,
            'other' => $other,
        ),
        array( PRESI_TABLE_PORTFOLIOS_ID => $pid ),
        array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        ),
        array(
            '%d',
        )
    );

    $response->status = 'success';
    $response->pid = $pid;
    presi_ajax_return($response);
}


function wp_ajax_presi_get_positions(){
    global $wpdb;
    $response = new stdClass();

    if(!isset($_GET['id'])){
        $response->status = 'error';
        $response->errormsg = 'Invalid position identifier!';
        presi_ajax_return($response);
    }

    $pid = (int)$_GET['id'];
    $query = $wpdb->prepare("SELECT * FROM ".PRESI_TABLE_POSITIONS." WHERE ".PRESI_TABLE_POSITIONS_ID." = %d", $pid);
    $res = $wpdb->get_results( $query , OBJECT );

    if(count($res)){
        $position = $res[0];
        $response->status = 'success';
        $response->presidium = $position;
    }else{
        $response->status = 'error';
        $response->errormsg = 'Unknown position identifier!';
    }

    presi_ajax_return($response);
}

function wp_ajax_presi_get_positions_list(){
    global $wpdb;
    $response = new stdClass();

    $query = "SELECT * FROM ".PRESI_TABLE_POSITIONS;
    $res = $wpdb->get_results( $query , OBJECT );

    $positions = array();

    foreach( $res as $position ){
        $positions[$position->position_id] = $position;
    }

    if( count($res) ){
        $response->status = 'success';
        $response->presidium = $positions;
    }else{
        $response->status = 'error';
        $response->errormsg = 'Position list is empty!';
    }

    presi_ajax_return($response);
}

function wp_ajax_presi_save_positions() {
    global $wpdb;
    $response = new stdClass();

    if( !isset($_POST['data']) ){
        $response->status = 'error';
        $response->errormsg = 'Invalid position passed!';
        presi_ajax_return( $response );
    }

    //Convert to stdClass object
    $position = json_decode( stripslashes( $_POST['data']), true );

    $pid = isset($position[PRESI_TABLE_POSITIONS_ID]) ? (int)$position[PRESI_TABLE_POSITIONS_ID] : 0;

    //Insert if portfolio is draft yet
    if(isset($position['isDraft']) && (int)$position['isDraft']){
        $name = isset($position['name']) ? filter_var($position['name'], FILTER_SANITIZE_STRING) : "";

        $wpdb->insert(
            PRESI_TABLE_POSITIONS,
            array(
                'name' => $name,
            ),
            array(
                '%s',
            )
        );

        //Get real identifier and use it instead of draft identifier for tmp usage
        $pid = $wpdb->insert_id;
    }

    $name = isset($position['name']) ? filter_var($position['name'], FILTER_SANITIZE_STRING) : "";

    $wpdb->update(
        PRESI_TABLE_POSITIONS,
        array(
            'name' => $name,
        ),
        array( PRESI_TABLE_POSITIONS_ID => $pid ),
        array(
            '%s',
        ),
        array(
            '%d'
        )
    );

    $response->status = 'success';
    $response->pid = $pid;
    presi_ajax_return($response);
}

