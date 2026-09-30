const { __ } = wp.i18n;
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from "@wordpress/block-editor";
import { ItemsSlider } from './Components/ItemsSlider';
import { useState } from '@wordpress/element'
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';

registerBlockType(mrk_block_var.prefix + '/services', {
	title: __('Services', mrk_block_var.domain),
	icon: 'portfolio',
	category: 'camacol',
	keywords: [
		__('list', mrk_block_var.domain),
		__('products', mrk_block_var.domain),
	],
	attributes: {},
	supports: {
		anchor: true,
		align: ["wide", "full"]
	},
	edit: (props) => {
		const postType = useSelect(
			(select) => select('core/editor').getCurrentPostType(),
			[]
		);

		// Manejo de metacampos
		const [meta, setMeta] = useEntityProp('postType', postType, 'meta');
		const [services, setServices] = useState(
			 (meta['services'] == undefined || meta['services'] == '') ? [] : JSON.parse(meta['services'])
		);

		return <div className={props.className}>
			<ItemsSlider items={(!Array.isArray(services)) ? [] : services}
				updateItems={
					(newItems) => {
						setServices(newItems);
						setMeta({ ...meta, services: JSON.stringify(newItems) })
					}
				}
			/>
		</div>;
	},
	save: (props) => {
		// Para mantener la visual del sitio en caso de que este plugin
		// sea desactivado se recomienda incluir el HTML.
		// Puede ser similar al twig. Lo importante es que incluya el
		// div de apertura con la clase y un contenido real.
		const blockProps = useBlockProps.save();
		return <div {...blockProps}>
			<p>Services</p>
		</div>;
	},
});
