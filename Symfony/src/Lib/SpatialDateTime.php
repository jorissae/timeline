<?php

namespace App\Lib;

class SpatialDateTime{

    private $id = null;


    //y/m/d
    public function __construct($date){
        if(strstr($date, '/')) {
            print_r(explode( '/', $date));
            $this->id = $this->calcIdDate(explode('/', $date));
        }else{
            $this->id = $date;
        }
    }

    public function addDays($days){
        $nbdays = base_convert($this->id,36,10);
        $this->id = base_convert($nbdays+= $days,10,36);
    }

    public function __toString(){
        $date = $this->calcDate($this->id);
        return $date[2].'/'.$date[1].'/'.$date[0];
    }

    public function isBis($annee) {
        $annee = (int)$annee;
        if($annee < 0) return false;
        if($annee === 0) return true;
        if( (is_int($annee/4) && !is_int($annee/100)) || is_int($annee/400)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function calcIdDate($date){
        $nbBis = 0;
        $y = $date[0];
        for($i=0; $i < $y; $i++){
            if($this->isBis($i)){
                $nbBis++;
            }
        }

        $lastYear = $y-1;
        $nbBis = floor(($lastYear /4 - floor($lastYear/100)) + floor($lastYear/400)) + 1;
        if($y < 0) $nbBis = 0;
        $nbday = $date[0] * 365 + $nbBis; //years

        if(!$this->isBis($y)){
            $months = [0,31,59,90,120,151,181,212,243,273,304,334,365];
        }else{
            $months = [0,31,60,91,121,152,182,213,244,274,305,335,366];
        }

        $nbday += $months[$date[1]-1];
        $nbday += $date[2];
        return base_convert($nbday + 14000000000 * 365,10,36);
    }

    public function opti2($nbDay, $debug = false){
        $y = floor($nbDay / 366);
        $nbBis = floor(($y /4 - floor($y/100)) + floor($y/400))+1;
        if($this->isBis($y)) $nbBis--;

        $d2 = $nbBis * 366 + ($y-$nbBis) * 365;
        $last = 0;
        //echo $d2 .'='. $nbBis.' * 366 + '.($y-$nbBis).' * 365<br/>';
        //echo $d2 .'<'.$nbDay .':';
        while ($d2 < $nbDay) {
            if ($this->isBis($y)) {
                //echo 'bis';
                $d2 += 366;
                $last = $d2 - 366;
            } else {
                //echo 'nbis';
                $d2 += 365;
                $last = $d2 - 365;
            }
            $y++;
        }
        /*if($d2 != $nbDay){
            $y--;
        }*/
        $y--;
        //echo $last;
        $nbDay -= $last;
        /*if($nbDay ==0){
            $nbDay = 1;
            if($last) $y++;
        }*/
        return [$y, $nbDay];

    }

    public function calcDate($idDate){

        $nbDay = base_convert($idDate, 36, 10);
        $nbDay -= 14000000000 * 365;
        if($nbDay >= 0) {
            $n = $nbDay;
            /*[$y1, $nbDay1] = $this->opti2($nbDay);
            [$y, $nbDay] =$this->nonOpti($nbDay);
            if($y1 != $y || $nbDay1 != $nbDay){
                echo '['.$n .'---->';
                echo $y1 .'/'. $nbDay1 .' is not '. $y .'/'. $nbDay;
                echo ']<br/>';
            }*/
            [$y, $nbDay] = $this->opti2($nbDay);
            //[$y, $nbDay] =$this->nonOpti($nbDay);
        }else{
            $y = floor($nbDay / 365);
            if(!($nbDay % 365)){
                $y--;
            }
            $nbDay = 365 + ($nbDay % 365);
        }
        if(!$this->isBis($y)){
            $months = [0,31,59,90,120,151,181,212,243,273,304,334,365];
        }else{
            $months = [0,31,60,91,121,152,182,213,244,274,305,335,366];
        }
        $m =1;
        for($i=0;$i<=12;$i++){
            if($nbDay > $months[$i] && $nbDay <= $months[$i+1]){
                $nbDay-=$months[$i];
                $m = $i+1;
                break;
            }
        }

        return [$y,$m,$nbDay];
    }


}