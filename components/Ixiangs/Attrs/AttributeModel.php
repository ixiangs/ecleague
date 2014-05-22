<?php
namespace Ixiangs\Attrs;

use Toy\Orm;
use Toy\Util\ArrayUtil;
use Toy\View\Html\FormField;
use Toy\View\Html\Helper;
use Toy\Db;

class AttributeModel extends Orm\Model
{

//    public function getData($name, $default = null)
//    {
//        if ($name == 'options') {
//            switch ($this->input_type) {
//                case Constant::INPUT_TYPE_SELECT:
//                case Constant::INPUT_TYPE_OPTION_LIST:
//                    if (!array_key_exists('options', $this->data)) {
//                        $this->data['options'] = OptionModel::find()->eq('attribute_id', $this->getId())->load();
//                    }
//                    return $this->data['options'];
//            }
//            return array();
//        }
//        return parent::getData($name, $default);
//    }

//    public function insert($db = null)
//    {
//        if (is_null($db)) {
//            return Db\Helper::withTx(function ($tx) {
//                return parent::insert($tx);
//            });
//        } else {
//            try {
//                $db->begin();
//                $result = parent::insert($db);
//                $db->commit();
//                return $result;
//            } catch (\Exception $ex) {
//                $db->rollback();
//                throw $ex;
//            }
//
//        }
//    }

//    protected function afterInsert($db)
//    {
//        $options = $this->getOptions();
//        if (count($options) > 0) {
//            Db\Helper::delete(Constant::TABLE_OPTION)->eq('attribute_id' . $this->id)->execute($db);
//            foreach ($options as $option) {
//                $option->insert($db);
//            }
//        }
//    }
//
//    protected function afterUpdate($db)
//    {
//        $options = $this->getOptions();
//        if (count($options) > 0) {
//            Db\Helper::delete(Constant::TABLE_OPTION)->eq('attribute_id' . $this->id)->execute($db);
//            foreach ($options as $option) {
//                $option->insert($db);
//            }
//        }
//    }

    public function toFormField()
    {
        $html = Helper::singleton();
        $lid = Localize::singleton()->getLanguageId();
        $is = $this->getInputSetting(array());
        $field = new FormField($this->label[$lid]);
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
                if ($is['default_option'] == 'empty') {
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
//        Orm\BooleanProperty::create('indexable')->setDefaultValue(false)->setNullable(false),
//        Orm\BooleanProperty::create('required')->setDefaultValue(false)->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)->setNullable(false),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\StringProperty::create('label')->setNullable(false),
        Orm\StringProperty::create('memo')->setNullable(false),
        Orm\SerializeProperty::create('input_setting')
    ),
    'relations'=>array(
        Orm\Relation::childrenRelation('options', '\Ixiangs\Attrs\OptionModel', 'attribute_id')
    )
));