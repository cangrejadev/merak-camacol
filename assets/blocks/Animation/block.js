const { __ } = wp.i18n;
import { PanelBody, SelectControl, CheckboxControl, TextControl } from "@wordpress/components";
import { addFilter } from "@wordpress/hooks";
import { InspectorControls } from "@wordpress/block-editor";
import { createHigherOrderComponent } from "@wordpress/compose";

const settings = function (settings, name) {
	if (name.includes("core/")) {
		settings.attributes = {
			...settings.attributes,
			animation: {
				type: "string",
				default: "",
			},
			zoomeffect: {
				type: "bool",
				default: false,
			},
			animationdelay: {
				type: "string",
				default: "",
			}
		};
	}

	return settings;
};

const edit = createHigherOrderComponent(function (BlockEdit) {
	return function (props) {
		const { name, attributes, setAttributes } = props;

		// Early return if the block is not the Table block.
		if (!name.includes("core/")) {
			return <BlockEdit {...props} />;
		}

		const { animation, zoomeffect, animationdelay } = attributes;

		const control_zoomeffect = (name == "core/image") ?
			<CheckboxControl
				label={__("Zoom Effect", mrk_block_var.domain)}
				checked={zoomeffect}
				onChange={
					function (newZoomEffect) {
						setAttributes({
							zoomeffect: newZoomEffect
						});
					}
				}>
			</CheckboxControl> :
			null;

		return <>
			<BlockEdit {...props}></BlockEdit>
			<InspectorControls>
				<PanelBody title={__("Animation", mrk_block_var.domain)}>
					<SelectControl
						label={__("Effect", mrk_block_var.domain)}
						value={animation}
						options={[
							{ label: __("None", mrk_block_var.domain), value: "" },
							{ label: __("From left to right", mrk_block_var.domain), value: "left-right" },
							{ label: __("From right to left", mrk_block_var.domain), value: "right-left" },
							{ label: __("From bottom to top", mrk_block_var.domain), value: "bottom-up" },
							{ label: __("Zoom in", mrk_block_var.domain), value: "zoom-in" },
						]}
						onChange={
							function (newAnimation) {
								setAttributes({
									animation: newAnimation
								});
							}
						}>
					</SelectControl>
					<TextControl
						label={__("Delay", mrk_block_var.domain)}
						value={animationdelay}
						type="number"
						onChange={
							function (newValue) {
								setAttributes({
									animationdelay: newValue
								});
							}
						}>
					</TextControl>
					{control_zoomeffect}
				</PanelBody>
			</InspectorControls>
		</>;

	};
});

const save = function (props, blockType, attributes) {
	if (blockType.name.includes("core/")) {
		const { animation, zoomeffect, animationdelay } = attributes;
		return {
			...props,
			...(animation != '' ? { "data-animation": animation } : {}),
			...(animationdelay != '' ? { "data-animationdelay": animationdelay } : {}),
			...(zoomeffect ? { "zoom-effect": "true" } : {}),
		}
	}

	return props;
};

addFilter("blocks.registerBlockType", "core/filterBlockAttributes", settings);
addFilter("editor.BlockEdit", "core/filterBlockAttributes", edit);
addFilter("blocks.getSaveContent.extraProps", "core/filterBlockAttributes", save);
