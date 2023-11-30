<?php

$presi_pid = 0;

if(isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])){
    $presi_action = 'edit';
    $presi_pid = (int)$_GET['id'];
}else if(isset($_GET['action']) && $_GET['action'] === 'create'){
    $presi_action = 'create';
}

global $presi_theme;
?>

<div class="presi-presidium-header">

    <div class="presi-three-parts presi-fl">
        <a id="presi-button-secondary" class='button-secondary presidium-button presi-glazzed-btn presi-glazzed-btn-dark' href="<?php echo "?page={$presi_adminPage}"; ?>">
            <div class='presi-icon presi-presidium-button-icon'><i class="fa fa-long-arrow-left"></i></div>
        </a>
    </div>

    <div class="presi-three-parts presi-fl presi-title-part">
    <input id="presi-portfolio-title" class="presi-presidium-title" name="presidium-title" maxlength="250" placeholder="Enter person name" type="text"></div>

    <div class="presi-three-parts presi-fr">
        <a id="presi-save-portfolio-button" class='button-secondary presidium-button presi-glazzed-btn presi-glazzed-btn-green presi-fr' href="#">
            <div class='presi-icon presi-presidium-button-icon'><i class="fa fa-save fa-fw"></i></div>
        </a>
    </div>
</div>

<hr />

<table id="presi-gallery-project-list">
    <tr id="presi-gallery-project-id" class="presi-gallery-project">
        <td class="presi-draggable"><i class="fa fa-reorder"></i></td>
        <td class="presi-attachment">
            <div>
                <div class="presi-attachment-img">
                    <div class="presi-attachment-img-overlay" onclick="presi_onImageEdit('id')"><i class="fa fa-pencil"></i>
                    </div>
                </div>
                <input type="hidden" class="presi-project-cover-src" name="portfolio.image" value="" />
            </div>
        </td>
        <td class="presi-content">
            <div id="presi-portfolio-position" class="presi-content-box select-box">
                <div class="select-box-current" tabindex="1">
                    <div class="select-box-value">
                        <input class="select-box-input" type="radio" id="presi-0" value="0" name="presi" checked="checked">
                        <p class="select-box-input-text" placeholder>Enter the position</p>
                    </div>
                    <img class="select-box-icon" src="https://cdn.onlinewebfonts.com/svg/img_295694.svg" alt="Arrow Icon" aria-hidden="true">
                </div>
            </div>
            <div class="presi-content-box"><input id="presi-portfolio-e-mail" type="text" placeholder="Enter e-mail"  value=""></div>
            <div class="presi-content-box"><input id="presi-portfolio-phone" type="text" placeholder="Enter phone"  value=""></div>
            <div class="presi-content-box"><input type="text" id="presi-portfolio-address" placeholder="Enter address"  value=""></div>
            <div class="presi-content-box"><textarea rows=3 id="presi-portfolio-about" placeholder="Enter about" ></textarea></div>
        </td>
    </tr>
</table>

<script>

//Show loading while the page is being complete loaded
presi_showSpinner();

//Configure javascript vars passed PHP
var presi_adminPage = "<?php echo $presi_adminPage ?>";
var presi_action = "<?php echo $presi_action ?>";
var presi_selectedProjectId = 0;

var presi_categoryAutocompleteDS = [];
var presi_attachmentTypePicture = 'pic';

//Configure portfolio model
var presi_portfolio = {};
var presi_positions = {};
presi_portfolio.id = "<?php echo $presi_pid ?>";
presi_portfolio.isDraft = true;


//Perform some actions when window is ready
jQuery(window).load(function () {


    //In case of edit we should perform ajax call and retrieve the specified portfolio for editing
    if(presi_action == 'edit'){
        presi_portfolio = presiAjaxGetWithId(presi_portfolio.id, 'presi_get_portfolio');

        //NOTE: The validation and moderation is very important thing. Here could be not expected conversion
        //from PHP to Javascript JSON objects. So here we will validate, if needed we will do changes
        //to meet our needs
        presi_portfolio = validatedPortfolio(presi_portfolio);
        //This portfolio is already exists on server, so it's not draft item
        presi_portfolio.isDraft = false;

    }

    presi_positions = presiAjaxGet('presi_get_positions_list');
    presi_createPositionList();

    jQuery("#presi-save-portfolio-button").on( 'click', function( evt ){
        evt.preventDefault();

        //Apply last changes to the model
        presi_updateModel();

        //Validate saving

        if(!presi_portfolio.title){
            alert("Oops! You're trying to save a portfolio without person name.");
            return;
        }

        if(!presi_portfolio.position_id){
            alert("Oops! You're trying to save a portfolio without person's position");
            return;
        }
        //Show spinner
        presi_showSpinner();

        //Perform Ajax calls
        presi_result = presiAjaxSave(presi_portfolio, 'presi_save_portfolio');

        //Get updated model from the server
        presi_portfolio = presiAjaxGetWithId(presi_result['pid'], 'presi_get_portfolio');
        presi_portfolio = validatedPortfolio(presi_portfolio);
        presi_portfolio.isDraft = false;

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

            jQuery( "#presi-save-portfolio-button" ).trigger( "click" );
            return false;
        }
        return true;
    });

    //Update UI based on retrieved/(just create) model
    presi_updateUI();

    //When the page is ready, hide loading spinner
    presi_hideSpinner();
});

function presi_createPositionList()
{
    let html = '';

    if( presi_positions && Object.entries(presi_positions).length ){
        for( const [positionIndex, position] of Object.entries(presi_positions) ){
            html +='<div class="select-box-value">'+
                        '<input class="select-box-input" type="radio" id="presi-'+ position.position_id+'"value="'+position.position_id+'" name="presi">'+
                        '<p class="select-box-input-text">'+position.name+'</p>'+
                    '</div>';
        }
    }

    jQuery("#presi-portfolio-position .select-box-current").append(html);

    html = '<ul class="select-box-list">';

    if( presi_positions && Object.entries(presi_positions).length ){
        for( const [positionIndex, position] of Object.entries(presi_positions) ){
            html += '<li>'+
                        '<label class="select-box-option" for="presi-'+ position.position_id+'" aria-hidden="aria-hidden">'+position.name+'</label>'+
                    '</li>'
        }
    }

    html += '</ui>';

    jQuery("#presi-portfolio-position").append(html);
}

function presi_changeImageCover(imageId, picInfo) {
    var thumb_img = "<?php echo ($presi_theme == 'dark') ? '/general/glazzed-image-placeholder_dark.png' : '/general/glazzed-image-placeholder.png'; ?>";

    if(picInfo) {
        picInfo.type = presi_attachmentTypePicture;
    }
    var bgImage = (picInfo ? picInfo.src : PRESI_IMAGES_URL + thumb_img);
    jQuery("#presi-gallery-project-"+imageId+" .presi-project-cover-src").val(JSON.stringify(picInfo));
    jQuery("#presi-gallery-project-"+imageId+" .presi-attachment-img").css('background', 'url('+bgImage+') center center / cover no-repeat');
}

function presi_onImageEdit(imageId) {
    presi_openMediaUploader(function callback(picInfo) {
        presi_changeImageCover(imageId, picInfo);
    }, false);
}

function presi_updateUI(){
    if(presi_portfolio.title){
        jQuery("#presi-portfolio-title").val( presi_portfolio.title );
    }

    if(presi_portfolio.e_mail){
        jQuery("#presi-portfolio-e-mail").val( presi_portfolio.e_mail );
    }

    if(presi_portfolio.phone){
        jQuery("#presi-portfolio-phone").val( presi_portfolio.phone );
    }

    if( presi_portfolio.address ){
        jQuery("#presi-portfolio-address").val( presi_portfolio.address );
    }

    if( presi_portfolio.about ){
        jQuery("#presi-portfolio-about").val( presi_portfolio.about );
    }

    var image = presi_portfolio.image ? JSON.parse(PresiBase64.decode(presi_portfolio.image)) : null;
    presi_changeImageCover('id', image);

    if( presi_portfolio.position_id ){
        jQuery(`#presi-portfolio-position .select-box-input[value="${presi_portfolio.position_id}"]`).click();
    }

}

function presi_updateModel(){
    //To make sure it's valid JS object
    presi_portfolio = validatedPortfolio(presi_portfolio);

    presi_portfolio.title = jQuery("#presi-portfolio-title").val();
    presi_portfolio.e_mail = jQuery("#presi-portfolio-e-mail").val();
    presi_portfolio.phone = jQuery("#presi-portfolio-phone").val();
    presi_portfolio.address = jQuery("#presi-portfolio-address").val();
    presi_portfolio.about = jQuery("#presi-portfolio-about").val();
    presi_portfolio.image = PresiBase64.encode(jQuery("input[name='portfolio.image']").val());
    presi_portfolio.position_id = Number(jQuery("#presi-portfolio-position .select-box-input:checked").val());
}

function validatedPortfolio(portfolio){
    if (!portfolio) {
      portfolio = {};
    }
    return portfolio;
}

function htmlEntitiesEncode(str){
    return jQuery('<div/>').text(str).html();
}

</script>
