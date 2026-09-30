<?php

namespace Merakcamacol\Settings;

/**
 * Componentes y controles para el panel de personalización de rede sociales.
 */
class SocialNetworks
{
    use \Baxtian\SingletonTrait;

    /**
     * Inicializa el componente.
     */
    protected function __construct()
    {
        // Agregar el panel a la pantalla de administración y persoanlización
        add_action('customize_register', [$this, 'options']);

        // Inicializar widget
        add_action('widgets_init', function () {
            register_widget('Merakcamacol\Widgets\SocialNetworks');
        });
    }

    /**
     * Agregar el panel a la pantalla de administración y persoanlización.
     *
     * @param WP_Customize_Manager $wp_customize Instancia del controlador del personalizador
     */
    public function options($wp_customize)
    {
        // Agregar sección de 'Redes Sociales'
        $wp_customize->add_section(
            'social_networks',
            [
                'title' => __('Social networks', MRK_D),
            ]
        );

        // Declarar el campo para 'Facebook'
        $wp_customize->add_setting(
            'social_network[facebook]',
            [
                'type' => 'option', // o 'theme_mod'
                'sanitize_callback' => 'esc_url_raw',
                'capability' => 'edit_theme_options',
            ]
        );

        // Opción para definir el campo para 'Facebook'
        $wp_customize->add_control(
            'social_network[facebook]',
            [
                'label' => __('Facebook', MRK_D),
                'section' => 'social_networks',
                'settings' => 'social_network[facebook]',
                'type' => 'text',
                'description' => __('Facebook profile link', MRK_D),
            ]
        );

        // Declarar el campo para 'Twitter'
        $wp_customize->add_setting(
            'social_network[twitter]',
            [
                'type' => 'option', // o 'theme_mod'
                'sanitize_callback' => 'esc_url_raw',
                'capability' => 'edit_theme_options',
            ]
        );

        // Opción para definir el campo para 'Twitter'
        $wp_customize->add_control(
            'social_network[twitter]',
            [
                'label' => __('Twitter', MRK_D),
                'section' => 'social_networks',
                'settings' => 'social_network[twitter]',
                'type' => 'text',
                'description' => __('Twitter profile link', MRK_D),
            ]
        );

        // Declarar el campo para 'Linkedin'
        $wp_customize->add_setting(
            'social_network[linkedin]',
            [
                'type' => 'option', // o 'theme_mod'
                'sanitize_callback' => 'esc_url_raw',
                'capability' => 'edit_theme_options',
            ]
        );

        // Opción para definir el campo para 'Linkedin'
        $wp_customize->add_control(
            'social_network[linkedin]',
            [
                'label' => __('Linkedin', MRK_D),
                'section' => 'social_networks',
                'settings' => 'social_network[linkedin]',
                'type' => 'text',
                'description' => __('Linkedin profile link', MRK_D),
            ]
        );

        // Declarar el campo para 'Instagram'
        $wp_customize->add_setting(
            'social_network[instagram]',
            [
                'type' => 'option', // o 'theme_mod'
                'sanitize_callback' => 'esc_url_raw',
                'capability' => 'edit_theme_options',
            ]
        );

        // Opción para definir el campo para 'Instagram'
        $wp_customize->add_control(
            'social_network[instagram]',
            [
                'label' => __('Instagram', MRK_D),
                'section' => 'social_networks',
                'settings' => 'social_network[instagram]',
                'type' => 'text',
                'description' => __('Instagram profile link', MRK_D),
            ]
        );

        // Declarar el campo para 'YouTube'
        $wp_customize->add_setting(
            'social_network[youtube]',
            [
                'type' => 'option', // o 'theme_mod'
                'sanitize_callback' => 'esc_url_raw',
                'capability' => 'edit_theme_options',
            ]
        );

        // Opción para definir el campo para 'YouTube'
        $wp_customize->add_control(
            'social_network[youtube]',
            [
                'label' => __('YouTube', MRK_D),
                'section' => 'social_networks',
                'settings' => 'social_network[youtube]',
                'type' => 'text',
                'description' => __('YouTube chanel link', MRK_D),
            ]
        );

        // Declarar el campo para 'Vimeo'
        $wp_customize->add_setting(
            'social_network[vimeo]',
            [
                'type' => 'option', // o 'theme_mod'
                'sanitize_callback' => 'esc_url_raw',
                'capability' => 'edit_theme_options',
            ]
        );

        // Opción para definir el campo para 'Vimeo'
        $wp_customize->add_control(
            'social_network[vimeo]',
            [
                'label' => __('Vimeo', MRK_D),
                'section' => 'social_networks',
                'settings' => 'social_network[vimeo]',
                'type' => 'text',
                'description' => __('Vimeo profile link', MRK_D),
            ]
        );
    }
}
