const { __ } = wp.i18n;
import { PanelBody, TextControl } from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls } from "@wordpress/block-editor";
import ServerSideRender from '@wordpress/server-side-render';

registerBlockType(mrk_block_var.prefix + '/latestposts', {
	title: __('Latest posts', mrk_block_var.domain),
	icon: 'list-view',
	category: 'camacol',
	keywords: [
		__('posts', mrk_block_var.domain),
		__('news', mrk_block_var.domain),
		__('grid', mrk_block_var.domain),
	],
	attributes: {
		columns: {
			type: 'number',
			default: 3
		}
	},
	edit: (props) => {
		var {
			columns
		} = props.attributes;

		// Caja lateral
		const inspectorcontrol = <InspectorControls>
			<PanelBody
				title={__('Category slider', mrk_block_var.domain)}
				initialOpen={true}
			>
				<TextControl
					label={__('Columns', mrk_block_var.domain)}
					type="number"
					min={1}
					max={4}
					value={columns}
					onChange={(newValue) => {
						props.setAttributes({ columns: parseInt(newValue) });
					}}
				/>
			</PanelBody>
		</InspectorControls>;

		return <>
			{inspectorcontrol}
			<h2>{__('Latest posts', mrk_block_var.domain)}</h2>
			<p>{__('Cards with latest posts.', mrk_block_var.domain)}</p>
		</>;
	},
	save: (props) => {
		// Para mantener la visual del sitio en caso de que este plugin
		// sea desactivado se recomienda incluir el HTML.
		// Puede ser similar al twig. Lo importante es que incluya el
		// div de apertura con la clase y un contenido real.
		return <div className={props.className}>
			{__('Latest posts', mrk_block_var.domain)}.
		</div>;
	},
});
