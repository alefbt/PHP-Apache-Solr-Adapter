<?php

/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: Mar 6, 2012
 * Time: 10:46:14 AM
 */

class KoSolr_Server_Request_Admin extends KoSolr_Server_Request{
    /**
     * Timeout settings
     * @var int
     */
    protected $_timeout = 160;    
    /**
     * all request data
     * default values
     * @var array
     */
    protected $_request_data = array(
        'wt'=>KoSolr_Server_Request::RETURN_TYPE_PHPS
    );
    /**
     * Clear request data 
     */
    public function reset_request() {
        $this->_request_data = array();
        $this->wt = KoSolr_Server_Request::RETURN_TYPE_PHPS;
    }
    /**
     * Server instance
     * @var KoSolr_Server_Admin 
     */
    protected $_server_instance = null;
    /**
     * Command on solr
     * @var string
     */
    protected $_requestHandler = KoSolr_Server_Request::SOLR_REQUEST_HANDLER_ADMIN;    
    /**
     * Admin instance
     * @param KoSolr_Server_Admin $server 
     */
    public function __construct(KoSolr_Server_Admin &$server) {        
        parent::__construct();
        $this->_server_instance = $server;
    }
    
}

?>
