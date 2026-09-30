<?php
namespace Merakcamacol\Blocks;

use Merakcamacol\Init as Merakcamacol;
use Baxtian\WP_Block as Block;

/**
 * Bloque Accordions
 */
class Accordions extends Block
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente
	 */
	protected function __construct()
	{
		$this->merak        = Merakcamacol::get_instance();
		$this->blockname    = MRK_P . '/accordions';
		$this->classname    = 'Accordions';
		$this->dependencies = [
			// 'block' => [],
			// 'editor' => [],
			// 'frontend' => [],
			// 'style'    => [],
		];
		$this->attributes = [
			'groupToggle' => [
				'type'    => 'boolean',
				'default' => false,
				'global'  => false,
			],
		];

		add_action('init', [$this, 'init']);

		parent::__construct();
	}

	/**
	 * Modifica el bloque para que atienda la función de renderizado.
	 */
	public function init()
	{
		$this->register_block_type();
	}
}
