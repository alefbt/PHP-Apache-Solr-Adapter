<?php
/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: 3/4/12
 * Time: 3:43 PM 
 */
class KoSolr
{
    /**
     * Default connction configuration 
     */
    const DEFAULT_CONNECTION_CONFIGURATION = 'default-solr-configuration';
    /**
     * list of connction configuration names
     * @var array 
     */
    private $_connection_configurations = array(
        'default-solr-configuration'=>array(
            'server'=>'solr',
            'port'=>8080,
            'app_path'=>'/apache-solr-3.5.0',
            'core'=>'members'
        ),
    );
    /**
     * Array of solr instances
     * @var array 
     */
    private static $instances = array();
    /**
     * get active instance
     * @param string $name
     * @return KoSolr 
     */
    public static function getInstance($name=KoSolr::DEFAULT_CONNECTION_CONFIGURATION)
    {
        if(!isset(self::$instances[$name]))
        {
            return self::$instances[$name] = new KoSolr($name);
        }            
        
        return self::$instances[$name];
    }

    /************************************************************
     * Class 
     ************************************************************/

    /**
     * Constractor
     * @param string $configuration 
     */
    public function __construct($configuration = null) {
        $this->setServer($configuration);
    }    
    /**
     * Server Instance
     * @var KoSolr_Server 
     */
    private $server = null;
    /**
     *
     * @param string $configuration 
     */
    public function setServer($configuration = null)
    {
        
        if(!$configuration)
        {
            $configuration = KoSolr::DEFAULT_CONNECTION_CONFIGURATION;
        }
        
        if(!isset($this->_connection_configurations[$configuration]))
        {
            $configuration = KoSolr::DEFAULT_CONNECTION_CONFIGURATION;
        }
        
        $conf = $this->_connection_configurations[$configuration];
        $this->server = new KoSolr_Server($conf['server'],$conf['port'],$conf['app_path'],$conf['core']);
    }
    /**
     * Get current server
     * @return KoSolr_Server 
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Delete Objects on solr
     * @param string $query
     * @return KoSolr_Server_Response_IExtend 
     */
    public function Delete($query)
    {     
        return $this->getServer()->execute(
                $this->getServer()->create_delete_request($query)
        );        
    }
    /**
     * Commit Changes
     * @return KoSolr_Server_Response_IExtend 
     */
    public function Commit()
    {
        return $this->getServer()->commit();
        
    }
    /**
     * Optimize Changes
     * @return KoSolr_Server_Response_IExtend 
     */
    public function Optimize()
    {
        return $this->getServer()->optimize();
        
    }
}
