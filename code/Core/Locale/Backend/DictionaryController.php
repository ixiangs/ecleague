<?php
namespace Core\Locale\Backend;

use Toy\Data\Helper;
use Toy\Web;
use Locale\Model\DictionaryModel;
use Locale\Model\LanguageModel;

class DictionaryController extends Web\Controller
{

    public function listAction()
    {
        $lid = $this->request->getParameter('languageid');
        $pi = $this->request->getParameter("pageindex", 1);
        $lang = LanguageModel::load($lid);
        $count = DictionaryModel::find()->selectCount()->execute()->getFirstValue();
        $models = DictionaryModel::find()
            ->eq('language_id', $lid)
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->execute()
            ->getModelArray();
        return Web\Result::templateResult(array(
                'models' => $models,
                'language' => $lang,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        $lid = $this->request->getParameter('languageid');
        return Web\Result::templateResult(
            array('models' => array(DictionaryModel::create()),
                'language' => LanguageModel::load($lid)),
            'locale/dictionary/add'
        );
    }

    public function addPostAction()
    {
        $lang = $this->context->locale;
        $codes = $this->request->getParameter('codes');
        $labels = $this->request->getParameter('labels');
        $lid = $this->request->getParameter('languageid');
        $models = array();

        $tmplFunc = function($models, $lid){
            return Web\Result::templateResult(
                array('models' => $models,
                    'language' => LanguageModel::load($lid)),
                'locale/dictionary/add'
            );
        };


        foreach($codes as $index=>$code){
            $models[] = DictionaryModel::create(array(
                'code' => $code,
                'label' => $labels[$index],
                'language_id' => $lid
            ));
        }

        foreach ($models as $m) {
            $vr = $m->validateProperties();
            if ($vr !== true) {
                $this->session->set('errors', $lang->_('err_input_invalid'));
                return $tmplFunc($models, $lid);
            }
            $ur= $m->validateUnique();
            if ($ur !== true) {
                $this->session->set('errors', $lang->_('locale_err_code_exists', $m->getCode()));
                return $tmplFunc($models, $lid);
            }
        }

        try {
            Helper::withTx(function ($db) use ($models) {
                foreach ($models as $m) {
                    if (!$m->insert()) {
                        throw new \Exception();
                    }
                }
            });
        } catch (\Exception $ex) {
            $this->session->set('errors', $lang->_('err_system'));
            return $tmplFunc($models, $lid);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list', array('languageid'=>$lid)));
    }

    public function editAction($id)
    {
        return $this->getEditTemplateResult(DictionaryModel::load($id));
    }

    public function editPostAction()
    {
        $lang = $this->context->locale;
        $m = DictionaryModel::merge($this->request->getParameter('id'), $this->request->getAllParameters());
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

    private function getEditTemplateResult($model)
    {
        $lid = $this->request->getParameter('languageid');
        return Web\Result::templateResult(
            array('model' => $model,
                'language' => LanguageModel::load($lid)),
            'locale/dictionary/edit'
        );
    }
}