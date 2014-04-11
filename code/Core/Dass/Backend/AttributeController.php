<?php
namespace Core\Dass\Backend;

use Toy\Data\Helper;
use Toy\Util\ArrayUtil;
use Toy\Web;

class AttributeController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = \Tops::loadModel('dass/attribute')->find()->selectCount()->execute()->getFirstValue();
        $models = \Tops::loadModel('dass/attribute')->find()
            ->asc('code')
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function typeAction()
    {
        return Web\Result::templateResult();
    }

    public function addAction()
    {
        $model = \Tops::loadModel('dass/attribute')->fillArray(array(
            'data_type' => $this->request->getQuery('data_type'),
            'input_type' => $this->request->getQuery('input_type')
        ));
        $versions = $model->getVersions();
        foreach ($this->context->locale->getLanguages() as $lang) {
            $versions->append(AttributeVersionModel::create(array(
                'language_id' => $lang['id']
            )));
        }
        return $this->getEditTemplateResult($model);
    }

    public function addPostAction()
    {
        $locale = $this->context->locale;
        $m = \Tops::loadModel('dass/attribute')->fillArray($this->request->getPost('main'));
        $versions = $m->getVersions();
        foreach ($this->request->getPost('versions') as $l => $data) {
            $versions->append(AttributeVersionModel::create($data)->setLanguageId($l));
        }

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->validateUnique()) {
            $this->session->set('errors', $locale->_('dass_err_attribute_exists', $m->getCode()));
            return $this->getEditTemplateResult($m);
        }

        $result = Helper::withTx(function ($db) use ($m, $versions, $locale) {
            if (!$m->insert($db)) {
                $this->session->set('errors', $locale->_('err_system'));
                return false;
            }

            foreach ($versions as $version) {
                $version->setMainId($m->getId());
                $vr = $version->validateProperties();
                if ($vr !== true) {
                    $this->session->set('errors', $locale->_('err_input_invalid'));
                    return false;
                }
                if (!$version->insert($db)) {
                    $this->session->set('errors', $locale->_('err_system'));
                    return false;
                }
            }

            return true;
        });

        return $result ?
            Web\Result::redirectResult($this->router->buildUrl('list')) :
            $this->getEditTemplateResult($m);
    }

    public function editAction($id)
    {
        $m = \Tops::loadModel('dass/attribute')->load($id);
        $m->getVersions()->load();
        return $this->getEditTemplateResult($m);
    }

    public function editPostAction()
    {
        $locale = $this->context->locale;
        $mainData = $this->request->getPost('main');
        $m = \Tops::loadModel('dass/attribute')->merge($mainData['id'], $mainData);
        $versions = $m->getVersions()->load();
        foreach ($this->request->getPost('versions') as $data) {
            $versions->findById($data['version_id'])->fillArray($data);
        }

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        $result = Helper::withTx(function ($db) use ($m, $versions, $locale) {
            if (!$m->update($db)) {
                $this->session->set('errors', $locale->_('err_system'));
                return false;
            }

            foreach ($versions as $version) {
                $version->setMainId($m->getId());
                $vr = $version->validateProperties();
                if ($vr !== true) {
                    $this->session->set('errors', $locale->_('err_input_invalid'));
                    return false;
                }
                if (!$version->update($db)) {
                    $this->session->set('errors', $locale->_('err_system'));
                    return false;
                }
            }

            return true;
        });

        return $result ?
            Web\Result::redirectResult($this->router->buildUrl('list')) :
            $this->getEditTemplateResult($m);
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = \Tops::loadModel('dass/attribute')->load($id);

        if (!$m) {
            $this->session->set('errors', $lang->_('err_system'));
            return Web\Result::redirectResultt($this->router->buildUrl('list'));
        }

        if (!$m->delete()) {
            $this->session->set('errors', $lang->_('err_system'));
            return Web\Result::redirectResult($this->router->buildUrl('list'));
        }
        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function optionsAction($attributeid)
    {
        $attr = \Tops::loadModel('dass/attribute')->load($attributeid);
        $options = $attr->getOptions()->load();
        return Web\Result::templateResult(array(
            'attribute' => $attr,
            'options' => $options
        ));
    }

    public function optionsPostAction($attributeid)
    {
        $lang = $this->context->locale;
        $attr = \Tops::loadModel('dass/attribute')->load($this->request->getPost('attribute_id'));
        $options = ArrayUtil::toArray($this->request->getPost('options'), function($item) use($attr){
            return \Tops::loadModel('dass/attributeOption')
                        ->fillArray($item)
                        ->setAttributeId($attr->getId());
        });

        //check unique
        $values = ArrayUtil::toArray($options, function($item){
            return $item->getValue();
        });
        $repeated = ArrayUtil::contains(array_count_values($values), function($item){
            return $item > 1;
        });
        if($repeated){
            $this->session->set('errors', $lang->_('dass_err_option_repeated'));
            return Web\Result::templateResult(array(
                'attribute' => $attr,
                'options' => $options
            ));
        }

        $res = Helper::withTx(function($db) use($attr, $options, $lang){
            foreach($options as $option){
                $b = $option->getId()? $option->update($db): $option->insert($db);
                if(!$b){
                    return false;
                }
            }
            return true;
        });

        if($res){
            return Web\Result::redirectResult($this->router->buildUrl('list'));
        }else{
            $this->session->set('errors', $lang->_('err_system'));
            return Web\Result::templateResult(array(
                'attribute' => $attr,
                'options' => $options
            ));
        }
    }

    private function getEditTemplateResult($model)
    {
        return Web\Result::templateResult(
            array('model' => $model),
            'dass/attribute/edit'
        );
    }
}