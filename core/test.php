<?php
$e=explode(',','SID:020,5,CBS19D,RW27,YJ404,ZY,P,C,E   ,L,010,DF, , , , , ,      ,    ,    ,    ,    ,,+,07500,     ,     , ,   ,    ,   , , , , , , , , ');
if($e[23]=="+"){
    $fix="A";
}elseif($e[23]=="-"){
    $fix="B";
}elseif($e[23]=="H"){
    $fix="";
}elseif(empty(str_replace(" ",'',$e[23]))){
    $fix="";
}elseif($e[23]=="J"){
    $fix="";
}

$str=substr($e[9],3,1);
if($str=="M"){
    $alt="MAP";
}else{
    $alt=ltrim($e[24],0).$fix;
}

var_dump($alt);

/**
SID:020,5,CBS19D,RW27,YJ404,ZY,P,C,E   ,L,010,DF, , , , , ,      ,    ,    ,    ,    ,,+,07500,     ,     , ,   ,    ,   , , , , , , , , ;
SID:020,5,CBS18D,RW27,YNJ,ZY,D, ,V   ,L,010,DF, , , , , ,      ,    ,    ,    ,    ,,+,06500,     ,     , ,   ,    ,   , , , , , , , , ;
SID:030,5,CBS18D,RW27,P08,ZY,E,A,E   ,L,010,DF, , , , , ,      ,    ,    ,    ,    ,,+,06500,     ,     , ,   ,    ,   , , , , , , , , ;
SID:020,5,CBS17D,RW27,P08,ZY,E,A,E   ,L,010,DF, , , , , ,      ,    ,    ,    ,    ,,+,06500,     ,     , ,   ,    ,   , , , , , , , , ;

 */