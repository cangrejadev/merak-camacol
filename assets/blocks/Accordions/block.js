const { __ } = wp.i18n;
import { PanelBody, CheckboxControl, Toolbar } from '@wordpress/components';
import { registerBlockType, createBlock } from '@wordpress/blocks';
import { InspectorControls, InnerBlocks, BlockControls, useBlockProps } from "@wordpress/block-editor";

registerBlockType(mrk_block_var.prefix + '/accordions', {
	title: __('Accordions', mrk_block_var.domain),
	icon: 'list-view',
	category: 'camacol',
	keywords: [
		__('Accordion', mrk_block_var.domain),
		__('Group', mrk_block_var.domain),
		__('Commuter', mrk_block_var.domain),
	],
	attributes: {
		groupToggle: {
			type: 'bool',
			default: false
		},
	},
	edit: (props) => {
		const { clientId } = props;
		const { select, dispatch } = wp.data;
		var { groupToggle } = props.attributes;

		const allowedBlocks = [mrk_block_var.prefix + '/accordion'];
		const template = [[mrk_block_var.prefix + '/accordion']];
		const parentBlock = select('core/block-editor').getBlocksByClientId(clientId)[0];
		const childBlocks = parentBlock.innerBlocks;

		// Agregar elemento
		const addElement = function () {
			// Crear bloque
			const block = createBlock(mrk_block_var.prefix + '/accordion');

			// Insert the block
			dispatch('core/block-editor').insertBlocks(block, childBlocks.length, clientId);

		};

		// Barra de botones
		const toolbarControls = [
			{
				icon: 'plus-alt',
				label: __('Add accordion', mrk_block_var.domain),
				onClick: () => addElement()
			}
		];

		const inspectorcontrol = <InspectorControls>
			<PanelBody
				title={__('Accordions', mrk_block_var.domain)}
				initialOpen={true}
			>
				<CheckboxControl
					label={__('Keep only one accordion open', mrk_block_var.domain)}
					checked={groupToggle}
					onChange={
						function (newUnfolded) {
							props.setAttributes({
								groupToggle: newUnfolded
							});
						}
					}
				/>
			</PanelBody>
		</InspectorControls>;

		return <>
			{inspectorcontrol}
			<BlockControls>
				<Toolbar controls={toolbarControls} />
			</BlockControls>
			<InnerBlocks template={template} allowedBlocks={allowedBlocks} />
		</>;
	},
	save: (props) => {
		const blockProps = useBlockProps.save();
		const { groupToggle } = props.attributes;
		return <div { ...blockProps } data-group-toggle={(groupToggle) ? "true": "false" }>
			<InnerBlocks.Content />
		</div>;
	}
});
