<?php
/**
 * Plantilla de la página de inicio.
 */
use Merakcamacol\Init as Merakcamacol;
use Timber\Timber;

$context         = Timber::context();
$posts            = Timber::get_posts();
$context['posts'] = $posts;
Merakcamacol::get_instance()->render(['templates/home.twig', 'templates/index.twig'], $context);
