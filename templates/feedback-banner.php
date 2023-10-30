<?php
$banner_active = get_option('feedback_banner_active');

if (!empty($banner_active)) {

    $banner_title = get_option('feedback_banner_title');
    $banner_text = get_option('feedback_banner_text');
    $banner_btn_text = get_option('feedback_banner_btn_text');
    $banner_btn_link = get_option('feedback_banner_btn_link');

    ?>
    <div class="content row">
        <div class="main col-sm-12">
            <div class="feedback-banner">
                <h2><?php echo $banner_title; ?></h2>
                <p><?php echo $banner_text; ?></p>
                <a class="feedback-banner-link" href="<?php echo $banner_btn_link; ?>"><?php echo $banner_btn_text; ?></a>
            </div>
        </div>
    </div>
<?php } ?>