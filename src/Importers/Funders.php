<?php

namespace Merakcamacol\Importers;

use Baxtian\WP_Importer\Data\Media;
use Baxtian\WP_Importer as WP_Importer;

// use Merakcamacol\Relationships;

/**
 * Componente para importar proveedores
 */
class Funders extends WP_Importer
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
		$this->tipo   = 'funder';
		$this->tipos  = 'funders';
		$this->accion = 'import_funders';

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
				'name' => 'services_title',
				'type' => 'services',
			],
			[
				'name' => 'services_text',
				'type' => 'services',
			],
			[
				'name' => 'services_link',
				'type' => 'services',
			],
			[
				'name'  => 'services_image',
				'type'  => 'services',
				'media' => true,
			],
		];

		// Textos que se mostrarán en el proceso.
		$this->textos = [
			'mensaje_publicacion' => __('Funders added: %d, Funders updated: %d, Terms added: %d', MRK_D),
			'import_button'       => __('Import Funders', MRK_D),
			'import_file'         => __('Funders Archive', MRK_D),
			'plural'              => __('funders', MRK_D),
			'description'         => __('Import Funders from a file.', MRK_D),
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
			$text = '<!-- wp:mrk/funderdata --><div class="wp-block-mrk-funderdata"><p>Supplier data</p></div><!-- /wp:mrk/funderdata --><!-- wp:spacer {"height":"80px"} --><div style="height:80px" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer --><!-- wp:heading --><h2 class="wp-block-heading">Catálogo</h2><!-- /wp:heading --><!-- wp:spacer {"height":"60px"} --><div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer --><!-- wp:mrk/services --><div class="wp-block-mrk-services"><p>Services</p></div><!-- /wp:mrk/services -->';
			$post = [
				'ID'           => $post_id,
				'post_content' => $text,
			];
			wp_update_post($post);

			// Crear arreglos de servicios
			$services_title = explode('|', $data['services_title']);
			$services_text  = explode('|', $data['services_text']);
			$services_link  = explode('|', $data['services_link']);
			$services_image = explode('|', $data['services_image']);

			$services = [];
			foreach($services_title as $key => $product) {
				$imageId    = Media::get_instance()->import_media($services_image[$key]);
				$imageUrl   = ($imageId) ? wp_get_attachment_image_url($imageId, 'thumbnail') : '';
				$services[] = [
					'id'       => mt_rand(11111, 99999),
					'imageId'  => $imageId,
					'imageUrl' => $imageUrl,
					'title'    => $services_title[$key],
					'text'    => $services_text[$key],
					'link'     => $services_link[$key],
				];
			}

			// Guadar catálogo
			update_post_meta($post_id, 'services', json_encode($services));
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
				$services_title = $services_text = $services_link = $services_image = [];

				// Extraer services
				$services = get_post_meta($item[0], 'services', true);
				if(!empty($services)) {
					$services = json_decode($services);
					if(is_array($services)) {
						foreach($services as $product) {
							$services_title[] = $product->title;
							$services_text[]  = $product->text;
							$services_link[]  = $product->link;
							$services_image[] = Media::get_instance()->export_media($product->imageId);
						}
					}
				}

				// Crear arreglos services_title, services_link y services_image
				// y anexarlos al item
				$item[10] = implode('|', $services_title);
				$item[11] = implode('|', $services_text);
				$item[12] = implode('|', $services_link);
				$item[13] = implode('|', $services_image);

			}
		}

		return $data;
	}

	// /**
	//  * Importar los datos de esta estructura a medida
	//  *
	//  * @param array $data
	//  * @param string $tipo
	//  * @param string $tipos
	//  * @param array $campos
	//  * @return array
	//  */
	// public function import_data($answer, $tipo, $import_batch, $fields)
	// {
	// 	// Si no es tipo supplier retornar el answer directamente

	// 	// Agregar a fileds el campo post_content

	// 	// Agregar a import_batch el campo popst content
	// 	$a = 1;
	// 	return $answer;
	// }
}
