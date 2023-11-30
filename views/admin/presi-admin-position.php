<?php

$presi_pid = 0;

if(isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])){
    $presi_action = 'edit';
    $presi_pid = (int)$_GET['id'];
}else if(isset($_GET['action']) && $_GET['action'] === 'create'){
    $presi_action = 'create';
}

?>

<div class="presi-presidium-header">

    <div class="presi-three-parts presi-fl">
        <a id="presi-button-secondary" class='button-secondary presidium-button presi-glazzed-btn presi-glazzed-btn-dark' href="<?php echo "?page={$presi_adminPage}"; ?>">
            <div class='presi-icon presi-presidium-button-icon'><i class="fa fa-long-arrow-left"></i></div>
        </a>
    </div>

    <div class="presi-three-parts presi-fl presi-title-part">
    <input id="presi-position-name" class="presi-presidium-title" name="presidium-title" maxlength="250" placeholder="Enter position name" type="text"></div>

    <div class="presi-three-parts presi-fr">
        <a id="presi-save-position-button" class='button-secondary presidium-button presi-glazzed-btn presi-glazzed-btn-green presi-fr' href="#">
            <div class='presi-icon presi-presidium-button-icon'><i class="fa fa-save fa-fw"></i></div>
        </a>
    </div>
</div>

<hr />

<script>

//Show loading while the page is being complete loaded
presi_showSpinner();

//Configure javascript vars passed PHP
var presi_adminPage = "<?php echo $presi_adminPage ?>";
var presi_action = "<?php echo $presi_action ?>";
var presi_selectedProjectId = 0;

var presi_categoryAutocompleteDS = [];

//Configure position model
var presi_position = {};
presi_position.id = "<?php echo $presi_pid ?>";
presi_position.isDraft = true;


//Perform some actions when window is ready
jQuery(window).load(function () {


    //In case of edit we should perform ajax call and retrieve the specified position for editing
    if(presi_action == 'edit'){
        presi_position = presiAjaxGetWithId(presi_position.id, 'presi_get_positions');

        //NOTE: The validation and moderation is very important thing. Here could be not expected conversion
        //from PHP to Javascript JSON objects. So here we will validate, if needed we will do changes
        //to meet our needs
        presi_position = validatedPosition(presi_position);
        //This position is already exists on server, so it's not draft item
        presi_position.isDraft = false;
    }


    jQuery("#presi-save-position-button").on( 'click', function( evt ){
        evt.preventDefault();

        //Apply last changes to the model
        presi_updateModel();

        //Validate saving

        if(!presi_position.name){
            alert("Oops! You're trying to save a competition name without name.");
            return;
        }

        //Show spinner
        presi_showSpinner();

        //Perform Ajax calls
        presi_result = presiAjaxSave(presi_position, 'presi_save_positions');

        //Get updated model from the server
        presi_position = presiAjaxGetWithId(presi_result['pid'], 'presi_get_positions');
        presi_position = validatedPosition(presi_position);
        presi_position.isDraft = false;

        //Update UI
        presi_updateUI();

        //Hide spinner
        presi_hideSpinner();

        //Redirect to previous page
        jQuery( "#presi-button-secondary" )[0].click();
    });



    jQuery(document).keypress(function(event) {
        //cmd+s or control+s
        if (event.which == 115 && (event.ctrlKey||event.metaKey)|| (event.which == 19)) {
            event.preventDefault();

            jQuery( "#presi-save-position-button" ).trigger( "click" );
            return false;
        }
        return true;
    });

    //Update UI based on retrieved/(just create) model
    presi_updateUI();

    //When the page is ready, hide loading spinner
    presi_hideSpinner();
});

function presi_updateUI(){
    if(presi_position.name){
        jQuery("#presi-position-name").val( presi_position.name );
    }
}

function presi_updateModel(){
    //To make sure it's valid JS object
    presi_position = validatedPosition(presi_position);

    presi_position.name = jQuery("#presi-position-name").val();
}

function validatedPosition(position){
    if (!position) {
        position = {};
    }
    return position;
}

function htmlEntitiesEncode(str){
    return jQuery('<div/>').text(str).html();
}

</script>
