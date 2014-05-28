<?php
namespace Ixiangs\Attrs;

use Toy\View\Html;
use Toy\Web\Application;

class Helper
{

    public function buildHtmlForm($model, $form = null)
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
                    if (in_array($field->id, $fieldIds)) {
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
                $htmlField = self::buildHtmlField($html, $field, $options);
                $htmlField->getInput()->setAttribute('value', $model->getData($field->name));
                $form->addField($htmlField);
            }
        }
        $idProperty = $model->getIdProperty();
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
} 