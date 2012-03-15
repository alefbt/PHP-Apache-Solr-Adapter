<?php
/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: 3/4/12
 * Time: 4:11 PM
 */
class KoSolr_Server_Response extends KoSolr_Server_Response_IExtend
{
    /**
     * @static
     * @param $request KoSolr_Server_Request
     * @param $context
     * @param $content_response
     * @return mixed|null
     */
    public static function factory($request,$context,$content_response)
    {        
        $cls ='KoSolr_Server_Response_' . ucfirst(strtolower($request->getReturnType()));
        
        if(!class_exists($cls))
            return null;

        return call_user_func_array(
            "$cls::factory",
            array($request,$context,$content_response)
        );

    }
}
