<?php
namespace Core\Attrs\Model;

use Core\Locale\Localize;
use Toy\Orm;
use Toy\Util\ArrayUtil;
use Toy\View\Html\InputField;
use Toy\View\Html\OptionListField;
use Toy\View\Html\SelectField;

class AttributeModel extends Orm\Model
{

    const TABLE_NAME = '{t}attrs_attribute';

    const INPUT_TYPE_TEXTBOX = 'textbox';
    const INPUT_TYPE_TEXTAREA = 'textarea';
    const INPUT_TYPE_EDITOR = 'editor';
    const INPUT_TYPE_SELECT = 'select';
    const INPUT_TYPE_OPTION_LIST = 'option_list';
    const INPUT_TYPE_DATE_PICKER = 'date_picker';

    const DATA_TYPE_STRING = 'string';
    const DATA_TYPE_INTEGER = 'integer';
    const DATA_TYPE_BOOLEAN = 'boolean';
    const DATA_TYPE_NUMBER = 'number';
    const DATA_TYPE_ARRAY = 'array';
    const DATA_TYPE_EMAIL = 'email';
    const DATA_TYPE_DATE = 'date';

    public function toFormField()
    {
        $lid = Localize::singleton()->getCurrentLanguageId();
        $is = $this->getInputSetting(array());
        switch ($this->input_type) {
            case self::INPUT_TYPE_TEXTBOX:
                $res = new InputField('text', $this->display_text[$lid]);
                $res->getInput()->setAttribute(array(
                    'id' => $this->name,
                    'name' => 'data[' . $this->name . ']'
                ));
                break;
            case self::INPUT_TYPE_SELECT:
                $options = ArrayUtil::toArray($this->options, function ($item, $index) use ($lid) {
                    return array($item['labels'][$lid], $item['value']);
                });
                $res = new SelectField($this->display_text[$lid]);
                $res->getInput()
                    ->setCaption('')
                    ->setOptions($options)
                    ->setAttribute(array(
                        'id' => $this->name,
                        'name' => 'data[' . $this->name . ']'
                    ));
                if (array_key_exists('multiple', $is)) {
                    $res->getInput()->setAttribute('multiple', 'multiple');
                    if (array_key_exists('size', $is)) {
                        $res->getInput()->setAttribute('size', 5);
                    }
                }
                break;
            case self::INPUT_TYPE_OPTION_LIST:
                $options = ArrayUtil::toArray($this->options, function ($item, $index) use ($lid) {
                    return array($item['labels'][$lid], $item['value']);
                });
                $res = new OptionListField($this->display_text[$lid], array_key_exists('multiple', $is) && $is['multiple']);
                $res->getInput()
                    ->setOptions($options)
                    ->setAttribute(array(
                        'name' => 'data[' . $this->name . ']'
                    ));
                break;
        }
        if ($this->required) {
            $res->addValidateRule('required', true);
        }
        return $res;
    }
}

Orm\Model::register('Core\Attrs\Model\AttributeModel', array(
    'table' => AttributeModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\StringProperty::create('data_type')->setNullable(false),
        Orm\StringProperty::create('input_type')->setNullable(false),
        Orm\StringProperty::create('input_id'),
        Orm\StringProperty::create('input_name'),
        Orm\BooleanProperty::create('indexable')->setDefaultValue(false)->setNullable(false),
        Orm\BooleanProperty::create('required')->setDefaultValue(false)->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)->setNullable(false),
        Orm\BooleanProperty::create('localizable')->setDefaultValue(false)->setNullable(false),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\SerializeProperty::create('display_text')->setNullable(false),
        Orm\SerializeProperty::create('memo')->setNullable(false),
        Orm\SerializeProperty::create('options'),
        Orm\SerializeProperty::create('input_setting'),
    )
));