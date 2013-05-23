<?php
/*
 * ActiveRecord
 *
 * CopyRight @ Charlie <take3812@gmail.com>
 * 
 */
Class ActiveRecord extends DBDriver
{

    public static $fields = array();

    private static $registed = false;

    private static $tableinfo;

    public function __GET($name)
    {
        if(array_key_exists($name,self::$fields))
        {
            return self::$fields[$name];
        }else
        {
            exit("there is no field named: {$name}");
        }
    }
    
    public function __SET($name,$value)
    {
        self::$fields[$name] = $value;
    }

    public static function register($tableName,$primaryKey = null)
    {
        if(empty($primaryKey) || empty($tableName))
        {
            exit('parameter not null');
        }else if(self::setTableInfo($tableName) == false)
        {    
            exit('set table info failed');
        }
        self::$registed = true;
        self::debug();
    }

    public function debug()
    {
        echo '<pre>';var_dump(self::$fields);
    }

    private function setTableInfo($tableName)
    {
        $sql = 'DESC '.$tableName;
        $query = DBDriver::Connect()->prepare($sql);
        $query->execute();
        if($query->errorCode() == '00000'){
            self::$fields = $query->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE);
        }else{
            return false;
        }
    }

    public function findByPk($pk)
    {
        if(empty($pk)) return false;
        $pirmarykey = self::$table['PirmaryKey'];
        $result = $this->getRow("{$pirmarykey} = {$pk}");
        return $this;
    }

    private function getRow($condition,$fields = '*')
    {
        $tableName = self::$table['tableName'];
        $condition = 'WHERE '.$condition;
        $sth = self::connect()->prepare("SELECT {$fields} FROM {$tableName} {$condition}");
        $sth->execute();
        var_dump($sth->errorInfo());
        return $sth->fetch();
    }
}

class DBDriver extends APP
{
    //this is a simple SQL driver used to ActiceRecord Design Pattern
    public static $conn;
    //default DB configration
    public $DBconfig = array(
        'db'    => 'mysql',
        'host'  => '127.0.0.1',
        'port'  => '3306',
        'dbName'=> ''
    );
    //DB connction
    private function conn(){
        static::$conn = new PDO('mysql:host=localhost;dbname=YGZSBK;','root','root');
        return static::$conn;
    }

    public function SelectDB($DBName)
    {
        if( isset($this->DBconfig) && !empty($DBName) )
        {
            $this->DBconfig['dbname'] = $DBName;
            return true;
        }
        return false;
    }

    //Singleton
    public static function Connect(){
        if(isset(self::$conn)){
            return DBDriver::$conn;
        }else{
            return self::conn();
        }
    }

}
