<?php
/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: 3/4/12
 * Time: 4:54 PM
 */
class KoSolr_Server_Response_IExtend
{
    /**
     * Last executed serach id
     * @var string 
     */
    public $latest_query_id='';
    /**
     * Response data saved
     * @var array|mixed
     */
    private $_response_data = array();
    /**
     * Request Context
     * @var array|mixed
     */
    private $_context_data = array();    
    /**
     * @static
     * @param $request KoSolr_Server_Request
     * @param $context
     * @param $content_response
     */
    public static function factory($request,$context,$content_response){}
    /**
     * @param $request KoSolr_Server_Request
     * @param $context
     * @param $content_response
     */
    protected function __construct($request,$context,$content_response){
        @$this->_response_data = unserialize($content_response);      
        $this->_context_data = $context;
        $this->latest_query_id= md5( serialize($request) . date('r') . rand(0,100) );
    }
   /**
     * magic method
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if(!isset($this->_response_data[$name]))
            return null;
        
        return $this->_response_data[$name];
    }
    /**
     * Throws an Exception there is no need to use setting objects
     * @param $name
     * @param $value
     * @throws Exception
     */
    public function __set($name,$value)
    {
        throw new KoSolr_Exception('No setting values - readonly object');
    }
}
