<?php
namespace Ixiangs\Catalogue\Model;

use Toy\Db;
use Toy\Db\InsertStatement;
use Toy\Db\UpdateStatement;
use Toy\Orm;

use Ixiangs\Attrs;
use Ixiangs\Attrs\Model\AttributeModel;
use Ixiangs\Locale\Localize;

class ProductModel extends Orm\Model
{

    const TABLE_NAME = '{t}catalogue_product';

    protected $languageId = null;

    public function __construct($data = array()){
        $this->languageId = Localize::singleton()->getLanguageId();
        parent::__construct($data);
    }

    public function bindAttributeSet($setId = 1)
    {
        $cn = get_class($this);
        if(array_key_exists('extensionProperties', self::$metadatas[$cn]) &&
            array_key_exists($setId, self::$metadatas[$cn]['extensionProperties'])){
            $this->properties = array_merge($this->properties, self::$metadatas[$cn]['extensionProperties'][$setId]);
            return $this;
        }

        $atree = Attrs\Helper::getAttributeTree($setId);
        $cps = array();
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
                    'localize'=>$attribute->getLocalizable()
                ));
                $cps[$attribute->getName()] = $prop;
            }
        }
        $this->properties = array_merge($this->properties, $cps);
        if(!array_key_exists('extensionProperties', self::$metadatas[$cn])){
            self::$metadatas[$cn]['extensionProperties'] = array();
        }
        self::$metadatas[$cn]['extensionProperties'][$setId] = $cps;
        return $this;
    }

    public function insert($db = null)
    {
        $cdb = $db ? $db : Data\Helper::openDb();
        $this->beforeInsert($cdb);
        $values = array();
        $gvalues = array();
        $lvalues = array();
        foreach ($this->properties as $n => $p) {
            if ($p->getInsertable()) {
                $ps = $p->getSettings();
                if (array_key_exists('extended', $ps) && $ps['extended']) {
                    if (array_key_exists('localize', $ps) && $ps['localize']) {
                        $lvalues[$n] = $p->toDbValue($this->getData($n));
                    }else{
                        $gvalues[$n] = $p->toDbValue($this->getData($n));
                    }
                } else {
                    $values[$n] = $p->toDbValue($this->getData($n));
                }
            }
        }
        $values['name'] = serialize(array($this->languageId=>$this->name));
        $values['description'] = serialize(array($this->languageId=>$this->description));
        $values['extension_global_data'] = serialize($gvalues);
        $values['extension_localize_data'] = serialize(array($this->languageId=>$lvalues));

        $result = $cdb->insert(new InsertStatement($this->tableName, $values));
        if ($this->idProperty->getAutoIncrement()) {
            $this->setIdValue($cdb->getLastInsertId());
        }
        $this->afterInsert($cdb);
        return $result;
    }

    public function update($db = null)
    {
        $cdb = $db ? $db : Data\Helper::openDb();
        $this->beforeUpdate($cdb);
        $values = array();
        $gvalues = array();
        $lvalues = array();
        foreach ($this->properties as $n => $p) {
            if ($p->getUpdateable()) {
                $ps = $p->getSettings();
                if (array_key_exists('extended', $ps) && $ps['extended']) {
                    if (array_key_exists('localize', $ps) && $ps['localize']) {
                        $lvalues[$n] = $p->toDbValue($this->getData($n));
                    }else{
                        $gvalues[$n] = $p->toDbValue($this->getData($n));
                    }
                } else {
                    $values[$n] = $p->toDbValue($this->getData($n));
                }
            }
        }
        $values['name'] = serialize(array_merge($this->originalData['name'], array($this->languageId=>$this->name)));
        $values['description'] =  serialize(array_merge($this->originalData['description'], array($this->languageId=>$this->description)));
        $values['extension_global_data'] = serialize($gvalues);
        $values['extension_localize_data'][$this->languageId] = serialize($lvalues);

        if (count($values) == 0) {
            return false;
        }

        $us = new UpdateStatement($this->tableName, $values);
        $us->eq($this->idProperty->getName(), $this->getIdValue());
        $result = $cdb->update($us);
        $this->afterUpdate($cdb);
        return $result;
    }

    public function fromDbValues(array $row)
    {
        parent::fromDbValues($row);
        $this->bindAttributeSet($row['attribute_set_id']);
        $props = $this->properties;
        foreach ($this->getExtensionGlobalData() as $field=>$value){
            $this->data[$field] = array_key_exists($field, $props)? $props[$field]->fromDbValue($value): $value;
        }

        $eld = $this->getExtensionLocalizeData();
        reset($eld);
        $ld = array_key_exists($this->languageId, $eld)? $eld[$this->languageId]: current($el);
        foreach ($ld as $field=>$value){
            $this->data[$field] = array_key_exists($field, $props)? $props[$field]->fromDbValue($value): $value;
        }
        $this->originalData = $this->data;
        $this->data['name'] = $this->name[$this->languageId];
        $this->data['description'] = $this->description[$this->languageId];
        return $this;
    }
}

Orm\Model::register('Ixiangs\Catalogue\Model\ProductModel', array(
    'table' => ProductModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('attribute_set_id')->setNullable(false),
        Orm\StringProperty::create('sku')->setNullable(false),
//        Orm\StringProperty::create('name')->setNullable(false),
//        Orm\StringProperty::create('description')->setNullable(false),
        Orm\ListProperty::create('pictures'),
        Orm\BooleanProperty::create('enabled')->setNullable(false),
        Orm\SerializeProperty::create('global_data'),
        Orm\SerializeProperty::create('localize_data')
    )
));