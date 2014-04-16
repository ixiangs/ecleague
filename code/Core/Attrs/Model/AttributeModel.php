<?php
namespace Core\Attrs\Model;

use Toy\Orm;

class AttributeModel extends Orm\Model
{

    const TABLE_NAME = '{t}attrs_attribute';

    const INPUT_TYPE_TEXTBOX = 'textbox';
    const INPUT_TYPE_TEXTAREA = 'textarea';
    const INPUT_TYPE_DROPDOWN = 'dropdown';
    const INPUT_TYPE_LISTBOX = 'listbox';
    const INPUT_TYPE_CHECKBOX_LIST = 'checkbox_list';
    const INPUT_TYPE_RADIO_LIST = 'radio_list';
    const INPUT_TYPE_DATE_PICKER = 'date_picker';

    const DATA_TYPE_STRING = 'string';
    const DATA_TYPE_INTEGER = 'integer';
    const DATA_TYPE_BOOLEAN = 'boolean';
    const DATA_TYPE_NUMBER = 'number';
    const DATA_TYPE_ARRAY = 'array';
    const DATA_TYPE_EMAIL = 'email';
    const DATA_TYPE_DATE = 'date';

}

Orm\Model::register('Core\Attrs\Model\AttributeModel', array(
    'table' => AttributeModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true)->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('data_type')->setNullable(false),
        Orm\StringProperty::create('input_type')->setNullable(false),
        Orm\StringProperty::create('input_id'),
        Orm\StringProperty::create('input_name'),
        Orm\BooleanProperty::create('indexable')->setDefaultValue(false)->setNullable(false),
        Orm\BooleanProperty::create('required')->setDefaultValue(false)->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(false)->setNullable(false),
        Orm\SerializeProperty::create('names')->setNullable(false),
        Orm\SerializeProperty::create('display_labels')->setNullable(false),
        Orm\SerializeProperty::create('form_labels')->setNullable(false),
        Orm\SerializeProperty::create('input_setting')
    ),
    'relations'=>array(
        Orm\Relation::childrenRelation('options', 'Core\Attrs\Model\AttributeOptionModel', 'attribute_id')
    )
));