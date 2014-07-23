<?php
namespace Void\Weiweb\Member;

use Toy\Platform\FileUtil;
use Toy\Platform\PathUtil;
use Toy\Util\RandomUtil;
use Void\Auth;
use Toy\Web;
use Void\Weiweb\MenuModel;

class MenuController extends Web\Controller
{

    public function listAction()
    {
        $pi = $this->request->getParameter("pageindex", 1);
        $count = MenuModel::find()
            ->eq('website_id', $this->session->get('websiteId'))
            ->fetchCount();
        $models = MenuModel::find()
            ->eq('website_id', $this->session->get('websiteId'))
            ->limit(PAGINATION_SIZE, ($pi - 1) * PAGINATION_SIZE)
            ->load();
        $types = array();
        $locale = $this->localize;
        foreach (Web\Application::$components as $component) {
            $mts = $component->getSetting('menu_types');
            if ($mts) {
                foreach ($mts as $k => $v) {
                    $types[$k] = $locale->_($v['title']);
                }
            }
        }
        return Web\Result::templateResult(array(
                'models' => $models,
                'types' => $types,
                'total' => $count,
                'pageIndex' => $pi)
        );
    }

    public function typeAction()
    {
        $types = array();
        $locale = $this->localize;
        foreach (Web\Application::$components as $component) {
            $mts = $component->getSetting('menu_types');
            if ($mts) {
                foreach ($mts as $k => $v) {
                    $types[$k] = $locale->_($v['title']);
                }
            }
        }
        return Web\Result::templateResult(array('types' => $types));
    }

    public function addAction($type)
    {
        return $this->getEditTemplateResult(new MenuModel(array(
            'type_id' => $type
        )));
    }

    public function addPostAction()
    {
        return $this->save();
    }

    public function editAction($id)
    {
        $model = MenuModel::load($id);
        return $this->getEditTemplateResult($model);
    }

    public function editPostAction()
    {
        return $this->save();
    }

    private function save()
    {
        $data = $this->request->getPost('data');
        $model = $data['id'] ?
            MenuModel::merge($data['id'], $data) :
            MenuModel::create($data);
        if ($model->isNewed()) {
            $model->setAccountId($this->context->identity->getId())
                ->setWebsiteId($this->session->get('websiteId'));
        }
        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $this->localize->_('err_input_invalid'));
            return $this->getEditTemplateResult($model);
        }

        if (!$model->save()) {
            $this->session->set('errors', $this->localize->_('err_system'));
            return $this->getEditTemplateResult($model);
        }
        return Web\Result::redirectResult($this->router->findHistory('list'));
    }

    public function deleteAction($id)
    {
        $m = MenuModel::load($id);

        if (!$m) {
            $this->session->set('errors', $this->localize->_('err_system'));
            return Web\Result::redirectResultt($this->router->buildUrl('list'));
        }

        if (!$m->delete()) {
            $this->session->set('errors', $this->localize->_('err_system'));
            return Web\Result::redirectResult($this->router->buildUrl('list'));
        }
        return Web\Result::redirectResult($this->router->buildUrl('list'));
    }

    public function iconAction($id = null)
    {
        $files = array();
        if ($id) {
            $files[] = MenuModel::load($id)->getIcon();
        }
        return Web\Result::templateResult(array(
            'files' => $files,
            'maxCount' => 1,
            'inputId' => 'icon',
            'accept' => '.jpg,.jpeg,.gif,.png'
        ), '/upload');
    }

    public function iconPostAction()
    {
        $upload = $this->request->getFile('uploadfile');
        $path = PathUtil::combines(ASSET_PATH, 'weiweb', 'menus', 'icons');
        if ($upload->isOk() && $upload->isImage()) {
            while (true) {
                $fname = RandomUtil::randomCharacters() . '.' . $upload->getExtension();
                $target = $path . DS . $fname;
                if (!FileUtil::checkExists($target)) {
                    FileUtil::moveUploadFile($upload->getTmpName(), $target);
                    return Web\Result::templateResult(array(
                        'files' => array('/assets/weiweb/menus/icons/' . $fname),
                        'maxCount' => 1,
                        'inputId' => 'icon',
                        'accept' => '.jpg,.jpeg,.gif,.png'
                    ), '/upload');
                }
            }
        }
        return Web\Result::templateResult(array(
            'files' => $this->request->getPost('existed_files'),
            'maxCount' => 1,
            'inputId' => 'icon'
        ), '/upload');
    }

    private function getEditTemplateResult($model)
    {
        $type = $model->getTypeId();
        $typeName = '';
        $path = null;
        foreach (Web\Application::$components as $component) {
            $mts = $component->getSetting('menu_types');
            if ($mts) {
                foreach ($mts as $k => $v) {
                    if ($k == $type) {
                        $path = $v['form'];
                        $typeName = $this->localize->_($v['title']);
                        break;
                    }
                }
            }
        }
        $parents = MenuModel::find(false)
            ->select('id', MenuModel::propertyToField('parent_id', 'parentId'), 'title')
            ->eq('account_id', $this->context->identity->getId())
            ->asc('parent_id');
        if ($model->getId()) {
            $parents->ne('id', $model->getId());
        }
        $parents = $parents->load()->toArray(function ($item) {
            return array(null, array(
                'id'=>$item->getId(),
                'value' => $item->getId(),
                'text' => $item->getTitle(),
                'parentId' => $item->getParentId()
            ));
        });
        return Web\Result::templateResult(
            array('model' => $model, 'typeName' => $typeName, 'formPath' => $path, 'parents' => $parents),
            'edit'
        );
    }
}