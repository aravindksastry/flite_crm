<?php

Yii::import('application.models._base.BaseConferenceTable');

class ConferenceTable extends BaseConferenceTable {

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
            'table_length' => 'Table Length',
            'table_top_depth' => 'Table Top Depth',
            'conference_support_id' => 'Conference Support',
            'cable_access_type_id' => 'Cable Access Type',
            'cable_entry_type_id' => 'Cable Entry Type',
            'modesty_height' => 'Modesty Height',
            'modesty_type_id' => 'Modesty Type',
            'shared_cable_tray' => 'Shared Cable Tray',
            'access_points_per_table' => 'Access Points Per Table',
            'data' => 'Data',
            'data_other_check_design' => 'Data Other Check Design',
            'data_other_check_mktg' => 'Data Other Check Mktg',
        );
    }

    public function beforeDelete() {
        ConferenceTableFinish::model()->deleteAll('conference_table_id=' . $this->id);
        return (parent::beforeDelete());
    }

    public function afterSave() {
        $item = $this->item;
        $item->quoted_option = $this->id;
        $item->save();
        return (parent::afterSave());
    }

    public function getSpec() {
        for ($indlen = 2400; $indlen > 900; $indlen -= 150)
            if ($this->table_length % $indlen === 0)
                break;
        $ret = array();
        $ret['Table'] = $this->tableTopType->name . ' ' . $this->profile->name . ' table of Size: ' . $this->table_length . ' L x ' . $this->table_top_depth . ' D ';
        $ret['Understructure'] = ' Supports: ' . $this->conferenceSupport->name . ($this->modesty_height ? ' Modesty Panel: Type: ' . $this->modestyType->name . ' ' . $this->modesty_height . ' mm Height' : '');
        $ret['Wire Management'] = ($this->cable_access_type_id > 1 ? 'Cable Access: ' . $this->cableAccessType->name : '') . ' Qty:' . $this->access_points_per_table * ($this->table_length / $indlen) . ' nos. ' . ($this->cable_entry_type_id > 1 ? ' Cable Entry: ' . $this->cableEntryType->name : '');
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
//18,23,24,26,41,45,49,50,55,64,61
        for ($indlen = 1800; $indlen > 900; $indlen -= 150)
            if ($this->table_length % $indlen === 0)
                break;
        //$indlen = ($this->table_length % 1800 ? ($this->table_length % 1650 ? ($this->table_length % 1500 ? ($this->table_length % 1350 ? ($this->table_length % 1200 ? ($this->table_length % 1050 ? 0 : 1050) : 1200) : 1350) : 1500) : 1650) : 1800);
        if ($indlen === 900)
            throw new Exception('Invalid table length');
        $ntbl = $this->table_length / $indlen;
        $cable_access = $this->cable_access_type_id;
        $cable_access = 1;
        $profile = $this->profile_id;
        $profile = 1;
        $part = array(
            array(64, $cable_access, $this->table_top_type_id, $profile, 0, 0, $this->table_length / $ntbl, $this->table_top_depth, 0, 0, 'tbl', $ntbl),
            array(26, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 2 * ($ntbl - 1)),
        );
//modesty
        $md2 = 0;
        $pm = array();
        if ($this->modesty_height) {
            $part[] = array(41, 1, $this->modesty_type_id, 0, 0, 0, $this->modesty_height, ($this->table_length / $ntbl) - 295, 0, 0, 'pm', 4);
            $md2 = $this->table_length / $ntbl;
        }
        $sup = array();
        parse_str($this->conferenceSupport->data, $sup);
//perform leg
        if (array_key_exists(23, $sup)) {
            $spacercount = array(
                'legs' => array(
                    'leg_sh_sid' => array(
                        'dim' => $this->table_top_depth - 300 + ($sup[23]['s'] == 4 ? 220 : 0),
                        'qty' => 2,
                    ),
                    'leg_sh_cen' => array(
                        'dim' => $this->table_top_depth - 300 + ($sup[23]['s'] == 4 ? 220 : 0),
                        'qty' => 2 * ($ntbl - 1),
                    ),
                ),
                'beam' => array(
                    'beam_sid' => array(
                        'dim' => ($this->table_length / $ntbl) - 285,
                        'qty' => 4,
                    ),
                    'beam_cen' => array(
                        'dim' => ($this->table_length / $ntbl) - 50,
                        'qty' => ($ntbl - 2) * 2,
                    ),
                ),
            );
            /*
            foreach ($spacercount as $skey => $sval) {
                foreach ($sval as $sk => $sv) {
                    if ($skey == 'legs') {
                        $part[] = array(78, 1, 0, 0, 0, 0, 0, 0, 0, 0, 'sf,', ($sv['dim'] > 1200 ? 6 : 4) * $sv['qty']); //spacer 6 if > 1200 leg else 4
                        $part[] = array(78, 4, 0, 0, 0, 0, 0, 0, 0, 0, 'sf', ($sv['dim'] > 1200 ? 6 : 4) * $sv['qty']); //leg screw = spacer
                    } elseif ($skey == 'beam') {
                        $part[] = array(78, 1, 0, 0, 0, 0, 0, 0, 0, 0, 'sf', floor($sv['dim'] / 300) * $sv['qty']); //spacer 1 per rft of beam
                        $part[] = array(78, 3, 0, 0, 0, 0, 0, 0, 0, 0, 'sf', floor($sv['dim'] / 300) * $sv['qty']); //support screw = spacer
                        $part[] = array(78, 6, 0, 0, 0, 0, 0, 0, 0, 0, 'sf', 8 * $sv['qty']); //allen screw m6x20 8 per beam
                    }
                }
            }
            --------------------------
            foreach ($spacercount as $skey => $sval) {
                foreach ($sval as $sk => $sv) {
                    if ($skey === 'legs') {
                        $qty_spacer = 0;
                        if (strpos($sk, '_sh_') !== False) {
                            $qty_spacer = 4;
                        } elseif (strpos($sk, '_nsh_') !== False) {
                            $qty_spacer = 2;
                        }
                        $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf,', 2 * $qty_spacer * $sv['qty']); // 10x19 screws for spacer
                        //Line above meant for 10x19 screws where Each leg has 2 or 4 spacers & each spacer has 2 such screws
                    } elseif ($skey === 'beam') {
                        $part[] = array(31, 0, 0, 23, 0, 0, 0, 0, 0, 0, 'sf,', floor($sv['dim'] / 300) * $sv['qty']); // No.8x60 screws for every 300 mm of beam
                    }
                }
            }*/
            foreach ($spacercount as $skey => $sval) {
                foreach ($sval as $sk => $sv) {
                    if ($skey === 'legs') {
                        $qty_spacer = 0;
                        if (strpos($sk, '_sh_') !== False) {
                            $qty_spacer = 4;
                        } elseif (strpos($sk, '_nsh_') !== False) {
                            $qty_spacer = 2;
                        }
                        $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf,', 2 * $qty_spacer * $sv['qty']); // 10x19 screws for spacer
                        //Line above meant for 10x19 screws where Each leg has 2 or 4 spacers & each spacer has 2 such screws
                    } elseif ($skey === 'beam') {
                        $part[] = array(31, 0, 0, 23, 0, 0, 0, 0, 0, 0, 'sf,', floor($sv['dim'] / 300) * $sv['qty']); // No.8x60 screws for every 300 mm of beam
                        $beam_leg_joint = 2;
                        if ($sk == 'r-x'){
                            $beam_leg_joint = 1;
                            $part[] = array(31, 0, 0, 29, 0, 0, 0, 0, 0, 0, 'sf,', $beam_leg_joint * $sv['qty']); // M6x50 Allen bolt leg to beam
                        }
                        $part[] = array(31, 0, 0, 14, 0, 0, 0, 0, 0, 0, 'sf,', $beam_leg_joint * 2 * $sv['qty']); // M6x20 Allen bolt
                        $part[] = array(31, 0, 0, 24, 0, 0, 0, 0, 0, 0, 'sf,', $beam_leg_joint * 2 * $sv['qty']); // M6x20 Allen bolt with Flange
                    } elseif ($skey === 'rw') {
                        //raceway fasteners to be added here but only count of rw is collected
                        //$part[] = array(31, 0, 0, 3, 0, 0, 0, 0, 0, 0, 'sf,', 2 * $sv['qty']); // M6x45 Allen bolt with washer
                        $rw_count += $sv['qty'];
                    }
                }
            }
            $sid_leg_h = $sup[23]['sid']['leg_h'];
            $cen_leg_h = $sup[23]['cen']['leg_h'];
            $leg_h = $sup[23]['leg_h'];
            $si_leg_red = $sup[23]['si']['leg_red'];
            $cen_leg_red = $sup[23]['cen']['leg_red'];
            $beam_qty_pertab = 2;
            $s2 = 32;
            if ($this->table_top_depth >= 1500){
                $beam_qty_pertab = 4;//if the table depth is >= 1500 4 beams else 2 beams per table
                $s2 = 34;
            }
            $part[] = array(23, $sup[23]['sys']['sid'], 0, 11, $s2, 3, $this->table_top_depth - $si_leg_red, $sid_leg_h, $sid_leg_h, 0, 'sgf', 2);
            $part[] = array(24, 1, 2, 0, 0, 0, ($this->table_length / $ntbl) - 114, 0, 0, 0, 'sgf', $beam_qty_pertab * 2);
            $part[] = array(23, $sup[23]['sys']['cen'], 0, 21, $s2, 3, $this->table_top_depth - $cen_leg_red, $cen_leg_h, $cen_leg_h, 0, 'sgf', $ntbl - 1);
            if ($ntbl > 2)
                $part[] = array(24, 1, 2, 0, 0, 0, ($this->table_length / $ntbl) - 70, 0, 0, 0, 'sgf', ($ntbl - 2) * $beam_qty_pertab);
            if ($md2)
                $md2 -= 60;
        }
//metal leg
        if (array_key_exists(55, $sup)) {
            $part[] = array(55, 0, 0, $sup[55]['s1'], ($this->table_top_depth > 1050 ? 3 : 2), 1, 0, 0, 0, 0, 'sup', 2);
            $part[] = array(55, 0, 0, $sup[55]['s1'], ($this->table_top_depth > 1050 ? 3 : 2), 2, 0, 0, 0, 0, 'sup', $ntbl - 1);
        }
        $ca = array();
        parse_str($this->cableAccessType->data, $ca);
//gable end
        if (array_key_exists(18, $sup)) {
            $part[] = array(18, 4, 2, 1, 0, 0, 710, $this->table_top_depth - 300, 0, 0, 'sup', 2);
            $part[] = array(18, (array_key_exists(50, $ca) && $this->shared_cable_tray ) ? 2 : 4, 2, 1, 0, 0, 710, $this->table_top_depth - 300, 0, 0, 'sup', $ntbl - 1);
            $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 4 * $ntbl);
            if ($md2)
                $md2 -= 25;
        }
        if ($md2 && $ntbl > 2)
            $part[] = array(41, 1, $this->modesty_type_id, 0, 0, 0, $this->modesty_height, $md2, 0, 0, 'pm', 2 * ($ntbl - 2));
        //cable access
        if (array_key_exists(49, $ca))
            $part[] = array(49, $ca[49]['s'], 0, 0, 0, 0, 0, 0, 0, 0, 'ca', $ntbl * $this->access_points_per_table);
        if (array_key_exists(50, $ca)) {
            $part[] = array(50, $ca[50]['s'], 0, 0, 0, 0, ($this->table_length / $ntbl) - 415, 0, 0, 0, 'ch', 2);//end cable trays
            if ($ntbl > 2)
                $part[] = array(50, $ca[50]['s'], 0, 0, 0, 0, ($this->table_length / $ntbl) - 120, 0, 0, 0, 'ch', $ntbl - 2);//center cable trays
        }
        $ce = array();
        parse_str($this->cableEntryType->data, $ce);
        //cable entry
        if (array_key_exists(45, $ce)){
            if ($ce[45]['s'] == 'cover|box'){
                if (array_key_exists(18, $sup)) {
                    $entry_sys = 1;
                } elseif (array_key_exists(23, $sup)){
                    $entry_sys = 4;
                } else {
                    $entry_sys = 3;
                }
            } else {
                $entry_sys = $ce[45]['s'];
            }
            $part[] = array(45, $entry_sys, 0, 0, 0, 0, 0, 0, 0, 0, 'ce', $ntbl - 1);
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