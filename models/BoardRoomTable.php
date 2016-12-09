<?php

Yii::import('application.models._base.BaseBoardRoomTable');

class BoardRoomTable extends BaseBoardRoomTable {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'item_id' => 'Item',
            'table_top_type_id' => 'Table Top Type',
            'profile_id' => 'Profile',
            'pf_profile_id' => 'Post Forming Profile',
            'board_room_table_support_id' => 'Board Room Table Support',
            'cable_access_type_id' => 'Cable Access Type',
            'cable_carrier_type_id' => 'Cable Carrier Type',
            'cable_entry_type_id' => 'Cable Entry Type',
            'modesty_height' => 'Modesty Height',
            'modesty_type_id' => 'Modesty Type',
            'shared_cable_tray' => 'Shared Cable Tray',
            'add_on_qty_per_side' => 'Add On Qty Per Side',
            'cable_access_per_table' => 'Cable Access Per Table',
            'main_table_length' => 'Main Table Length',
            'main_table_depth' => 'Main Table Depth',
            'add_on_table_length' => 'Add On Table Length',
            'addon_table_depth' => 'Addon Table Depth',
            'data' => 'Data',
            'data_other_check_design' => 'Data Other Check Design',
            'data_other_check_mktg' => 'Data Other Check Mktg',
        );
    }

    public function beforeDelete() {
        BoardRoomTableFinish::model()->deleteAll('board_room_table_id=' . $this->id);
        return (parent::beforeDelete());
    }

    public function afterSave() {
        $item = $this->item;
        $item->quoted_option = $this->id;
        $item->save();
        return (parent::afterSave());
    }

    public function getSpec() {
        $ret = array('Name' => $this->name);
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
        //18,23,24,26,41,45,49,50,55,57,61,65
        $ca = array();
        $sup = array();
        $ce = array();
        $cc = array();
        $isq = (($this->add_on_qty_per_side - 1) * 2) * ($this->shared_cable_tray ? 1 : 2);
        $addq = $this->add_on_qty_per_side * 2;
        $endmodred = 0;
        $cenmodred = 0;
        $endcabred = 0;
        $cencabred = 0;
        $endrwred = 0;
        $cenrwred = 0;
        $headcabred = 0;
        $headrwred = 0;
        parse_str($this->boardRoomTableSupport->data, $sup);
        parse_str($this->cableAccessType->data, $ca);
        parse_str($this->cableEntryType->data, $ce);
        parse_str($this->cableCarrierType->data, $cc);
        $cable_access = $this->cable_access_type_id;
        $cable_access = 1;
        $profile = $this->profile_id;
        $profile = 1;
        $support_beam = true;
        $add_tab_len = $this->add_on_table_length;
        $add_tab_depth = $this->addon_table_depth;
        $main_tab_len = $this->main_table_length;
        $main_tab_len = $this->main_table_depth;
        $part = array(
            array(65, $cable_access, $this->table_top_type_id, $profile, 0, 0, $main_tab_len, $main_tab_depth, 0, 0, 'tbl', 1),
            array(57, $cable_access, $this->table_top_type_id, 5, 3, 1, $add_tab_len, $add_tab_depth, $add_tab_depth, 0, 'tbl', $addq), //linear table ,must have d3 also mentioned
        );

        if ($this->shared_cable_tray){
            $part[] = array(26, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', ($addq - 2) * 2);
        }

        if (array_key_exists(18, $sup)) {
            $endmodred = $this->shared_cable_tray ? 39 : 51;
            $cenmodred = $this->shared_cable_tray ? 25 : 51;
            $endcabred = $this->shared_cable_tray ? 89 : 101;
            $cencabred = $this->shared_cable_tray ? 35 : 101;
            $headcabred = 650;
            $part[] = array(18, 4, 2, 1, 0, 0, 710, $main_tab_depth - 150, 0, 0, 'sup', 2); //head table gable end
            $part[] = array(18, 4, 2, 1, 0, 0, 710, $add_tab_depth - 10, 0, 0, 'sup', 4); //addon table Non Sharing gable end
            $part[] = array(18, ($this->shared_cable_tray ? (!array_key_exists(50, $ca) || !array_key_exists(50, $cc)) ? 4 : ($cc[50]['s'] == 3 ? 3 : (array_key_exists(50, $ca) ? 2 : 4))  : 4), 2, 1, 0, 0, 710, $add_tab_depth, 0, 0, 'sup', $isq); //addon table intermediate gable end
            $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 12 + ($this->add_on_qty_per_side - 1) * 8);
        }
        if (array_key_exists(23, $sup)) {
            $support_beam = false;
            $spacercount = array(
                'legs' => array(
                    'm_nsh_s' => array(
                        'dim' => $main_tab_depth - 50,
                        'qty' => 2,
                    ),
                    'm_nsh_c' => array(
                        'dim' => $add_tab_depth - 50,
                        'qty' => 2 * ($this->add_on_qty_per_side - 1),
                    ),
                ),
                'beam' => array(
                    'bh' => array(
                        'dim' => 0,
                        'qty' => 0,
                    ),
                    'ba' => array(
                        'dim' => 0,
                        'qty' => 0,
                    ),
                ),
            );
            $endmodred = $this->shared_cable_tray ? 85 : 120;
            $cenmodred = $this->shared_cable_tray ? 50 : 120;
            $endcabred = $this->shared_cable_tray ? 95 : 130;
            $cencabred = $this->shared_cable_tray ? 60 : 130;
            $endrwred = $this->shared_cable_tray ? 39 : 74;
            $cenrwred = $this->shared_cable_tray ? 4 : 74;
            $headcabred = 670;
            $headrwred = 604;
            $sids1 = 11;
            $cens1 = 21;
            $leg_h = $sup[23]['leg_h'];
            $head_leg_red = $sup[23]['head']['si']['leg_red'];
            $addon_si_leg_red = $sup[23]['addon']['si']['leg_red'];
            $addon_cen_leg_red = $sup[23]['addon']['cen']['leg_red'];
            $part[] = array(23, $sup[23]['s'], 0, $sids1, 11, 1, $main_tab_depth - $head_leg_red, $leg_h, $leg_h, 0, 'sgf', 1); //head table leg
            $part[] = array(23, $sup[23]['s'], 0, $sids1, 11, 2, $main_tab_depth - $head_leg_red, $leg_h, $leg_h, 0, 'sgf', 1); //head table leg
            $part[] = array(23, $sup[23]['s'], 0, $sids1, 11, 1, $add_tab_depth - $addon_si_leg_red, $leg_h, $leg_h, 0, 'sgf', 2); //addon table Non Sharing leg
            $part[] = array(23, $sup[23]['s'], 0, $sids1, 11, 2, $add_tab_depth - $addon_si_leg_red, $leg_h, $leg_h, 0, 'sgf', 2); //addon table Non Sharing leg
            $part[] = array(23, $sup[23]['s'], 0, $cens1, 11, $this->shared_cable_tray ? 3 : 1, $add_tab_depth - $addon_cen_leg_red, $leg_h, $leg_h, 0, 'sgf', $isq / 2); //addon table intermediate leg - left
            $part[] = array(23, $sup[23]['s'], 0, $cens1, 11, $this->shared_cable_tray ? 3 : 2, $add_tab_depth - $addon_cen_leg_red, $leg_h, $leg_h, 0, 'sgf', $isq / 2); //addon table intermediate leg - right
            $part[] = array(24, 1, 2, 0, 0, 0, $main_tab_len - 600, 0, 0, 0, 'sgf', 1); // head table beam
            $part[] = array(24, 1, 2, 0, 0, 0, $add_tab_len - 50 - ($this->shared_cable_tray ? 0 : 25), 0, 0, 0, 'sgf', 4); // addon table side beam
            $part[] = array(24, 1, 2, 0, 0, 0, $add_tab_len - 50 - ($this->shared_cable_tray ? 0 : 50), 0, 0, 0, 'sgf', $addq - 4); // addon table centre beam
        }
        
        if (array_key_exists(55, $sup)) {
            $endmodred = 0;
            $cenmodred = 0;
            $endcabred = $this->shared_cable_tray ? 125 : 140;
            $cencabred = $this->shared_cable_tray ? 110 : 140;
            $headcabred = 760;
            $legwdt = $main_tab_depth < 750 ? 1 : 2;
            $part[] = array(55, 0, 0, $sup[55]['s1'], $legwdt, 1, 0, 0, 0, 0, 'sup', 2); // head table support
            $part[] = array(55, 0, 0, $sup[55]['s1'], $legwdt, $this->shared_cable_tray ? 2 : 1, 0, 0, 0, 0, 'sup', $isq); // addon centre table support
            $part[] = array(55, 0, 0, $sup[55]['s1'], $legwdt, 1, 0, 0, 0, 0, 'sup', 4); // addon end table support
        }

        if ($this->modesty_height) {
            $part[] = array(41, 1, $this->modesty_type_id, 0, 0, 0, $this->modesty_height, $main_tab_len - 650, 0, 0, 'pm', 1); //head table modesty
            $part[] = array(41, 1, $this->modesty_type_id, 0, 0, 0, $this->modesty_height, $add_tab_len - $endmodred, 0, 0, 'pm', 4); //addon table end modesty
            $part[] = array(41, 1, $this->modesty_type_id, 0, 0, 0, $this->modesty_height, $add_tab_len - $cenmodred, 0, 0, 'pm', $addq - 4); //addon table centre modesty
        }

        if (array_key_exists(50, $ca)) { //cable tray
            $part[] = array(50, $ca[50]['s'], 0, 0, 0, 0, $main_tab_len - 710, 0, 0, 0, 'ch', 1); //Head table cable tray
            $part[] = array(50, $ca[50]['s'], 0, 0, 0, 0, $add_tab_len - $cencabred, 0, 0, 0, 'ch', 4); //Centre Addon table cable tray
            $part[] = array(50, $ca[50]['s'], 0, 0, 0, 0, $add_tab_len - $endcabred, 0, 0, 0, 'ch', $addq - 4); //End Addon table cable tray
        }

        if (array_key_exists(50, $cc)) { //boxing raceway
            $part[] = array(50, $cc[50]['s'], 0, 0, 0, 0, $main_tab_len - 710, 0, 0, 0, 'ch', $cc[50]['q']); //Head table boxing raceway
            $part[] = array(50, $cc[50]['s'], 0, 0, 0, 0, $add_tab_len - $cencabred, 0, 0, 0, 'ch', $cc[50]['q'] * 4); //Centre Addon table boxing raceway
            $part[] = array(50, $cc[50]['s'], 0, 0, 0, 0, $add_tab_len - $endcabred, 0, 0, 0, 'ch', $cc[50]['q'] * ($addq - 4)); //End Addon table boxing raceway
        }

        if (array_key_exists(25, $cc)) { //perform raceway
            $part[] = array(25, $cc[25]['s'], 1, 1, $cc[25]['s2'], $cc[25]['s3'], $main_tab_len - 604, 0, 0, 0, 'sf', 1); //Head table Perform raceway
            $part[] = array(25, $cc[25]['s'], 1, 1, $cc[25]['s2'], $cc[25]['s3'], $add_tab_len - $cenrwred, 0, 0, 0, 'sf', 4); //Centre Addon table Perform raceway
            $part[] = array(25, $cc[25]['s'], 1, 1, $cc[25]['s2'], $cc[25]['s3'], $add_tab_len - $endrwred, 0, 0, 0, 'sf', $addq - 4); //End Addon table Perform raceway
        }
        
        if ($support_beam == true){
            $part[] = array(81, 0, 0, 0, 0, 0, $main_tab_len - 655, 0, 0, 0, 'sf', 1);//head table
            $part[] = array(81, 0, 0, 0, 0, 0, $add_tab_len - 43, 0, 0, 0, 'sf', $addq > 4 ? 4 : $addq);//side end table
            $part[] = array(81, 0, 0, 0, 0, 0, $add_tab_len - 30, 0, 0, 0, 'sf', $addq > 4 ? $addq - 4 : 0);//side center table
        }
            
        if (array_key_exists(45, $ce)){
            $entry_sys = 1;
            if ($ce[45]['s'] == 'cover|box'){
                if (array_key_exists(18, $sup)){
                    $entry_sys = 1;
                } elseif (array_key_exists(23, $sup)){
                    $entry_sys = $this->shared_cable_tray == 1 ? 5 : 3;//individual legs - wire entry box, sharing legs - non sharing entry cover
                } else {
                    $entry_sys = 3;
                }
            } else {
                $entry_sys = $ce[45]['s'];
            }
            $part[] = array(45, $entry_sys, 0, 0, 0, 0, 0, 0, 0, 0, 'ce', $this->shared_cable_tray == 1 ? 3 : 1 + $addq);
        }

        if (array_key_exists(49, $ca))
            $part[] = array(49, $ca[49]['s'], 0, 0, 0, 0, 0, 0, 0, 0, 'ca', (1 + $addq) * $this->cable_access_per_table);

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