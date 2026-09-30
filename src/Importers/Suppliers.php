<?php

namespace Merakcamacol\Importers;

use Baxtian\WP_Importer\Data\Media;
use Baxtian\WP_Importer as WP_Importer;

// use Merakcamacol\Relationships;

/**
 * Componente para importar proveedores
 */
class Suppliers extends WP_Importer
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente
	 */
	protected function __construct()
	{
		add_action('init', [$this, 'init']);

		// Acciones a ejecutar una vez terminada una fila de datos
		add_action('MrkImporter/row_added', [$this, 'row_added'], 10, 3);

		// Estructura particular así que debemos usar sistemas personalizados
		// de exportación e importación
		add_filter('MrkImporter/export_data', [$this, 'export_data'], 10, 4);
		// add_filter('MrkImporter/import_data', [$this, 'import_data'], 10, 4);

		parent::__construct();
	}

	/**
	 * Declarar los datos para la importación.
	 * Se hace en esta función para dar tiempo a cargar las traducciones.
	 */
	public function init()
	{
		$this->tipo   = 'supplier';
		$this->tipos  = 'suppliers';
		$this->accion = 'import_suppliers';

		// Indicar el número de items por página e importación
		// Esto se hace para estructuras muy grandes
		// $this->items_per_page = 20;

		// Indicar si se debe activar el importador de archivos adjuntos
		$this->import_attachments = true;

		$this->campos = [
			// Determine en este arreglo las columnas de datos que va a importar
			// Los tipos disponibles son
			//	id: es el campo ID de la entrada
			//	field: campo normal de una entrada
			//	thumbnail: imagen destacada
			//	term: taxonomía vinculada a la entrada
			//	postmeta: dato vinculado a la entrada por postmeta
			//	postmeta_textarea: datos tipo html y textarea
			//	postmeta_date: dato tipo fecha
			//	postmeta_datetime: dato tipo fecha y hora
			//	postmeta_bool: dato vinculado a la entrada por post_meta y que es
			//					de tipo booleano
			//  p2p: relaciones tipo p2p.
			// Por defecto deje estos dos campos iniciales por tratarse de
			// los elementos básicos que se necesita para hacer la importación
			// ***** Si es un elemento tipo 'p2p' debe incluir la variable 'p2p'
			// que llevará la instalcia que creó la relación
			// ***** Si un elemento de tipo 'field' tiene un campo 'postmeta',
			// entonces ese valor se almacenará tanto en el 'field' como en
			// el 'postmeta'
			//****************************//
			[
				'name' => 'id',
				'type' => 'id',
			],
			[
				'name'     => 'post_title',
				'type'     => 'field',
				'postmeta' => 'nombre',
			],
			[
				'name' => 'thumbnail',
				'type' => 'thumbnail',
			],
			//****************************//
			[
				'name' => 'project_type',
				'type' => 'term',
			],
			[
				'name' => 'speciality',
				'type' => 'postmeta',
			],
			[
				'name' => 'phone',
				'type' => 'postmeta',
			],
			[
				'name' => 'address',
				'type' => 'postmeta',
			],
			[
				'name' => 'email',
				'type' => 'postmeta',
			],
			[
				'name' => 'whatsapp',
				'type' => 'postmeta',
			],
			[
				'name'  => 'logo',
				'type'  => 'postmeta',
				'media' => true,
			],
			[
				'name' => 'catalog_title',
				'type' => 'catalog',
			],
			[
				'name' => 'catalog_link',
				'type' => 'catalog',
			],
			[
				'name'  => 'catalog_image',
				'type'  => 'catalog',
				'media' => true,
			],
		];

		// Textos que se mostrarán en el proceso.
		$this->textos = [
			'mensaje_publicacion' => __('Suppliers added: %d, Suppliers updated: %d, Terms added: %d', MRK_D),
			'import_button'       => __('Import Suppliers', MRK_D),
			'import_file'         => __('Suppliers Archive', MRK_D),
			'plural'              => __('suppliers', MRK_D),
			'description'         => __('Import Suppliers from a file.', MRK_D),
		];

		//Ya con las variables podemos inicializar los llamados a las funciones
		parent::init();
	}

	/**
	 * Acciones a ejecutar una vez incluida una fila de datos
	 *
	 * @param integer $post_id
	 * @param string $tipo
	 * @param array $data
	 * @return void
	 */
	public function row_added($post_id, $tipo, $data)
	{
		if($tipo == $this->tipo) {
			$text = '<!-- wp:mrk/supplierdata --><div class="wp-block-mrk-supplierdata"><p>Supplier data</p></div><!-- /wp:mrk/supplierdata --><!-- wp:spacer {"height":"80px"} --><div style="height:80px" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer --><!-- wp:heading {"level":"2"} --><h2 class="wp-block-heading">Catálogo</h2><!-- /wp:heading --><!-- wp:spacer {"height":"60px"} --><div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer --><!-- wp:mrk/catalog --><div class="wp-block-mrk-catalog"><p>Catalog</p></div><!-- /wp:mrk/catalog -->';
			$post = [
				'ID'           => $post_id,
				'post_content' => $text,
			];
			wp_update_post($post);

			// Crear arreglos de catálogo
			$catalog_title = explode('|', $data['catalog_title']);
			$catalog_link  = explode('|', $data['catalog_link']);
			$catalog_image = explode('|', $data['catalog_image']);

			$catalog = [];
			foreach($catalog_title as $key => $product) {
				$imageId   = Media::get_instance()->import_media($catalog_image[$key]);
				$imageUrl  = ($imageId) ? wp_get_attachment_image_url($imageId, 'thumbnail') : '';
				$catalog[] = [
					'id'       => mt_rand(11111, 99999),
					'imageId'  => $imageId,
					'imageUrl' => $imageUrl,
					'title'    => $catalog_title[$key],
					'link'     => $catalog_link[$key],
				];
			}

			// Guadar catálogo
			update_post_meta($post_id, 'catalog', json_encode($catalog));
		}
	}

	/**
	 * Incluir los datos a exportar de esta estructura a medida
	 *
	 * @param array $data
	 * @param string $tipo
	 * @param string $tipos
	 * @param array $campos
	 * @return array
	 */
	public function export_data($data, $tipo, $tipos, $campos)
	{
		// Si no es el tipo retornar
		if($tipo != $this->tipo) {
			return $data;
		}

		// Recorrer directorio de datos
		foreach($data as $key => &$item) {
			// La fila 0 es la del encabezado así que se descarta
			if($key > 0) {
				// Inicializar arreglos
				$catalog_title = $catalog_link = $catalog_image = [];

				// Extraer catalog
				$catalog = get_post_meta($item[0], 'catalog', true);
				if(!empty($catalog)) {
					$catalog = json_decode($catalog);
					if(is_array($catalog)) {
						foreach($catalog as $product) {
							$catalog_title[] = $product->title;
							$catalog_link[]  = $product->link;
							$catalog_image[] = Media::get_instance()->export_media($product->imageId);
						}
					}
				}

				// Crear arreglos catalog_title, catalog_link y catalog_image
				// y anexarlos al item
				$item[10] = implode('|', $catalog_title);
				$item[11] = implode('|', $catalog_link);
				$item[12] = implode('|', $catalog_image);

			}
		}

		return $data;
	}

	// /**
	//  * IMportar los datos de esta estructura a medida
	//  *
	//  * @param array $data
	//  * @param string $tipo
	//  * @param string $tipos
	//  * @param array $campos
	//  * @return array
	//  */
	// public function import_data($answer, $tipo, $import_batch, $fields)
	// {
	// 	$a = 1;
	// 	return $answer;
	// }
}
