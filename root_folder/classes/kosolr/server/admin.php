<?php
/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: Mar 6, 2012
 * Time: 10:16:32 AM
 */
class KoSolr_Server_Admin extends KoSolr_Server{
   /**
    * @param $host
    * @param int $port
    * @param string $app_path
    * @param string $core
    * @param string $protocol
    */
    public function __construct($host,$port=8080,$app_path='/solr', $protocol='http')
    {
        parent::__construct($host,$port,$app_path,'',$protocol);
    }   
    
    /**
     * Create request 
     * @return KoSolr_Server_Request_Admin
     */
    public function create_request()
    {        
        return new KoSolr_Server_Request_Admin($this);
    }   
    /**
     * Create Request Admin Cores
     * @return \KoSolr_Server_Request_Admin_Cores 
     */
    public function create_request_admin_cores()
    {        
        return new KoSolr_Server_Request_Admin_Cores($this);
    }   
    
    /**
     * Execute request
     * @param KoSolr_Server_Request_Admin $request
     * @param int|bool $timeout
     * @return KoSolr_Server_Response_IExtend
     * @throws Exception 
     */
    public function execute(KoSolr_Server_Request_Admin $request, $timeout=FALSE)
    {
        return parent::execute($request, $timeout);
    }
}

?>
