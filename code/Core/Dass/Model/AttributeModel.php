<?php
namespace Core\Dass\Model;

use Toy\Orm;

class AttributeModel extends Orm\Model
{

    const TABLE_NAME = '{t}dass_attribute_main';

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

Orm\Model::register('Core\Dass\Model\AttributeModel', array(
    'table' => AttributeModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true)->setNullable(false),
        Orm\StringProperty::create('data_type')->setNullable(false),
        Orm\StringProperty::create('input_type')->setNullable(false),
        Orm\BooleanProperty::create('indexable')->setDefaultValue(false)->setNullable(false),
        Orm\BooleanProperty::create('required')->setDefaultValue(false)->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(false)->setNullable(false),
        Orm\SerializeProperty::create('form_setting'),
        Orm\SerializeProperty::create('validate_setting')
    ),
    'relations'=>array(
        array('name'=>'versions', 'model'=>'Core\Dass\Model\AttributeVersionModel', 'parentId'=>'id', 'childId'=>'main_id', 'type'=>'oneToMore')
    )
));