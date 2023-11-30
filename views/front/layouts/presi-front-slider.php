<?php
    $iconStyle = 'fa-angle-';
    $leftArrClass = substr($iconStyle, -1) == '-' ? $iconStyle.'left' : $iconStyle;
    $rightArrClass = substr($iconStyle, -1) == '-' ? $iconStyle.'right' : $iconStyle;
?>

<style>

    #presi-slider-<?php echo $presi_schedule->id; ?> .owl-carousel {
        padding-left: 0;
        padding-right: 0
    }

    #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-image-wrapper {
        height: 400px
    }

    #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-image {
        background-size: cover;
        background-position: center
    }

    #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-prev, #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-next {
        top: 200px
    }

    #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-prev, #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-next {
        padding: 34px;
        margin-left: 20px;
        margin-right: 20px
    }

    #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-prev i, #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-next i {
        color: #e2e2e2;
        font-size: 60px
    }

    #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-prev:hover i, #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-next:hover i, #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-prev:active i, #presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-next:active i {
        color: #fff
    }

</style>

<div id="presi-slider-<?php echo $presi_schedule->id; ?>" class="presi-slider-layout">
    <a class="presi-slider-ctrl presi-slider-ctrl-prev"><i class="fa <?php echo $leftArrClass; ?>"></i></a>
    <a class="presi-slider-ctrl presi-slider-ctrl-next"><i class="fa <?php echo $rightArrClass; ?>"></i></a>
    <div class="owl-carousel">
        <?php
            foreach ($presi_schedule->projects as $presi_project) {
                $coverInfo = PRESIHelper::decode2Obj(PRESIHelper::decode2Str($presi_project->cover));
                if (empty($coverInfo)) {
                    continue;
                }
                $url = isset($presi_project->url) ? $presi_project->url : "";
                $title = isset($presi_project->title) ? PRESIHelper::decode2Str($presi_project->title) : "";

                $coverInfo = PRESIHelper::decode2Obj(PRESIHelper::decode2Str($presi_project->cover));
                $coverType = !isset($coverInfo->type) ? PRESIAttachmentType::PICTURE : $coverInfo->type;
                $meta = PRESIHelper::getAttachementMeta($coverInfo->id, $presi_schedule->options[PRESIOption::kThumbnailQuality]);
                $metaOriginal = PRESIHelper::getAttachementMeta($coverInfo->id);
            ?>

                    <div class="presi-slider-cell">
                        <div class="presi-slider-image-wrapper">
                            <?php
                                $imgHtml = '<div class="presi-slider-image" style="background-image: url('.$meta['src'].'"></div>';
                                $blank = ($presi_schedule->options[PRESIOption::kLoadUrlBlank]) ? ' target="blank" ' : '';
                                echo !empty($url) ? '<a href="' . $url . '" '.$blank.'>'.$imgHtml.'</a>' : $imgHtml;
                            ?>
                        </div>
                    </div>
            <?php

            }
        ?>
    </div>
</div>


<script>
    jQuery(document).ready(function(){

        jQuery('#presi-slider-<?php echo $presi_schedule->id; ?> .owl-carousel').owlCarousel({
            lazyLoad: false,
            items: 1,
            margin: 10,
            center: false,
            loop: true,
            autoplay: false,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            autoHeight: false,
            mouseDrag: true,
            touchDrag: true,
            nav: false,
            slideBy: 1,
            dots: false,
            dotsEach: false,
            animateOut: '',
            animateIn: ''
        });

        jQuery('#presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-prev').click(function() {
            jQuery(this).closest('.presi-slider-layout').find('.owl-carousel').trigger('prev.owl.carousel');
        });

        jQuery('#presi-slider-<?php echo $presi_schedule->id; ?> .presi-slider-ctrl-next').click(function() {
            jQuery(this).closest('.presi-slider-layout').find('.owl-carousel').trigger('next.owl.carousel');
        });

        jQuery(window).resize(function(){
            presi_AdjustSlider(jQuery("#presi-slider-<?php echo $presi_schedule->id; ?>"));
        });

        function presi_AdjustSlider(slider) {
            if (slider.width() <= 600) {
                slider.addClass('presi-slider-mobile');
            } else {
                slider.removeClass('presi-slider-mobile');
            }
        }
        presi_AdjustSlider(jQuery("#presi-slider-<?php echo $presi_schedule->id; ?>"));

    });
</script>
