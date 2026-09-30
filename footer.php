<?php
/**
 * Plantilla para forzar el uso del footer.
 */
use Merakcamacol\Init as Merakcamacol;
use Exception;

$timberContext = $GLOBALS['timberContext'];
if (! isset($timberContext)) {
	throw new Exception('Timber context not set in footer.');
}
$timberContext['content'] = ob_get_contents();
ob_end_clean();
$templates = ['page-plugin.twig'];
Merakcamacol::get_instance()->render($templates, $timberContext);
