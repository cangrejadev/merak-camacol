<?php

namespace Merakcamacol\Widgets;

use Merakcamacol\Init as Merakcamacol;

/**
 * Componentes y controles para el panel de personalización de rede sociales.
 */
class SocialNetworks extends \WP_Widget
{
    use \Baxtian\SingletonTrait;

    /**
     * Inicializa el componente.
     */

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            'foo_widget', // Base ID
            esc_html__('Social networks', MRK_D), // Name
            ['description' => esc_html__('Display list of social networks', MRK_D)] // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     widget arguments
     * @param array $instance saved values from database
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'].apply_filters('widget_title', $instance['title']).$args['after_title'];
        }
        $context['social_network'] = get_option('social_network');
        $tpl = ['atoms/images/social-media.twig'];

        Merakcamacol::get_instance()->render($tpl, $context);
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance previously saved values from database
     */
    public function form($instance)
    {
        $title = $instance['title']; ?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', MRK_D); ?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance values just sent to be saved
     * @param array $old_instance previously saved values from database
     *
     * @return array updated safe values to be saved
     */
    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

        return $instance;
    }
}
