<?php

Yii::import('application.models._base.BaseAccessory');

class Accessory extends BaseAccessory {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'item_id' => 'Item',
            'accessory_type_id' => 'Accessory Type',
            'design_id' => 'Design',
            'data' => 'Data',
        );
    }

    public function beforeDelete() {
        AccessoryFinish::model()->deleteAll('accessory_id =' . $this->id);
        return (parent::beforeDelete());
    }

    public function getSpec() {
        return array('Accessory' => $this->accessoryType->name);
    }

    public function getParts($qty = 1) {
        $parts = array();
        $parts[] = array(39, 0, 0, $this->accessory_type_id, 0, 0, 0, 0, 0, 0, 'acc,' . $this->id, $qty);
        return $parts;
    }

    public function beforeSave() {
        $this->name = $this->accessoryType->name;
        return parent::beforeSave();
    }

    public function afterSave() {
        if (!$this->design_id) {
            $item = $this->item;
            $item->quoted_option = $this->id;
            $item->save();
        }
        return (parent::afterSave());
    }

}

?>