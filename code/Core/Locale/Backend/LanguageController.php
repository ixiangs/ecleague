<?php
namespace Core\Locale\Backend;

use Toy\Platform\FileUtil;
use Toy\Web;

class LanguageController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = \Tops::loadModel('locale/language')->find()->selectCount()->execute()->getFirstValue();
        $models = \Tops::loadModel('locale/language')->find()->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateResult(\Tops::loadModel('locale/language'));
    }

    public function addPostAction()
    {
        $lang = $this->context->locale;
        $m = \Tops::loadModel('locale/language')->fillArray($this->request->getAllParameters());
        if (LanguageModel::checkUnique('code', $m->getCode())) {
            $this->session->set('errors', $lang->_('err_code_exists', $m->getCode()));
            return $this->getEditTemplateResult($m);
        }

        $vr = $m->validate();
        if ($vr !== true) {
            $this->session->set('errors', $lang->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if (!$m->insert()) {
            $this->session->set('errors', $lang->_('err_system'));
            return $this->getEditTemplateResult($m);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(\Tops::loadModel('locale/language')->load($id));
    }

    public function editPostAction()
    {
        $lang = $this->context->locale;
        $m = \Tops::loadModel('locale/language')->merge($this->request->getParameter('id'), $this->request->getAllParameters());
        $vr = $m->validateProperties();
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
        $m = \Tops::loadModel('locale/language')->load($id);

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

    public function importAction(){
        return Web\Result::templateResult();
    }

    public function importPostAction(){
        $lang = $this->context->locale;
        $up = $this->request->getFile('upload');
        if(!$up->checkExtension('csv')){
            $this->session->set('errors', $lang->_('locale_err_import'));
            return Web\Result::templateResult();
        }
        $langs = $this->context->locale->getLanguages();
        $lines = FileUtil::readCsv($up->getTmpName());
        $titles = array_shift($lines);
        foreach($lines as $line){
            for($i = 1; $i < count($titles); $i++){
                \Tops::loadModel('locale/dictionary')->fillArray(array(
                    'code'=>$line[0],
                    'label'=>$line[$i],
                    'language_id'=>$langs[strtolower($titles[$i])]['id']
                ))->insert();
            }
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    private function getEditTemplateResult($model)
    {
        return Web\Result::templateResult(
            array('model' => $model),
            'locale/language/edit'
        );
    }
}