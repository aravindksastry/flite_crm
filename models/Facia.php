<?php

Yii::import('application.models._base.BaseFacia');

class Facia extends BaseFacia {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'addon2_height' => 'Addon2 Height',
            'addon2_type' => 'Addon2 Type',
            'addon1_height' => 'Addon1 Height',
            'addon1_type' => 'Addon1 Type',
            'top_tile_height' => 'Top Tile Height',
            'top_tile_type' => 'Top Tile Type',
            'top_tile_split_width' => 'Top Tile Split Width',
            'top_tile_split_type' => 'Top Tile Split Type',
            'middle_tile_height' => 'Middle Tile Height',
            'middle_tile_type' => 'Middle Tile Type',
            'middle_tile_split_width' => 'Middle Tile Split Width',
            'middle_tile_split_type' => 'Middle Tile Split Type',
            'raceway_above_table_top_height' => 'Raceway Above Table Top Height',
            'band_tile_height' => 'Band Tile Height',
            'band_tile_type' => 'Band Tile Type',
            'raceway_below_table_top_height' => 'Raceway Below Table Top Height',
            'bottom_tile_height' => 'Bottom Tile Height',
            'bottom_tile_type' => 'Bottom Tile Type',
            'bottom_tile_split_width' => 'Bottom Tile Split Width',
            'bottom_tile_split_type' => 'Bottom Tile Split Type',
            'skirting_height' => 'Skirting Height',
            'value' => 'Value',
            'total_ht' => 'Total Ht',
            'data' => 'Data',
        );
    }

    public function beforeSave() {
        $this->name = ($this->addon2_height > 0 ? 'ADD2:' . $this->addon2_height . ' ' . (GxHtml::encode(GxHtml::valueEx($this->addon2Type))) . ' | ' : null);
        $this->name .=($this->addon1_height > 0 ? 'ADD1:' . $this->addon1_height . ' ' . GxHtml::encode(GxHtml::valueEx($this->addon1Type)) . ' | ' : null);
        if ($this->top_tile_height > 0 && $this->top_tile_type > 0) {
            $this->name .= 'TOP:' . $this->top_tile_height . ' ' . GxHtml::encode(GxHtml::valueEx($this->topTileType));
            $this->name .= ($this->top_tile_split_width > 0 ? '-' . $this->top_tile_split_width . ' ' .
                            GxHtml::encode(GxHtml::valueEx($this->top_tile_split_width)) .
                            ' ' . GxHtml::encode(GxHtml::valueEx($this->top_tile_split_type)) . ' | ' : ' | ');
        }
        if ($this->middle_tile_height > 0 && $this->middle_tile_type > 0) {
            $this->name .= ('MID:' . $this->middle_tile_height . ' ' . GxHtml::encode(GxHtml::valueEx($this->middleTileType)));
            $this->name .= ($this->middle_tile_split_width !== null ? '-' . $this->middle_tile_split_width . ' ' .
                            GxHtml::encode(GxHtml::valueEx($this->middleTileSplitType)) . ' | ' : ' | ');
        }
        $this->name .=($this->raceway_above_table_top_height > 0 ? 'RWA:' . $this->raceway_above_table_top_height . ' | ' : null);

        $this->name .=($this->band_tile_height > 0 ? 'BAND:' . $this->band_tile_height . ' ' . GxHtml::encode(GxHtml::valueEx($this->bandTileType)) . ' | ' : null);

        $this->name .=($this->raceway_below_table_top_height > 0 ? 'RWB:' . $this->raceway_below_table_top_height . ' | ' : null);

        if ($this->bottom_tile_height > 0 && $this->bottom_tile_type > 0) {
            $this->name .='BOT:' . $this->bottom_tile_height . ' ' . GxHtml::encode(GxHtml::valueEx($this->bottomTileType));
            $this->name .=($this->bottom_tile_split_width !== null ? '-:' . $this->bottom_tile_split_width . ' ' .
                            GxHtml::encode(GxHtml::valueEx($this->bottomTileSplitType)) . ' | ' : ' | ');
        }

        $this->name .=($this->skirting_height > 0 ? 'SK:' . $this->skirting_height : null);

        $this->value = ($this->addon2_height > 0 ? $this->addon2_height : 0) . ':' . ($this->addon2_type > 0 ? $this->addon2_type : 0);
        $this->value .= ';' . ($this->addon1_height > 0 ? $this->addon1_height : 0) . ':' . ($this->addon1_type > 0 ? $this->addon1_type : 0);
        $this->value .= ';' . ($this->top_tile_height > 0 ? $this->top_tile_height : 0) . ':' . ($this->top_tile_type > 0 ? $this->top_tile_type : 0);
        $this->value .= ';' . ($this->middle_tile_height > 0 ? $this->middle_tile_height : 0) . ':' . ($this->middle_tile_type > 0 ? $this->middle_tile_type : 0);
        $this->value .= ';' . ($this->raceway_above_table_top_height > 0 ? $this->raceway_above_table_top_height : 0);
        $this->value .= ';' . ($this->band_tile_height > 0 ? $this->band_tile_height : 0) . ':' . ($this->band_tile_type > 0 ? $this->band_tile_type : 0);
        $this->value .= ';' . ($this->raceway_below_table_top_height > 0 ? $this->raceway_below_table_top_height : 0);
        $this->value .= ';' . ($this->bottom_tile_height > 0 ? $this->bottom_tile_height : 0) . ':' . ($this->bottom_tile_type > 0 ? $this->bottom_tile_type : 0);
        $this->value .= ';' . ($this->skirting_height > 0 ? $this->skirting_height : 0);
        $this->value .= ';' . ($this->top_tile_split_width > 0 ? $this->top_tile_split_width : 0) . ':' . ($this->top_tile_split_type > 0 ? $this->top_tile_split_type : 0);
        $this->value .= ';' . ($this->middle_tile_split_width > 0 ? $this->middle_tile_split_width : 0) . ':' . ($this->middle_tile_type > 0 ? $this->middle_tile_type : 0);
        $this->value .= ';' . ($this->bottom_tile_split_width > 0 ? $this->bottom_tile_split_width : 0) . ':' . ($this->bottom_tile_split_type > 0 ? $this->bottom_tile_split_type : 0);
        $this->total_ht = $this->addon2_height + $this->addon1_height + $this->top_tile_height + $this->middle_tile_height + $this->band_tile_height + $this->bottom_tile_height + $this->skirting_height + $this->raceway_above_table_top_height + $this->raceway_below_table_top_height;
        /*
          $sk = $this->skirting_height ? '1 Skirting' : '';
          $rw = ($this->raceway_above_table_top_height ? '1 BTT' : '') . ($this->raceway_above_table_top_height ? ' 1 ATT' : '');
          $band = ($this->band_tile_height ? $this->bandTileType->name : '');
          $btt = ($this->bottom_tile_height ? $this->bottomTileType->name : '');
          $att = ($this->middle_tile_height ? ($this->middleTileType->name . ($this->middle_tile_split_width ? 'split:' . $this->middleTileSplitType->name : '')) : '');
          $top = ($this->top_tile_height ? ($this->topTileType->name . ($this->top_tile_split_width ? 'split:' . $this->topTileSplitType->name : '')) : '');
          $add = ($this->addon1_height ? $this->addon1Type->name : '') . ($this->addon2_height ? ', ' . $this->addon2Type->name : '');
          $this->data = 'Tiles:' . ($btt ? ' BTT:' . $btt : '') . ($band ? ' Band:' . $band : '') . ($att ? ' ATT:' . $att : '') . ($top ? ' TOP:' . $top : '') . ($add ? ' Addon:' . $add : '') . (($sk || $rw ) ? ' Raceways:' : '') . ($sk ? ' ' . $sk : '') . ($rw ? ' ' . $rw : '');
         */
        return parent::beforeSave();
    }

    public function getSpec() {
        /* $rarr = array();
          if ($this->bottom_tile_height)
          $rarr['BOT'] = $this->bottomTileType->name;
          if ($this->band_tile_height)
          $rarr['Band'] = $this->bandTileType->name;
          if ($this->middle_tile_height)
          $rarr['ATT'] = $this->middleTileType->name . ($this->middle_tile_split_width ? ' Split:' . $this->middleTileSplitType->name : '');
          if ($this->top_tile_height)
          $rarr['TOP'] = $this->topTileType->name . ($this->top_tile_split_width ? ' Split:' . $this->topTileSplitType->name : '');
          if ($this->addon1_height)
          $rarr['Addon'] = $this->addon1Type->name . ($this->addon2_height ? ', ' . $this->addon2Type->name : '');
          if ($this->skirting_height || $this->raceway_above_table_top_height || $this->raceway_above_table_top_height)
          $rarr['Wire Management'] = ($this->skirting_height ? '1 Skirting' : '') .
          ($this->raceway_above_table_top_height ? ' 1 BTT' : '') . ($this->raceway_above_table_top_height ? ' 1 ATT' : '');
          return array('Tiles' => $rarr); */

        return ' Tiles :' . ($this->bottom_tile_height ? ' BTT-' . $this->bottomTileType->name : '') .
                ($this->band_tile_height ? ', Band-' . $this->bandTileType->name : '') .
                ($this->middle_tile_height ? (', ATT-' . $this->middleTileType->name . ($this->middle_tile_split_width ? ' split:' . $this->middleTileSplitType->name : '')) : '') .
                ($this->top_tile_height ? (', TOP-' . $this->topTileType->name . ($this->top_tile_split_width ? ' split:' . $this->topTileSplitType->name : '')) : '') .
                ($this->addon1_height ? ', Addon-' . $this->addon1Type->name : '') . ($this->addon2_height ? ', ' . $this->addon2Type->name : '') .
                (($this->skirting_height > 0 || $this->raceway_above_table_top_height > 0 || $this->raceway_above_table_top_height > 0 ) ?
                        (' (Raceways:' . ($this->skirting_height ? '1 Skirting' : '') . ($this->raceway_below_table_top_height ? ', 1 BTT' : '') .
                        ($this->raceway_above_table_top_height ? ', 1 ATT' : '')) . ')' : '');
    }

}

?>