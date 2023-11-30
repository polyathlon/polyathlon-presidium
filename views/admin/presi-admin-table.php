<?php

if( $presi_adminPageType == PRESITableType::PORTFOLIO ){
    require_once( PRESI_CLASSES_DIR_PATH.'/PRESIPortfolioListTable.php');

    //Create an instance of our package class...
    $listTable = new PRESIPortfolioListTable();
}else{
    require_once( PRESI_CLASSES_DIR_PATH.'/PRESIPositionListTable.php');

    //Create an instance of our package class...
    $listTable = new PRESIPositionListTable();
}

//Prepare items of our package class...
$listTable->prepare_items();

function featuresListTooltip(){
    $tooltip = "";
    $tooltip .= "<div class=\"presi-tooltip-content\">";
    $tooltip .= "<ul>";
    $tooltip .= "<li>* Do Full Design Adjustments</li>";
    $tooltip .= "<li>* Put Multiple Grids On Pages</li>";
    $tooltip .= "<li>* Setup Masonry, Puzzle, Grid Layouts</li>";
    $tooltip .= "<li>* Embed YouTube, Vimeo & Native Videos</li>";
    $tooltip .= "<li>* Popup iFrame & Google Maps</li>";
    $tooltip .= "<li>* Open Light/Dark/Fixed/Fullscreen Popups</li>";
    $tooltip .= "<li>* 100+ Hover Styles & Animations</li>";
    $tooltip .= "<li>* Allow Category Filtration & Pagination</li>";
    $tooltip .= "<li>* Enable Social Sharing</li>";
    $tooltip .= "<li>* Perform Ajax/Lazy Loading</li>";
    $tooltip .= "<li>* Receive Product Enquiries</li>";
    $tooltip .= "</ul>";
    $tooltip .= "</div>";

    $tooltip = htmlentities($tooltip);
    return $tooltip;
}
?>

<div id="presi-dashboard-wrapper">
    <div id="presi-dashboard-add-new-wrapper">
        <div>
            <?php if ($presi_adminPageType == PRESITableType::PORTFOLIO) { ?><a id="add-portfolio-button" class='button-secondary add-schedule-button presi-glazzed-btn presi-glazzed-btn-green' href="<?php echo "?page={$presi_adminPage}&action=create&type=".PRESITableType::PORTFOLIO; ?>" title='Add new portfolio'>+ Portfolio</a><?php }
            else { ?><a id="add-position-button" class='button-secondary add-portfolio-button presi-glazzed-btn presi-glazzed-btn-green' href="<?php echo "?page={$presi_adminPage}&action=create&type=".PRESITableType::POSITION; ?>" title='Add new position'>+ Position</a><?php } ?>
        </div>
    </div>
<!--    <div><a class='button-secondary upgrade-button presi-tooltip presi-glazzed-btn presi-glazzed-btn-orange' href='--><?php //echo PRESI_PRO_URL ?><!--' title='--><?php //echo featuresListTooltip(); ?><!--'>* UNLOCK ALL FEATURES *</a></div>-->

    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $presi_adminPage ?>" />
        <!-- Now we can render the completed list table -->
        <?php $listTable->display() ?>
    </form>

</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".tablenav.top", jQuery(".wp-list-table .no-items").closest("#presi-dashboard-wrapper")).hide();
    });
</script>