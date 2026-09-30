const { __ } = wp.i18n;

import { Button } from '@wordpress/components';
import SortableList, { SortableItem } from 'react-easy-sort'
import {arrayMoveImmutable} from 'array-move'

import { Item } from "./Item";

/**
 * PostSelector Component
 */
const ItemsSlider = (props) => {
  let {
    items
  } = props;

  const setItem = (key, label, value) => {
    const _items = items.map(function (item, index) {
      if (item.id === key) {
        item[label] = value;
      }
      return item;
    });
    props.updateItems(_items);
  }

  const addItem = (e) => {
    let _items = [...items];
    _items.push(
      {
        id: Math.floor((Math.random() * 899999) + 100000),
        imageId: undefined,
        imageUrl: '',
        title: '',
        subtitle: ''
      }
    );
    props.updateItems(_items);
  }

  const addItemBegining = (e) => {
    let _items = [...items];
    _items.unshift(
      {
        id: Math.floor((Math.random() * 899999) + 100000),
        imageId: undefined,
        imageUrl: '',
        title: '',
        subtitle: '',
      }
    );
    props.updateItems(_items);
  }

  const removeItem = (key) => {
    let _items = items.filter((item, index) => (item.id !== key));
    props.updateItems(_items);
  }

  const onSortEnd = (oldIndex, newIndex) => {
    const _items = arrayMoveImmutable(items, oldIndex, newIndex);
    props.updateItems(_items)
  }

  const half_part = (items.length > 0) ? <><SortableList onSortEnd={onSortEnd} className="items_slider">
    {items.map((item, index) => (
      <SortableItem key={item}>
        <div className="items_slider__item col">
          <Item ID={item.id} item={item} onItemChange={setItem} onRemoveItem={removeItem} />
        </div>
      </SortableItem>
    ))}
  </SortableList>
    <Button
      className="add_item"
      variant="primary"
      icon="plus"
      text={__('Add Item', mrk_block_var.domain)}
      onClick={addItem} /></> : null;

  return <>
    <Button
      className="add_item"
      variant="primary"
      icon="plus"
      text={__('Add Item', mrk_block_var.domain)}
      onClick={addItemBegining} />
     {half_part}
  </>;
}

export { ItemsSlider };
