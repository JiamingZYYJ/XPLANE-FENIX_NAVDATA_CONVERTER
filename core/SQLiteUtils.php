<?php
require_once '../Config/FenixNav.config.php';

class SQLiteUtils{

    use Config;

    private static $db;

    private static function instance(){
        if (!self::$db) {
            self::$db = new SQLite3(self::Sqlite_Path());
        }
    }

    /**
     * 创建表
     * @param string $sql
     */
    public static function create($sql){
        self::instance();
        $result = @self::$db->query($sql);
        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * 执行增删改操作
     * @param string $sql
     * @return false
     */
    public static function execute(string $sql){
        self::instance();
        $result = @self::$db->exec($sql);
        if ($result) {
            return $result;
        }
        return false;
    }

    /**
     * 获取记录条数
     * @param string $sql
     * @return int
     */
    public static function count($sql){
        self::instance();
        $result = @self::$db->querySingle($sql);
        return $result ? $result : 0;
    }

    /**
     * 查询单个字段
     * @param string $sql
     * @return void|string
     */
    public static function querySingle($sql){
        self::instance();
        $result = self::$db->querySingle($sql);
        return $result ? $result : '';
    }

    /**
     * 查询单条记录
     * @param string $sql
     * @return array
     */
    public static function queryRow(string $sql): array
    {
        self::instance();
        $result = @self::$db->querySingle($sql,true);
        return $result;
    }

    /**
     * 查询多条记录
     * @param string $sql
     * @return array
     */
    public static function queryList(string $sql): array
    {
        self::instance();
        $result = array();
        $ret = @self::$db->query($sql);
        if (!$ret) {
            return $result;
        }
        while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
            $result[] = $row;
        }
        return $result;
    }

}