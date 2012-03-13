<?php
/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: 3/4/12
 * Time: 4:55 PM
 */
class KoSolr_Server_Response_Phps extends KoSolr_Server_Response_IExtend{


    /**
     * @static
     * @param $request KoSolr_Server_Request
     * @param $context
     * @param $content_response
     * @return KoSolr_Server_Response_Phps
     */
    public static function factory($request,$context,$content_response){ return new self($request,$context,$content_response);}
    /**
     * @param $request KoSolr_Server_Request
     * @param $context
     * @param $content_response
     */
    protected function __construct($request,$context,$content_response)
    {
        parent::__construct($request, $context, $content_response);
    }
 

}
