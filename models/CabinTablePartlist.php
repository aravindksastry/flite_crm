<?php

Yii::import('application.models._base.BaseCabinTablePartlist');

class CabinTablePartlist extends BaseCabinTablePartlist {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'region_id' => 'Region',
            'custom_name' => 'Custom Name',
            'units_per_cluster' => 'Units Per Cluster',
            'total_qty' => 'Table Set Total Qty',
            'segment1_qty' => 'Segment1 Qty',
            'segment2_qty' => 'Segment2 Qty',
            'segment3_qty' => 'Segment3 Qty',
            'segment4_qty' => 'Segment4 Qty',
            'segment5_qty' => 'Segment5 Qty',
            'segment6_qty' => 'Segment6 Qty',
            'segment7_qty' => 'Segment7 Qty',
            'segment8_qty' => 'Segment8 Qty',
            'segment9_qty' => 'Segment9 Qty',
            'segment10_qty' => 'Segment10 Qty',
            'include_LH_table' => 'Include Lh Table',
            'include_RH_table' => 'Include Rh Table',
            'data' => 'Data',
        );
    }

    public function beforeDelete() {
        PartModification::model()->deleteAll('clusterset_id=' . $this->id . ' and region_id=' . $this->region_id);
        return (parent::beforeDelete());
    }

    /** @param CabinTable $cab	 */
    public function getParts($cab) {
        if ($this->include_LH_table || $this->include_RH_table)
            return $cab->getParts($this->include_LH_table ? 1 : 2, $this->units_per_cluster);
        else
            return array();
    }

    public function SegQty() {
        return array(
            $this->segment1_qty ? $this->segment1_qty : 0,
            $this->segment2_qty ? $this->segment2_qty : 0,
            $this->segment3_qty ? $this->segment3_qty : 0,
            $this->segment4_qty ? $this->segment4_qty : 0,
            $this->segment5_qty ? $this->segment5_qty : 0,
            $this->segment6_qty ? $this->segment6_qty : 0,
            $this->segment7_qty ? $this->segment7_qty : 0,
            $this->segment8_qty ? $this->segment8_qty : 0,
            $this->segment9_qty ? $this->segment9_qty : 0,
            $this->segment10_qty ? $this->segment10_qty : 0,
        );
    }

    public function TotQty() {
        return ($this->total_qty ? $this->total_qty : 0);
    }

}

?>