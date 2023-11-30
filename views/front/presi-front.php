<?php
    global $presi_portfolios;

    //Validation goes here
    if( $presi_portfolios ){
        //Setup ordered projects array
        // $presi_portfolio = getOrderedPortfolios($presi_portfolio);

        require(PRESI_FRONT_VIEWS_DIR_PATH . "/layouts/presi-front-presidium.php");

        // if($presi_portfolio->grid_type == PRESIGridType::SLIDER ){
        //     require(PRESI_FRONT_VIEWS_DIR_PATH . "/layouts/presi-front-slider.php");
        // }else{
        //     require_once(PRESI_FRONT_VIEWS_DIR_PATH . "/layouts/presi-front-tiled-layout-lightgallery.php");
        // }

        //Render user specified custom css
        // echo "<style>". $presi_portfolio->options[PRESIOption::kCustomCSS]."</style>";

        //Finally render custom js
        // echo "<script> jQuery(window).load(function() {".$presi_portfolio->options[PRESIOption::kCustomJS]."});</script>";

    }else{
        echo "Ooooops!!! Short-code related presidium wasn't found in your database!";
    }


function getOrderedPortfolios( $presi_portfolio ){
    $orderedPortfolios = array();

    if(isset($presi_portfolio->projects) && isset($presi_portfolio->corder)){
        foreach($presi_portfolio->corder as $pid){
            $orderedProjects[] = $presi_portfolio->projects[$pid];
        }
    }

    return $orderedProjects;
}
