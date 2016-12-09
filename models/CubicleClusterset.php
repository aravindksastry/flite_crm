<?php

Yii::import('application.models._base.BaseCubicleClusterset');

class CubicleClusterset extends BaseCubicleClusterset {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'region_id' => 'Region',
            'units_per_cluster' => 'Units Per Cluster',
            'total_qty' => 'Total Qty',
            'image' => 'Image',
            'cubicle_cluster_id' => 'Cubicle Cluster',
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
            'data' => 'Data',
        );
    }

    public function beforeDelete() {
        PartModification::model()->deleteAll('clusterset_id=' . $this->id . ' and region_id=' . $this->region_id);
        return (parent::beforeDelete());
    }

    /** @param Cubicle $cub	 */
    public function getParts($cub) {
        $pts = array();
        foreach ($this->cubicleCluster->cubicleSpines as $val) {
            $pts[$val->name0->id][] = array(
                '0' => array('w' => $val->up1_width, 'e1' => $val->upF1E1, 'e2' => $val->upF1E2),
                '1' => array('w' => $val->up2_width, 'e1' => $val->upF2E1, 'e2' => $val->upF2E2),
                '2' => array('w' => $val->up3_width, 'e1' => $val->upF3E1, 'e2' => $val->upF3E2),
                '3' => array('w' => $val->up4_width, 'e1' => $val->upF4E1, 'e2' => $val->upF4E2),
                '4' => array('w' => $val->up5_width, 'e1' => $val->upF5E1, 'e2' => $val->upF5E2),
                '5' => array('w' => $val->up6_width, 'e1' => $val->upF6E1, 'e2' => $val->upF6E2),
                'ex' => $val->upward_extend_to_post,
            );
            $pts[$val->name0->id][] = array(
                '0' => array('w' => $val->right1_width, 'e1' => $val->rightF1E1, 'e2' => $val->rightF1E2),
                '1' => array('w' => $val->right2_width, 'e1' => $val->rightF2E1, 'e2' => $val->rightF2E2),
                '2' => array('w' => $val->right3_width, 'e1' => $val->rightF3E1, 'e2' => $val->rightF3E2),
                '3' => array('w' => $val->right4_width, 'e1' => $val->rightF4E1, 'e2' => $val->rightF4E2),
                '4' => array('w' => $val->right5_width, 'e1' => $val->rightF5E1, 'e2' => $val->rightF5E2),
                '5' => array('w' => $val->right6_width, 'e1' => $val->rightF6E1, 'e2' => $val->rightF6E2),
                'ex' => $val->rightward_extend_to_post,
            );
            $pts[$val->name0->id][] = array(
                '0' => array('w' => $val->down_f1_width, 'e1' => $val->downF1E1, 'e2' => $val->downF1E2),
                '1' => array('w' => $val->down_f2_width, 'e1' => $val->downF2E1, 'e2' => $val->downF2E2),
                '2' => array('w' => $val->down_f3_width, 'e1' => $val->downF3E1, 'e2' => $val->downF3E2),
                '3' => array('w' => $val->down_f4_width, 'e1' => $val->downF4E1, 'e2' => $val->downF4E2),
                '4' => array('w' => $val->down_f5_width, 'e1' => $val->downF5E1, 'e2' => $val->downF5E2),
                '5' => array('w' => $val->down_f6_width, 'e1' => $val->downF6E1, 'e2' => $val->downF6E2),
                'ex' => $val->downward_extend_to_post,
            );
            $pts[$val->name0->id][] = array(
                '0' => array('w' => $val->left_f1_width, 'e1' => $val->leftF1E1, 'e2' => $val->leftF1E2),
                '1' => array('w' => $val->left_f2_width, 'e1' => $val->leftF2E1, 'e2' => $val->leftF2E2),
                '2' => array('w' => $val->left_f3_width, 'e1' => $val->leftF3E1, 'e2' => $val->leftF3E2),
                '3' => array('w' => $val->left_f4_width, 'e1' => $val->leftF4E1, 'e2' => $val->leftF4E2),
                '4' => array('w' => $val->left_f5_width, 'e1' => $val->leftF5E1, 'e2' => $val->leftF5E2),
                '5' => array('w' => $val->left_f6_width, 'e1' => $val->leftF6E1, 'e2' => $val->leftF6E2),
                'ex' => $val->leftward_extend_to_post,
            );
        }
        return $cub->getParts($pts, $this->cubicleCluster->cubicleTables, $this->units_per_cluster);
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