<?php
namespace Ixiangs\Entities;

use Toy\Orm\BooleanProperty;
use Toy\Orm\DateTimeProperty;
use Toy\Orm\EmailProperty;
use Toy\Orm\FloatProperty;
use Toy\Orm\IntegerProperty;
use Toy\Orm\SerializeProperty;
use Toy\Orm\StringProperty;
use Toy\Html;
use Toy\Web\Application;

class Helper
{

    static public function buildHtmlForm($model, $form = null)
    {
        $class = get_class($model);
        $entity = EntityModel::find()->eq('model', $class)->load()->getFirst();

        $html = Html\Helper::singleton();
        $fields = $entity->getFields()->load();
        $groups = $entity->getGroups()->load();
        $attributeIds = $fields->toArray(function ($item) {
            return $item->attribute_id;
        });
        $options = OptionModel::find()->in('attribute_id', $attributeIds)->load();
        if (count($groups) > 0) {
            if (is_null($form)) {
                $form = $html->groupedForm()->setAttribute('action', Application::$context->router->buildUrl('save'));
            }
            foreach ($groups as $group) {
                $fieldIds = $group->field_ids;
                $form->beginGroup('group_' . $group->id, $group->name);
                foreach ($fields as $field) {
                    if (($field->getInsertable() || $field->getUpdateable()) && in_array($field->id, $fieldIds)) {
                        $form->addField(self::buildHtmlField($html, $field, $options));
                    }
                }
                $form->endGroup();
            }
        } else {
            if (is_null($form)) {
                $form = $html->form()->setAttribute('action', Application::$context->router->buildUrl('save'));
            }
            foreach ($fields as $field) {
                if (($field->getInsertable() || $field->getUpdateable())) {
                    $htmlField = self::buildHtmlField($html, $field, $options);
                    $htmlField->getInput()->setAttribute('value', $model->getData($field->name));
                    $form->addField($htmlField);
                }
            }
        }
        $idProperty = $model->getMetadata()->getPrimaryKey();
        $form->addHidden($idProperty->getName(), 'data[' . $idProperty->getName() . ']', $model->getIdValue());
        return $form;
    }

    static private function buildHtmlField($html, $field, $options)
    {
        $is = $field->getInputSetting(array());
        switch ($field->input_type) {
            case Constant::INPUT_TYPE_TEXTBOX:
                $htmlInput = $html->textbox($field->name, 'data[' . $field->name . ']');
                break;
            case Constant::INPUT_TYPE_TEXTAREA:
                $htmlInput = $html->textarea($field->name, 'data[' . $field->name . ']');
                break;
            case Constant::INPUT_TYPE_SELECT:
                $selectOptions = $options->filter(function ($item) use ($field) {
                    return $item->attribute_id == $field->attribute_id;
                });
                $selectOptions = ArrayUtil::toArray($selectOptions, function ($item) {
                    return array($item->value, $item->label);
                });
                $htmlInput = $html->select($field->name, 'data[' . $field->name . ']', null, $selectOptions);
                $htmlInput->setAttribute('size', $is['size']);
                if (array_key_exists('multiple', $is) && $is['multiple']) {
                    $htmlInput->setAttribute('multiple', 'multiple');
                }
                if (array_key_exists('default_option', $is) && $is['default_option'] == 'empty') {
                    $htmlInput->setCaption('');
                }
                break;
            case Constant::INPUT_TYPE_OPTION_LIST:
                $selectOptions = $options->filter(function ($item) use ($field) {
                    return $item->attribute_id == $field->attribute_id;
                });
                $selectOptions = ArrayUtil::toArray($selectOptions, function ($item) {
                    return array($item->value, $item->label);
                });
                $htmlInput = $html->optionList($field->name, 'data[' . $field->name . ']', null, $selectOptions);
                if (array_key_exists('multiple', $is) && $is['multiple']) {
                    $htmlInput->setMultiple(true);
                }
                break;
        }
        $htmlField = new Html\FormField($field->label);
        if ($field->required) {
            $htmlField->setRequired(true);
            $htmlInput->addValidateRule('required', true);
        }
        $htmlField->setInput($htmlInput);
        return $htmlField;
    }

    static public function getModelMetadata($model)
    {
        $entity = EntityModel::find()->eq('model', $model)->load()->getFirst();
        $fields = $entity->getFields()->load();
        $result = array('table' => $entity->getTableName(), 'properties' => array());
        $property = null;
        foreach ($fields as $field) {
            switch ($field->getDataType()) {
                case Constant::DATA_TYPE_STRING:
                    $property = StringProperty::create($field->getName());
                    break;
                case Constant::DATA_TYPE_NUMBER:
                    $property = FloatProperty::create($field->getName());
                    break;
                case Constant::DATA_TYPE_INTEGER:
                    $property = IntegerProperty::create($field->getName());
                    break;
                case Constant::DATA_TYPE_BOOLEAN:
                    $property = BooleanProperty::create($field->getName());
                    break;
                case Constant::DATA_TYPE_EMAIL:
                    $property = EmailProperty::create($field->getName());
                    break;
                case Constant::DATA_TYPE_ARRAY:
                    $property = SerializeProperty::create($field->getName());
                    break;
                case Constant::DATA_TYPE_DATE:
                    $property = DateTimeProperty::create($field->getName());
                    break;
            }
            $property->setNullable(!$field->getRequired());
            $property->setPrimaryKey($field->getPrimaryKey());
            $property->setAutoIncrement($field->getAutoIncrement());
            $property->setInsertable($field->getInsertable());
            $property->setUpdateable($field->getUpdateable());
            $result['properties'][] = $property;
        }

        return $result;
    }
} 