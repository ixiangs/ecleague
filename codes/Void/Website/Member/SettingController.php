<?php
namespace Void\Website\Member;

use Toy\Platform\FileUtil;
use Toy\Platform\PathUtil;
use Toy\Util\RandomUtil;
use Void\Auth;
use Toy\Web;
use Void\Website\WebsiteModel;

class SettingController extends Web\Controller
{
    public function editAction()
    {
        $model = WebsiteModel::load($this->session->get('websiteId'));
        return Web\Result::templateResult(array('model' => $model));
    }

    public function editPostAction()
    {
        $data = $this->request->getPost('data');
        $model = WebsiteModel::merge($data['id'], $data);
        $vr = $model->validate();
        if ($vr !== true) {
            $this->session->set('errors', $this->localize->_('err_input_invalid'));
        }

        if (!$model->save()) {
            $this->session->set('errors', $this->localize->_('err_system'));
        }
        return Web\Result::templateResult(array('model' => $model));
    }

    public function imageAction()
    {
        $files = array();
        $image = WebsiteModel::load($this->session->get('websiteId'))->getBackgroundImage();
        if($image){
            $files[] = $image;
        }
        return Web\Result::templateResult(array(
            'files' => $files,
            'maxCount' => 1,
            'inputId' => 'background_image',
            'accept' => '.jpg,.jpeg,.png'
        ), '/upload');
    }

    public function imagePostAction()
    {
        $upload = $this->request->getFile('uploadfile');
        $path = PathUtil::combines(ASSET_PATH, 'weiweb', 'settings');
        if ($upload->isOk() && $upload->isImage()) {
            while (true) {
                $fname = RandomUtil::randomCharacters() . '.' . $upload->getExtension();
                $target = $path . DS . $fname;
                if (!FileUtil::checkExists($target)) {
                    FileUtil::moveUploadFile($upload->getTmpName(), $target);
                    return Web\Result::templateResult(array(
                        'files' => array('/assets/weiweb/settings/' . $fname),
                        'maxCount' => 1,
                        'inputId' => 'background_image',
                        'accept' => '.jpg,.jpeg,.png'
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
}