<?php

Yii::import('application.models._base.BaseCubicle');

class Cubicle extends BaseCubicle {

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
            'furniture_arrangement_id' => 'Furniture Arrangement',
            'cabin_support_system_id' => 'Cabin Support System',
            'front_length' => 'Front Length',
            'front_depth' => 'Front Depth',
            'side_length' => 'Side Length',
            'side_depth' => 'Side Depth',
            'back_length' => 'Back Length',
            'back_depth' => 'Back Depth',
            'tip_type_id' => 'Tip Type',
            'both_side_radius' => 'Both Side Radius',
            'frame_height' => 'Frame Height',
            'frame_system' => 'Frame System',
            'default_att_inside_tile_id' => 'Default Att Inside Tile',
            'default_btt_inside_tile_id' => 'Default Btt Inside Tile',
            'default_att_passage_tile_id' => 'Default Att Passage Tile',
            'default_btt_passage_tile_id' => 'Default Btt Passage Tile',
            'special_tile1_id' => 'Special Tile1',
            'special_tile2_id' => 'Special Tile2',
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
            'data_other_checks_mktg' => 'Data Other Checks Mktg',
            'data_other_checks_design' => 'Data Other Checks Design',
            'side_storage_div_width' => 'Side Storage Div Width',
            'back_storage_div_width' => 'Back Storage Div Width',
            'side_storage_handle_type' => 'Side Storage Handle Type',
            'back_storage_handle_type' => 'Back Storage Handle Type',
            'data' => 'Data',
        );
    }

    public function beforeDelete() {
        CubicleFinish::model()->deleteAll('cubicle_id=' . $this->id);
        Storage::model()->deleteAll('design_id =' . $this->id . ' and item_id=' . $this->item_id);
        Accessory::model()->deleteAll('design_id =' . $this->id . ' and item_id=' . $this->item_id);
        OverHeadStorage::model()->deleteAll('design_id =' . $this->id . ' and item_id=' . $this->item_id);
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
        $main_unit = array();
        $side_unit = array();
        $back_unit = array();
        $ret = array();
        if ($this->furniture_arrangement_id) {
            parse_str($this->furnitureArrangement->main_unit, $main_unit);
            parse_str($this->furnitureArrangement->side_unit, $side_unit);
            parse_str($this->furnitureArrangement->back_unit, $back_unit);
            if (array_key_exists(71, $main_unit)){
                $ret['L Shaped Table Size'] = $this->front_length . '(' . $this->frontDepth .')' . 'x' . $this->sideLength . '(' . $this->sideDepth . ')';
            } else {
                $ret['Main Linear Table'] = $this->front_length . ' L x ' . $this->frontDepth .'D' ;
            }
            
            if (array_key_exists(72, $side_unit))
                $ret['Side Table'] = $this->sideLength . ' L x ' . $this->sideDepth . ' D';
            elseif (array_key_exists(38, $side_unit))
                $ret['Side Storage'] = $this->sideLength . ' L x ' . $this->sideDepth . ' D';
            if (array_key_exists(38, $back_unit))
                $ret['Back Storage'] = $this->backLength . ' L x ' . $this->backDepth . ' D';
        }
        else
            $ret['Furniture Arrangement'] = 'ERROR';

        if ($this->front_modesty_height || $this->side_modesty_height)
            $ret['Modesty Panels'] = ($this->front_modesty_height ? 'Front:' . $this->frontModestyType->name . ' ' . $this->front_modesty_height . 'H' : '') .
                    ($this->side_modesty_height ? 'Side:' . $this->sideModestyType->name . ' ' . $this->side_modesty_height . 'H' : '') . ' Supported on:' . $this->cabinSupportSystem->name;
        if ($this->front_cable_access > 1 || $this->side_cable_access > 1)
            $ret['Cable Access'] = ($this->front_cable_access > 1 ? ' Front:' . $this->frontCableAccess->name : '') .
                    ($this->side_cable_access > 1 ? ' Side:' . $this->sideCableAccess->name : '');
        if ($this->front_cable_carrier > 1 || $this->side_cable_carrier > 1)
            $ret['Cable Management'] = ($this->front_cable_carrier > 1 ? ' Front:' . $this->frontCableCarrier->name : '') .
                    ($this->side_cable_carrier > 1 ? ' Side:' . $this->sideCableCarrier->name : '');
        if ($this->front_cable_entry > 1 || $this->side_cable_entry > 1)
            $ret['Cable Entry'] = ($this->front_cable_entry > 1 ? ' Front:' . $this->frontCableEntry->name : '') .
                    ($this->side_cable_entry > 1 ? ' Side:' . $this->sideCableEntry->name : '');
        $ret['Frame System'] = $this->frame_system ? $this->frameSystem->name . ' ' . $this->frame_height . 'mm Height' : 'ERROR';
        $ret['Tiles'] = 'Inside ATT-' . ($this->default_att_inside_tile_id ? $this->defaultAttInsideTile->name : 'ERROR') .
                ' BTT-' . ($this->default_btt_inside_tile_id ? $this->defaultBttInsideTile->name : 'ERROR') .
                ' Passage ATT-' . ($this->default_att_passage_tile_id ? $this->defaultAttPassageTile->name : 'ERROR') .
                ' BTT-' . ($this->default_btt_passage_tile_id ? $this->defaultBttPassageTile->name : 'ERROR') .
                ' Special Tiles -' . ($this->special_tile1_id ? $this->specialTile1->name : 'ERROR') . ',' .
                ($this->special_tile2_id ? $this->specialTile2->name : 'ERROR');

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
        //description
        /*        $quote = '';
          $quote = $this->furnitureArrangement->name;
          if ($mainL)
          $quote = $quote . 'L Shaped Table Size:' . $fl - $ftlr . '(' . $ftd . ')' . $sl - $ltrr . '(' . $sd . ')';
          if ($mainlin)
          $quote = $quote . 'Main Linear Table:' . $fl - $ftlr . 'x' . $ftd;
          if ($sidelin)
          $quote = $quote . 'Side Table:' . $sl - $surr . 'L x ' . $sd . 'D';
          if ($sidesto)
          $quote = $quote . 'Side Storage:' . $this->sideStorageDbt->name . 'Configuration:' . $this->sideStorageFc->name . ' Size- 750Hx' . $sl - $fd - ($backsto ? $bd : 0) . 'Wx' . 'Top:' . $sl - $fd - $surr . 'Wx' . $sd . 'D';
          if ($backsto)
          $quote = $quote . 'Back Storage' . $this->backStorageDbt->name . 'Configuration:' . $this->backStorageFc->name . ' Size- 750Hx' . $bl . 'Wx' . 'Top:' . $bl + ($surr || $ltrr ? $sd : 0) . 'Wx' . $bd . 'D';;
          if ($this->front_modesty_height || $this->side_modesty_height)
          $quote = $quote . ' Modesty Panels : ' . ($this->front_modesty_height ? 'Front:' . $this->frontModestyType->name . ' ' . $this->front_modesty_height . 'H' : '') . ($this->side_modesty_height ? 'Side:' . $this->sideModestyType->name . ' ' . $this->side_modesty_height . 'H' : '');
          //supports
          $quote = $quote . 'Supported on:' . $this->cabinSupportSystem->name;
          //wire management
          if ($this->front_cable_access > 1 || $this->side_cable_access > 1) {
          $quote = $quote . 'Cable Access:' . ($this->front_cable_access > 1 ? ' Front:' . $this->frontCableAccess->name : '') . ($this->side_cable_access > 1 ? ' Side:' . $this->sideCableAccess->name : '');
          }
          if ($this->front_cable_carrier > 1 || $this->side_cable_carrier > 1) {
          $quote = $quote . 'Cable Management:' . ($this->front_cable_carrier > 1 ? ' Front:' . $this->frontCableCarrier->name : '') . ($this->side_cable_carrier > 1 ? ' Side:' . $this->sideCableCarrier->name : '');
          }
          if ($this->front_cable_entry > 1 || $this->side_cable_entry > 1) {
          $quote = $quote . 'Cable Entry:' . ($this->front_cable_entry > 1 ? ' Front:' . $this->frontCableEntry->name : '') . ($this->side_cable_entry > 1 ? ' Side:' . $this->sideCableEntry->name : '');
          }
          //frames
          $quote = $quote . ' Frame System:' . $this->frameSystem->name . ' ' . $this->frame_height . 'mm Height';
          $quote = $quote . 'Tiles: Inside ATT-' . $this->defaultAttInsideTile->name . ' BTT-' . $this->defaultBttInsideTile->name . ' Passage ATT-' . $this->defaultAttPassageTile->name . ' BTT-' . $this->defaultBttPassageTile->name . 'Special Tiles -' . $this->specialTile1->name . ',' . $this->specialTile2->name;
          return $quote; */
    }

    /** @var $cubTbl CubicleTable[] */
    public function getParts($point, $cubTbl, $upc) {

        $part = array();
        $frmdata = array();
        $coordcalc = array();
        $coordcalc[0] = 1;
        $coordcalc[1] = 10;
        $coordcalc[2] = -1;
        $coordcalc[3] = -10;


        $axlen = $frmcnt = $f = $dir = 0;
        $coordplus = $coordminus = $axplus = $axminus = $axopp = $fs1 = $fs2 = $axis = $coordnxt = 0;
        parse_str($this->frameSystem->data, $frmdata);
        $x = $y = $f = $coord = $axlen = $frmcnt = $trim1len = $axis = $js1 = $js2 = $xadd = $yadd = 0;
// start of partitions 
        while ($x < 9) {  //The matrix has 9 points on x axis - 0 to 8
            $y = 0;
            while ($y < 3) {  //The matrix has 3 points on y axis - 0 to 2
                $coord = $x * 10 + $y;  //The first digit of the value of coord (co-ordinate) refers to x co-ordinate & second digit refers to y-axis co-ordinate
                $point[$coord]['dir'] = 0;
                $axis = 0;
                $dir = 0;
                while ($axis < 4) {  //Each Coordinate has 4 axes - 1-up, 2-right, 3-down, 4-left
// point is an array which holds all data given in cubicle cluster
// [coord] refers to location of a set of frames in different direction
// [coord][axis] refers to frames in a poarticular direction (up/right/down/left))
// [coord][axis][f(1/2/3/4/5/6)] refers to the count of frames in a given direction
// [coord][axis][f(1/2/3/4/5/6)][w] refers to the width of a frame in the given direction
// [coord][axis][f(1/2/3/4/5/6)][e1] refers to the facia selection for a given frame - inside (E1)
// [coord][axis][f(1/2/3/4/5/6)][e1] refers to the facia selection for a given frame - outside (E2)
                    $fs1 = 1;
                    $axlen = 0;
                    $frmcnt = 0;
                    $f = 0;
                    $axplus = $axis + ($axis == 3 ? -3 : 1);
                    $axminus = $axis + ($axis == 0 ? 3 : -1);
                    $axopp = $axis + (($axis == 3 || $axis == 2) ? -2 : 2);
                    //$coordplus = $coord + $coordcalc[$axis];
                    //$coordminus = $coord - $coordcalc[$axis];
                    $coordnxt = ($coord + ($axis == 0 ? 1 : ($axis == 1 ? 10 : ($axis == 2 ? -1 : -10))));
                    $coordprev = ($coord - ($axis == 0 ? 1 : ($axis == 1 ? 10 : ($axis == 2 ? -1 : -10))));
//slotting calculation
                    if (($x == 0 && $axis == 3) || ($x == 8 && $axis == 1) || ($y == 0 && $axis == 2) || ($y == 2 && $axis == 0)) {
                        $fs1 = 1;
                    } else {
                        if ($point[$coord][$axplus][0]['w'] && $point[$coord][$axminus][0]['w']) {
                            $fs1 = 2;
                        } else if ($coordnxt > -1 && $coordnxt % 10 < 3 && $coordnxt / 10 < 9) {

                            if ($point[$coordnxt][$axplus][0]['w'] && $point[$coordnxt][$axminus][0]['w']) {
                                $fs1 = 2;
                            }
                        } else if ($coordprev > -1 && $coordprev % 10 < 3 && $coordprev / 10 < 9) {
                            if ($point[$coordprev][$axplus][0]['w'] || $point[$coordprev][$axminus][0]['w']) {
                                $fs1 = 2;
                            }
                        } else {
                            $fs1 = 1;
                        }
                    }


// Frame Addition
                    while ($f < 6) {
                        if ($point[$coord][$axis][$f]['w'] > 0 && $point[$coord][$axis][$f]['e1']->facia_id > 0 && $point[$coord][$axis][$f]['e2']->facia_id > 0) {
                            $this->addframe($part, $point[$coord][$axis][$f]['w'], $fs1, $point[$coord][$axis][$f]['e1'], $point[$coord][$axis][$f]['e2']); //give frames, tiles, raceway, skirting & stoppers in partlist. 
                            $axlen += $point[$coord][$axis][$f]['w'];
                            $frmcnt += 1;
// H6 Clips                                                        
                            $part[] = array(32, $this->frame_system, 0, 1, 0, 0, 0, 0, 0, 0, 'co:' . $coord . ', ax:' . $axis . ', fr:' . $f, 3);
                            if ($f > 0) {
//fastner frame to frame
                                $part[] = array(31, 0, 0, ($this->frame_system < 3 ? 2 : 3), 0, 0, 0, 0, 0, 0, 'co:' . $coord . ', ax:' . $axis . ', fr:' . $f, floor($this->frame_height / 300));
                            }
                        }
                        $f += 1;
                    }
// Add Grouting Post

                    if ($point[$coord][$axis]['ex'] == 2 || $point[$coord][$axis]['ex'] == 4) {
                        $part[] = array(80, $this->frame_system, 0, 0, 0, 0, $this->frame_height, 0, 0, 0, 'sf', 1); //big grouting post
                        $part[] = array(31, 0, 0, 6, 0, 0, 0, 0, 0, 0, '', 4); //hilti grouting bolt
                    }
// Add Grouting Flange
                    if ($point[$coord][$axis]['ex'] == 3 || $point[$coord][$axis]['ex'] == 4) {
                        $part[] = array(79, $this->frame_system, 0, 0, 0, 0, 0, 0, 0, 0, 'sf', 1); //Grouting flange
                        $part[] = array(31, 0, 0, 6, 0, 0, 0, 0, 0, 0, '', 4); //hilti grouting bolt
                        $part[] = array(31, 0, 0, 10, 0, 0, 0, 0, 0, 0, '', 2); //8x19 screws
                    }
// Top Trims
                    $i = 0;
                    $trim1len = 0;
                    while ($i < $frmcnt) {
                        if ($trim1len + $point[$coord][$axis][$i]['w'] < 3601) {
                            $trim1len += $point[$coord][$axis][$i]['w'];
                        }
                        $i += 1;
                    }
                    if ($point[$coord][$axis]['ex'] == 2 || $point[$coord][$axis]['ex'] == 4) {
// Add Big Grouting Post thickness to trim length
                        $trim1len += $frmdata['frm']['thk'];
                        $axlen += $frmdata['frm']['thk'];
                    }
                    if ($trim1len > 0) {
                        $part[] = array(27, $this->frame_system, 0, 0, 0, 0, $trim1len, 0, 0, 0, 'sf', 1); //add top trims
                    }
                    if ($trim1len < $axlen) { //additional top trims if > 3600
                        $part[] = array(27, $this->frame_system, 0, 0, 0, 0, $axlen - $trim1len, 0, 0, 0, 'sf', 1);
                    }
//if($x < 1)
//$part[] = array(12, 0, 0, 0, 0, 0, 0, 0, 0, 0, $coord.'('.$axis.'-'.$axopp.'),cnx:'.$coordnxt.' cpl:'.$coordplus.' cpm:'.$coordminus, 1);
//if(($coordnxt < 0) || ($coordnxt % 10 == 9) || (floor($coordnxt / 10) == 9) || ($coordnxt % 10 == 3))
                    if (($coordnxt < 0) || ($coordnxt % 10 > 2) || (floor($coordnxt / 10) > 8)) { //Coord next outside the grid
                        if ($point[$coord][$axis][0]['w']) { //if frame going outwards from outer ring
                            $part[] = array(30, $this->frame_system, 0, 0, 0, 0, $this->frame_height, 0, 0, 0, 'sf', 1); //outer side trim
                            $part[] = array(44, $this->frame_system, 1, 0, 0, 0, 0, 0, 0, 0, 'sf', 1);
                            $part[] = array(32, $this->frame_system, 0, 2, 0, 0, 0, 0, 0, 0, $coord, floor($this->frame_height / 375));
                            $dir += 1;
                        }
                    } else {
                        if ($point[$coord][$axis][0]['w'] || (($point[$coordnxt][$axopp]['ex'] == 1) && ($point[$coordnxt][$axopp][0]['w']))) {//keep adding dir for each axis when you find frame
                            $dir += 1;
                        }
                        if ($point[$coord][$axis][0]['w'] && $point[$coord][$axis]['ex'] != 1 && $point[$coordnxt][$axopp]['ex'] != 1) { //for side trims in between - not at coordinate
                            if ($point[$coord][$axis]['ex'] == 2 || $point[$coord][$axis]['ex'] == 4) {
                                $part[] = array(44, $this->frame_system, 2, 0, 0, 0, 0, 0, 0, 0, 'sf', 1); //End Cap - straight cap
                            } else {
                                $part[] = array(30, $this->frame_system, 0, 0, 0, 0, $this->frame_height, 0, 0, 0, 'sf', 1); //side trims
                                $part[] = array(44, $this->frame_system, 1, 0, 0, 0, 0, 0, 0, 0, 'sf', 1); //End Cap - std end cap
                                $part[] = array(32, $this->frame_system, 0, 2, 0, 0, 0, 0, 0, 0, $coord, floor($this->frame_height / 375)); //H7 clips
                            }
                        }
                    }
                    $axis += 1;
                }
//junction components
                if ($dir > 1) {
                    $js1 = ($dir == 2 ? 22 : ($dir == 3 ? 30 : 41));
                    $js2 = $frmdata['frm']['thk'];
//Junction holders
                    $part[] = array(28, $dir == 4 ? 1 : 2, 0, $js1, $js2, $js2, $dir == 4 ? 75 : $this->frame_height, $dir == 4 ? 75 : $this->frame_height, 0, 0, 'sf', $dir == 4 ? floor($this->frame_height / 500) : 1);
                    $part[] = array(31, 0, 0, $this->frame_system < 3 ? 2 : 1, 0, 0, 0, 0, 0, 0, $coord, ($dir == 4 ? floor($this->frame_height / 500) : 1) * ($dir == 4 ? 4 : floor($this->frame_height / 300) * $dir));
//Junction Caps
                    $part[] = array(29, 0, 0, $js1, $js2, $js2, 0, 0, 0, 0, 'sf,c' . $coord . ' a' . ($axis - 1), 1);
                } else if ($dir == 1) {
                    $part[] = array(30, $this->frame_system, 0, 0, 0, 0, $this->frame_height, 0, 0, 0, 'sf,coord trim', 1); //coordinate side trim
                    $part[] = array(44, $this->frame_system, 1, 0, 0, 0, 0, 0, 0, 0, 'sf', 1); //End Cap
                    $part[] = array(32, $this->frame_system, 0, 2, 0, 0, 0, 0, 0, 0, $coord, floor($this->frame_height / 375)); //H7 clips for side trims
                }
                $y += 1;
            }
            $x += 1;
        }
//end of partitions
        foreach ($cubTbl as $value) {
            /** @var $value CubicleTable  */
            if ($value->include_LH_table || $value->include_RH_table)
                $this->addtables($part, $value->understructure_reqd, $value->side_frames_matching, $value->back_frames_matching, $value->include_LH_table ? 1 : 2, $value->table_qty);
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

    /**  @param CubicleElevation $ele1 */

    /**  @param CubicleElevation $ele2 */
    public function addframe(&$part, $width, $slot, $ele1, $ele2) {
        $s2 = 0;
        $s2 += (($ele1->facia_id && $ele1->facia->raceway_above_table_top_height) || ($ele2->facia_id && $ele2->facia->raceway_above_table_top_height)) ? 20 : 0;
        $s2 += (($ele1->facia_id && $ele1->facia->raceway_below_table_top_height) || ($ele2->facia_id && $ele2->facia->raceway_below_table_top_height)) ? 2 : 0;
        $s2 += $s2 == 0 ? 1 : 0;
        if ($s2 == 1 && $ele1->facia->total_ht > 1275) {
            $s2 = 2;
        }
        $part[] = array(12, $this->frame_system, 1, $slot, $s2, 0, $ele1->facia->total_ht, $width, 0, 0, 'sf', 1);
        $this->addtiles($part, $ele1, $this->frame_system, $width);
        $this->addtiles($part, $ele2, $this->frame_system, $width);
    }

    /** @param CubicleElevation $ele */
    public function addtiles(&$part, $ele, $sys, $width) {
        $fin = 'cef,' . $ele->id . ',';
        $frmdata = array();
        parse_str($this->frameSystem->data, $frmdata);
//addon 2
        if ($ele->facia->addon2_height) {
            $part[] = array(15, $frmdata[15]['s'], $ele->facia->addon2_type, 1, 0, 0, $ele->facia->addon2_height, $width, 0, 0, $fin . 'a2', 1);
            if ($ele->facia->addon2_type == 3) {
                $part[] = array(75, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 4);
            }
        }
//addon 1
        if ($ele->facia->addon1_height) {
            $part[] = array(15, $frmdata[15]['s'], $ele->facia->addon1_type, 1, 0, 0, $ele->facia->addon1_height, $width, 0, 0, $fin . 'a1', 1);
            if ($ele->facia->addon1_type == 3) {
                $part[] = array(75, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 4);
            }
        }
//top
        if ($ele->facia->top_tile_height) {
            $part[] = array(15, $frmdata[15]['s'], $ele->facia->top_tile_type, $ele->facia->top_tile_split_width ? 2 : 1, 0, 0, $ele->facia->top_tile_height, $width - $ele->facia->top_tile_split_width, 0, 0, $fin . 'tt', 1);
            if ($ele->facia->middle_tile_height) {
                $part[] = array(37, 1, 0, 0, 0, 0, $width, 0, 0, 0, '', 1);
            }
            if ($ele->facia->top_tile_type == 3) {
                $part[] = array(75, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 4);
            }
        }
//topsplit
        if ($ele->facia->top_tile_split_width) {
            $part[] = array(37, 2, 0, 0, 0, 0, $ele->facia->top_tile_height, 0, 0, 0, '', 1);
            $part[] = array(15, $frmdata[15]['s'], $ele->facia->top_tile_split_type, 2, 0, 0, $ele->facia->top_tile_height, $ele->facia->top_tile_split_width, 0, 0, $fin . 'ts', 1);
            if ($ele->facia->top_tile_split_type == 3) {
                $part[] = array(75, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 4);
            }
        }
//mid
        if ($ele->facia->middle_tile_height) {
            $part[] = array(15, $frmdata[15]['s'], $ele->facia->middle_tile_type, $ele->facia->middle_tile_split_width ? 2 : 1, 0, 0, $ele->facia->middle_tile_height, $width - $ele->facia->middle_tile_split_width, 0, 0, $fin . 'mt', 1);
            if ((!$ele->facia->raceway_above_table_top_height && !$ele->facia->band_tile_height && !$ele->facia->raceway_below_table_top_height) || (!$ele->facia->raceway_above_table_top_height && $ele->facia->band_tile_height)) {
                $part[] = array(37, 1, 0, 0, 0, 0, $width, 0, 0, 0, 'w', 1);
            }
            if ($ele->facia->middle_tile_type == 3) {
                $part[] = array(75, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 4);
            }
        }
//midsplit
        if ($ele->facia->middle_tile_split_width) {
            $part[] = array(37, 2, 0, 0, 0, 0, $ele->facia->middle_tile_height, 0, 0, 0, '', 1);
            $part[] = array(15, $frmdata[15]['s'], $ele->facia->middle_tile_split_type, 2, 0, 0, $ele->facia->middle_tile_height, $ele->facia->middle_tile_split_width, 0, 0, $fin . 'ms', 1);
            if ($ele->facia->middle_tile_split_type == 3) {
                $part[] = array(75, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 4);
            }
        }
//rwa
        if ($ele->facia->raceway_above_table_top_height) {
            $part[] = array(13, $frmdata[13]['s'], 0, 0, 0, 0, $ele->facia->raceway_above_table_top_height, $width, 0, 0, $fin . 'ra', 1);
        }
//band
        if ($ele->facia->band_tile_height) {
            $part[] = array(15, $frmdata[15]['s'], $ele->facia->band_tile_type, 1, 0, 0, $ele->facia->band_tile_height, $width, 0, 0, $fin . 'b', 1);
            if ($ele->facia->bottom_tile_height && !$ele->facia->raceway_below_table_top_height) {
                $part[] = array(37, 1, 0, 0, 0, 0, $width, 0, 0, 0, '', 1);
            }
            if ($ele->facia->band_tile_type == 3) {
                $part[] = array(75, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 4);
            }
        }
//rwb
        if ($ele->facia->raceway_below_table_top_height) {
            $part[] = array(13, $frmdata[13]['s'], 0, 0, 0, 0, $ele->facia->raceway_below_table_top_height, $width, 0, 0, $fin . 'rb', 1);
        }
//bot
        if ($ele->facia->bottom_tile_height) {
            $part[] = array(15, $frmdata[15]['s'], $ele->facia->bottom_tile_type, $ele->facia->bottom_tile_split_width ? 4 : 3, 0, 0, $ele->facia->bottom_tile_height, $width - $ele->facia->bottom_tile_split_width, 0, 0, $fin . 'bt', 1);
        }
//botsplit
        if ($ele->facia->bottom_tile_split_width) {
            $part[] = array(15, $frmdata[15]['s'], $ele->facia->bottom_tile_split_type, 4, 0, 0, $ele->facia->bottom_tile_height, $ele->facia->bottom_tile_split_width, 0, 0, $fin . 'bs', 1);
            $part[] = array(37, 2, 0, 0, 0, 0, $ele->facia->bottom_tile_height, 0, 0, 0, '', 1);
        }
//Sk
        if ($ele->facia->skirting_height) {
            $part[] = array(14, $frmdata[14]['s'], 0, 0, 0, 0, $ele->facia->skirting_height, $width, 0, 0, $fin . 'sk', 1);
        }
    }

    public function addtables(&$part, $supreqd, $smatch, $bmatch, $direct, $qty) {
// start of tables
        $mainL = $mainlin = $sidelin = $sidesto = $backsto = $ltrr = $surr = $sqr_pst = $ftlr = $ftmr = $tips1 = $mesup = $mcsup = $rcsup = $resup = 0;

// $direct = 1; 
        $directopp = $direct == 1 ? 2 : 1;
        $fl = $this->front_length;
        $fd = $this->front_depth;
        $sl = $this->side_length;
        $sd = $this->side_depth;
        $bl = $this->back_length;
        $bd = $this->back_depth;
        $ftd = $fd;
        $mspolereq = $sidestouid = $backstouid = $sidediv1 = $sidediv2 = $backdiv1 = $backdiv2 = $msupuid = $rsupuid = $fmodred = $smodred = $modside = 0;
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
            $sidestouid = array_key_exists(38, $sidestoft) ? 38 : (array_key_exists(60, $sidestoft) ? 60 : (array_key_exists(67, $sidestoft) ? 67 : 0));

        if ($backsto)
            $backstouid = array_key_exists(38, $backstoft) ? 38 : (array_key_exists(60, $backstoft) ? 60 : (array_key_exists(67, $backstoft) ? 67 : 0));

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
                $part[] = array(66, 1, $this->table_top_type_id, $tips1, 0, 0, $ftlr, $ftd, 0, 0, 'tbl', $qty); //odd table addition in partlist
            }
        }
        #$cable_access = $this->cable_access_id;
        $cable_access = 1;
        $profile = $this->profile_id;
        $profile = 1;
        if ($mainL) //L table addition in PL
            $part[] = array(71, $cable_access, $this->table_top_type_id, $profile, $direct, ($fl > 2400 ? 1 : $this->tip_type_id), $fl - $ftlr, $sl, $ftd, $sd, 'tbl', $qty); //L table addition in partlist

        if ($mainlin)//Main Linear table addition in PL
            $part[] = array(72, $cable_access, $this->table_top_type_id, $profile, $direct, ($fl > 2400 ? 1 : $this->tip_type_id), $fl - $ftlr, $ftd, 0, 0, 'tbl', $qty); //linear table addition in partlist

        if ($mspolereq)//Pole addition in PL
            $part[] = array(40, 0, 0, 0, 0, 0, 725, 85, 200, 0, 'sup,b', $qty); //ms pole addition in partlist

        if ($sidelin) {//Side Linear table addition in PL
            $part[] = array(57, $cable_access, $this->table_top_type_id, 5, $directopp, 1, $sl, $sd, 0, 0, 'tbl', $qty); //side linear table addition in partlist
            $part[] = array(26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'tt flat', 2 * $qty);
        }

        if ($sidesto)//Side Storage addition in PL
            $part[] = array($sidestouid, $this->side_storage_fc_id, $this->side_storage_dbt_id, $direct, $this->side_storage_div_width, $this->side_storage_handle_type, 750, $sl, $sl + ($backsto ? $bd : 0), $sd, 'st,s', $qty); //side storage addition in partlist

        if ($backsto)//Back Storage addition in PL
            $part[] = array($backstouid, $this->back_storage_fc_id, $this->back_storage_dbt_id, $directopp, $this->back_storage_div_width, $this->back_storage_handle_type, 750, $bl, $bl + ($mainL ? $sd : 0), $bd, 'st,b', $qty); //back storage addition in partlist
//UNDERSTRUCTURE.
        if ($supreqd) {
            $msupuid = $supdata['fr']['uid'];
            $rsupuid = $supdata['si']['uid'];
            $mesup = 1;
            if ($mainL) {
                if ($smatch && !$bmatch) {
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
                        $modside = 0;
                    }
                } elseif ($smatch && $bmatch) {
                    if ($msupuid == 23 && $rsupuid == 23) { //Perform Support
                        $mcsup = 1;
                        $rcsup = 0;
                        $resup = 0;
                        $sqr_pst = 0;
                        $modside = 0;
                    } else { //GE/MS Support
                        $mcsup = 0;
                        $rcsup = 0;
                        $resup = 0;
                        $sqr_pst = 1;
                        $modside = 0;
                    }
                } else {
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
                }
            } else if ($sidelin) {
                if ($smatch && !$bmatch) {
                    if ($msupuid == 23 && $rsupuid == 23) { //Perform Support
                        $mcsup = 1;
                        $rcsup = 1;
                        $resup = 1;
                        $sqr_pst = 0;
                    } else { //GE/MS Support
                        $mcsup = 0;
                        $rcsup = 1;
                        $resup = 1;
                        $sqr_pst = 1;
                        $modside = 1;
                    }
                } elseif ($smatch && $bmatch) {
                    if ($msupuid == 23 && $rsupuid == 23) { //Perform Support
                        $mcsup = 1;
                        $rcsup = 1;
                        $resup = 1;
                        $sqr_pst = 0;
                        $modside = 0;
                    } else { //GE/MS Support
                        $mcsup = 0;
                        $rcsup = 0;
                        $resup = 0;
                        $sqr_pst = 1;
                        $modside = 0;
                    }
                } else {
                    if ($msupuid == 23 && $rsupuid == 23) { //Perform Support
                        $mcsup = 1;
                        $rcsup = 1;
                        $resup = 1;
                        $sqr_pst = 0;
                        $modside = 0;
                    } else { //GE/MS Support
                        $mcsup = 0;
                        $rcsup = 1;
                        $resup = 1;
                        $sqr_pst = 1;
                        $modside = 1;
                    }
                }
            } else {
                if ($smatch) {
                    if ($msupuid == 23 && $rsupuid == 23) { //Perform Support
                        $mcsup = 1;
                        $rcsup = 0;
                        $resup = 0;
                        $sqr_pst = 0;
                        $modside = 0;
                    } else { //GE/MS Support
                        $mcsup = 0;
                        $rcsup = 0;
                        $resup = 0;
                        $sqr_pst = 1;
                        $modside = 0;
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
            }
            //Brackets - 17" / 13" / 2"
            if (!$mcsup) {
                $part[] = array(35, 0, 0, 2, $directopp, 0, 0, 0, 0, 0, '2" back', $qty); //2" Bracket for Main Corner front
                $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf', 2 * $qty);
                if ($mainlin) {
                    $part[] = array(35, 0, 0, 2, $direct, 0, 0, 0, 0, 0, '2" back', $qty); //2" Bracket for Main Corner Inside
                    $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf', 2 * $qty);
                }
            }
            if (!$resup && ($sidelin || $mainL)) {
                $part[] = array(35, 0, 0, 2, $directopp, 0, 0, 0, 0, 0, '2" back', $qty); //2" Bracket for Back Corner
                $part[] = array(35, 0, 0, 2, $direct, 0, 0, 0, 0, 0, '2" back', $qty); //2" Bracket for Back Tip
                $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
            }
            if ($sidelin && !$rcsup && $msupuid != 23) {
                $part[] = array(22, 2, 0, 0, 0, 0, 0, 0, 0, 0, 'sup,b', $qty); //17" Bracket Side
                $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf', 6 * $qty);
            }
            if (!$modside && ($mainL || $sidelin) && $sl > 1200) {
                $part[] = array(35, 0, 0, 1, $direct, 0, 0, 0, 0, 0, '13" side', $qty); //13" Bracket Side
                $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
            }


// Gable ends
            if ($msupuid == 18 && $rsupuid == 18) {
                $part[] = array(18, 4, 1, 1, 0, 0, 710, $fd - 10, 0, 0, 'sup,f', $qty); //Main end support
                $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2 * $qty);
                $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
                if ($mcsup) {
                    $part[] = array(18, 4, 1, 1, 0, 0, 710, $fd - 10, 0, 0, 'sup,f', 1); //Main corner support
                    $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2 * $qty);
                    $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
                }
                if ($resup) {
                    $part[] = array(18, 4, 1, 1, 0, 0, 710, $sd - ($smatch ? 100 : 10), 0, 0, 'sup,s', $qty); //Return End Supports
                    $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2 * $qty);
                    $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
                    if ($smatch) {
                        $part[] = array(19, 0, 0, 1, $direct, 0, 0, 0, 0, 0, 'GE brac', $qty); //Return End Supports holder
                        $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
                    }
                }
                if ($rcsup) {
                    $part[] = array(18, 4, 1, 1, 0, 0, 710, 300, 0, 0, 'sup,s', $qty); //Return Corner Supports
                    $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2 * $qty);
                    $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
                    if ($smatch) {
                        $part[] = array(19, 0, 0, 1, $directopp, 0, 0, 0, 0, 0, 'GE brac', $qty); //Return Corner Supports holder
                        $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
                    }
                }
                $fmodred = 30 + (!$tips1 ? 50 : 0) + ($sqr_pst ? 150 : 30);
                if ($modside) {
                    $smodred = 30 + ($rcsup ? 30 : 150);
                }
            }
//Perform
            if ($msupuid == 23 && $rsupuid == 23) {
                $fr_leg_red = $supdata['fr']['leg_red'];
                $leg_h = $supdata['fr']['leg_h'];
                $si_leg_red = $supdata['si']['leg_red'];
                $part[] = array(23, $supdata['fr']['s'], 0, 11, $fd > 600 ? 12 : 11, $fd > 750 ? 3 : $directopp, $fd - $fr_leg_red, $leg_h, $leg_h, 0, 'sgf', $qty); //Main end support
                $spacercount['legs']['mesup']['dim'] = $fd - 10;
                $spacercount['legs']['mesup']['qty'] = $qty;
                if ($mcsup) {
                    $part[] = array(23, $supdata['fr']['s'], 0, 11, $fd > 600 ? 12 : 11, $fd > 600 ? 3 : $direct, $fd - $fr_leg_red, $leg_h, $leg_h, 0, 'sgf', $qty); //Main corner support
                    $part[] = array(24, 1, 2, 0, 0, 0, $fl - 160, 0, 0, 0, 'sup,f', $qty * ($fd > 600 ? 2 : 1)); // Front table beam
                    $spacercount['legs']['mcsup']['dim'] = $fd - 10;
                    $spacercount['legs']['mcsup']['qty'] = $qty;
                    $spacercount['beam']['f_beam']['dim'] = $fl - 160;
                    $spacercount['beam']['f_beam']['qty'] = $qty * ($fd > 750 ? 2 : 1);
                }
                if ($resup) {
                    $part[] = array(23, $supdata['si']['s'], 0, 11, 11, $directopp, $sd - $si_leg_red, $leg_h, $leg_h, 0, 'sgf', $qty); //Return End support
                    $spacercount['legs']['resup']['dim'] = $sd - 10;
                    $spacercount['legs']['resup']['qty'] = $qty;
                    $part[] = array(24, 1, 2, 0, 0, 0, $sl - ($rcsup ? 110 : 300), 0, 0, 0, 'sup,s', $qty * ($fd > 600 ? 2 : 1)); // Return table beam
                    $spacercount['beam']['r_beam']['dim'] = $sl - ($rcsup ? 110 : 300);
                    $spacercount['beam']['r_beam']['qty'] = $qty;
                    if ($rcsup) {
                        $part[] = array(23, $supdata['si']['s'], 0, 11, 11, $direct, $sd - $si_leg_red, $leg_h, $leg_h, 0, 'sgf', $qty); //Return Corner
                        $spacercount['legs']['rcsup']['dim'] = $sd - 150;
                        $spacercount['legs']['rcsup']['qty'] = $qty;
                    }
                }
                $fmodred = 50 + ($tips1 ? 50 : 0) + ($sqr_pst ? 150 : 50);
                if ($modside) {
                    $smodred = 55 + ($rcsup ? 55 : 300);
                }
                
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
                            if ($sk == 'r_beam'){
                                $beam_leg_joint = 1;
                                $part[] = array(31, 0, 0, 24, 0, 0, 0, 0, 0, 0, 'sgf,', $beam_leg_joint * $sv['qty']); // M6x50 Allen bolt leg to beam
                            }
                            $part[] = array(31, 0, 0, 14, 0, 0, 0, 0, 0, 0, 'sgf,', $beam_leg_joint * 2 * $sv['qty']); // M6x20 Allen bolt
                            $part[] = array(31, 0, 0, 24, 0, 0, 0, 0, 0, 0, 'sgf,', $beam_leg_joint * 2 * $sv['qty']); // M6x20 Allen bolt with Flange
                        }
                    }
                }
            }
//MS Legs
            if ($msupuid == 55) {
                $part[] = array(55, 0, 0, $supdata['fr']['s1'], $fd > 600 ? 2 : 1, 1, 0, 0, 0, 0, 'sup,f', $qty); //Main End Support
                if ($mcsup) {
                    $part[] = array(55, 0, 0, $supdata['fr']['s1'], $fd > 600 ? 2 : 1, 1, 0, 0, 0, 0, 'sup,f', $qty); //Main Corner Support
                }
                if ($rsupuid == 55) {
                    //return MS Legs
                    if ($resup) {
                        $part[] = array(55, 0, 0, $supdata['si']['s1'], $sd > 600 ? 2 : 1, 1, 0, 0, 0, 0, 'sup,s', $qty); //Return End Support
                    }
                    if ($rcsup) {
                        $part[] = array(55, 0, 0, $supdata['si']['s1'], $sd > 600 ? 2 : 1, 1, 0, 0, 0, 0, 'sup,s', $qty); //Return Corner Support
                    }
                    if ($modside) {
                        $smodred = 30 + ($rcsup ? 30 : 150);
                    }
                } else {
                    //return Gable Ends
                    if ($resup) {
                        $part[] = array(18, 4, 1, 1, 0, 0, 710, $sd - ($smatch ? 100 : 10), 0, 0, 'sup,s', $qty); //Return End Supports
                        $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2 * $qty);
                        $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
                        if ($smatch) {
                            $part[] = array(19, 0, 0, 1, $direct, 0, 0, 0, 0, 0, 'GE brac', $qty); //Return Corner Supports holder
                            $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
                        }
                    }
                    if ($rcsup) {
                        $part[] = array(18, 4, 1, 1, 0, 0, 710, 300, 0, 0, 'sup,s', $qty); //Return Corner Supports
                        $part[] = array(61, 0, 0, 0, 0, 0, 0, 0, 0, 0, '3/4" ends', 2 * $qty);
                        $part[] = array(31, 0, 0, 5, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
                        if ($smatch) {
                            $part[] = array(19, 0, 0, 1, $directopp, 0, 0, 0, 0, 0, 'GE brac', $qty); //Return Corner Supports holder
                            $part[] = array(31, 0, 0, 4, 0, 0, 0, 0, 0, 0, 'sf', 4 * $qty);
                        }
                        if ($modside) {
                            $smodred = 30 + ($rcsup ? 30 : 150);
                        }
                    }
                }
                $fmodred = 30 + ($tips1 ? 50 : 0) + ($sqr_pst ? 150 : 30);
            }

            //AL Square Post
            if ($sqr_pst) {
                $part[] = array(48, 1, 0, 0, 0, 0, 725, 0, 0, 0, 'ap', $qty);
            }
            //Front Modesty
            if ($this->front_modesty_height)
                $part[] = array(41, 1, $this->front_modesty_type, 0, 0, 0, $this->front_modesty_height, $fl - $ftmr - $fmodred, 0, 0, 'pm,f', $qty);

            if ($fl > 1199 && $msupuid != 23 && $rsupuid != 23) {
                //This is a support beam 
                $sup_beam_len = $fl - $ftmr - 55;
                $part[] = array(81, 0, 0, 0, 0, 0, $sup_beam_len, 0, 0, 0, 'sf', 1);
                //$part[] = array(31, 0, 0, 7, 0, 0, 0, 0, 0, 0, 'pm,f', 4);//No. 8 x 16 PH Screws
                //$part[] = array(31, 0, 0, 8, 0, 0, 0, 0, 0, 0, 'pm,f', floor($sup_beam_len/300));//No. 8 x 38 Screws
            }
            if ($modside)
                $part[] = array(41, 1, $this->side_modesty_type, 0, 0, 0, $this->side_modesty_height, $sl - $smodred, 0, 0, 'pm,s', $qty);

            if (array_key_exists(50, $fcarstr)) //front boxing raceway
                $part[] = array(50, $fcarstr[50]['s'], 0, 0, 0, 0, $fl - $ftmr - $fmodred - 50, 0, 0, 0, 'ch,f', $fcarstr[50]['q'] * $qty);

            if (array_key_exists(50, $scarstr)) //side boxing raceway
                $part[] = array(50, $scarstr[50]['s'], 0, 0, 0, 0, $sl - $smodred - 50, 0, 0, 0, 'ch,s', $scarstr[50]['q'] * $qty);

            if (array_key_exists(25, $fcarstr)) //front perform raceway
                $part[] = array(25, $fcarstr[25]['s'], 0, 1, $fcarstr[25]['s2'], $fcarstr[25]['s3'], $fl - $ftmr - $fmodred + 46, 0, 0, 0, 'ch,f', $qty);

            if (array_key_exists(25, $scarstr)) //side perform raceway
                $part[] = array(25, $scarstr[25]['s'], 0, 1, $scarstr[25]['s2'], $scarstr[25]['s3'], $sl - $smodred + 46, 0, 0, 0, 'ch,s', $qty);

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
                $part[] = array(45, $entry_sys, 0, $fcabent[45]['s1'] == 0 ? 3 : 1, 0, 0, 0, 0, 0, 0, 'ce,f', $qty);
            }

            if (array_key_exists(45, $scabent)){ //side cable entry
                if ($scabent[45]['s'] == 'cover|box'){
                    $entry_sys = $sup == 18 ? 1 : 3;
                } else {
                    $entry_sys = $scabent[45]['sys'];
                }
                $part[] = array(45, $entry_sys, 0, $scabent[45]['s1'] == 0 ? 3 : 1, 0, 0, 0, 0, 0, 0, 'ce,s', $qty);
            }
        }
        if (array_key_exists(49, $fcabstr)) //front cable access
            $part[] = array(49, $fcabstr[49]['s'], 0, 0, 0, 0, 0, 0, 0, 0, 'ca,f', $qty);

        if (array_key_exists(49, $scabstr)) //side cable access
            $part[] = array(49, $scabstr[49]['s'], 0, 0, 0, 0, 0, 0, 0, 0, 'ca,s', $qty);
        if (array_key_exists(50, $fcabstr)) //front cable tray
            $part[] = array(50, $fcabstr[50]['s'], 0, 0, 0, 0, $fcabstr[50]['d1'], 0, 0, 0, 'ch,f', $qty);

        if (array_key_exists(50, $scabstr)) //side cable tray
            $part[] = array(50, $scabstr[50]['s'], 0, 0, 0, 0, $scabstr[50]['d1'], 0, 0, 0, 'ch,s', $qty);
// end of tables
    }

}

?>