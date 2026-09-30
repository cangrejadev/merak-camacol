<?php
/**
 * Los complementos de terceros que secuestran el tema harán un llamado a
 * wp_head() para obtener la plantilla de encabezado.
 * Usamos esto para iniciar nuestro propio búfer de salida y renderizar en la
 * plantilla templates/page-plugin.twig en footer.php
 *
 * Si no está utilizando un complemento que requiere este comportamiento (incluyendo
 * Events Calendar Pro y WooCommerce) puede eliminar este archivo junto con footer.php
 */

use Timber\Timber;

$GLOBALS['timberContext'] = Timber::context();
ob_start();
