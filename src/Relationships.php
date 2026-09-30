<?php

namespace Merakcamacol;

use Baxtian\WP_P2P as WP_P2P;
use Merakcamacol\Init as Merakcamacol;

class Relationships extends WP_P2P
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente
	 */
	protected function __construct()
	{
		parent::__construct(Merakcamacol::FILE);
	}

	/**
	 * Declare relationships.
	 */
	public function connections(): void
	{

		$this->register_relationship([
			'name' => 'post_to_supplier',
			'from' => [
				'type'        => 'post',
			],
			'to' => [
				'type'                    => 'supplier',
				'title'                   => __('Supplier', MRK_D),
				'display_in_admin_column' => -2,
				'cardinality' => 'ONE',
			],
		]);

		$this->register_relationship([
			'name' => 'post_to_funder',
			'from' => [
				'type'        => 'post',
			],
			'to' => [
				'type'                    => 'funder',
				'title'                   => __('Funder', MRK_D),
				'display_in_admin_column' => -2,
				'cardinality' => 'ONE',
			],
		]);
	}
}
