<?php
require_once '../PHPExcel/PHPExcel.class.php';
require_once 'SQLiteUtils.php';
require_once '../Config/FenixNav.config.php';
header("Content-Type: text/html;charset=utf-8");

//这里的代码不要动！不要动！不要动！有报错私信6189，不要随便改！！！不然就跑不动了！！！

class Transfers extends SQLiteUtils{

    use Config;
    use ExcelUtils;

    public $source;
    //public $test;
    public $option;//调试模式别乱动
    public $airport;
    public $insertPoint;
    public $waypoint;
    public $user;

    public static $PHPExcel;
    public static $airportID;
    public static $temp_terminals;
    public static $temp_legs;

    const cols="ZABCDEFGHIJKLMNOPQRSTUVWXY";

    /**
     * @throws Exception
     */

    public function __construct(){
        self::$PHPExcel=new PHPExcel();
        self::$PHPExcel->getDefaultStyle()->getFont()->setName('等线');

        //TerminalLegs
        self::$PHPExcel->getActiveSheet()->setTitle("TerminalLegs");;
        self::$PHPExcel
            ->setActiveSheetIndex(0)
            ->setCellValue('A1',$this->TerminalLegs()[0])
            ->setCellValue('B1',$this->TerminalLegs()[1])
            ->setCellValue('C1',$this->TerminalLegs()[2])
            ->setCellValue('D1',$this->TerminalLegs()[3])
            ->setCellValue('E1',$this->TerminalLegs()[4])
            ->setCellValue('F1',$this->TerminalLegs()[5])
            ->setCellValue('G1',$this->TerminalLegs()[6])
            ->setCellValue('H1',$this->TerminalLegs()[7])
            ->setCellValue('I1',$this->TerminalLegs()[8])
            ->setCellValue('J1',$this->TerminalLegs()[9])
            ->setCellValue('K1',$this->TerminalLegs()[10])
            ->setCellValue('L1',$this->TerminalLegs()[11])
            ->setCellValue('M1',$this->TerminalLegs()[12])
            ->setCellValue('N1',$this->TerminalLegs()[13])
            ->setCellValue('O1',$this->TerminalLegs()[14])
            ->setCellValue('P1',$this->TerminalLegs()[15])
            ->setCellValue('Q1',$this->TerminalLegs()[16])
            ->setCellValue('R1',$this->TerminalLegs()[17])
            ->setCellValue('S1',$this->TerminalLegs()[18])
            ->setCellValue('T1',$this->TerminalLegs()[19])
            ->setCellValue('U1',$this->TerminalLegs()[20])
            ->setCellValue('V1',$this->TerminalLegs()[21]);

        //Terminals
        self::$PHPExcel->createSheet();
        self::$PHPExcel->setActiveSheetIndex(1);
        self::$PHPExcel->getActiveSheet()->setTitle("Terminals");
        self::$PHPExcel
            ->setActiveSheetIndex(1)
            ->setCellValue('A1',$this->Terminals()[0])
            ->setCellValue('B1',$this->Terminals()[1])
            ->setCellValue('C1',$this->Terminals()[2])
            ->setCellValue('D1',$this->Terminals()[3])
            ->setCellValue('E1',$this->Terminals()[4])
            ->setCellValue('F1',$this->Terminals()[5])
            ->setCellValue('G1',$this->Terminals()[6])
            ->setCellValue('H1',$this->Terminals()[7])
            ->setCellValue('I1',$this->Terminals()[8]);

        //Waypoints
        self::$PHPExcel->createSheet();
        self::$PHPExcel->setActiveSheetIndex(2);
        self::$PHPExcel->getActiveSheet()->setTitle("Waypoints");
        self::$PHPExcel
            ->setActiveSheetIndex(2)
            ->setCellValue('A1',$this->WaypointsVal()[0])
            ->setCellValue('B1',$this->WaypointsVal()[1])
            ->setCellValue('C1',$this->WaypointsVal()[2])
            ->setCellValue('D1',$this->WaypointsVal()[3])
            ->setCellValue('E1',$this->WaypointsVal()[4])
            ->setCellValue('F1',$this->WaypointsVal()[5])
            ->setCellValue('G1',$this->WaypointsVal()[6]);

        //TerminalLegsEx
        self::$PHPExcel->createSheet();
        self::$PHPExcel->setActiveSheetIndex(3);
        self::$PHPExcel->getActiveSheet()->setTitle("TerminalLegsEx");
        self::$PHPExcel
            ->setActiveSheetIndex(2)
            ->setCellValue('A1',$this->TerminalLegsEx()[0])
            ->setCellValue('B1',$this->TerminalLegsEx()[1])
            ->setCellValue('C1',$this->TerminalLegsEx()[2])
            ->setCellValue('D1',$this->TerminalLegsEx()[3]);
    }

    /**
     * 整理数据
     * @return int
     */

    public function CountNums():int
    {
        $pre=str_replace(":",",",$this->source);
        return array_count_values(str_split(str_replace(":",",",$pre)))[';'];
    }

    /**
     *
     *匹配导航点
     *
     * @method SELECT
     *
     * @Params ID,Ident,Collocated,Name,Latitude,Longtitude,NavaidID
     *
     *
     * @throws Exception
     */

    public function SepData(){
        $pre = str_replace(":",",",$this->source);
        return explode(';',$pre);
    }

    public function getAirportID(){
        $row=SQLiteUtils::queryRow("SELECT * FROM Airports WHERE ICAO='$this->airport'");
        self::$airportID=$row['ID'];
    }

    /**
     * @throws Exception
     */

    public function GenerateExtraWaypoints(){
        return;
    }

    public function GenerateWaypoint()
    {
        self::$PHPExcel->setActiveSheetIndex(2);
        for ($i = 0; $i < $this->CountNums(); $i++) {
            $e = explode(",", $this->SepData()[$i]);
            //$index=$this->insertPoint+$i;
            $data = SQLiteUtils::execute("SELECT * FROM Waypoints WHERE Ident='$e[5]'");
//            if($data){
//                continue;
//            }
            if(!$data){
                $arr=array(
                    "Ident"=>$e[5],
                    "ID"=>$this->insertPoint+$i,
                    "Name"=>$e[5],
                    "Collocated"=>0,
                    "Latitude"=>"",
                    "Longtitude"=>"",
                    "NavaidID"=>""
                );
            }
//                SQLiteUtils::execute("INSERT into Waypoints(ID,Ident,Collocated,Name,Latitude,Longtitude,NavaidID)
//                VALUES('$index','$e[5]',0,'$e[5]',' ',' ',' ')");
//                $data = SQLiteUtils::queryRow("SELECT * FROM Waypoints WHERE Ident='$e[5]'");
            $arr=array(
                "Ident"=>$data['Ident'],
                "ID"=>$data['ID'],
                "Name"=>$data['Name'],
                "Collocated"=>$data['Collocated'],
                "Latitude"=>$data['Latitude'],
                "Longtitude"=>$data['Longtitude'],
                "NavaidID"=>$data['NavaidID']
            );
            self::$PHPExcel->setActiveSheetIndex(2);
                self::$PHPExcel
                    ->getActiveSheet()
                    ->setCellValue($this->getExcelCeilIndex(1,$i+2),$arr['ID'])
                    ->setCellValue($this->getExcelCeilIndex(2,$i+2),$arr['Ident'])
                    ->setCellValue($this->getExcelCeilIndex(3,$i+2),$arr['Collocated'])
                    ->setCellValue($this->getExcelCeilIndex(4,$i+2),$arr['Name'])
                    ->setCellValue($this->getExcelCeilIndex(5,$i+2),$arr['Latitude'])
                    ->setCellValue($this->getExcelCeilIndex(6,$i+2),$arr['Longtitude'])
                    ->setCellValue($this->getExcelCeilIndex(7,$i+2),$arr['NavaidID']);
        }

    }

    public function GenerateProcedure(){
        $this->getAirportID();
        self::$PHPExcel->setActiveSheetIndex(1);
        $airportID=self::$airportID;
        $a = explode(",", $this->SepData()[0]);
        //0->程序名，1->ID，2->行

        self::$temp_terminals=array(" ",$this->insertPoint-1,1);

        for($i = 0;$i < $this->CountNums();$i++){
            $e = explode(",", $this->SepData()[$i]);
            $runway=substr($e[4],2,2);
            //$data['ID']返回跑道ID
            $data = SQLiteUtils::queryRow("SELECT * FROM Runways WHERE AirportID='$airportID' AND Ident='$runway'");
            if($e[3] != self::$temp_terminals[0]){
                //更新缓存
                self::$temp_terminals=array($e[3],self::$temp_terminals[1]+1,self::$temp_terminals[2]+1);
            }
            self::$PHPExcel
                ->getActiveSheet()
                ->setCellValue($this->getExcelCeilIndex(1,self::$temp_terminals[2]),self::$temp_terminals[1])
                ->setCellValue($this->getExcelCeilIndex(2,self::$temp_terminals[2]),$airportID)
                ->setCellValue($this->getExcelCeilIndex(3,self::$temp_terminals[2]),$this->TurnProcInIndex(str_replace(PHP_EOL,'',$e[0])))
                ->setCellValue($this->getExcelCeilIndex(4,self::$temp_terminals[2]),$this->airport)
                ->setCellValue($this->getExcelCeilIndex(5,self::$temp_terminals[2]),self::$temp_terminals[0])
                ->setCellValue($this->getExcelCeilIndex(6,self::$temp_terminals[2]),self::$temp_terminals[0])
                ->setCellValueExplicit($this->getExcelCeilIndex(7,self::$temp_terminals[2]),$runway)
                ->setCellValue($this->getExcelCeilIndex(8,self::$temp_terminals[2]),$data['ID'])
                ->setCellValue($this->getExcelCeilIndex(9,self::$temp_terminals[2]),"");
        }
    }

    public function GenerateLegs(){
        self::$PHPExcel->setActiveSheetIndex(0);
        //0:程序名 1:程序ID
        self::$temp_legs=array(" ",$this->insertPoint-1);

        for($i=0;$i<$this->CountNums();$i++){
            $e = explode(",", $this->SepData()[$i]);
            $data=SQLiteUtils::queryRow("SELECT * FROM Waypoints WHERE Ident='$e[5]'");

            if($e[3] != self::$temp_legs[0]){
                self::$temp_legs=array($e[3],self::$temp_legs[1]+1);
            }

            $extra_data=SQLiteUtils::queryRow("SELECT * FROM Waypoints WHERE Ident='$e[14]'");
            $k=SQLiteUtils::queryRow("SELECT * FROM Waypoints WHERE Ident='$e[31]'");
            $NavEquip=$this->NavEquipData($e[19],$e[20],$e[21],$e[22],$e[29]);
            self::$PHPExcel
                ->getActiveSheet()
                ->setCellValue($this->getExcelCeilIndex(1,$i+2),$this->insertPoint+$i)
                ->setCellValue($this->getExcelCeilIndex(2,$i+2),self::$temp_legs[1])
                ->setCellValue($this->getExcelCeilIndex(3,$i+2),$e[2])
                ->setCellValue($this->getExcelCeilIndex(4,$i+2),$e[4])
                ->setCellValue($this->getExcelCeilIndex(5,$i+2),$e[12])
                ->setCellValue($this->getExcelCeilIndex(6,$i+2),$this->FixPos($data)[0])
                ->setCellValue($this->getExcelCeilIndex(7,$i+2),$this->FixPos($data)[1])
                ->setCellValue($this->getExcelCeilIndex(8,$i+2),$this->FixPos($data)[2])
                ->setCellValue($this->getExcelCeilIndex(9,$i+2),str_replace(' ','',$e[10]))
                ->setCellValue($this->getExcelCeilIndex(10,$i+2),"")
                ->setCellValue($this->getExcelCeilIndex(11,$i+2),$this->NavPos($extra_data)[0])
                ->setCellValue($this->getExcelCeilIndex(12,$i+2),$this->NavPos($extra_data)[1])
                ->setCellValue($this->getExcelCeilIndex(13,$i+2),$NavEquip[0])
                ->setCellValue($this->getExcelCeilIndex(14,$i+2),$NavEquip[1])
                ->setCellValue($this->getExcelCeilIndex(15,$i+2),$NavEquip[2])
                ->setCellValue($this->getExcelCeilIndex(16,$i+2),$NavEquip[3])
                ->setCellValue($this->getExcelCeilIndex(17,$i+2),$this->Alt($e[23],$e[24],$e[9]))
                //需要改进Vnav精度
                ->setCellValue($this->getExcelCeilIndex(18,$i+2),$NavEquip[4])//VNAV
                ->setCellValue($this->getExcelCeilIndex(19,$i+2),$this->FixPos($k)[0])
                ->setCellValue($this->getExcelCeilIndex(20,$i+2),$this->FixPos($k)[1])
                ->setCellValue($this->getExcelCeilIndex(21,$i+2),$this->FixPos($k)[2])
                ->setCellValue($this->getExcelCeilIndex(22,$i+2),trim(' ',$e[9]));
        }
    }

    public function GenerateLegsEx(){
        for($i=0;$i<$this->CountNums();$i++){
            $e = explode(",", $this->SepData()[$i]);
        }
    }

    /**
     * $PHPExcel->createSheet();
     * @throws Exception
     */

    public function Export(){
            $this->GenerateWaypoint();
            $this->GenerateProcedure();
            $this->GenerateLegs();
            $this->GenerateExtraWaypoints();
            ob_end_clean();
            $PHPExcelWriter=new PHPExcel_Writer_Excel5(self::$PHPExcel);
            $path=$this->Output_Path().$this->user.DIRECTORY_SEPARATOR.$this->airport.'.xlsx';
            $temp_path=$this->Output_Path().$this->user.DIRECTORY_SEPARATOR;
            $make=mkdir($temp_path,0777,true);
            if(!$make){
                $jsonstring=["code"=>"777","msg"=>"Some Error Probably Occur,error at data","data"=>'failed create directory'];
            }
            try{
                $is_save=$PHPExcelWriter->save($path);
            }catch (Exception $e){}
        if($is_save){
            $jsonstring=["code"=>"400","msg"=>"file saved successfully!path to.$path","data"=>$path];
        }else{
            $jsonstring=["code"=>"777","msg"=>"Some Error Probably Occur,error at data","data"=>$e];
        }
        return json_encode($jsonstring);
    }
}