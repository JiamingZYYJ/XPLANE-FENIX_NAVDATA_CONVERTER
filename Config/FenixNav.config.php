<?php
//配置文件，别乱动！别乱动！别乱动！
trait Config{

    private function TerminalLegs(): array
    {
        return [
            "ID",
            "TerminalID",
            "Type",
            "Transition",
            "TrackCode",
            "WptID",
            "WptLat",
            "WptLon",
            "TurnDir",
            "NavID",
            "NavLat",
            "NavLon",
            "NavBear",
            "NavDist",
            "Course",
            "Distance",
            "Alt",
            "Vnav",
            "CenterID",
            "CeterLat",
            "CenterLon",
            "WptDescCode"
        ];
    }

    private function Terminals():array
    {
        return [
            "ID",
            "AirportID",
            "Proc",
            "ICAO",
            "FullName",
            "Name",
            "Rwy",
            "RwyID",
            "IlsID"
        ];
    }

    private function TurnProcInIndex($type)
    {
        if($type=="SID"){
            return 1;
        }elseif($type=="STAR"){
            return 2;
        }elseif($type=="APPCH"){
            return 3;
        }
    }

    private function WaypointsVal():array
    {
        return [
            "ID",
            "Ident",
            "Collocated",
            "Name",
            "Latitude",
            "Longtitude",
            "NavaidID"
        ];
    }

    private function TerminalLegsEx():array
    {
        return [
            "ID",
            "IsFlyOver",
            "SpeedLimit",
            "SpeedLimitDescription"
        ];
    }

    private function Alt($mark,$val,$option):string
    {
        if($mark=="+"){
            $fix="A";
        }elseif($mark=="-"){
            $fix="B";
        }elseif($mark=="H"){
            $fix="";
        }elseif(empty(str_replace(" ",'',$mark))){
            $fix="";
        }elseif($mark=="J"){
            $fix="";
        }
        $str=substr($option,3,1);
        if($str=="M"){
            $alt="MAP";
        }else{
            $alt=ltrim($val,0).$fix;
        }
        return str_replace(' ','',$alt);
    }

    private function FixPos($row):array
    {
        if(sizeof($row)==0){
            return [
                "",
                "",
                ""
            ];
        }else{
            return [
                $row['ID'],
                $row['Latitude'],
                $row['Longtitude']
            ];
        }
    }

    private function NavPos($extra_data): array
    {
        if(sizeof($extra_data)==0){
            return [
                "",
                ""
            ];
        }else{
            return [
                $extra_data['Latitude'],
                $extra_data['Longtitude']
            ];
        }
    }

    private function NavEquipData($a,$b,$c,$d,$e):array
    {
        $Navbear=intval(ltrim($a,'0'))/10;
        $NavDist=intval(ltrim($b,'0'))/10;
        $Course=intval(ltrim($c,'0'))/10;
        $Distance=intval(ltrim($d,'0'))/10;
        if($Navbear==0){
            $Navbear="";
        }
        if($NavDist==0){
            $NavDist="";
        }
        if($Course==0){
            $Course="";
        }
        if($Distance==0){
            $Distance="";
        }
        if(intval($e)>0){
            $VnavVal="";
        }else{
            $VnavVal=(float)number_format(abs(-300)/100,1);
        }

        return [
            $Navbear,
            $NavDist,
            $Course,
            $Distance,
            $VnavVal
        ];
    }

    private function Output_Path(): string
    {
        return '../output/';
    }

    private function Sqlite_Path():string
    {
        return '../defaultDB/nd.db3';
    }

}

trait ExcelUtils{
    public function getExcelCeilIndex($col, $row)
    {
        if($row > 0 && $col > 0)
        {
            $str = self::cols;
            $col_str = '';
            do {
                $col_tmp = $col % 26;
                $col = $col_tmp == 0 ? intval($col / 26) - 1 : intval($col / 26);
                $col_str = $str[$col_tmp] . $col_str;
            }while($col);
            return $col_str . $row;
        }
        return false;
    }
}

