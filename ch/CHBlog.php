<?php
if( !defined('cc') ) exit();

class CHBlog extends ActiveRecord
{
    private $_primaryKey = 'Card_ID';

    private $_tableName = 'shopnc_cards';

    public function __construct($pk = null,$tn = null)
    {
        parent::register($this->_tableName,$this->_primaryKey);
    }


}
