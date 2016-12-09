<?php

Yii::import('application.models._base.BaseItem');

class Item extends BaseItem {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Item Name',
            'type_id' => 'Item Type',
            'project_id' => 'Project',
            'tender_specs' => 'Tender Specs',
            'quoted_option' => 'Quoted Option',
            'exclude_item' => 'Exclude Item',
            'tender_qty' => 'Tender Qty',
            'layout_qty' => 'Layout Qty',
            'PO_qty' => 'Po Qty',
            'offered_unit_price' => 'Offered Unit Price',
            'discount' => 'Discount',
            'remark' => 'Remark',
            'data_other_checks_mktg' => 'Data Other Checks Mktg',
            'data_other_checks_design' => 'Data Other Checks Design',
            'quote_value' => 'Quote Value',
            'quote_date' => 'Quote Date',
            'revision' => 'Revision',
            'PO_matching_offer' => 'Po Matching Offer',
            'quote_spec' => 'Quote Spec',
            'data' => 'Data',
        );
    }

    public function afterSave() {
        if ($this->isNewRecord) {
            foreach (ApprovalType::model()->findAll("item='1'") as $val) {
                $ia = new ItemApproval;
                $ia->name = $val->id;
                $ia->item_id = $this->id;
                $ia->save();
            }
            foreach (ItemDocType::model()->findAll() as $val) {
                $id = new ItemDoc;
                $id->name = $val->id;
                $id->item_id = $this->id;
                $id->save();
            }
            foreach (SchType::model()->findAll() as $val) {
                $is = new ItemSchedule;
                $is->name = $val->id;
                $is->item_id = $this->id;
                $is->save();
            }
            //Region Creation start
            $rgn = new Region;
            $rgn->name = 'Region 1';
            $rgn->item_id = $this->id;
            $val = $this->project->layouts;
            $rgn->layout1_id = $val[0]->id;
            $rgn->layout2_id = $val[0]->id;
            $rgn->layout3_id = $val[0]->id;
            $rgn->layout4_id = $val[0]->id;
            $rgn->layout5_id = $val[0]->id;
            $rgn->layout6_id = $val[0]->id;
            $rgn->layout7_id = $val[0]->id;
            $rgn->layout8_id = $val[0]->id;
            $rgn->layout9_id = $val[0]->id;
            $rgn->layout10_id = $val[0]->id;
            $rgn->save();
            //Region Creation end

            

            /* if (strpos($this->name, ':clone') === FALSE) {
              $nobj = new $this->type->model_class;
              $nobj->item_id = $this->id;
              $nobj->name = 'option1';
              $nobj->save();
              } */
        }
        return (parent::afterSave());
    }

    public function beforeSave() {
        if (!$this->quote_date) {
            $this->quote_date = date('Y-m-d H:i:s');
        }
        if (strlen($this->data) == 1) {
            $this->data = '0' . $this->data;
        }
        if ($this->isNewRecord) {
            $this->quoted_option = null;
        }
        return parent::beforeSave();
    }

    public function beforeDelete() {
        foreach (ItemApproval::model()->findAll('item_id=' . $this->id) as $ra)
            $ra->delete();
        foreach (ItemDoc::model()->findAll('item_id=' . $this->id) as $rd) {
            $p = Yii::app()->getBasePath() . '/data/I' . $rd->id;
            if (file_exists($p))
                unlink($p);
            $rd->delete();
        }
        foreach (ItemSchedule::model()->findAll('item_id=' . $this->id) as $rs)
            $rs->delete();

        foreach (Region::model()->findAll('item_id=' . $this->id) as $rr)
            $rr->delete();

        return (parent::beforeDelete());
    }

    public function getParts($quote) {
        $parts = array();
        $scount = array();
        $tq = 0;
        foreach ($this->regions as $rgn) {
            if ($quote === 'qq') {
                $parts = array_merge($parts, $rgn->getParts($quote, false));
                $tq += $parts['tq'];
                unset($parts['tq']);
                continue;
            }
            $parts[$rgn->name] = $rgn->getParts($quote, false);
            if (isset($parts[$rgn->name]['debug']))
                return $parts[$rgn->name];

            if (isset($parts[$rgn->name][0]))
                $scount[$rgn->name] = count($parts[$rgn->name][0]) - 6;
            else
                unset($parts[$rgn->name]);
        }
        if (!count($parts))
            return $parts;
        if ($quote === 'qq')
            return Region::model()->consolidatePL($parts, true, false, $tq, 0);
        if (count($parts) == 1)
            return current($parts);
        return $this->consolidateTPL($parts, $scount);
        /* $pl = array();
          $lp = 0;
          $sum = array_sum($scount);
          foreach ($parts as $k => $p) {
          $rp = $sum - $lp - $scount[$k];
          foreach ($p as $v) {
          $pl[] = $lp ? ( $rp ? array_merge(array_slice($v, 0, 5), array_fill(0, $lp, ''), array_slice($v, 5, $scount[$k]), array_fill(0, $rp, '')) :
          array_merge(array_slice($v, 0, 5), array_fill(0, $lp, ''), array_slice($v, 5, $scount[$k]))) :
          ($rp ? array_merge(array_slice($v, 0, 5), array_slice($v, 5, $scount[$k]), array_fill(0, $rp, '')) :
          array_merge(array_slice($v, 0, 5), array_slice($v, 5, $scount[$k])));
          }
          $lp += $scount[$k];
          }
          $pl = $this->consolidateTPL($pl);
          $pl['item'] = $scount;
          return $pl; */
    }

    public function consolidateTPL($parts, $scount) {
        $pl = array();
        $lp = 0;
        $sum = array_sum($scount);
        foreach ($parts as $k => $p) {
            $rp = $sum - $lp - $scount[$k];
            foreach ($p as $v) {
                $pl[] = $lp ? ( $rp ? array_merge(array_slice($v, 0, 5), array_fill(0, $lp, ''), array_slice($v, 5, $scount[$k]), array_fill(0, $rp, '')) :
                                array_merge(array_slice($v, 0, 5), array_fill(0, $lp, ''), array_slice($v, 5, $scount[$k]))) :
                        ($rp ? array_merge(array_slice($v, 0, 5), array_slice($v, 5, $scount[$k]), array_fill(0, $rp, '')) :
                                array_merge(array_slice($v, 0, 5), array_slice($v, 5, $scount[$k])));
            }
            $lp += $scount[$k];
        }
        asort($pl);
        $cmp = array(0, 0, 0, 0);
        $pl[] = $cmp;
        $pcount = count($pl);
        $mseg = count($pl[0]) - 5;
        $parts = array();
        foreach ($pl as $v) {
            $pcount--;
            if (array_slice($cmp, 0, 5) == array_slice($v, 0, 5)) {
                $cnt = 5;
                while ($cnt < 5 + $mseg && (!$v[$cnt] || !$cmp[$cnt])) {
                    $cmp[$cnt]+= $v[$cnt];
                    $cnt++;
                }
                if ($pcount)
                    continue;
            }
            if ($cmp[0]) {
                $cmp[] = array_sum(array_slice($cmp, 5, $mseg));
                if (end($cmp))
                    $parts[] = $cmp;
            }
            $cmp = $v;
        }
        $parts['item'] = $scount;
        return $parts;
    }

    public function getHeader() {
        $tmp = $this->regions;
        if (!count($tmp))
            return null;
        if (count($tmp) == 1)
            $ret = $this->getRegionHeader($tmp[0]->name);
        else {
            $ret = $this->getRegionHeader();
            foreach ($tmp as $rgn)
                $ret['Region'] .= ' | ' . $rgn->name;
        }
        return $ret;
    }

    public function getRegionHeader($name = null) {
        $selmdl = (new $this->type->model_class);
        $anymdl = $selmdl->findByPk($this->quoted_option);
        return array(
            'Project' => $this->project->name,
            'Region' => $name ? $name : '',
            'Item' => $this->name,
            'Option' => ($anymdl ? $anymdl->name : 'Not Defined'),
            'Revision' => 1,
            'Client' => ($this->project->enquiryTo ? $this->project->enquiryTo->name : '?'),
            'Sales Person' => ($this->project->salesPerson ? $this->project->salesPerson->name : '?'),
            'Designer' => ($this->project->designer ? $this->project->designer->name : '?'),
            'Coordinator' => ($this->project->coordinator ? $this->project->coordinator->name : '?'),
        );
    }

}

?>