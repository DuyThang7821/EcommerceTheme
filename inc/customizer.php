<?php

/**
 * Homepage Customizer settings
 */

function my_theme_customize_register($wp_customize)
{

    // HOME PAGE SECTION
    $wp_customize->add_section('homepage_settings', array(
        'title' => __('Cài đặt trang chủ', 'my-theme'),
        'priority' => 30,
    ));

    // Hero Section
    $wp_customize->add_setting('hero_title', array(
        'default' => 'Welcome to Our Website',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_title', array(
        'label' => __('Hero Title', 'my-theme'),
        'section' => 'homepage_settings',
        'type' => 'text',
    ));

    $wp_customize->add_setting('hero_description', array(
        'default' => 'This is a custom WordPress theme built from scratch.',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('hero_description', array(
        'label' => __('Hero Description', 'my-theme'),
        'section' => 'homepage_settings',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('hero_btn_text', array(
        'default' => 'Get Started',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_btn_text', array(
        'label' => __('Hero Button Text', 'my-theme'),
        'section' => 'homepage_settings',
        'type' => 'text',
    ));

    $wp_customize->add_setting('hero_btn_url', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('hero_btn_url', array(
        'label' => __('Hero Button URL', 'my-theme'),
        'section' => 'homepage_settings',
        'type' => 'url',
    ));

    // Features Section
    $wp_customize->add_setting('features_title', array(
        'default' => 'Our Features',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('features_title', array(
        'label' => __('Features Title', 'my-theme'),
        'section' => 'homepage_settings',
        'type' => 'text',
    ));

    // Feature 1
    $wp_customize->add_setting('feature1_title', array(
        'default' => 'Fast Performance',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('feature1_title', array(
        'label' => __('Feature 1 Title', 'my-theme'),
        'section' => 'homepage_settings',
        'type' => 'text',
    ));

    $wp_customize->add_setting('feature1_desc', array(
        'default' => 'Lightning fast loading times and optimized code.',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('feature1_desc', array(
        'label' => __('Feature 1 Description', 'my-theme'),
        'section' => 'homepage_settings',
        'type' => 'textarea',
    ));

    // Add more feature controls as needed...

    // CTA Section
    $wp_customize->add_setting('cta_title', array(
        'default' => 'Ready to Get Started?',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('cta_title', array(
        'label' => __('CTA Title', 'my-theme'),
        'section' => 'homepage_settings',
        'type' => 'text',
    ));

    $wp_customize->add_setting('cta_btn_text', array(
        'default' => 'Start Now',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('cta_btn_text', array(
        'label' => __('CTA Button Text', 'my-theme'),
        'section' => 'homepage_settings',
        'type' => 'text',
    ));
}
add_action('customize_register', 'my_theme_customize_register');
