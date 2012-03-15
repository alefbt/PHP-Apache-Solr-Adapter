<?php
/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: 3/5/12
 * Time: 10:54 AM
 */
class KoSolr_Utils
{
    /**
     * Const default delimiter
     */
    const QUERY_STRING_DELIMITER = '&';

    public static function smart_add_to_array(array &$data,string $key,$value)
    {
        /**
         * If key is already set
         */
        if (array_key_exists($key, $data))
        {

            /**
             * If the value is NOT array
             */
            if(!is_array($data[$key]))
            {
                /**
                 * Save old value
                 */
                $old_value = $data[$key];

                /**
                 * If value is real
                 */
                if($old_value)
                {
                    /**
                     * Add value as in array
                     */
                    $data[$key] = array($old_value);
                }
                else
                {
                    /**
                     * Create array
                     */
                    $data[$key] = array();
                }
            }
            /**
             * Add new value to array
             */
            $data[$key][] = $value;

        }
        else
        {
            /**
             * Add scalar value
             */
            $data[$key] = $value;
        }
    }
    /**
     * @static
     * @param array $arr_post
     * @return string
     */
    public static function convert_array_to_solr_query(array $arr_post)
    {
        // use http_build_query to encode our arguments because its faster
        // than urlencoding all the parts ourselves in a loop
        $queryString = http_build_query($arr_post, null, self::QUERY_STRING_DELIMITER);

        // because http_build_query treats arrays differently than we want to, correct the query
        // string by changing foo[#]=bar (# being an actual number) parameter strings to just
        // multiple foo=bar strings. This regex should always work since '=' will be urlencoded
        // anywhere else the regex isn't expecting it
        $queryString = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $queryString);

        return $queryString;
    }

    /**
     * Analize the http request
     * @static
     * @param $httpHeaders
     * @return array
     */
    public static function http_content_response($httpHeaders)
    {
        //Assume 0, 'Communication Error', utf-8, and  text/plain
        $status = 0;
        $statusMessage = 'Communication Error';
        $type = 'text/plain';
        $encoding = 'UTF-8';

        //iterate through headers for real status, type, and encoding
        if (is_array($httpHeaders) && count($httpHeaders) > 0)
        {
            //look at the first headers for the HTTP status code
            //and message (errors are usually returned this way)
            //
            //HTTP 100 Continue response can also be returned before
            //the REAL status header, so we need look until we find
            //the last header starting with HTTP
            //
            //the spec: http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.1
            //
            //Thanks to Daniel Andersson for pointing out this oversight
            while (isset($httpHeaders[0]) && substr($httpHeaders[0], 0, 4) == 'HTTP')
            {
                $parts = explode(' ', substr($httpHeaders[0], 9), 2);

                $status = $parts[0];
                $statusMessage = trim($parts[1]);

                array_shift($httpHeaders);
            }

            //Look for the Content-Type response header and determine type
            //and encoding from it (if possible - such as 'Content-Type: text/plain; charset=UTF-8')
            foreach ($httpHeaders as $header)
            {
                if (strncasecmp($header, 'Content-Type:', 13) == 0)
                {
                    //split content type value into two parts if possible
                    $parts = explode(';', substr($header, 13), 2);

                    $type = trim($parts[0]);

                    if ($parts[1])
                    {
                        //split the encoding section again to get the value
                        $parts = explode('=', $parts[1], 2);

                        if ($parts[1])
                        {
                            $encoding = trim($parts[1]);
                        }
                    }

                    break;
                }
            }

            return array(
                'rawResponse' => '',
                'type' => $type,
                'encoding' => $encoding,
                'httpStatus' => $status,
                'httpStatusMessage' => $statusMessage
                );
        }
    }
    /**
     * converting date/time from mysql to solr as TZ-Time
     * @param string $dateString
     * @return string
     */
    public static function date_mysql_to_solr($dateString)
    {
        $ts = strtotime($dateString);
        return date('Y-m-d', $ts) . 'T' . date('H:i:s', $ts) . 'Z';
    }
    /**
    * Escape a value for special query characters such as ':', '(', ')', '*', '?', etc.
    *
    * NOTE: inside a phrase fewer characters need escaped, use {@link Apache_Solr_Service::escapePhrase()} instead
    *
    * @param string $value
    * @return string
    */
    static public function escape($value)
    {
        //list taken from http://lucene.apache.org/java/docs/queryparsersyntax.html#Escaping%20Special%20Characters
        $pattern = '/(\+|-|&&|\|\||!|\(|\)|\{|}|\[|]|\^|"|~|\*|\?|:|\\\)/';
        $replace = '\\\$1';

        return preg_replace($pattern, $replace, $value);
    }

    /**
    * Escape a value meant to be contained in a phrase for special query characters
    *
    * @param string $value
    * @return string
    */
    static public function escapePhrase($value)
    {
        $pattern = '/("|\\\)/';
        $replace = '\\\$1';

        return preg_replace($pattern, $replace, $value);
    }  
    /**
     * Check geo cordinates 
     * @param string $latitude
     * @param string $longitude
     * @return boolean | string
     */
    static public function isLatitudeLongitudeValid($latitude,$longitude)
    {
        $lat = floatval($latitude);
        $lon = floatval($longitude);
        if($lat < -90.0  && $lat > 90.0)
            return FALSE;
        
        if($lon < -180.0  && $lon > 180.0)
            return FALSE;
        
        return "$lat, $lon";       
    }
}
