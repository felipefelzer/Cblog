<?php
/*
 * ActiveRecord
 *
 * CopyRight @ Charlie <take3812@gmail.com>
 * 
 */
Class ActiveRecord extends DBDriver
{

    private static $fields = array();

    private static $_saveLock = false;

    private static $table = array();

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
        }else if(!self::_setTableInfo($tableName))
        {    
            exit('set table info failed');
        }
        self::$table['tableName'] = $tableName;
        self::$table['primaryKey'] = $primaryKey;
        //self::debug();
    }

    public function debug()
    {
        echo '<pre>';var_dump(self::$fields,self::$table);
    }

    private function _setTableInfo($tableName)
    {
        $sql = 'DESC '.$tableName;
        $query = DBDriver::_connect()->prepare($sql);
        $query->execute();
        if($query->errorCode() == '00000'){
            self::$fields = $query->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE);
            return SUCCESS;
        }
        return FAILED;
    }

    public function FindByPk($pk = null)
    {
        if( empty($pk) ) return false;
        $pirmarykey = self::$table['primaryKey'];
        $result = $this->_getRow(self::$table['tableName'], "{$pirmarykey} = {$pk}");
        if( $result != false )
            self::$fields = $result;
        else
            var_dump($result);
            
        return $this;
    }

    public function Save()
    {
        $this->_insertRow(self::$table['tableName'],self::$fields);
    }

}

class DBDriver extends APP
{
    //this is a simple SQL driver used to ActiceRecord Design Pattern
    public static $conn;

    protected function _getTableFields($tableName)
    {
        $sql = 'DESC '.$tableName;
        $query = self::_execSQL($sql);
        if($query != false && $query->errorCode() == '00000'){
            return $query->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE);
        }
        return FAILED;
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
    public static function _connect(){
        if(isset(self::$conn)){
            return DBDriver::$conn;
        }else{
            $conf = Config::GetConf('DB');
            if( $conf != false )
            {
                static::$conn = new PDO(
                    "mysql:host={$conf['host']};dbname={$conf['dbname']};",
                    $conf['username'],
                    $conf['password']
                );
                return static::$conn;
            }
            return FAILED;
        }
    }

    protected function _getRow($tableName,$condition,$fields = '*')
    {
        if( !empty($tableName) && !empty($condition) )
        {
            $condition = 'WHERE '.$condition;
            $sql = "SELECT {$fields} FROM {$tableName} {$condition}";
            $sth = $this->_execSQL($sql);
            if( $sth != false )
                return $sth->fetch(PDO::FETCH_ASSOC);
        }
        return FAILED;
    }

    protected function _insertRow($table,$fields)
    {
        list($col,$fields) = $this->_getFields($fields);
        if( $fields != FAILED )
            $sql = "INSERT INTO {$table}({$col}) VALUES({$fields})";

        echo $sql;
        //$result = $this->execSQL($sql);
    }
        
    protected function _getFields($fields)
    {
        if( !empty($fields) && is_array($fields) )
        {
            $field  = null;
            $col    = null;
            foreach($fields as $key => $value)
            {
                $col   .= $key.',';
                $field .= $value.',';
            }
            $col    = rtrim($col,',');
            $field  = rtrim($field,',');
            return array($col,$field);
        }
        return FAILED;
    }

    protected function _execSQL($sql)
    {
        if( !empty($sql) )
        {
            $conn = self::_connect();
            if( $conn != FAILED )
            {
                $sth = $conn->prepare($sql);
                if( $sth->execute() )
                    return $sth;
                else
                    var_dump($sth->errorInfo(),$sql);
            }
        }
        return FAILED;
    }
}
