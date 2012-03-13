<?php
/**
 * B.S.D
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: 3/4/12
 * Time: 3:53 PM
 */
class KoSolr_Server
{
    const REQUEST_TYPE_SELECT = 0;
    const REQUEST_TYPE_UPDATE_JSON = 1;
    /**
     * @var int Host port
     */
    private $_port = 8080;
    /**
     * @var string Host uri
     */
    private $_host = '';
    /**
     * @var string core name
     */
    private $_core = '';
    /**
     * @var string protocol
     */
    private $_protocol = 'http';
    /**
     * @var string tomcat/jetty path
     */
    private $_app_path = '/solr';

    /**
     * @param $host
     * @param int $port
     * @param string $app_path
     * @param string $core
     * @param string $protocol
     */
    public function __construct($host,$port=8080,$app_path='/solr', $core='',$protocol='http')
    {
        $this->_host = $host;
        $this->_port = $port;
        $this->_core = $core;
        $this->_app_path = $app_path;
        $this->_protocol = $protocol;
    }
    /**
     * if server available
     * @return bool
     */
    public function is_available()
    {
        // not implemented yeat....
        return true;
    }
    /**
     * Create request 
     * also can be created like
     *  $request = new KoSolr_Server_Request_UpdateJson($some_server)
     * 
     * @see KoSolr_ServerHandler::REQUEST_TYPE_SELECT
     * @return KoSolr_Server_Request|null
     */
    public function create_request($type=KoSolr_Server::REQUEST_TYPE_SELECT)
    {
        $_request = null;
        switch($type)
        {
            case KoSolr_ServerHandler::REQUEST_TYPE_UPDATE_JSON:
                $_request = new KoSolr_Server_Request_UpdateJson(&$this);
            break;

            case KoSolr_ServerHandler::REQUEST_TYPE_SELECT:
                $_request = new KoSolr_Server_Request();
            break;


            default:
                throw new Exception('Invalid type use KoSolr_ServerHandler::REQUEST_TYPE_*');
            break;
        }

        return $_request;

    }
    /**
     * Creating solr update request
     * @return KoSolr_Server_Request_UpdateJson 
     */
    public function create_update_request()
    {
        return new KoSolr_Server_Request_UpdateJson(&$this);
    }
    /**
     * Returns search request
     * @return \KoSolr_Query 
     */
    public function create_search_request()
    {
        return new KoSolr_Query();
    }
    /**
     * Create delete request
     * @param type $query
     * @return type 
     */
    public function create_delete_request($query='*:*')
    {
        $req = $this->create_update_request();
        $req->add_command(KoSolr_Server_Request::COMMAND_DELETE,array('query'=>$query));                
        return $req;
    }

    /**
     * Commit
     * @return KoSolr_Server_Response_IExtend 
     */
    public function commit()
    {
        $request = new KoSolr_Server_Request_UpdateJson(&$this);
        $request->setRequestHandler(KoSolr_Server_Request::SOLR_REQUEST_HANDLER_UPDATE_JSON);
        $request->add_command(KoSolr_Server_Request::COMMAND_COMMIT);
        return $this->execute($request);        
    }
    /**
     * Optimize
     * @return KoSolr_Server_Response_IExtend 
     */
    public function optimize()
    {
        $request = new KoSolr_Server_Request_UpdateJson(&$this);
        $request->add_command(KoSolr_Server_Request::COMMAND_OPTIMIZE);
        return $this->execute($request);        
    }
    /**
     * Generate request uri
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->_protocol . "://{$this->_host}" . ($this->_port?":{$this->_port}":"") . ($this->_app_path?$this->_app_path:'/'). ($this->_core?"/{$this->_core}":'');
    }
    /**
     * Execute request
     * @param KoSolr_Server_Request $request
     * @param int|bool $timeout
     * @return KoSolr_Server_Response_IExtend
     * @throws Exception 
     */
    public function execute(KoSolr_Server_Request $request, $timeout=FALSE)
    {
        
        $context = stream_context_create();
        $url =    $this->getRequestUrl() . $request->getRequestHandler();
        
        $header = array(
            'http' => array(
                'timeout' => ($timeout)?$timeout:$request->getTimeout(),
            )
        );
        
        
        if($request->getSendMethod() == KoSolr_Server_Request::SEND_METHOD_POST)
        {
            $header['http']['method'] = $request->getSendMethod();
            $header['http']['content'] = $request->getContentRaw();
            $header['http']['header'] = 'Content-Type: ' . $request->getRequestContentType();
        }
        elseif ($request->getSendMethod() == KoSolr_Server_Request::SEND_METHOD_GET) 
        {
            $url.='?'.$request->getContentRaw();
        }
        
        stream_context_set_option( $context,  $header );

        
        $raw_response = @file_get_contents($url, false, $context);
        $raw_headerResponse =  KoSolr_Utils::http_content_response($http_response_header);

        if( $raw_headerResponse['httpStatus']!=200)
        {
            throw new KoSolr_Exception("Request url : $url Request ".print_r($header,true) .' Solr Service : ' . $raw_headerResponse['httpStatusMessage']);
        }
        return KoSolr_Server_Response::factory($request,$context,$raw_response);
    }






}
