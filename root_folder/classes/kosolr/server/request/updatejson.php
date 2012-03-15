<?php
/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: 3/5/12
 * Time: 2:14 PM
 */
class KoSolr_Server_Request_UpdateJson extends KoSolr_Server_Request
{
    /**
     * Auto-subbmit threshold
     * @var int
     */
    private $_auto_submit_threshold = 1000;

    /**
     * Auto-subbmit threshold  enable
     * @var bool
     */
    private $_auto_submit_threshold_enabled = TRUE;
    /**
     *
     * @var string 
     */
    protected $_requestHandler = KoSolr_Server_Request::SOLR_REQUEST_HANDLER_UPDATE_JSON;

    /**
     * Timeout settings
     * @var int
     */
    protected $_timeout = 160;
    /**
     * Content type
     * @var string
     */
    protected $_requestContentType = KoSolr_Server_Request::CONTENT_TYPE_JSON;
    /***
     * Command type
     * @var string
     */
    protected $_command = KoSolr_Server_Request::SOLR_REQUEST_HANDLER_UPDATE_JSON;
    /**
     * Command set
     * @var array
     */
    private $_command_list = array();
    /**
     *
     * @var array
     */
    private $_commands_rnd_names = array();
    /**
     * @var KoSolr_Server
     */
    private $_server = null;
    /**
     * Adding command to command list
     * @param string $command_name
     * @param array $value
     */
    public function add_command($command_name = KoSolr_Server_Request::COMMAND_COMMIT,$value=array())
    {
        $removable_name  = $this->get_removable_name($command_name);
        
        if($command_name == KoSolr_Server_Request::COMMAND_COMMIT)
        {
            $this->_command_list["{$command_name}{$removable_name}"] = "%MAJIC_EMPTY_JSON_OBJ%";
        }
        elseif($command_name == KoSolr_Server_Request::COMMAND_OPTIMIZE)
        {
            if(count($value)==0)
                $this->_command_list["{$command_name}{$removable_name}"] = "%MAJIC_EMPTY_JSON_OBJ%";
        }
        else
        {            
            $this->_command_list["{$command_name}{$removable_name}"] = $value;           
        }
            
        $this->_commands_rnd_names[] =$removable_name;
            
        $this->threshold_check();
    }
    /**
     * if
     * @return bool
     */
    public function threshold_check()
    {
        if(!$this->_auto_submit_threshold_enabled)
            return false;

        if(count($this->_commands_rnd_names)<$this->_auto_submit_threshold)
            return false;

        if(!$this->_server)
            return false;
        
        $this->execute();
        
        $this->clear_commands();
    }
    /**
     *Clear command list 
     */
    public function clear_commands()
    {
        $this->_commands_rnd_names = $this->_command_list = array();
    }

    /**
     * Executes on server 
     */
    public function execute()
    {
        $this->_server->execute($this);    
    }

    /**
     * Add document to solr
     * @param KoSolr_Document $so_doc
     * @return \KoSolr_Server_Request_UpdateJson 
     */
    public function add_document(KoSolr_Document $so_doc)
    {
       $this->add_command(KoSolr_Server_Request::COMMAND_ADD, array('commitWithin'=>5000, 'doc'=>$so_doc->getArrayData()) );
       return $this;
    }
    /**
     * Get random md5 string to command names
     * @param $name
     * @return string
     */
    private function get_removable_name($name)
    {
        return '-%%%'.md5($name+date('c')+rand(0,999)+mt_rand());
    }
    /**
     * @return mixed|string
     */
    public function getContentRaw()
    {
        $json_str = json_encode($this->_command_list);

        foreach($this->_commands_rnd_names as $rname)
             $json_str = str_replace($rname,'',$json_str);
        
        $json_str = str_replace('"%MAJIC_EMPTY_JSON_OBJ%"','{}',$json_str);

        return $json_str;
    }
    /**
     * @param $server_handler KoSolr_Server
     */
    public function __construct(&$server = null)
    {
         $this->_server = $server;
    }
}
