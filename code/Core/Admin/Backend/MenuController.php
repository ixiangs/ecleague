<?php
namespace Core\Admin\Backend;

use Toy\Web;

class MenuController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = \Ecleague\Tops::loadModel('admin/menu')->find()->selectCount()->execute()->getFirstValue();
        $models = \Ecleague\Tops::loadModel('admin/menu')->find()
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        return Web\Result::templateResult(array(
                'models' => $models,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function sortAction()
    {
        $menus = \Ecleague\Tops::loadModel('admin/menu')->find()
            ->asc('parent_id', 'position')
            ->load();
        return Web\Result::templateResult(array('menus' => $menus));
    }

    public function sortPostAction()
    {
        $data = $this->request->getPost('data');
        $sorts = json_decode($data, true);
        \Ecleague\Tops::loadModel('admin/menu')->updatePosition($sorts);
        return $this->sortAction();
    }

    public function addAction()
    {
        $model = \Ecleague\Tops::loadModel('admin/menu');
        return $this->getEditTemplateResult($model);
    }

    public function savePostAction()
    {
        $locale = $this->context->locale;
        $m = \Ecleague\Tops::loadModel('admin/menu')->fillArray($this->request->getPost('data'));

        $vr = $m->validateProperties();
        if ($vr !== true) {
            $this->session->set('errors', $locale->_('err_input_invalid'));
            return $this->getEditTemplateResult($m);
        }

        if ($m->getId()) {
            if (!$m->merge()->update()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        } else {
            if (!$m->insert()) {
                $this->session->set('errors', $locale->_('err_system'));
                return $this->getEditTemplateResult($m);
            }
        }

        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function editAction($id)
    {
        $m = \Ecleague\Tops::loadModel('admin/menu')->load($id);
        return $this->getEditTemplateResult($m);
    }

    public function deleteAction($id)
    {
        $lang = $this->context->locale;
        $m = \Ecleague\Tops::loadModel('admin/attribute')->load($id);

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
        $locale = $this->context->locale;
        $lid = $locale->getCurrentLanguageId();
        $find = \Ecleague\Tops::loadModel('admin/menu')->find()
            ->asc('parent_id');
        if ($model->getId()) {
            $find->ne('id', $model->getId());
        }

        $menus = $find->load()->toArray(function ($item) use ($lid) {
            return array(null, array(
                'id' => $item->getId(),
                'parentId' => $item->getParentId(),
                'value' => $item->getId(),
                'text' => $item->names[$lid],
            ));
        });
        return Web\Result::templateResult(
            array('model' => $model, 'menus' => $menus),
            'admin/menu/edit'
        );
    }
}