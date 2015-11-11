<?php

namespace app\components;
use yii\base\Component;

class Ndate extends Component
{
    private function getMonth($m = 0) {
        $arr = array(
            "",
            "มกราคม",
            "กุมภาพันธ์",
            "มีนาคม",
            "เมษายน",
            "พฤษภาคม",
            "มิถุนายน",
            "กรกฏาคม",
            "สิงหาคม",
            "กันยายน",
            "ตุลาคม",
            "พฤศจิกายน",
            "ธันวาคม"
        );
        return $arr[$m];
    }

    public function getShortMonth($m = 0) {
        $arr = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        return $arr[$m];
    }

    public function getNowThai() {
        return date("d/m/") . (date("Y") + 543);
    }

    public function getThaiYearOption($start = null, $end = null) {

        $arr = array();
        $y = date("Y") + 543;
        for ($i = ($y - $start); $i <= ($y + $end); $i++) {
            $arr[$i] = $i;
        }
        return $arr;
    }

    public function convertMysqlToThaiDate($date, $time = true) {
        $arr = explode(" ", $date);
        $arrDate = explode("-", $arr[0]);
        $y = $arrDate[0] + 543;
        $m = $arrDate[1];
        $d = $arrDate[2];
        if ($time) {
            $dDate = "$d/$m/$y $arr[1]";
        } else {
            $dDate = "$d/$m/$y";
        }

        return $dDate;
    }

    public function getThaiShortNow() {
        $y = date('Y') + 543;
        $m = $this->getShortMonth(date('n'));
        $d = date('j');

        return "$d $m $y";
    }
    
    public function getThaiLongNow() {
        $y = date('Y') + 543;
        $m = $this->getMonth(date('n'));
        $d = date('j');

        return "$d $m $y";
    }

    public function getThaiShortDate($date) {
        if ($date != '') {
            $arr = explode(" ", $date);
            $arrDate = explode("-", $arr[0]);
            $y = $arrDate[0] + 543;
            $mn = (int) $arrDate[1];
            $m = $this->getShortMonth($mn);
            $d = $arrDate[2];
            $dDate = "$d $m $y";
            if($d=='0') $dDate = '';

            return $dDate;
        } else {
            return '';
        }
    }
    
    public function getThaiLongDate($date) {
        if ($date != '') {
            $arr = explode(" ", $date);
            $arrDate = explode("-", $arr[0]);
            $y = $arrDate[0] + 543;
            $mn = (int) $arrDate[1];
            $m = $this->getMonth($mn);
            $d = $arrDate[2];
            $dDate = "$d $m $y";
            if($d=='0') $dDate = '';

            return $dDate;
        } else {
            return '';
        }
    }
}

?>