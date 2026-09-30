<?php

namespace Merakcamacol\Settings;

/**
 * Componentes y controles para el panel de personalización de rede sociales
 */
class Site
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente
	 */
	protected function __construct()
	{
		// Agregar el panel a la pantalla de administración y persoanlización
		add_action('customize_register', [$this, 'options']);
	}

	/**
	 * Agregar el panel a la pantalla de administración y persoanlización
	 * @param  WP_Customize_Manager $wp_customize Instancia del controlador del personalizador
	 */
	public function options($wp_customize)
	{

		// Declarar el campo para 'visualización menú'
		$wp_customize->add_setting(
			'header_config[menu_layout]',
			[
				'type'              => 'option', // o 'theme_mod'
				'sanitize_callback' => 'sanitize_textarea_field',
				'capability'        => 'edit_theme_options',
			]
		);

		// Opción para definir el campo para 'visualización menú'
		$wp_customize->add_control(
			'header_config[menu_layout]',
			[
				'label'       => __('Menu layout', MRK_D),
				'section'     => 'title_tagline',
				'settings'    => 'header_config[menu_layout]',
				'type'        => 'select',
				//'description' => __('Text to be displayed at the bottom of the website. Use %d to add the current year.', MRK_D),
				'choices' => [
					'default' => __('Default', MRK_D),
					'center'  => __('Centered logo', MRK_D),
				],
			]
		);

		// Declarar el campo para 'Página de error'
		$wp_customize->add_setting(
			'site[error_page]',
			[
				'type'              => 'option', // o 'theme_mod'
				'sanitize_callback' => 'sanitize_textarea_field',
				'capability'        => 'edit_theme_options',
			]
		);

		// Opción para definir el campo para 'Footer'
		$wp_customize->add_control(
			'site[error_page]',
			[
				'label'       => __('Error page', MRK_D),
				'section'     => 'title_tagline',
				'settings'    => 'site[error_page]',
				'type'        => 'dropdown-pages',
				'description' => __('Use this page as 404 page.', MRK_D),
			]
		);

		// Declarar el campo para 'imagen al compartir'
		$wp_customize->add_setting(
			'site[og_image]',
			[
				'type'              => 'option', // o 'theme_mod'
				'capability'        => 'edit_theme_options',
			]
		);

		// Asignar control definir imagen por defecto al compartir
		$wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$wp_customize,
				'site[og_image]',
				[
					'label'       => __('Default image when sharing', MRK_D),
					'description' => __('Image to display as the default thumbnail when sharing a page.', MRK_D),
					'section'     => 'title_tagline',
					'settings'    => 'site[og_image]',
				]
			)
		);

		// Declarar el campo para 'Footer'
		$wp_customize->add_setting(
			'footer[text]',
			[
				'type'              => 'option', // o 'theme_mod'
				'sanitize_callback' => 'sanitize_textarea_field',
				'capability'        => 'edit_theme_options',
			]
		);

		// Opción para definir el campo para 'Footer'
		$wp_customize->add_control(
			'footer[text]',
			[
				'label'       => __('Footer text', MRK_D),
				'section'     => 'title_tagline',
				'settings'    => 'footer[text]',
				'type'        => 'textarea',
				'description' => __('Text to be displayed at the bottom of the website. Use %d to add the current year.', MRK_D),
			]
		);
	}
}
