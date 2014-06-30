<?php
namespace Ixiangs\Catalogue\Backend;

use Ecleague\Tops;
use Toy\Web;

class ProductController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = Tops::loadModel('catalogue/product')->find()->selectCount()->execute()->getFirstValue();
        $models = Tops::loadModel('catalogue/product')->find()
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function addAction()
    {
        return $this->getEditTemplateResult(Tops::loadModel('catalogue/product'));
    }

    public function addPostAction()
    {
        $locale = $this->context->locale;
        $post = $this->request->getAllPost();
        $model = Tops::loadModel('catalogue/product')
                    ->bindAttributeSet()
                    ->setAllData($post['data']);
        $vr = $model->validate();
//        print_r($vr);
//        die();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($model);
        }

        if($model->insert()){
            return Web\Result::redirectResult($this->router->buildUrl('list'));
        }else{
            return $this->getEditTemplateResult($model);
        }
    }

    public function editAction($id)
    {
        $m = Tops::loadModel('catalogue/product');
        $m->load($id);
        return $this->getEditTemplateResult($m, null);
    }

    public function editPostAction()
    {
        $locale = $this->context->locale;
        $m = Tops::loadModel('catalogue/product')
                    ->load($this->request->getPost('id'))
                    ->setAllData($this->request->getPost('data'));

        $vr = $m->validate();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m, null);
        }

        if (!$m->update()) {
            $this->session->set('errors', $locale->_('err_system'));
            return $this->getEditTemplateResult($m, null);
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = Tops::loadModel('catalogue/product')->load($id);

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
        $attrTree = \Ixiangs\Entities\Helper::getAttributeTree(1);
        return Web\Result::templateResult(
            array('model' => $model, 'attributeSet'=>$attrTree),
            'catalogue/product/edit'
        );
    }
}