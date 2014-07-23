<?php
namespace Void\Content;

use Toy\Orm;
use Void\Content\Constant;
use Toy\Platform\FileUtil;
use Toy\Platform\PathUtil;
use Toy\Util\RandomUtil;

class ArticleModel extends Orm\Model
{

    protected function afterDelete($db)
    {
        if ($this->directory) {
            FileUtil::deleteDirectory(
                PathUtil::combines(
                    ASSET_PATH, 'articles', $this->account_id, $this->directory));
        }
    }

    public function createDirectory()
    {
        $path = PathUtil::combines(ASSET_PATH, 'articles');
        while (true) {
            $rmd = RandomUtil::randomCharacters();
            if (!FileUtil::isDirectory($path . DS . $rmd)) {
                FileUtil::createDirectory($path . DS . $rmd);
                $this->directory = $rmd;
                return true;
            }
        }

        return false;
    }
}

ArticleModel::registerMetadata(array(
    'table' => Constant::TABLE_ARTICLE,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('category_id')->setNullable(false),
        Orm\IntegerProperty::create('account_id')->setNullable(false),
        Orm\StringProperty::create('title')->setNullable(false),
        Orm\StringProperty::create('content')->setNullable(false),
        Orm\IntegerProperty::create('start_time')->setDefaultValue(0),
        Orm\IntegerProperty::create('end_time')->setDefaultValue(0),
        Orm\IntegerProperty::create('status')->setDefaultValue(0),
        Orm\StringProperty::create('directory')->setUpdateable(false),
        Orm\StringProperty::create('introduction'),
        Orm\StringProperty::create('intro_image'),
        Orm\StringProperty::create('article_image')
    )
));