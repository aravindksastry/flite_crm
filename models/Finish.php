<?php

Yii::import('application.models._base.BaseFinish');

class Finish extends BaseFinish {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'finish_type_id' => 'Finish Type',
			'finish1_id' => 'Finish1',
			'finish2_id' => 'Finish2',
			'finish3_id' => 'Finish3',
			'finish4_id' => 'Finish4',
			'finish5_id' => 'Finish5',
			'finish6_id' => 'Finish6',
			'finish7_id' => 'Finish7',
			'finish8_id' => 'Finish8',
			'finish9_id' => 'Finish9',
			'finish10_id' => 'Finish10',
			'finish11_id' => 'Finish11',
			'finish12_id' => 'Finish12',
			'project_id' => 'Project',
			'display_color' => 'Display Color',
			'data' => 'Data',
			'finish1_vendor_id' => 'Finish1 Vendor',
			'finish2_vendor_id' => 'Finish2 Vendor',
			'finish3_vendor_id' => 'Finish3 Vendor',
			'finish4_vendor_id' => 'Finish4 Vendor',
			'finish5_vendor_id' => 'Finish5 Vendor',
			'finish6_vendor_id' => 'Finish6 Vendor',
			'finish7_vendor_id' => 'Finish7 Vendor',
			'finish8_vendor_id' => 'Finish8 Vendor',
			'finish9_vendor_id' => 'Finish9 Vendor',
			'finish10_vendor_id' => 'Finish10 Vendor',
			'finish11_vendor_id' => 'Finish11 Vendor',
			'finish12_vendor_id' => 'Finish12 Vendor',
			'finish1_series_id' => 'Finish1 Series',
			'finish2_series_id' => 'Finish2 Series',
			'finish3_series_id' => 'Finish3 Series',
			'finish4_series_id' => 'Finish4 Series',
			'finish5_series_id' => 'Finish5 Series',
			'finish6_series_id' => 'Finish6 Series',
			'finish7_series_id' => 'Finish7 Series',
			'finish8_series_id' => 'Finish8 Series',
			'finish9_series_id' => 'Finish9 Series',
			'finish10_series_id' => 'Finish10 Series',
			'finish11_series_id' => 'Finish11 Series',
			'finish12_series_id' => 'Finish12 Series',
		);
	}

	public function beforeSave() {
            if (strlen($this->finishType->manage) > 0) {
                    $fmt = explode(',', $this->finishType->manage);
                    $fmt[0] = str_replace('?', '%s', $fmt[0]);
                    for ($cnt = 1; $cnt < count($fmt); $cnt++) {
                            $str = 'finish' . $fmt[$cnt];
                            $fmt[$cnt] = $this->$str->name;
                    }
                    while ($cnt < 13) {
                            $fmt[] = '';
                            $cnt++;
                    }
                    $this->name = sprintf($fmt[0], $fmt[1], $fmt[2], $fmt[3], $fmt[4], $fmt[5], $fmt[6], $fmt[7], $fmt[8], $fmt[9], $fmt[10], $fmt[11], $fmt[12]);
            }
            return parent::beforeSave();
	}

	public function getFinID() {
            $rstr = ($this->finish_type_id && $this->finish_type_id > 1 ? $this->finish_type_id : '0');
            $cnt = 1;
            while ($cnt < 13) {
                    $str = 'finish' . $cnt . '_id';
                    $rstr .= '-' . ($this->$str && $this->$str > 1 ? $this->$str : '0');
                    $cnt++;
            }
            return $rstr;
	}

}

?>