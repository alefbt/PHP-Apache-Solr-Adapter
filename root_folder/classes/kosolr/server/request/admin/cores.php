<?php
/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: 3/5/12
 * Time: 2:14 PM
 */
class KoSolr_Server_Request_Admin_Cores extends KoSolr_Server_Request_Admin
{
    /**
     * Command on solr
     * @var string
     */
    protected $_requestHandler = KoSolr_Server_Request::SOLR_REQUEST_HANDLER_ADMIN_CORES;
    /**
     * Send method
     * @var string
     */
    protected $_sendMethod = KoSolr_Server_Request::SEND_METHOD_GET;    
    /**
     * Execute current request
     * @param bool $reset_request
     * @return KoSolr_Server_Response_Phps 
     */
    private function do_execute($reset_request=true)
    {
        $response = $this->_server_instance->execute($this);
        if($reset_request)
        {
            $this->reset_request();
        }
        return $response;        
    }

    /**
     * Constactor
     * @param KoSolr_Server_Admin $server 
     */
    public function __construct(KoSolr_Server_Admin &$server) 
    {
        parent::__construct($server);
    }
    /**
     * Get the status for a given core or all cores if no core is specified
     * @see http://wiki.apache.org/solr/CoreAdmin#STATUS
     * @param type $core
     * @param type $reset_request
     * @return KoSolr_Server_Response_Phps 
     */
    public function getStatus($core=null,$reset_request = true) 
    {
        $this->action = 'STATUS';    
        
        if($core)
            $this->core = $core;
        
        return $this->do_execute($reset_request);        
    }
    /**
     * Creates a new core based on preexisting instanceDir/solrconfig.xml/schema.xml, 
     * and registers it. If persistence is enabled (persist=true), the configuration
     * for this new core will be saved in 'solr.xml'. If a core with the same name
     * exists, while the "new" created core is initalizing, the "old" one will
     * continue to accept requests. Once it has finished, all new request will
     * go to the "new" core, and the "old" core will be unloaded.
     * @see http://wiki.apache.org/solr/CoreAdmin#CREATE
     * @param string|null $name
     * @param string|null $instanceDir
     * @param string|null $dataDir
     * @param string|null $config
     * @param string|null $schema
     * @return KoSolr_Server_Response_Phps 
     */
    public function create($name,$instanceDir,$dataDir=null,$config=null,$schema=null) 
    {
        $this->action = 'CREATE';
        $this->name = $name;
        $this->instanceDir = $instanceDir;
        if($dataDir)
            $this->dataDir = $dataDir;
        if($config)
            $this->config = $config;
        if($schema)
            $this->schema = $schema;
                
        return $this->do_execute();          
    }    
    /**
     * Load a new core from the same configuration as an existing registered core
     * @see http://wiki.apache.org/solr/CoreAdmin#RELOAD
     * @param type $core
     * @return KoSolr_Server_Response_Phps 
     */
    public function reload($core) 
    {
        $this->action = 'RELOAD';
        $this->core = $core;
        return $this->do_execute();          
    }
    /**
     * Change the names used to access a core
     * @see http://wiki.apache.org/solr/CoreAdmin#RENAME
     * @param string $fromCore
     * @param string $toCore
     * @return KoSolr_Server_Response_Phps 
     */
    public function rename($fromCore , $toCore) 
    {
        $this->action = 'RENAME';
        $this->core = $fromCore;
        $this->other = $toCore;
        return $this->do_execute();          
    }
    /**
     * Adds an additional name for a core
     * Solr3.5 - (Experimental)
     * @see http://wiki.apache.org/solr/CoreAdmin#ALIAS
     * @param string $core
     * @param string $other
     * @return KoSolr_Server_Response_Phps 
     */
    public function alias($core , $other) 
    {
        $this->action = 'ALIAS';
        $this->core = $core;
        $this->other = $other;
        return $this->do_execute();          
    }
    /**
     * Atomically swaps the names used to access two existing cores
     * @see http://wiki.apache.org/solr/CoreAdmin#SWAP
     * @param string $core
     * @param string $other
     * @return KoSolr_Server_Response_Phps 
     */
    public function swap($core , $other) 
    {
        $this->action = 'SWAP';
        $this->core = $core;
        $this->other = $other;
        return $this->do_execute();          
    }
    /**
     * Removes a core from Solr
     * Note: in Solr3.3 An optional boolean parameter "deleteIndex"
     * @see http://wiki.apache.org/solr/CoreAdmin#UNLOAD
     * @param type $core
     * @param type $deleteIndex
     * @return type 
     */
    public function unload($core , $deleteIndex=null) 
    {
        if($deleteIndex)
            $this->deleteIndex=$deleteIndex;
        
        $this->action = 'UNLOAD';
        $this->core = $core;
        $this->other = $other;
        return $this->do_execute();          
    }    
}
