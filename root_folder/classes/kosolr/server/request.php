<?php
/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: 3/4/12
 * Time: 4:11 PM
 */
class KoSolr_Server_Request
{
    /**
     * Solr method to return the data
     * @see solr wt param
     * @see KoSolr_Server_Response_Phps class names must match
     */
    const RETURN_TYPE_PHPS='phps';

    const SOLR_REQUEST_HANDLER_SELECT='/select/';
    const SOLR_REQUEST_HANDLER_UPDATE_JSON='/update/json';
    const SOLR_REQUEST_HANDLER_ADMIN='/admin/';
    const SOLR_REQUEST_HANDLER_ADMIN_CORES='/admin/cores';
    const COMMAND_COMMIT        = 'commit';
    const COMMAND_OPTIMIZE      = 'optimize';
    const COMMAND_DELETE        = 'delete';
    const COMMAND_ADD           = 'add';

    const CONTENT_TYPE_X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';
    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_XML_UTF = 'text/xml; charset=UTF-8';

    const SEND_METHOD_POST='POST';
    const SEND_METHOD_GET='GET';
    /**
     * all request data
     * default values
     * @var array
     */
    protected $_request_data = array(
        'wt'=>KoSolr_Server_Request::RETURN_TYPE_PHPS,
        'rows'=>'500',
        'start'=>'0',
        'q'=>'*:*'
    );
    /**
     * Magic Getter
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_request_data))
        {
            return $this->_request_data[$name];
        }
    }
    /**
     * Magic Setter
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->_request_data[$name] = $value;
    }
    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_request_data[$name]);
    }
    /**
     * @param $name
     */
    public function __unset($name)
    {
        unset($this->_request_data[$name]);
    }
    /**
     * @return array
     */
    public function getDataArray(){
        return $this->_request_data;
    }
    /**
     * @return array
     */
    public function getContentRaw()
    {
        return KoSolr_Utils::convert_array_to_solr_query( $this->getDataArray() );
    }
    /**
     * Constructor 
     */
    public function __construct() {/*nothing to do*/}

    /***************************************************************************************
     *
     * User friendly getters/setters
     *
     ***************************************************************************************/

    /**
     * Command on solr
     * @var string
     */
    protected $_requestHandler = KoSolr_Server_Request::SOLR_REQUEST_HANDLER_SELECT;
    /**
     * command to execute
     * @param string $command
     */
    public function setRequestHandler($requestHandler = KoSolr_Server_Request::SOLR_REQUEST_HANDLER_SELECT)
    {$this->_requestHandler = $requestHandler;}
    /**
     * Getter of command
     * @return string
     */
    public function getRequestHandler()
    {return $this->_requestHandler;}
    /**
     * Content send type
     * @var string
     */
    protected $_requestContentType = KoSolr_Server_Request::CONTENT_TYPE_X_WWW_FORM_URLENCODED;
    /**
     * @return string
     */
    public function getRequestContentType()
    { return $this->_requestContentType; }
    /**
     * Content type
     * @param $content_type
     */
    public function setRequestContentType($content_type=CONTENT_TYPE_X_WWW_FORM_URLENCODED)
    { $this->_requestContentType = $content_type; }
    /**
     * Return type see const RETURN_TYPE_*
     * @return string
     */
    public function getReturnType()
    {return $this->wt;}
    /**
     * Set return type
     * @param string $return_type
     * @return KoSolr_Server_Request
     */
    public function setReturnType($return_type=KoSolr_Server_Request::RETURN_TYPE_PHPS)
    { $this->wt=$return_type; return $this; }
    /**
     * timeout
     * @var int
     */
    protected $_timeout = 60;
    /**
     * Get request timeout
     * @return int
     */
    public function getTimeout()
    {return $this->_timeout; }
    /**
     * set timeout
     * @param $timeout int
     */
    public function setTimeout($timeout)
    {$this->_timeout=$timeout;}
    /**
     * Send method
     * @var string
     */
    protected $_sendMethod = KoSolr_Server_Request::SEND_METHOD_POST;
    /**
     * Sending method
     * @return string
     */
    public function getSendMethod()
    { return $this->_sendMethod; }
    /**
     *
     * @param string $method
     */
    public function setSendMethod($method = KoSolr_Server_Request::SEND_METHOD_POST)
    { $this->_sendMethod = $method; }



}
