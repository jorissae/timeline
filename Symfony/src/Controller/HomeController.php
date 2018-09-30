<?php

namespace App\Controller;

use App\Lib\SpatialDateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/json", name="json")
     */
    public function apiJson()
    {
        $date = '0/1/1';
        $sdt = new SpatialDateTime($date);
        $dt = \DateTime::createFromFormat('Y/m/d', $date);
        echo $sdt.'<br/>';
        for($i = 0; $i < 1011721; $i++){
            $sdt->addDays(1);
            $dt->add(new \DateInterval('P1D'));
            $dtstring = $dt->format('j/n/') . (int)$dt->format('Y');
            $sdtstring = (string)$sdt;
            if($sdtstring != $dtstring){
                echo $i.':'. $sdtstring .' not is ' . $dtstring;
            }
        }
        die();
        //die('ok');
        $this->test([365,366,367,1829,1830,1831,1096,2193]);
        //die();
        $s = microtime(true);
        $startdate = [-10,1,1];
        $idDate = $this->calcIdDate($startdate);
        //die();

        $date = $this->calcDate($idDate);
        print_r($date);
        $nb = base_convert($idDate,36,10);
        echo '<hr/>';
        $dt = \DateTime::createFromFormat('Y/m/d', implode('/',$startdate));
        if($dt) {
            $dtstring = $dt->format('j/n/') . (int)$dt->format('Y');
        }else{
            $dtstring = 'not supported';
        }
        echo $idDate .'--->'.$date[2].'/'.$date[1].'/'.$date[0].' | '. $dtstring;
        echo '<hr/>';
        $max = 500000;
        $min = 1;
        for($i=$min; $i <=$max; $i++){
            $idDate = base_convert($nb+$i,10,36);
            $date = $this->calcDate($idDate);
            if($dt) {
                $dt->add(new \DateInterval('P1D'));
                $dtstring = $dt->format('j/n/') . (int)$dt->format('Y');
            }else{
                $dt = \DateTime::createFromFormat('Y/m/d', implode('/',$date));
                $dtstring = 'not supported';
            }
            $dstring = $date[2].'/'.$date[1].'/'.$date[0];
            if($i === $min){
                echo 'START: '.$idDate . '--->' . $dstring . ' | ' . $dtstring;
                echo '<br/>';
                echo '<br/>';
            }
            if($dtstring != $dstring) {
                echo 'ERR: '.$idDate . '--->' . $dstring . ' | ' . $dtstring.' ----'.$this->calcIdDate($date);
                echo '<br/>';
            }else{
                //echo 'OK: '.$idDate . '--->' . $dstring . ' | ' . $dtstring;
                //echo '<br/>';
            }
            if($i >= $max){
                echo 'END: '.$idDate . '--->' . $dstring . ' | ' . $dtstring;
                echo '<br/>';
                echo '<br/>';
            }
        }
        echo (microtime(true) - $s) * 1;
        //$date = $this->calcDate($idDate);
        //print_r($date);

        die();
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

    public function nonOpti($nbDay, $debug = false){
        $i=0;
        $nbDayForYear = 0;
        $last = 0;
        while ($nbDayForYear < $nbDay) {

            //$y++;
            //echo $y.':';
            if ($this->isBis($i)) {
                //echo 'B';
                $nbDayForYear += 366;
                $last = $nbDayForYear - 366;
            } else {
                //echo 'N';
                $nbDayForYear += 365;
                $last = $nbDayForYear - 365;
            }
            //echo '.';
            $i++;

        }
        $y = $i - 1;
        //$y -= 1;
        $nbDay -= $last;
        return [$y,$nbDay];
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

    public function opti($nbDay, $debug = false){
        $y = floor($nbDay / 366);
        $nbBis = floor(($y /4 - floor($y/100)) + floor($y/400))+1;
        $nbDayForYear = ($y+1) * 365 + $nbBis;
        if($this->isBis($y)){
            $last = $nbDayForYear - 366;
        }else{
            $last = $nbDayForYear - 365;
        }
        $nbDay -= $last;
        return [$y, $nbDay];
    }

    public function test($nbdays){
        foreach($nbdays as $nbday){
            echo '<hr/>';
            echo '<h3>Test value '. $nbday .'</h3>';
            echo 'TEST nonOpti (reference)<br/>';
            print_r($this->nonOpti($nbday,true));
            echo '<br/>TEST Opti<br/>';
            print_r($this->opti($nbday, true));
            echo '<br/>TEST Opti2<br/>';
            print_r($this->opti2($nbday, true));
        }
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
