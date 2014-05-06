<?php
namespace Ixiangs\Attrs;

use Ixiangs\Locale\Localize;
use Toy\Orm;
use Toy\Util\ArrayUtil;
use Toy\View\Html\FormField;
use Toy\View\Html\Helper;

class AttributeModel extends Orm\Model
{
    public function toFormField()
    {
        $html = Helper::singleton();
        $lid = Localize::singleton()->getCurrentLanguageId();
        $is = $this->getInputSetting(array());
        $field = new FormField($this->display_text[$lid]);
        switch ($this->input_type) {
            case Constant::INPUT_TYPE_TEXTBOX:
                $field->setInput($html->textbox($this->name, 'data[' . $this->name . ']'));
                break;
            case Constant::INPUT_TYPE_SELECT:
                $options = ArrayUtil::toArray($this->options, function ($item, $index) use ($lid) {
                    return array($item['labels'][$lid], $item['value']);
                });
                $field->setInput($html->select($this->name, 'data[' . $this->name . ']', null, $options));
                $field->getInput()->setAttribute('size', $is['size']);
                if ($is['multiple']) {
                    $field->getInput()->setAttribute('multiple', 'multiple');
                }
                if($is['default_option'] == 'empty'){
                    $field->getInput()->setCaption('');
                }
                break;
            case Constant::INPUT_TYPE_OPTION_LIST:
                $options = ArrayUtil::toArray($this->options, function ($item, $index) use ($lid) {
                    return array($item['labels'][$lid], $item['value']);
                });
                $field->setInput($html->optionList($this->name, 'data[' . $this->name . ']', null, $options));
                if ($is['multiple']) {
                    $field->getInput()->setMultiple(true);
                }
                break;
        }
        if ($this->required) {
            $field->getInput()->addValidateRule('required', true);
        }
        return $field;
    }
}

AttributeModel::register(array(
    'table' => Constant::TABLE_ATTRIBUTE,
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