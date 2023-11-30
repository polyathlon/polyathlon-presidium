<div class="presi-admin-header-banner">
<?php
    $presiBanner = PRESIHelper::getBanner('header');
    if (!empty($presiBanner)) {
        echo $presiBanner['content'];
    } else {
?>
        <style>
            .presi-default-banner-box {
                width: 100%;
                background-color: #6001d2;
                height: 100px;
                margin-bottom: 20px;
                color: white;
            }
            .presi-default-banner-box--bg-image {
                background-size: contain;
                background-position: center;
                background-repeat: no-repeat;
            }
            .presi-default-banner-box--logo-block {
                padding:4px 0px 0px 20px;
                float: left;
            }
            .presi-default-banner-box--logo {
                background-image: url('<?php echo PRESI_IMAGES_URL.'/admin/banner/logo.png'; ?>');
                width: 100px;
                height: 100px;
            }
            .presi-default-banner-box--logo-title {
                text-align: center;
                margin-top: -5px;
            }
            .presi-default-banner-box--title-block {
                padding-top: 20px;
            }
            .presi-default-banner-box--title-block-icon {
                background-image: url('<?php echo PRESI_IMAGES_URL.'/admin/banner/polyathlon.png'; ?>');
                width: 220px;
                height: 70px;
                margin: 0 auto;
            }
            .presi-default-banner-box--menu-block {
                float: right;
            }
            .presi-default-banner-box--menu-block-help {
                background-image: url('<?php echo PRESI_IMAGES_URL.'/admin/banner/support.png'; ?>');
                margin-top: -60px;
                width: 45px;
                height: 45px;
                margin-right: 20px;
                display: block;
            }
            .presi-default-banner-box--menu-block-help:hover {
                opacity: 0.8;
            }
            .presi-default-banner-box--menu-block-help:active,
            .presi-default-banner-box--menu-block-help:focus {
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;
            }
        </style>
        <div class="presi-default-banner-box">
            <div class="presi-default-banner-box--logo-block">
                <div class="presi-default-banner-box--logo presi-default-banner-box--bg-image"></div>
                <!-- <div class="presi-default-banner-box--logo-title">FREE</div> -->
            </div>
            <div class="presi-default-banner-box--title-block">
                <div class="presi-default-banner-box--title-block-icon presi-default-banner-box--bg-image"></div>
            </div>
            <div class="presi-default-banner-box--menu-block">
                <a href="https://wordpress.org/support/plugin/schedule-wp/" target="_blank" class="presi-default-banner-box--menu-block-help presi-default-banner-box--bg-image"></a>
            </div>
        </div>
<?php
    }
?>
</div>
