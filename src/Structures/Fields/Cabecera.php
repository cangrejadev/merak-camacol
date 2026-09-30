<?php
namespace Merakcamacol\Structures\Fields;

use \Baxtian\WP_PostMeta as PostMeta;

/**
 * Componente estructura cabecera
 */
class Cabecera extends PostMeta
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente
	 */
	protected function __construct()
	{
		// Determinar las variables
		$this->handle     = 'cabecera';
		$this->display    = 'side';
		$this->post_types = ['post','page'];
		$this->prefix     = MRK_P;
		$this->domain     = MRK_D;
		// $this->column_position = false;

		// Acciones
		add_action('init', [$this, 'init']);

		parent::__construct();
	}

	/**
	 * Declarar los datos de este PostMeta
	 */
	public function init()
	{
		// Declaración de las variables postmeta
		// Se hace acá para dar tiempo a que las funciones de idiomas carguen
		$this->label     = __('Heading image', MRK_D);
		$this->variables = [
			'heading_desktop' => [
				'type'      => 'image',
				'label'     => __('Desktop', MRK_D),
				'replace'   => __('Replace desktop image', MRK_D),
				'quickedit' => false,
				// 'column'    => true,
				// 'sortable' => false,
			],
			'heading_tablet' => [
				'type'      => 'image',
				'label'     => __('Tablet', MRK_D),
				'replace'   => __('Replace tablet image', MRK_D),
				'quickedit' => false,
				// 'column'    => true,
				// 'sortable' => false,
			],
			'heading_mobile' => [
				'type'      => 'image',
				'label'     => __('Mobile', MRK_D),
				'replace'   => __('Replace mobile image', MRK_D),
				'quickedit' => false,
				// 'column'    => true,
				// 'sortable' => false,
			],
		];

		// Ya con las variables podemos inicializar los llamados a las funciones
		parent::init();
	}
}
