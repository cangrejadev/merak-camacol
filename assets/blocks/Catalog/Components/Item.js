const { __ } = wp.i18n;

import { IconButton, TextControl, TextareaControl } from '@wordpress/components';
import { MediaUpload } from '@wordpress/media-utils';
import { SortableKnob } from 'react-easy-sort'

const Item = (props) => {

  let {
    ID,
    item
  } = props;

  var imageObj = false;

  const replaceImage = () => {
    imageObj.open();
  }

  const imageRender = (obj) => {
    imageObj = obj;
    if (item.imageId === undefined) {
      return <div />
    } else {
      return <><div
        className='selected'
        style={{ backgroundImage: 'url(' + item.imageUrl + ')' }}
      /></>
    }
  }

  const mediaUpload = <MediaUpload
    value={item.imageId}
    allowedTypes='image'
    onSelect={(newMedia) => {
      props.onItemChange(ID, 'imageId', newMedia.id);
      props.onItemChange(ID, 'imageUrl', newMedia.url);
    }}
    render={imageRender}
  />;

  return <div className="item">
    <div className="image"
      onClick={replaceImage}>
      {mediaUpload}
    </div>
    <div className="text">
      <TextControl
        className="title"
        placeholder={__('Title', mrk_block_var.domain)}
        value={item.title}
        onChange={(newValue) => {
          props.onItemChange(ID, 'title', newValue);
        }} />
      <TextControl
        className="title"
        placeholder={__('Link', mrk_block_var.domain)}
        value={item.link}
        onChange={(newValue) => {
          props.onItemChange(ID, 'link', newValue);
        }} />
    </div>
    <div className="remove">
      <IconButton
        icon="no"
        label="Remove Item"
        onClick={(e) => {
          props.onRemoveItem(ID);
        }} />
    </div>
    <SortableKnob>
      <div className="dragHandle dashicons dashicons-menu-alt2"></div>
    </SortableKnob>
  </div>;
}

export { Item };
