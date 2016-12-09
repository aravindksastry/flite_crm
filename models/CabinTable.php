<?php

Yii::import('application.models._base.BaseCabinTable');

class CabinTable extends BaseCabinTable {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'item_id' => 'Item',
            'furniture_arrangement_id' => 'Furniture Arrangement',
            'cabin_support_system_id' => 'Cabin Support System',
            'table_top_type_id' => 'Table Top Type',
            'profile_id' => 'Profile',
            'pf_profile_id' => 'Post Forming Profile',
            'front_length' => 'Front Length',
            'front_depth' => 'Front Depth',
            'side_length' => 'Side Length',
            'side_depth' => 'Side Depth',
            'back_length' => 'Back Length',
            'back_depth' => 'Back Depth',
            'tip_type_id' => 'Tip Type',
            'both_side_radius' => 'Both Side Radius',
            'front_modesty_height' => 'Front Modesty Height',
            'front_modesty_type' => 'Front Modesty Type',
            'side_modesty_height' => 'Side Modesty Height',
            'side_modesty_type' => 'Side Modesty Type',
            'back_modesty_height' => 'Back Modesty Height',
            'back_modesty_type_id' => 'Back Modesty Type',
            'front_cable_access' => 'Front Cable Access',
            'front_cable_carrier' => 'Front Cable Carrier',
            'front_cable_entry' => 'Front Cable Entry',
            'side_cable_access' => 'Side Cable Access',
            'side_cable_carrier' => 'Side Cable Carrier',
            'side_cable_entry' => 'Side Cable Entry',
            'back_cable_access' => 'Back Cable Access',
            'back_cable_carrier' => 'Back Cable Carrier',
            'back_cable_entry' => 'Back Cable Entry',
            'side_storage_ft_id' => 'Side Storage Facia Type',
            'back_storage_ft_id' => 'Back Storage Facia Type',
            'side_storage_fc_id' => 'Side Storage Configuration',
            'back_storage_fc_id' => 'Back Storage Configuration',
            'side_storage_dbt_id' => 'Side Storage Depth Body and Top Type',
            'back_storage_dbt_id' => 'Back Storage Depth Body and Top Type',
            'data_other_check_mktg' => 'Data Other Check Mktg',
            'data_other_check_design' => 'Data Other Check Design',
            'data' => 'Data',
            'side_div1' => 'Side Div1',
            'side_div2' => 'Side Div2',
            'back_div1' => 'Back Div1',
            'back_div2' => 'Back Div2',
        );
    }

    public function beforeDelete() {
        CabinTableFinish::model()->deleteAll('cabin_table_id=' . $this->id);
        Storage::model()->deleteAll('design_id =' . $this->id . ' and item_id=' . $this->item_id);
        Accessory::model()->deleteAll('design_id =' . $this->id . ' and item_id=' . $this->item_id);
        Pedestal::model()->deleteAll('design_id =' . $this->id . ' and item_id=' . $this->item_id);
        return (parent::beforeDelete());
    }

    public function afterSave() {
        $item = $this->item;
        $item->quoted_option = $this->id;
        $item->save();
        return (parent::afterSave());
    }

    public function getSpec() {
        //description
        $mainL = $mainlin = $sidelin = $sidesto = $backsto = $sqr_pst = $ftlr = $ftmr = $tips1 = 0;
        /* $direct = 1; */
        $fl = $this->front_length;
        $fd = $this->front_depth;
        $sl = $this->side_length;
        $sd = $this->side_depth;
        $bl = $this->back_length;
        $bd = $this->back_depth;
        $ftd = $fd;
        $mspolereq = $sidestouid = $backstouid = $msupuid = $rsupuid = $fmodred = $smodred = 0;
        $main_unit = array();
        $side_unit = array();
        $back_unit = array();
        $tip_data = array();
        $sidestoft = array();
        $backstoft = array();
        $supdata = array();
        $fcabstr = array();
        $scabstr = array();
        $fcabent = array();
        $scabent = array();
        $fcarstr = array();
        $scarstr = array();

        parse_str($this->tipType->data, $tip_data);
        parse_str($this->furnitureArrangement->main_unit, $main_unit);
        parse_str($this->furnitureArrangement->side_unit, $side_unit);
        parse_str($this->furnitureArrangement->back_unit, $back_unit);
        parse_str($this->sideStorageFt->data, $sidestoft);
        parse_str($this->backStorageFt->data, $backstoft);
        parse_str($this->cabinSupportSystem->data, $supdata);
        parse_str($this->frontCableAccess->data, $fcabstr);
        parse_str($this->frontCableCarrier->data, $fcarstr);
        parse_str($this->frontCableEntry->data, $fcabent);
        parse_str($this->sideCableAccess->data, $scabstr);
        parse_str($this->sideCableCarrier->data, $scarstr);
        parse_str($this->sideCableEntry->data, $scabent);

        if (array_key_exists(71, $main_unit)) //Main table type decision
            $mainL = 1;
        else
            $mainlin = 1;

        if (array_key_exists(72, $side_unit) && $this->side_length && $this->side_depth) //Side linear table presence
            $sidelin = 1;
        elseif (array_key_exists(38, $side_unit) && $this->side_length && $this->side_depth && $this->side_storage_dbt_id > 1) //Side storage presence
            $sidesto = 1;

        if (array_key_exists(38, $back_unit) && $this->back_length && $this->back_depth && $this->back_storage_dbt_id > 1) //Back storage presence
            $backsto = 1;

        $tips1 = array_key_exists(66, $tip_data) ? $tip_data[66]['s1'] : 0; //Value of tip profile - s1

        if ($sidesto)
            $sidestouid = array_key_exists(38, $sidestoft) ? 38 : (array_key_exists(60, $sidestoft) ? 60 : (array_key_exists(67, $sidestoft) ? 67 : 0)); //standard storage(38) or composite storage(60)

        if ($backsto)
            $backstouid = array_key_exists(38, $backstoft) ? 38 : (array_key_exists(60, $backstoft) ? 60 : (array_key_exists(67, $backstoft) ? 67 : 0)); //standard storage(38) or composite storage(60)

        if ($tips1) {
            if ($tips1 == 1) { //bull tip
                $ftmr = $fd / 2; //front modesty reduction
                $ftlr = $fl > 2400 ? $ftmr : 0; //front table length reduction
                $ftd = $fd; //front table depth display
                $mspolereq = $fl > 2400 ? 1 : 0;
            }
            if ($tips1 == 2) { //Single discussion tip
                $ftmr = ($fd + 150) / 2 + sqrt(150 * $fd); //front modesty reduction
                $ftlr = $fl > 2400 ? $ftmr : 0; //front table length reduction
                $ftd = $fd + 150; //front table depth display
                $mspolereq = 1;
            }

            if ($tips1 == 3) { //Double discussion tip
                $ftmr = ($fd + 300) / 2 + sqrt(150 * (150 + $fd)); //front modesty reduction
                $ftlr = $fl > 2400 ? $ftmr : 0; //front table length reduction
                $ftd = $fd + 300; //front table depth display
                $mspolereq = 1;
            }

            if ($fl > 2400) { //Custom Table addition in PL
                $part[] = array(66, 1, $this->table_top_type_id, $tips1, 0, 0, $ftlr, $ftd, 0, 0, 'tbl', 1); //odd table addition in partlist
            }
        }
        $ret = array();
        $ret[array_key_exists(71, $main_unit) ? 'L Shaped Table Size' : 'Main Linear Table'] = array_key_exists(71, $main_unit) ? $fl - $ftlr . ' (' . $fd . ') x ' . $sl . ' (' . $sd . ')' : $fl - $ftlr . ' W x ' . $fd . ' D';
        if (array_key_exists(72, $side_unit))
            $ret['Side Linear Table'] = $sl . ' W x ' . $sd . ' D';
        $ret['Supports'] = $this->cabinSupportSystem->name;
        if ($sidesto)
            $ret['Side Storage'] = '750 H x ' . $sl . ' W Top: ' . ($sl + ($backsto ? $bd : 0)) . ' W x ' . $sd . ' D';
        if ($backsto)
            $ret['Back Storage'] = '750 H x ' . $bl . ' W Top: ' . ($bl + ($mainL ? $sd : 0)) . ' W x ' . $bd . ' D';
        if ($this->front_modesty_height)
            $ret['Front Modesty Panel'] = ($this->front_modesty_height ? $this->frontModestyType->name . ' ' . $this->front_modesty_height . 'H' : '');
        if ($this->front_cable_access > 1 || $this->side_cable_access > 1)
            $ret['Cable Access'] = ($this->front_cable_access > 1 ? ' Front Table: ' . $this->frontCableAccess->name : '') .
                    ($this->side_cable_access > 1 ? ' Side Table:' . $this->sideCableAccess->name : '');
        if ($this->front_cable_carrier > 1 || $this->side_cable_carrier > 1)
            $ret['Cable Management'] = ($this->front_cable_carrier > 1 ? ' Front: ' . $this->frontCableCarrier->name : '') .
                    ($this->side_cable_carrier > 1 ? ' Side:' . $this->sideCableCarrier->name : '');
        if ($this->front_cable_entry > 1 || $this->side_cable_entry > 1)
            $ret['Cable Entry'] = ($this->front_cable_entry > 1 ? ' Front: ' . $this->frontCableEntry->name : '') .
                    ($this->side_cable_entry > 1 ? ' Side: ' . $this->sideCableEntry->name : '');

        $count = 1;
        foreach (Accessory::model()->findAll('item_id=' . $this->item_id . ' and design_id=' . $this->id) as $val) {
            if (!isset($ret['Accessory']))
                $ret['Accessory'] = '';
            $ret['Accessory'] .= '(' . $count . ') ' . $val->accessoryType->name;
            $count++;
        }
        $count = 1;
        foreach (Storage::model()->findAll('item_id=' . $this->item_id . ' and design_id=' . $this->id) as $val) {
            if (!isset($ret['Storage']))
                $ret['Storage'] = '';
            $ret['Storage'] .= '(' . $count . ') ' . 'Depth & Material:' . $val->storageDbtType->name .
                    ', Configuration:' . $val->storageFc->name . ', Size:' . $val->height_storage . ' H x ' . $val->width_storage .
                    ' W Top: ' . ($val->width_storage + $val->width_top) . ' Wx ' . ($val->storageDbtType->depth + $val->depth_top) . ' D';
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

    public function getParts($direct, $upc) {
        $mainL = $mainlin = $sidelin = $sidesto = $backsto = $sqr_pst = $ftlr = $ftmr = $tips1 = 0;
        /* $direct = 1; */
        $directopp = $direct == 1 ? 2 : 1;
        $fl = $this->front_length;
        $fd = $this->front_depth;
        $sl = $this->side_length;
        $sd = $this->side_depth;
        $bl = $this->back_length;
        $bd = $this->back_depth;
        $ftd = $fd;
        $mspolereq = $sidestouid = $backstouid = $msupuid = $rsupuid = $fmodred = $smodred = 0;
        $main_unit = array();
        $side_unit = array();
        $back_unit = array();
        $tip_data = array();
        $sidestoft = array();
        $backstoft = array();
        $supdata = array();
        $fcabstr = array();
        $scabstr = array();
        $fcabent = array();
        $scabent = array();
        $fcarstr = array();
        $scarstr = array();

        parse_str($this->tipType->data, $tip_data);
        parse_str($this->furnitureArrangement->main_unit, $main_unit);
        parse_str($this->furnitureArrangement->side_unit, $side_unit);
        parse_str($this->furnitureArrangement->back_unit, $back_unit);
        parse_str($this->sideStorageFt->data, $sidestoft);
        parse_str($this->backStorageFt->data, $backstoft);
        parse_str($this->cabinSupportSystem->data, $supdata);
        parse_str($this->frontCableAccess->data, $fcabstr);
        parse_str($this->frontCableCarrier->data, $fcarstr);
        parse_str($this->frontCableEntry->data, $fcabent);
        parse_str($this->sideCableAccess->data, $scabstr);
        parse_str($this->sideCableCarrier->data, $scarstr);
        parse_str($this->sideCableEntry->data, $scabent);

        $spacercount = array(
            'legs' => array(
                'mescup' => array(
                    'dim' => 0,
                    'qty' => 0,
                ),
                'mcsup' => array(
                    'dim' => 0,
                    'qty' => 0,
                ),
                'resup' => array(
                    'dim' => 0,
                    'qty' => 0,
                ),
                'rcsup' => array(
                    'dim' => 0,
                    'qty' => 0,
                ),
            ),
            'beam' => array(
                'f_beam' => array(
                    'dim' => 0,
                    'qty' => 0,
                ),
                'r_beam' => array(
                    'dim' => 0,
                    'qty' => 0,
                ),
            ),
        );
        
        #$cable_access = $this->cable_access_id;
        $cable_access = 1;
        $profile = $this->profile_id;
        $profile = 1;
        
        if (array_key_exists(71, $main_unit)) //Main table type decision
            $mainL = 1;
        else
            $mainlin = 1;

        if (array_key_exists(72, $side_unit) && $this->side_length && $this->side_depth) //Side linear table presence
            $sidelin = 1;
        elseif (array_key_exists(38, $side_unit) && $this->side_length && $this->side_depth && $this->side_storage_dbt_id > 1) //Side storage presence
            $sidesto = 1;

        if (array_key_exists(38, $back_unit) && $this->back_length && $this->back_depth && $this->back_storage_dbt_id > 1) //Back storage presence
            $backsto = 1;

        $tips1 = array_key_exists(66, $tip_data) ? $tip_data[66]['s1'] : 0; //Value of tip profile - s1

        if ($sidesto)
            $sidestouid = array_key_exists(38, $sidestoft) ? 38 : (array_key_exists(60, $sidestoft) ? 60 : (array_key_exists(67, $sidestoft) ? 67 : 0)); //standard storage(38) or composite storage(60)

        if ($backsto)
            $backstouid = array_key_exists(38, $backstoft) ? 38 : (array_key_exists(60, $backstoft) ? 60 : (array_key_exists(67, $backstoft) ? 67 : 0)); //standard storage(38) or composite storage(60)
        
        if ($tips1) {
            if ($tips1 == 1) { //bull tip
                $ftmr = $fd / 2; //front modesty reduction
                $ftlr = $fl > 2400 ? $ftmr : 0; //front table length reduction
                $ftd = $fd; //front table depth display
                $mspolereq = $fl > 2400 ? 1 : 0;
            }
            if ($tips1 == 2) { //Single discussion tip
                $ftmr = ($fd + 150) / 2 + sqrt(150 * $fd); //front modesty reduction
                $ftlr = $fl > 2400 ? $ftmr : 0; //front table length reduction
                $ftd = $fd + 150; //front table depth display
                $mspolereq = 1;
            }
            if ($tips1 == 3) { //Double discussion tip
                $ftmr = ($fd + 300) / 2 + sqrt(150 * (150 + $fd)); //front modesty reduction
                $ftlr = $fl > 2400 ? $ftmr : 0; //front table length reduction
                $ftd = $fd + 300; //front table depth display
                $mspolereq = 1;
            }
            if ($fl > 2400) { //Custom Table addition in PL
                $part[] = array(66, 1, $this->table_top_type_id, $tips1, 0, 0, $ftlr, $ftd, 0, 0, 'tbl', 1); //odd table addition in partlist
            }
        }

        if ($mainL) //L table addition in PL
            $part[] = array(71, $cable_access, $this->table_top_type_id, $profile, $direct, ($fl > 2400 ? 1 : $this->tip_type_id), $fl - $ftlr, $sl, $ftd, $sd, 'tbl', 1); //L table addition in partlist

        if ($mainlin)//Main Linear table addition in PL
            $part[] = array(72, $cable_access, $this->table_top_type_id, $profile, $direct, ($fl > 2400 ? 1 : $this->tip_type_id), $fl - $ftlr, $ftd, 0, 0, 'tbl', 1); //linear table addition in partlist

        if ($mspolereq)//Pole addition in PL
            $part[] = array(40, 0, 0, 0, 0, 0, 725, 85, 200, 0, 'sf', 1); //ms pole addition in partlist

        if ($sidelin) {//Side Linear table addition in PL
            $part[] = array(57, $cable_access, $this->table_top_type_id, 5, $directopp, 1, $sl, $sd, $sd, 0, 'tbl', 1); //side linear table addition in partlist
            $part[] = array(26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'sf', 2);
            #$part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sgf', 4); added in FG BOM
        }

        if ($sidesto)//Side Storage addition in PL
            $part[] = array($sidestouid, $this->side_storage_fc_id, $this->side_storage_dbt_id, $direct, $this->side_div1, $this->side_div2, 750, $sl, $sl + ($backsto ? $bd : 0), $sd, 'st,s', 1); //side storage addition in partlist

        if ($backsto)//Back Storage addition in PL
            $part[] = array($backstouid, $this->back_storage_fc_id, $this->back_storage_dbt_id, $directopp, $this->back_div1, $this->back_div2, 750, $bl, $bl + ($mainL ? $sd : 0), $bd, 'st,b', 1); //back storage addition in partlist

        /* if ($mainL) {
          $sqr_pst = 1;
          $part[] = array(48, 1, 0, 0, 0, 0, 725, 0, 0, 0, 'ap', 1);
          } */
        //UNDERSTRUCTURE
        $msupuid = $supdata['fr']['uid'];
        $rsupuid = $supdata['si']['uid'];
        $mesup = 1;
        if ($mainL) {
            if ($msupuid == 23 && $rsupuid == 23) { //Perform Support
                $mcsup = 1;
                $rcsup = 0;
                $resup = 1;
                $sqr_pst = 0;
                $modside = 0;
            } else { //GE/MS Support
                $mcsup = 0;
                $rcsup = 0;
                $resup = 1;
                $sqr_pst = 1;
                $modside = 1;
            }
        } else if ($sidelin) {
            if ($msupuid == 23 && $rsupuid == 23) { //Perform Support
                $mcsup = 1;
                $rcsup = 0;
                $resup = 1;
                $sqr_pst = 0;
                $modside = 0;
            } else { //GE/MS Support
                $mcsup = 1;
                $rcsup = 1;
                $resup = 1;
                $sqr_pst = 0;
                $modside = 1;
            }
        } else {
            if ($msupuid == 23 && $rsupuid == 23) { //Perform Support
                $mcsup = 1;
                $rcsup = 0;
                $resup = 0;
                $sqr_pst = 0;
                $modside = 0;
            } else { //GE/MS Support
                $mcsup = 1;
                $rcsup = 0;
                $resup = 0;
                $sqr_pst = 0;
                $modside = 0;
            }
        }
        // Gable ends
        if ($msupuid == 18 && $rsupuid == 18) {
            $part[] = array(18, 4, 2, 1, 0, 0, 710, $fd - 10, 0, 0, 'sup,f', 1); //Main end support
            $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2);
            $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sgf', 8 * 1);
            /* $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 4); */
            if ($mcsup) {
                $part[] = array(18, 4, 2, 1, 0, 0, 710, $fd - 10, 0, 0, 'sup,f', 1); //Main corner support
                $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2);
                $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sgf', 8 * 1);
            }
            if ($resup) {
                $part[] = array(18, 4, 2, 1, 0, 0, 710, $sd - 10, 0, 0, 'sup,s', 1); //Return End Supports
                $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2);
                $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sgf', 8 * 1);
            }
            if ($rcsup) {
                $part[] = array(18, 4, 2, 1, 0, 0, 710, 300, 0, 0, 'sup,s', 1); //Return Corner Supports
                $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2);
                $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sgf', 8 * 1);
            }
            $fmodred = 30 + (!$tips1 ? 50 : 0) + ($sqr_pst ? 150 : 30);
            if ($modside) {
                $smodred = 30 + ($rcsup ? 30 : 150); //30 for rc gable end & 150 for connecting to square post (gap from front)
            }
        }

        //Perform
        if ($msupuid == 23 && $rsupuid == 23) {
            $fr_leg_red = $supdata['fr']['leg_red'];
            $leg_h = $supdata['fr']['leg_h'];
            $si_leg_red = $supdata['si']['leg_red'];
            $si_beam_extend = $supdata['si']['beam']['extend'];
            $part[] = array(23, $supdata['fr']['s'], 0, 11, 32, 3, $fd - $fr_leg_red, $leg_h, $leg_h, 0, 'sgf', 1); //Main end support
            $spacercount['legs']['me_sh_sup']['dim'] = $fd - 10;
            $spacercount['legs']['me_sh_sup']['qty'] = 1;
            if ($mcsup) {
                $part[] = array(23, $supdata['fr']['s'], 0, 11, 32, 3, $fd - $fr_leg_red, $leg_h, $leg_h, 0, 'sgf', 1); //Main corner support
                $part[] = array(24, 1, 2, 0, 0, 0, $fl - 160, 0, 0, 0, 'sup1', 2); // Front table beam
                $spacercount['legs']['mc_sh_sup']['dim'] = $fd - 10;
                $spacercount['legs']['mc_sh_sup']['qty'] = 1;
                $spacercount['beam']['f_beam']['dim'] = $fl - 160;
                $spacercount['beam']['f_beam']['qty'] = 1 * ($fd > 599 ? 2 : 1);
            }
            if ($resup) {
                $part[] = array(23, $supdata['si']['s'], 0, 11, 11, $direct, $sd - $si_leg_red, $leg_h, $leg_h, 0, 'sgf', 1); //Return End support
                $spacercount['legs']['re_nsh_sup']['dim'] = $sd - 150;
                $spacercount['legs']['re_nsh_sup']['qty'] = 1;
                if ($sidelin){
                    $tot_side_len =  + $this->front_depth;
                    $ret_beam_len = $this->side_length + $si_beam_extend;
                } elseif ($mainL) {
                    $ret_beam_len = $this->side_length - $this->front_depth + $si_beam_extend;
                }
                $part[] = array(24, 2, 2, 0, 0, 0, $ret_beam_len, 0, 0, 0, 'sup1', 1); // Return table beam
                $spacercount['beam']['r_beam']['dim'] = $ret_beam_len;
                $spacercount['beam']['r_beam']['qty'] = 1;
                if ($rcsup) {
                    $part[] = array(23, $supdata['si']['s'], 0, 11, 11, $directopp, $sd - $si_leg_red, $leg_h, $leg_h, 0, 'sgf', 1); // Return Corner
                    $spacercount['legs']['rc_nsh_sup']['dim'] = $sd - 150;
                    $spacercount['legs']['rc_nsh_sup']['qty'] = 1;
                }
            }
            $fmodred = 70 + ($tips1 ? 50 : 20) + ($sqr_pst ? 150 : 50) + 60;
            if ($modside) {
                $smodred = 55 + ($rcsup ? 55 : 300);
            }

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
            -----------------
            foreach ($spacercount as $skey => $sval) {
                foreach ($sval as $sk => $sv) {
                    if ($skey === 'legs') {
                        $qty_spacer = 0;
                        if (strpos($sk, '_sh_') !== False) {
                            $qty_spacer = 4;
                        } elseif (strpos($sk, '_nsh_') !== False) {
                            $qty_spacer = 2;
                        }
                        $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sgf,', 2 * $qty_spacer * $sv['qty']); // 10x19 screws for spacer
                        //Line above meant for 10x19 screws where Each leg has 2 or 4 spacers & each spacer has 2 such screws
                    } elseif ($skey === 'beam') {
                        $part[] = array(31, 0, 0, 23, 0, 0, 0, 0, 0, 0, 'sgf,', floor($sv['dim'] / 300) * $sv['qty']); // No.8x60 screws for every 300 mm of beam
                        $beam_leg_joint = 2;
                        if ($sk == 'r-x'){
                            $beam_leg_joint = 1;
                            $part[] = array(31, 0, 0, 24, 0, 0, 0, 0, 0, 0, 'sgf,', $beam_leg_joint * $sv['qty']); // M6x50 Allen bolt leg to beam
                        }
                        $part[] = array(31, 0, 0, 14, 0, 0, 0, 0, 0, 0, 'sgf,', $beam_leg_joint * 2 * $sv['qty']); // M6x20 Allen bolt
                        $part[] = array(31, 0, 0, 24, 0, 0, 0, 0, 0, 0, 'sgf,', $beam_leg_joint * 2 * $sv['qty']); // M6x20 Allen bolt with Flange
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
                        if ($sk == 'r_beam'){
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
        }

        //MS Legs

        if ($msupuid == 55) {
            $part[] = array(55, 0, 0, $supdata['fr']['s1'], $fd > 600 ? 2 : 1, 1, 0, 0, 0, 0, 'sup,f', 1); //Main End Support
            if ($mcsup) {
                $part[] = array(55, 0, 0, $supdata['fr']['s1'], $fd > 600 ? 2 : 1, 1, 0, 0, 0, 0, 'sup,f', 1); //Main Corner Support
            }
            if ($rsupuid == 55) {
                //return MS Legs
                if ($resup) {
                    $part[] = array(55, 0, 0, $supdata['si']['s1'], $sd > 600 ? 2 : 1, 1, 0, 0, 0, 0, 'sup,s', 1); //Return End Support
                }
                if ($rcsup) {
                    $part[] = array(55, 0, 0, $supdata['si']['s1'], $sd > 600 ? 2 : 1, 1, 0, 0, 0, 0, 'sup,s', 1); //Return Corner Support
                }
                if ($modside) {
                    $smodred = 30 + ($rcsup ? 30 : 150);
                }
            } else {
                //return Gable Ends
                if ($resup) {
                    $part[] = array(18, 4, 2, 1, 0, 0, 710, $sd - 10, 0, 0, 'sup,s', 1); //Return End Supports
                    $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2);
                    $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sgf', 8);
                }
                if ($rcsup) {
                    $part[] = array(18, 4, 2, 1, 0, 0, 710, 300, 0, 0, 'sup,s', 1); //Return Corner Supports
                    $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2);
                    $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sgf', 8);
                    if ($modside) {
                        $smodred = 30 + ($rcsup ? 30 : 150);
                    }
                }
            }
            $fmodred = 50 + ($tips1 ? 50 : 0) + ($sqr_pst ? 150 : 30);
        }
        //AL Square Post
        if ($sqr_pst) {
            $part[] = array(48, 1, 0, 0, 0, 0, 725, 0, 0, 0, 'ap', 1);
        }

        if ($this->front_modesty_height){
            $modesty_len = $fl - $ftmr - $fmodred;
            $part[] = array(41, 1, $this->front_modesty_type, 0, 0, 0, $this->front_modesty_height, $modesty_len, 0, 0, 'pm,f', 1);//modesty panel
            if ($msupuid == 23) {
                $mod_brac_qty = $modesty_len > 1200 ? 3 : 2;
                $part[] = array(51, 2, 0, 0, 0, 0, 0, 0, 0, 0, 'pm,f', $mod_brac_qty);//modesty fixing bracket only in perform system
                $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'pm,f', 4 * $mod_brac_qty);//No. 10 x 19 Screws
                $part[] = array(31, 0, 0, 15, 0, 0, 0, 0, 0, 0, 'pm,f', 2 * $mod_brac_qty);//M6 x 25 Allen Bolt
            }
        }

        if ($fl > 1199 && $msupuid != 23 && $rsupuid != 23) {
            //This is a support beam 
            $sup_beam_len = $fl - $ftmr - 55;
            $part[] = array(81, 0, 0, 0, 0, 0, $sup_beam_len, 0, 0, 0, 'sf', 1);
            //$part[] = array(31, 0, 0, 7, 0, 0, 0, 0, 0, 0, 'pm,f', 4);//No. 8 x 16 PH Screws
            //$part[] = array(31, 0, 0, 8, 0, 0, 0, 0, 0, 0, 'pm,f', floor($sup_beam_len/300));//No. 8 x 38 Screws
        }

        if ($modside)
            $part[] = array(41, 1, $this->side_modesty_type, 0, 0, 0, $this->side_modesty_height, $sl - $smodred, 0, 0, 'pm,s', 1);

        if (array_key_exists(49, $fcabstr)) //front cable access
            $part[] = array(49, $fcabstr[49]['s'], 0, 0, 0, 0, 0, 0, 0, 0, 'ca,f', 1);

        if (array_key_exists(49, $scabstr)) //side cable access
            $part[] = array(49, $scabstr[49]['s'], 0, 0, 0, 0, 0, 0, 0, 0, 'ca,s', 1);

        if (array_key_exists(50, $fcabstr)) //front cable tray
            $part[] = array(50, $fcabstr[50]['s'], 0, 0, 0, 0, $fcabstr[50]['d1'], 0, 0, 0, 'ch,f', 1);

        if (array_key_exists(50, $scabstr)) //side cable tray
            $part[] = array(50, $scabstr[50]['s'], 0, 0, 0, 0, $scabstr[50]['d1'], 0, 0, 0, 'ch,s', 1);

        if (array_key_exists(50, $fcarstr)) //front boxing raceway
            $part[] = array(50, $fcarstr[50]['s'], 0, 0, 0, 0, $fl - $ftmr - $fmodred - 50, 0, 0, 0, 'ch,f', $fcarstr[50]['q']);

        if (array_key_exists(50, $scarstr)) //side boxing raceway
            $part[] = array(50, $scarstr[50]['s'], 0, 0, 0, 0, $sl - $smodred - 50, 0, 0, 0, 'ch,s', $scarstr[50]['q']);

        if (array_key_exists(25, $fcarstr)) //front perform raceway
            $part[] = array(25, $fcarstr[25]['s'], 1, 1, $fcarstr[25]['s2'], $fcarstr[25]['s3'], $fl - $ftmr - $fmodred + 46, 0, 0, 0, 'ch,f', 1);

        if (array_key_exists(25, $scarstr)) //side perform raceway
            $part[] = array(25, $scarstr[25]['s'], 1, 1, $scarstr[25]['s2'], $scarstr[25]['s3'], $sl - $smodred + 46, 0, 0, 0, 'ch,s', 1);

        if (array_key_exists(45, $fcabent)){ //front cable entry
            if ($fcabent[45]['s'] == 'cover|box'){
                if ($supdata['fr']['uid']==18) {
                    $entry_sys = 1;
                } elseif ($supdata['fr']['uid']==23){
                    $entry_sys = 3;//wire entry box is given for desking legs in cabin table
                } else {
                    $entry_sys = 3;
                }
            } else {
                $entry_sys = $fcabent[45]['s'];
            }
            $part[] = array(45, $entry_sys, 0, 0, 0, 0, 0, 0, 0, 0, 'ce,f', 1);
        }

        if (array_key_exists(45, $scabent)){ //side cable entry
            if ($scabent[45]['s'] == 'cover|box'){
                $entry_sys = $supdata['si']['uid']==18 ? 1 : 3;
            } else {
                $entry_sys = $scabent[45]['s'];
            }
            $part[] = array(45, $entry_sys, 0, 0, 0, 0, 0, 0, 0, 0, 'ce,s', 1);
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