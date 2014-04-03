<?php
namespace Core\Dass\Backend;

use Core\Dass\Model\AttributeVersionModel;
use Toy\Web;
use Core\Dass\Model\AttributeModel;

class AttributeController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = AttributeModel::findVersions()->selectCount()->execute()->getFirstValue();
        $models = AttributeModel::findVersions()
            ->asc('code')
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->execute()
            ->getModelArray();
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
        $model = AttributeModel::create(array(
           'data_type'=>$this->request->getQuery('data_type'),
           'input_type'=>$this->request->getQuery('input_type')
        ));
        foreach($this->context->locale->getLanguages() as $lang){
            $model->getVersions()->append(AttributeVersionModel::create(array(
                'language_id'=>$lang['id'],
                'data_type'=>$this->request->getQuery('data_type'),
                'input_type'=>$this->request->getQuery('input_type')
            )));
        }
        return $this->getEditTemplateResult($model);
    }

    public function addPostAction()
    {
        $locale = $this->context->locale;
        $m = AttributeModel::create($this->request->getPost('main'));
        foreach($this->request->getPost('versions') as $l=>$data){
            $m->getVersions()->append(AttributeVersionModel::create(
                $data
            )->setLanguageId($l));
        }

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if($m->validateUnique()){
            $this->session->set('errors', $locale->_('err_code_exists', $m->getCode()));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->insert()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->saveChildren()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(LanguageModel::load($id));
    }

    public function editPostAction()
    {
        $lang = $this->context->locale;
        $m = LanguageModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
        $vr = $m->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->update()) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = BehaviorModel::load($id);

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

    public function importAction()
    {
        return Web\Result::templateResult();
    }

    public function importPostAction()
    {
        $lang = $this->context->locale;
        $up = $this->request->getFile('upload');
        if (!$up->checkExtension('csv')) {
            $this->session->set('errors', $lang->_('locale_err_import'));
            return Web\Result::templateResult();
        }
        $langs = $this->context->locale->getLanguages();
        $lines = FileUtil::readCsv($up->getTmpName());
        $titles = array_shift($lines);
        foreach ($lines as $line) {
            for ($i = 1; $i < count($titles); $i++) {
                DictionaryModel::create(array(
                    'code' => $line[0],
                    'label' => $line[$i],
                    'language_id' => $langs[strtolower($titles[$i])]['id']
                ))->insert();
            }
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    private function getEditTemplateResult($model)
    {
        return Web\Result::templateResult(
            array('model' => $model),
            'dass/attribute/edit'
        );
    }
}