<?php

Yii::import('application.models._base.BaseDiscussionTable');

class DiscussionTable extends BaseDiscussionTable {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'item_id' => 'Item',
            'table_dimension' => 'Table Dimension',
            'table_top_type_id' => 'Table Top Type',
            'profile_id' => 'Profile',
            'pf_profile_id' => 'Post Forming Profile',
            'discussion_support_id' => 'Discussion Support',
            'cable_access_type_id' => 'Cable Access Type',
            'cable_entry_type_id' => 'Cable Entry Type',
            'data_other_checks_design' => 'Data Other Checks Design',
            'data_other_checks_mktg' => 'Data Other Checks Mktg',
            'data' => 'Data',
        );
    }

    public function beforeDelete() {
        DiscussionTableFinish::model()->deleteAll('discussion_table_id=' . $this->id);
        return (parent::beforeDelete());
    }

    public function afterSave() {
        $item = $this->item;
        $item->quoted_option = $this->id;
        $item->save();
        return (parent::afterSave());
    }

    public function getSpec() {
        $ret = array();
        $ret['Table'] = $this->tableTopType->name . ' ' . $this->profile->name . ' table of size: ' . $this->table_dimension . ' mm ';
        $ret['Understructure'] = $this->discussionSupport->name;
        if ($this->cable_access_type_id > 1) {
            $ret['Wire Management'] = 'Cable Access: ' . $this->cableAccessType->name . ($this->cable_entry_type_id > 1 ? ' Cable Entry: ' . $this->cableEntryType->name : '');
        }
        $count = 1;
        foreach (Accessory::model()->findAll('item_id=' . $this->item_id . ' and design_id=' . $this->id) as $val) {
            if (!isset($ret['Accessory']))
                $ret['Accessory'] = '';
            $ret['Accessory'] = '(' . $count . ') ' . $val->accessoryType->name;
            $count++;
        }
        $count = 1;
        foreach (Storage::model()->findAll('item_id=' . $this->item_id . ' and design_id=' . $this->id) as $val) {
            if (!isset($ret['Storage']))
                $ret['Storage'] = '';
            $ret['Storage'] .= '(' . $count . ') ' . 'Depth & Material:' . $val->storageDbtType->name .
                    ', Configuration:' . $val->storageFc->name . ', Size:' . $val->height_storage . ' H x ' . $val->width_storage .
                    ' W Top: ' . $val->width_storage + $val->width_top . ' Wx ' . $val->storageDbtType->depth + $val->depth_top . ' D';
            $count++;
        }
        $count = 1;
        foreach (OverHeadStorage::model()->findAll('item_id=' . $this->item_id . ' and design_id=' . $this->id) as $val) {
            if (!isset($ret['Overhead Storage']))
                $ret['Overhead Storage'] = '';
            $ret['Overhead Storage'].='(' . $count . ') ' . ' Depth & Material:' . $val->ohsDbType->name .
                    ', Facia:' . $val->ohsDoorType->name . ', Size:' . $val->height . ' Hx' . $val->width . 'W ';
            $count++;
        }
        $count = 1;
        foreach (Pedestal::model()->findAll('item_id=' . $this->item_id . ' and design_id=' . $this->id) as $val) {
            if (!isset($ret['Pedestal']))
                $ret['Pedestal'] = '';
            $ret['Pedestal'] .= '(' . $count . ') ' . 'Depth & Material:' . $val->pedestalDbtType->name .
                    ', Configuration:' . $val->configuration->name . ', Size:' . $val->height . 'H X ' . $val->width . 'W';
            $count++;
        }
        return $ret;
    }

    public function getParts($upc) {
        $cable_access = $this->cable_access_type_id;
        $cable_access = 1;
        $profile = $this->profile_id;
        $profile = 1;
        $part = array(
            array(63, $cable_access, $this->table_top_type_id, $profile, 0, 0, $this->table_dimension, $this->table_dimension, 0, 0, 'tbl', 1)
        );
        $cabstr = array();
        $supdata = array();
        $entstr = array();
        parse_str($this->discussionSupport->data, $supdata);
        parse_str($this->cableAccessType->data, $cabstr);
        parse_str($this->cableEntryType->data, $entstr);

        if (array_key_exists(49, $cabstr))
            $part[] = array(49, $cabstr[49]['s'], 0, 0, 0, 0, 0, 0, 0, 0, 'ca', 1);

        $cabtray = 0;
        if (array_key_exists(50, $cabstr)) {
            $part[] = array(50, $cabstr[50]['s'], 0, 0, 0, 0, $cabstr[50]['d1'], 0, 0, 0, 'ca', 1);
            $cabtray = 1;
        }
        if (array_key_exists(18, $supdata)) {
            $part[] = array(18, $cabtray ? 1 : 4, 2, 1, 0, 0, 710, ($this->table_dimension / 2) - 100, 0, 0, 'sup', 4);
            $part[] = array(28, 4, 0, 41, 1, 1, 750 - $cabtray * 150, 750 - $cabtray * 150, 0, 0, 'sf', 1);
        }

        if (array_key_exists(69, $supdata))
            $part[] = array(69, $supdata[69]['s'], $this->profile_id, 0, ($supdata[69]['s2'] == 1 ? ($cabtray == 1 ? 1 : 2) : 3), 0, $this->table_dimension, 0, 0, 0, 'sf', 1);

        if (array_key_exists(45, $entstr)){
            if ($entstr[45]['s'] == 'cover|box'){
                if (array_key_exists(18, $supdata)) {
                    $entry_sys = 1;
                } elseif (array_key_exists(23, $supdata)){
                    $entry_sys = 3;//always wire entry box
                } else {
                    $entry_sys = 3;
                }
                //$entry_sys = (array_key_exists(18, $supdata) ? 1 : 3);
            } else {
                $entry_sys = $entstr[45]['s'];
            }
            $part[] = array(45, $entry_sys, 0, 0, 0, 0, 0, 0, 0, 0, 'ce', 1);
        }
        
        if (array_key_exists(55, $supdata)){
            $leg_qty = ($this->table_dimension > 1050) ? 4 : 3;
            $part[] = array(55, 0, 0, $supdata[55]['s1'], 4, 3, 0, 0, 0, 0, 'sup', $leg_qty);
            $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf', $leg_qty * 4);
        }
        
        $t = array();
        foreach (Accessory::model()->findAll('item_id=' . $this->item_id . ' and design_id=' . $this->id) as $val) {
            /** @var $val  Accessory */
            $t = array_merge($t, $val->getParts($upc));
        }
        foreach (Storage::model()->findAll('item_id=' . $this->item_id . ' and design_id=' . $this->id) as $val) {
            /** @var $val  Storage */
            if ($val->storageFt->id > 1)
                $t = array_merge($t, $val->getParts($upc));
        }
        foreach (OverHeadStorage::model()->findAll('item_id=' . $this->item_id . ' and design_id=' . $this->id) as $val) {
            /** @var $val  OverHeadStorage */
            $t = array_merge($t, $val->getParts($upc));
        }
        foreach (Pedestal::model()->findAll('item_id=' . $this->item_id . ' and design_id=' . $this->id) as $val) {
            /** @var $val Pedestal */
            $t = array_merge($t, $val->getParts($upc));
        }
        return (array_merge($part, $t));
    }

}

?>