<?php

function feedback_banner_settings()
{

    add_settings_section(
        'feedback_banner_setting_section',
        'Feedback Banner Settings',
        'feedback_banner_section_intro',
        'feedback_banner'
    );

    add_settings_field(
        'feedback_banner_active',
        'Banner Active',
        'feedback_banner_setting_checkbox',
        'feedback_banner',
        'feedback_banner_setting_section',
        array('option_name' => 'feedback_banner_active')
    );

    add_settings_field(
        'feedback_banner_title',
        'Banner Title',
        'feedback_banner_setting_textfield',
        'feedback_banner',
        'feedback_banner_setting_section',
        array('option_name' => 'feedback_banner_title')
    );

    add_settings_field(
        'feedback_banner_text',
        'Banner Text',
        'feedback_banner_setting_textfield',
        'feedback_banner',
        'feedback_banner_setting_section',
        array('option_name' => 'feedback_banner_text')
    );

    add_settings_field(
        'feedback_banner_btn_text',
        'Button Text',
        'feedback_banner_setting_textfield',
        'feedback_banner',
        'feedback_banner_setting_section',
        array('option_name' => 'feedback_banner_btn_text')
    );

    add_settings_field(
        'feedback_banner_btn_link',
        'Button Link',
        'feedback_banner_setting_textfield',
        'feedback_banner',
        'feedback_banner_setting_section',
        array('option_name' => 'feedback_banner_btn_link')
    );

    // Register settings
    register_setting('feedback_banner', 'feedback_banner_active');
    register_setting('feedback_banner', 'feedback_banner_title');
    register_setting('feedback_banner', 'feedback_banner_text');
    register_setting('feedback_banner', 'feedback_banner_btn_text');
    register_setting('feedback_banner', 'feedback_banner_btn_link');
}

add_action('admin_init', 'feedback_banner_settings');

function feedback_banner_section_intro()
{
    echo '<p>Enter the fields below and a banner will display at the top of the website on all pages</p>';
}

function feedback_banner_setting_checkbox($args)
{
    $option_value = get_option($args['option_name']);
    ?>
    <input type='checkbox' name='<?php echo $args['option_name']; ?>' id='<?php echo $args['option_name']; ?>'
           value='yes' <?= checked('yes', $option_value ?? '') ?>
           class='moj-component-input-checkbox'>
    <?php

    return null;
}

function feedback_banner_setting_textfield($args)
{

    $option_value = get_option($args['option_name']);
    ?>
    <input type='text' name='<?php echo $args['option_name']; ?>' id='<?php echo $args['option_name']; ?>'
           value='<?php echo $option_value; ?>' class='moj-component-input'>
    <?php

    return null;
}

function feedback_banner_options_page()
{
    // add top level menu page
    add_options_page(
        'Feedback Banner',
        'Feedback Banner',
        'manage_options',
        'feedback_banner_settings',
        'feedback_banner_options_page_html'
    );
}

add_action('admin_menu', 'feedback_banner_options_page');


function feedback_banner_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php

            settings_fields('feedback_banner');

            do_settings_sections('feedback_banner');

            submit_button();
            ?>
        </form>
    </div>
    <?php
}