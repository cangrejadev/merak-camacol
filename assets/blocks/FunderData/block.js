const { __ } = wp.i18n;
import { TextControl, Button, Flex, FlexBlock, FlexItem } from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import { MediaUpload, useBlockProps } from "@wordpress/block-editor";
import { useState, useEffect } from '@wordpress/element'
import { useSelect } from '@wordpress/data'; // Requeridos para el evento al guardar el bloque
import { useEntityProp } from '@wordpress/core-data';

import apiFetch from '@wordpress/api-fetch';

registerBlockType(mrk_block_var.prefix + '/funderdata', {
	title: __('Funder Data', mrk_block_var.domain),
	icon: 'store',
	category: 'camacol',
	keywords: [
		__('supplier', mrk_block_var.domain),
		__('info', mrk_block_var.domain),
	],
	attributes: {},
	edit: (props) => {
		const postType = useSelect(
			(select) => select('core/editor').getCurrentPostType(),
			[]
		);

		// Tipos permitidos
		if (
			postType != 'funder'
		) return null;

		// Manejo de metacampos
		const [meta, setMeta] = useEntityProp('postType', postType, 'meta');
		const [logo, setLogo] = useState(meta['logo']);
		const [speciality, setSpeciality] = useState(meta['speciality']);
		const [email, setEmail] = useState(meta['email']);
		const [whatsapp, setWhatsapp] = useState(meta['whatsapp']);
		const [phone, setPhone] = useState(meta['phone']);
		const [address, setAddress] = useState(meta['address']);
		const [fileData, setFileData] = useState(false);
		const [fileURL, setFileURL] = useState('');

		useEffect(() => {
			const fetchTitle = async () => {
				if (logo !== undefined && logo != '') {
					apiFetch({ path: '/wp/v2/media/' + logo }).then((media) => {
						setFileData(media);
						setFileURL(media.source_url);
					}).catch((error) => { });
				}
			};
			fetchTitle();
		}, []);

		const button_style = {};

		return <div className={props.className}>
			<Flex gap={20} expanded={true} justify={'center'}>
				<FlexBlock>
					<MediaUpload
						allowedTypes={['image']}
						style={{ textAlign: 'center' }}
						value={logo}
						type={'image'}
						onSelect={function (newMedia) {
							setLogo(String(newMedia.id));
							setMeta({ ...meta, logo: String(newMedia.id) });
							setFileData(newMedia);
							setFileURL(newMedia.url);
						}}
						render={function (obj) {
							if (fileData === false || typeof fileData === 'undefined') {
								return <Button
									onClick={obj.open}
									className={'editor-post-featured-image__toggle'}
								>{__('Set logo', mrk_block_var.domain)}</Button>
							} else {
								return <Flex direction={'column'} justify={'center'}>
									<FlexBlock style={{ textAlign: 'center', cursor: 'pointer' }} onClick={obj.open}>
										<img
											src={fileURL}
										/>
									</FlexBlock>
									<FlexBlock style={{ textAlign: 'center' }}>
										<Button
											variant="link"
											isDestructive={true}
											onClick={function () {
												setFileData(false);
												setFileURL('');
												setLogo('');
												setMeta({ ...meta, logo: '' });
											}}>{__('Remove logo', mrk_block_var.domain)}</Button>
									</FlexBlock>
								</Flex>;
							}
						}}
					/>
				</FlexBlock>
				<FlexBlock>
					<TextControl
						label={__('Specialty', mrk_block_var.domain)}
						value={speciality}
						onChange={(newValue) => {
							setSpeciality(newValue);
							setMeta({ ...meta, speciality: newValue });
						}}
					/>
					<TextControl
						label={__('Phone', mrk_block_var.domain)}
						value={phone}
						onChange={(newValue) => {
							setPhone(newValue);
							setMeta({ ...meta, phone: newValue });
						}}
					/>
					<TextControl
						label={__('Address', mrk_block_var.domain)}
						value={address}
						onChange={(newValue) => {
							setAddress(newValue);
							setMeta({ ...meta, address: newValue });
						}}
					/>
					<TextControl
						label={__('Email', mrk_block_var.domain)}
						value={email}
						onChange={(newValue) => {
							setEmail(newValue);
							setMeta({ ...meta, email: newValue });
						}}
					/>
					<TextControl
						label={__('Whatsapp', mrk_block_var.domain)}
						value={whatsapp}
						onChange={(newValue) => {
							setWhatsapp(newValue);
							setMeta({ ...meta, whatsapp: newValue });
						}}
					/>
				</FlexBlock>
			</Flex>
		</div>;
	},
	save: (props) => {
		// Para mantener la visual del sitio en caso de que este plugin
		// sea desactivado se recomienda incluir el HTML.
		// Puede ser similar al twig. Lo importante es que incluya el
		// div de apertura con la clase y un contenido real.
		const blockProps = useBlockProps.save();
		return <div {...blockProps}>
			<p>Supplier data</p>
		</div>;
	},
});
