<?php

function presi_infoBox($presi_project){
    $output = "";

    if( (isset($presi_project->title) && $presi_project->title !== '' ) || (isset($presi_project->description) && $presi_project->description !== '' )){
        $output .= "<div class='lg-info'>";

        if(isset($presi_project->title) && $presi_project->title !== '' ){
            $title = PRESIHelper::decode2Str($presi_project->title);
            $output .= "<h4>".$title."</h4>";
        }

        if(isset($presi_project->description) && $presi_project->description !== '' ){
            $desc = PRESIHelper::decode2Str($presi_project->description);
            $output .= "<p>".$desc."</p>";
        }
        $output .= "</div>";
    }

    $output = htmlentities($output);
    $output = str_replace("\n",'</br>',$output);

    return $output;
}

$gridType =  isset($presi_schedule->extoptions['type']) ? $presi_schedule->extoptions['type'] : PRESIGridType::ALBUM;
$showTitle = ($gridType == PRESIGridType::SCHEDULE || $gridType == PRESIGridType::ALBUM || $gridType == PRESIGridType::GALLERY || $gridType == PRESIGridType::TEAM);
$showDesc = false;


?>

<style>
    /* Schedule Options Configuration Goes Here*/
    #gallery .tile:hover{
        cursor: <?php echo $presi_schedule->options[PRESIOption::kMouseType]; ?> !important;
    }

    /* - - - - - - - - - - - - - - -*/
    /* Tile Hover Customizations */

    /* Customize overlay background */
    #gallery .presi-tile-inner .overlay,
    #gallery .tile .caption {
        background-color: <?php echo PRESIHelper::hex2rgba($presi_schedule->options[PRESIOption::kTileOverlayColor].$presi_schedule->options[PRESIOption::kTileOverlayOpacity]) ?> !important;
    }

    #gallery .presi-tile-inner.presi-details-bg .details {
        background-color: <?php echo PRESIHelper::hex2rgba($presi_schedule->options[PRESIOption::kTileOverlayColor].$presi_schedule->options[PRESIOption::kTileOverlayOpacity]) ?> !important;
    }

    #gallery .presi-tile-inner .details h3 {
        color: <?php echo $presi_schedule->options[PRESIOption::kTileTitleColor] ?>;
        text-align: center;
        font-size: 18px;
    }

    #gallery .presi-tile-inner .details p {
        color: <?php echo $presi_schedule->options[PRESIOption::kTileDescColor] ?>;
        text-align: center;
        font-size: 11px;
    }

    <?php if(!$showDesc): ?>
    #gallery .presi-tile-inner .details h3 {
        margin-bottom: 0px;
    }
    <?php endif; ?>

</style>
<?php $isCatalog = (!empty($presi_schedule->extoptions) && !empty($presi_schedule->extoptions['type']) && $presi_schedule->extoptions['type'] == PRESIGridType::CATALOG); ?>

<!--Here Goes HTML-->
<div class="presi-wrapper">
    <div id="gallery">
        <div id="ftg-items" class="ftg-items">
            <?php foreach($presi_schedule->projects as $presi_project): ?>
                <div id="presi-tile-<?php echo $presi_project->id?>" class="tile" data-url="<?php echo isset($presi_project->url) ? $presi_project->url : ""?>">
                    <?php if ($gridType == PRESIGridType::CLIENT_LOGOS) { ?>
                    <div class="presi-tile-inner details27 image01">
                    <?php } else { ?>
                    <div class="presi-tile-inner details33 presi-details-bg image01">
                    <?php } ?>

                    <?php if($isCatalog) { ?>
                    <div class="presi-additional-block1">
                        <?php
                        $title = isset($presi_project->title) ? PRESIHelper::decode2Str($presi_project->title) : "";
                        if (!empty($title)) {
                            echo '<h3 class="presi-catalog-title">'.$title.'</h3>';
                        }
                        ?>
                    </div>
                    <?php } ?>

                    <?php
                        $coverInfo = PRESIHelper::decode2Str($presi_project->cover);
                        $coverInfo = PRESIHelper::decode2Obj($coverInfo);
                        $meta = PRESIHelper::getAttachementMeta($coverInfo->id, $presi_schedule->options[PRESIOption::kThumbnailQuality]);

                        if (isset($presi_project->details)) {
                            $presi_project->details = json_decode($presi_project->details);
                            $catalogPrice = (isset($presi_project->details) && isset($presi_project->details->price)) ? $presi_project->details->price : "";
                            $catalogSale = (isset($presi_project->details) && isset($presi_project->details->sale)) ? $presi_project->details->sale : "";
                        }
                    ?>

                    <a id="<?php echo $presi_project->id ?>" class="tile-inner">
                        <?php if ($isCatalog && !empty($catalogSale)) { ?>
                            <div class='presi-badge-box presi-badge-pos-RT'><div class="presi-badge"><span><?php echo '-'.$catalogSale.'%'; ?></span></div></div>
                        <?php } ?>
                        <img class="presi-item presi-tile-img" src="<?php echo $meta['src'] ?>" data-width="<?php echo $meta['width']; ?>" data-height="<?php echo $meta['height']; ?>" />
                        <?php
                        $html = '';
                        if ($showTitle || $showDesc) {
                            $html .= "<div class='overlay'></div>";
                            $title = isset($presi_project->title) ? PRESIHelper::decode2Str($presi_project->title) : "";
                            $desc = isset($presi_project->description) ? PRESIHelper::decode2Str($presi_project->description) : "";
                            $desc = PRESIHelper::truncWithEllipsis($desc, 15);

                            if ($title != '' || $desc != '') {
                                $html .= "<div class='details'>";
                                if ($showTitle) {
                                    $html .= "<h3>{$title}</h3>";
                                }
                                if ($showDesc) {
                                    $html .= "<p>{$desc}</p>";
                                }
                                $html .= "</div>";
                            }
                        } else {
                            if ($gridType != PRESIGridType::CLIENT_LOGOS && $gridType != PRESIGridType::CATALOG) {
                                $html .= '<div class="caption"></div>';
                            }
                        }
                        echo $html;
                        ?>
                    </a>
                    <?php if ($isCatalog) { ?>
                        <div class="presi-additional-block2">
                            <?php
                            if (isset($presi_project->details)) {
                                $sale = '';
                                $overline = '';
                                if (!empty($catalogSale) && !empty($catalogPrice)) {
                                    $sale = "$" . number_format((float)($catalogPrice - $catalogPrice * $catalogSale / 100), 2, '.', '');
                                    $overline = 'style="text-decoration: line-through;"';
                                    echo "<p><span {$overline}> "."$"."{$catalogPrice} </span> &nbsp;<span>{$sale}</span></p>";
                                } elseif (!empty($catalogPrice)) {
                                    echo "<p><span>"."$"."{$catalogPrice}</span></p>";
                                }
                            }
                            ?>
                            <?php if (!empty($presi_project->url)) { ?><p><button class="presi-product-buy-button" onclick="presi_loadHref('<?php echo (!empty($presi_project->url) ? $presi_project->url : '#'); ?>', true)">BUY NOW</button></p><?php } ?>
                        </div>
                    <?php } ?>
                    </div>

                    <?php if(($gridType == PRESIGridType::ALBUM || $gridType == PRESIGridType::SCHEDULE) && !$presi_schedule->options[PRESIOption::kDirectLinking]) : ?>

                    <ul id="presi-light-gallery-<?php echo $presi_project->id; ?>" class="presi-light-gallery" style="display: none;" data-sub-html="<?php echo presi_infoBox( $presi_project)?>" data-url="<?php echo isset($presi_project->url) ? $presi_project->url : ''; ?>">
                        <?php
                            $meta = PRESIHelper::getAttachementMeta($coverInfo->id);
                            $metaThumb = PRESIHelper::getAttachementMeta($coverInfo->id, "medium");
                        ?>

                        <li data-src="<?php echo $meta['src']; ?>" >
                            <a href="#">
                                <img src="<?php echo $metaThumb['src']; ?>" />
                            </a>
                        </li>

                        <?php foreach($presi_project->pics as $pic): ?>
                            <?php if(!empty($pic)): ?>
                                <?php
                                    $picInfo = PRESIHelper::decode2Str($pic);
                                    $picInfo = PRESIHelper::decode2Obj($picInfo);

                                    $meta = PRESIHelper::getAttachementMeta($picInfo->id);
                                    $metaThumb = PRESIHelper::getAttachementMeta($picInfo->id, "medium");
                                ?>

                                <li data-src="<?php echo $meta['src']; ?>">
                                    <a href="#">
                                        <img src="<?php echo $metaThumb['src']; ?>" />
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <?php if($gridType != PRESIGridType::ALBUM && $gridType != PRESIGridType::SCHEDULE && !$presi_schedule->options[PRESIOption::kDirectLinking]) : ?>
                <ul id="presi-light-gallery" class="presi-light-gallery" style="display: none;" >
                <?php foreach($presi_schedule->projects as $presi_project): ?>
                    <?php
                        $coverInfo = PRESIHelper::decode2Str($presi_project->cover);
                        $coverInfo = PRESIHelper::decode2Obj($coverInfo);
                        $meta = PRESIHelper::getAttachementMeta($coverInfo->id, $presi_schedule->options[PRESIOption::kThumbnailQuality]);
                        $meta = PRESIHelper::getAttachementMeta($coverInfo->id);
                        $metaThumb = PRESIHelper::getAttachementMeta($coverInfo->id, "medium");
                    ?>

                    <li id="presi-light-gallery-item-<?php echo $presi_project->id; ?>" data-src="<?php echo $meta['src']; ?>" data-sub-html="<?php echo presi_infoBox( $presi_project)?>" data-url="<?php echo isset($presi_project->url) ? $presi_project->url : ''; ?>">
                        <a href="#">
                            <img src="<?php echo $metaThumb['src']; ?>" />
                        </a>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
    $approxTileWidth = ( isset($presi_schedule->options[PRESIOption::kTileApproxWidth]) && !empty($presi_schedule->options[PRESIOption::kTileApproxWidth]) ) ? $presi_schedule->options[PRESIOption::kTileApproxWidth] : 220;
    $approxTileHeight = ( isset($presi_schedule->options[PRESIOption::kTileApproxHeight]) &&  !empty($presi_schedule->options[PRESIOption::kTileApproxHeight]) ) ? $presi_schedule->options[PRESIOption::kTileApproxHeight] : 220;
    $minTileWidth = ( isset($presi_schedule->options[PRESIOption::kTileMinWidth]) && !empty($presi_schedule->options[PRESIOption::kTileMinWidth]) ) ? $presi_schedule->options[PRESIOption::kTileMinWidth] : 200;
?>

<!--Here Goes JS-->
<script>
    (function($) {
        $(document).ready(function(){

            var tileParams = {};

            if(<?php echo ($gridType == PRESIGridType::CLIENT_LOGOS || $gridType == PRESIGridType::TEAM) ? 1 : 0 ?>) {
                tileParams.approxTileWidth = <?php echo $approxTileWidth; ?>;
                tileParams.approxTileHeight = <?php echo $approxTileHeight; ?>;
                tileParams.minTileWidth = <?php echo $minTileWidth; ?>;
            }

            if(<?php echo ($gridType == PRESIGridType::CATALOG) ? 1 : 0 ?>) {
                tileParams.addBlock1Height = 40;
                tileParams.addBlock2Height = 100;
            }
            jQuery('#gallery').presiTiledLayer(tileParams);

            $( ".presi-light-gallery" ).each(function() {
              var id = $( this ).attr("id");
              $("#" + id).lightGallery({
                mode: 'slide',
                useCSS: true,
                cssEasing: 'ease', //'cubic-bezier(0.25, 0, 0.25, 1)',//
                easing: 'linear', //'for jquery animation',//
                speed: 600,
                addClass: '',

                closable: true,
                loop: true,
                auto: false,
                pause: 6000,
                escKey: true,
                controls: true,
                hideControlOnEnd: false,

                preload: 1, //number of preload slides. will exicute only after the current slide is fully loaded. ex:// you clicked on 4th image and if preload = 1 then 3rd slide and 5th slide will be loaded in the background after the 4th slide is fully loaded.. if preload is 2 then 2nd 3rd 5th 6th slides will be preloaded.. ... ...
                showAfterLoad: true,
                selector: null,
                index: false,

                lang: {
                    allPhotos: 'All photos'
                },
                counter: false,

                exThumbImage: false,
                thumbnail: true,
                showThumbByDefault:false,
                animateThumb: true,
                currentPagerPosition: 'middle',
                thumbWidth: 150,
                thumbMargin: 10,


                mobileSrc: false,
                mobileSrcMaxWidth: 640,
                swipeThreshold: 50,
                enableTouch: true,
                enableDrag: true,

                vimeoColor: 'CCCCCC',
                youtubePlayerParams: false, // See: https://developers.google.com/youtube/player_parameters,
                videoAutoplay: true,
                videoMaxWidth: '855px',

                dynamic: false,
                dynamicEl: [],

                // Callbacks el = current plugin
                onOpen        : function(el) {}, // Executes immediately after the gallery is loaded.
                onSlideBefore : function(el) {}, // Executes immediately before each transition.
                onSlideAfter  : function(el) {}, // Executes immediately after each transition.
                onSlideNext   : function(el) {}, // Executes immediately before each "Next" transition.
                onSlidePrev   : function(el) {}, // Executes immediately before each "Prev" transition.
                onBeforeClose : function(el) {}, // Executes immediately before the start of the close process.
                onCloseAfter  : function(el) {}, // Executes immediately once lightGallery is closed.
                onOpenExternal  : function(el, index) {
                    if($(el).attr('data-url')) {
                        var href = $(el).attr("data-url");
                    } else {
                        var href = $("#presi-light-gallery li").eq(index).attr('data-url');
                    }
                    if(href) {
                        presi_loadHref(href,true);
                    }else {
                        return false;
                    }

                }, // Executes immediately before each "open external" transition.
                onToggleInfo  : function(el) {
                  var $info = $(".lg-info");
                  if($info.css("opacity") == 1){
                    $info.fadeTo("slow",0);
                  }else{
                    $info.fadeTo("slow",1);
                  }
                } // Executes immediately before each "toggle info" transition.
              });
            });

            jQuery(".tile").on('click', function (event){
                if(jQuery(event.target).hasClass('presi-product-buy-button') || jQuery(event.target).hasClass('presi-product-checkout-button')) {
                    return false;
                }
                <?php if($presi_schedule->options[PRESIOption::kDirectLinking]){ ?>
                event.preventDefault();
                var url = jQuery(this).attr("data-url");
                if(url != '') {
                    var blank = (<?php echo $gridType == PRESIGridType::CLIENT_LOGOS ? 1 : 0; ?>) ? true : false;
                    presi_loadHref(url, blank);
                } else {
                    return false;
                }
                <?php } ?>

                event.preventDefault();
                if(jQuery(event.target).hasClass("fa") && !jQuery(event.target).hasClass("zoom")) return;

                <?php if($gridType == PRESIGridType::ALBUM || $gridType == PRESIGridType::SCHEDULE) { ?>
                var tileId = jQuery(this).attr("id");
                var target = jQuery("#" + tileId + " .presi-light-gallery li:first");
                <?php } else { ?>
                var tileId = jQuery(".tile-inner", jQuery(this)).attr("id");
                var target = jQuery("#presi-light-gallery-item-"+tileId);
                <?php } ?>
                target.trigger( "click" );
            });

        });
    })( jQuery );

</script>
