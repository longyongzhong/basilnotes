<?php

class Random{
    public function getrdnum($num){
        if ($num==1){
            return "a";
        }
        if ($num==2){
            return "b";
        }
        if ($num==3){
            return "c";
        }
        if ($num==4){
            return "d";
        }
        if ($num==5){
            return "e";
        }
        if ($num==6){
            return "f";
        }
        if ($num==7){
            return "g";
        }
        if ($num==8){
            return "h";
        }
        if ($num==9){
            return "i";
        }
        if ($num==10){
            return "j";
        }
        if ($num==11){
            return "k";
        }
        if ($num==12){
            return "l";
        }
        if ($num==13){
            return "m";
        }
        if ($num==14){
            return "n";
        }
        if ($num==15){
            return "o";
        }
        if ($num==16){
            return "p";
        }
        if ($num==17){
            return "q";
        }
        if ($num==18){
            return "r";
        }
        if ($num==19){
            return "s";
        }
        if ($num==20){
            return "t";
        }
        if ($num==21){
            return "u";
        }
        if ($num==22){
            return "v";
        }
        if ($num==23){
            return "w";
        }
        if ($num==24){
            return "x";
        }
        if ($num==25){
            return "y";
        }
        if ($num==26){
            return "z";
        }
    }
    public function getrdcode($amount){
        $i = 1;
        $randomcode = "";
        while ($i<=$amount){
            $random  = rand(1,26);
            $randomcode.=$this->getrdnum($random);
            $i++;
        }
        return $randomcode;
    }
}