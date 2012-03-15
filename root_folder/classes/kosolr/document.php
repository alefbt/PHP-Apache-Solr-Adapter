<?php
/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: 3/5/12
 * Time: 4:42 PM
 */
class KoSolr_Document
{

    private $_data=array();

    /**
     * Magic Getter
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_data))
        {
            return $this->_data[$name];
        }
    }
    /**
     * Magic Setter
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }
    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }
    /**
     * @param $name
     */
    public function __unset($name)
    {
        unset($this->_data[$name]);
    }
    /**
     * getting all data
     * @return array
     */
    public function getArrayData()
    {
       return $this->_data;
    }
    /**
     * @param $name
     * @param $value
     */
    public function setField($name, $value)
    {
       $this->__set($name, $value);
    }
    /**
     * Set multiple value always addes
     * @param type $name
     * @param type $value 
     */
    public function setMultiValue($name, $value)
    {
        if(!$this->__isset($name))
            $this->setField($name, $value);
        
        if(!is_array($this->_data[$name]))
        {
            $old = $this->__get($name);
            $this->setField($name, array($value));
        }
        
        $this->_data[$name][] = $value;
    }
}
