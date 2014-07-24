<?php
namespace Void\Weiweb\Member;

use Toy\Orm\Db\Helper;
use Toy\Platform\FileUtil;
use Toy\Platform\PathUtil;
use Toy\Util\RandomUtil;
use Void\Auth;
use Toy\Web;
use Void\Weiweb\Constant;
use Void\Weiweb\MenuModel;

class MenuController extends Web\Controller
{

    private $_sortMenus = array();
    private $_menus = null;

    public function listAction()
    {
        $this->_menus = MenuModel::find()
            ->eq('website_id', $this->session->get('websiteId'))
            ->asc('parent_id', 'ordering')
            ->load();
        $this->sortMenus(0, 0);
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
            'models' => $this->_sortMenus,
            'types' => $types));
    }

    private function sortMenus($parentId, $level)
    {
        for ($i = 0; $i < count($this->_menus); $i++) {
            $menu = $this->_menus[$i];
            if ($menu['parent_id'] == $parentId) {
                $menu['level'] = $level;
                $this->_sortMenus[] = $menu;
                $this->sortMenus($menu['id'], ++$level);
                --$level;
            }
        }
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
            'type_id' => $type,
            'parent_id' => 0,
            'ordering' => 0
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

        $result = Helper::withTx(function ($tx) use ($model) {
            return $model->save($tx);
        });

        if (!$result) {
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
            $icon = MenuModel::load($id)->getIcon();
            if ($icon) {
                $files[] = icon;
            }
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

    public function orderingAction($parentid)
    {
        $orderings = MenuModel::find()
            ->eq('website_id', $this->session->get('websiteId'))
            ->eq('parent_id', $parentid)
            ->asc('ordering')
            ->load()
            ->toArray(function ($item) {
                return array(null, array(
                    'title' => $item->getTitle(),
                    'ordering' => $item->getOrdering()
                ));
            });
        return Web\Result::jsonResult($orderings);
    }

    public function orderingPostAction()
    {
        $data = $this->request->getPost('orderings');
        $result = Helper::withTx(function ($tx) use ($data) {
            foreach ($data as $k => $v) {
                Helper::update(Constant::TABLE_MENU, array(
                    'ordering' => $v
                ))->eq('id', $k)
                    ->eq('website_id', $this->session->get('websiteId'))
                    ->execute($tx);
            }
            return true;
        });
        if (!$result) {
            $this->session->set('errors', $this->localize->_('err_system'));
        }
        return Web\Result::redirectResult($this->request->getRefererUrl());
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
            ->select('id', 'parent_id', 'title')
            ->eq('website_id', $this->session->get('websiteId'))
            ->asc('parent_id', 'ordering');
        if ($model->getId()) {
            $parents->ne('id', $model->getId());
        }
        $parents = $parents->load()->toArray(function ($item) {
            return array(null, array(
                'id' => $item->getId(),
                'value' => $item->getId(),
                'text' => $item->getTitle(),
                'parentId' => $item->getParentId()
            ));
        });
        $orderings = MenuModel::find(false)
            ->select('ordering', 'title')
            ->eq('website_id', $this->session->get('websiteId'))
            ->eq('parent_id', $model->getParentId(0))
            ->asc('ordering')
            ->fetch()
            ->combineColumns('ordering', 'title');
        $orderings[0] = '-';
        return Web\Result::templateResult(
            array('model' => $model, 'typeName' => $typeName, 'formPath' => $path, 'orderings' => $orderings, 'parents' => $parents),
            'edit'
        );
    }
}