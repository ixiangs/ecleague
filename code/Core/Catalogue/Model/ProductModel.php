<?php
namespace Core\Catalogue\Model;

use Core\Attrs\Helper;
use Core\Attrs\Model\AttributeModel;
use Toy\Orm;

class ProductModel extends Orm\Model
{

    const TABLE_NAME = '{t}catalogue_product';

    public function bindAttributeSet($setId = 1)
    {
        $this->attribute_set_id = $setId;
        $atree = Helper::getAttributeTree($setId);
        $cps = self::$metadatas[get_class($this)]['properties'];
        foreach ($atree->getGroups() as $group) {
            foreach ($group->getAttributes() as $attribute) {
                switch ($attribute->getDataType()) {
                    case AttributeModel::DATA_TYPE_STRING:
                        $prop = Orm\StringProperty::create($attribute->getName());
                        break;
                    case AttributeModel::DATA_TYPE_BOOLEAN:
                        $prop = Orm\BooleanProperty::create($attribute->getName());
                        break;
                    case AttributeModel::DATA_TYPE_INTEGER:
                        $prop = Orm\IntegerProperty::create($attribute->getName());
                        break;
                    case AttributeModel::DATA_TYPE_EMAIL:
                        $prop = Orm\EmailProperty::create($attribute->getName());
                        break;
                    case AttributeModel::DATA_TYPE_ARRAY:
                        $prop = Orm\ListProperty::create($attribute->getName());
                        break;
                    case AttributeModel::DATA_TYPE_NUMBER:
                        $prop = Orm\FloatProperty::create($attribute->getName());
                        break;
                }
                if ($attribute->getRequired()) {
                    $prop->setNullable(false);
                }
                $prop->setSettings(array(
                    'extended' => true,
                    'localize'=>$prop->getLocalizable()
                ));
                $cps[$attribute->getName()] = $prop;
            }
        }
        $this->properties = $cps;
        return $this;
    }

    public function insert($db = null)
    {
        $lid = Localize::singleton()->getCurrentLanguageId();
        $cdb = $db ? $db : Helper::openDb();
        $this->beforeInsert($cdb);
        $values = array();
        $gvalues = array();
        $lvalues = array();
        foreach ($this->properties as $n => $p) {
            if ($p->getInsertable()) {
                $ps = $p->getSettings();
                if (array_key_exists('extended', $ps) && $ps['extended']) {
                    if (array_key_exists('localize', $ps) && $ps['localize']) {
                        $gvalues[$n] = $p->toDbValue($this->getData($n));
                    }else{
                        $lvalues[$n] = array($lid=>$p->toDbValue($this->getData($n)));
                    }
                } else {
                    $values[$n] = $p->toDbValue($this->getData($n));
                }
            }
        }
        $values['extension_global_data'] = $gvalues;
        $values['extension_localize_data'] = $lvalues;

        $result = $cdb->insert(new InsertStatement($this->tableName, $values));
        if ($this->idProperty->getAutoIncrement()) {
            $this->setIdValue($cdb->getLastInsertId());
        }
        $this->afterInsert($cdb);
        return $result;
    }
}

Orm\Model::register('Core\Catalogue\Model\ProductModel', array(
    'table' => ProductModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('attribute_set_id')->setNullable(false),
        Orm\StringProperty::create('sku')->setNullable(false),
        Orm\SerializeProperty::create('name')->setNullable(false),
        Orm\SerializeProperty::create('description')->setNullable(false),
        Orm\SerializeProperty::create('pictures')->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setNullable(false),
        Orm\SerializeProperty::create('extension_global_data'),
        Orm\SerializeProperty::create('extension_localize_data')
    )
));