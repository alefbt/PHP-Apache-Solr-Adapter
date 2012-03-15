<?php

/**
 * B.S.D.
 *
 * Created by Yehuda-Daniel Korotkin
 * Date: Mar 6, 2012
 * Time: 6:08:31 PM
 */

class KoSolr_Server_Request_Search extends KoSolr_Server_Request
{
    /**
     * Setting default location field for distnace filter
     * @see KoSolr_Server_Request_Search::setLocation($Lat,$Lon)
     */
    const DEFAULT_LOCATION_FIELD = 'location';
    /**
     * Setting default distance to filter
     * @see KoSolr_Server_Request_Search::setLocation($Lat,$Lon)
     */    
    const DEFAULT_LOCATION_DISTANCE = 160;
    
    /**
     * 
     *@see http://wiki.apache.org/solr/QueryParametersIndex 
     */
    
    /**
     *Constructor 
     */
    public function __construct() {
        parent::__construct();
        $this->select('*');
    }

    /**
     * Select like in sql
     * Selecting fields  
     * @see http://wiki.apache.org/solr/CommonQueryParameters#fl
     * @param string $fileds
     * @return \KoSolr_Server_Request_Search 
     */
    public function select($fields)
    {
        $this->fl = $fields;
        return $this;                
    }
    /**
     * add to where syntext
     * @param string $field
     * @param string $value
     * @param bool $equal
     * @return \KoSolr_Server_Request_Search 
     */
    public function equals($field,$value,$equal = true)
    {        
        return $this->addFilterQuery($this->create_filterQuery($field, $value,$equal));
    }
    /**
     * Creating filter query text
     * @param string $field
     * @param string $value
     * @param bool $equal
     * @return string 
     */
    private function create_filterQuery($field,$value,$equal = true)
    {
        $value = (is_bool($value))? ($value?'true':'false') : $value;
        return ($equal?'':'-') . "$field:$value";        
    }
    /**
     * Remove filter query
     * @param string $field
     * @param string $value
     * @param bool $equal
     * @return bool
     */
    public function equals_remove($field,$value,$equal = true)
    {
        return $this->removeFilterQuery($this->create_filterQuery($field, $value,$equal));
    }

    /**
     * Must to define sfield and distance
     * Like : dq={!geofilt}&sfield=store&pt=45.15,-93.85&d=5
     * @see http://wiki.apache.org/solr/SpatialSearch
     * @param string $Lat
     * @param string $Lon
     * @return \KoSolr_Server_Request_Search 
     */
    public function setLocation($Lat,$Lon)
    {
        $this->addFilterQuery('{!geofilt}');     
        $this->pt = "$Lat,$Lon";
        
        // Default distnace if not setted 
        if(!isset($this->d))
        {
            $this->d = KoSolr_Server_Request_Search::DEFAULT_LOCATION_DISTANCE;
        }
        
        if(!isset($this->sfield))
        {
            $this->sfield = KoSolr_Server_Request_Search::DEFAULT_LOCATION_FIELD;
        }
                
        return $this;
    }
    /**
     * Setting distance
     * Must use set location
     * @see \KoSolr_Server_Request_Search::setLocation($Lat,$Lon)
     * @param int|string $distance
     * @return \KoSolr_Server_Request_Search 
     */
    public function setDistance($distance)
    {
        $this->d = $distance;
        return $this;
    }
    /**
     * Set location field
     * @param string $field
     * @return \KoSolr_Server_Request_Search 
     */
    public function setLocationField($field)
    {
        $this->sfield = $field;
        return $this;
    }

    /**
     * adding filter query
     * @param type $value
     * @return \KoSolr_Server_Request_Search 
     */
    public function addFilterQuery($value)
    {
        if(!isset($this->fq))
            $this->fq = array();
                
        if(is_array($this->fq))            
            foreach($this->fq as $fq)
            {
                if($fq == $value)
                    return $this;

            }
        $this->fq = array_merge($this->fq, array($value));

        return $this;
    }    
    /**
     * Remove filter query
     * @param strung $value
     * @return boolean 
     */
    public function removeFilterQuery($value)
    {
        if(!isset($this->fq))
            return false;        
        
        $new_arr = array();
        foreach ($this->fq as $val)
            if( $val != $value)
                $new_arr[]=$val;
            
        
        $this->fq=$new_arr;           
        return false;
    }
    /**
     * create where syntext (Resets the query)
     * @param string $field
     * @param string $value
     * @param boolean $equal
     * @return \KoSolr_Server_Request_Search 
     */
    public function where_reset()
    {
        $this->fq = array();                
        return $this;
    }
    /**
     * Sort fields like 'price desc, name asc'
     * @see http://wiki.apache.org/solr/CommonQueryParameters#sort
     * @param string $field
     * @return \KoSolr_Server_Request_Search 
     */
    public function order_by($field)
    {
        $this->sort = $field;
        return $this;
    }
    /**
     * Used for pagination
     * @param int $rows
     * @param int|null $offset
     * @return \KoSolr_Server_Request_Search 
     */
    public function limit($rows,$offset=null)
    {
        $this->rows = $rows;
        
        if($offset)
            $this->offset($offset);
        
        return $this;
        
    }
    /**
     * set maximum rows per request
     * @param int $offset
     * @return \KoSolr_Server_Request_Search 
     */
    public function offset($offset=500)
    {
        $this->start = $offset;
        return $this;
    }
    /**
     * Set the query string
     * @param type $QueryString
     * @return \KoSolr_Server_Request_Search 
     */
    public function query($QueryString)
    {
        $this->q = KoSolr_Utils::escapePhrase( $QueryString );
        return $this;
    }
    /**
     * Setting query string
     * @param string $query 
     */
    public function setQuery($query)
    {
        return $this->query($query);              
    }   
    /**
     * Set start rows like in mysql
     * @see http://wiki.apache.org/solr/CommonQueryParameters#start
     * @param int $start 
     */
    public function setStart($start)  
    { 
        return $this->offset($start);
    }
    /**
     * Set start rows like in mysql limit
     * @see http://wiki.apache.org/solr/CommonQueryParameters#rows
     * @param int $rows 
     */
    public function setRows($rows=500)  
    { 
        $this->limit($rows);
    }    
    /**
     * Adding field to select
     * @see KoSolr_Server_Request_Search::select
     * @param type $field
     * @return \KoSolr_Server_Request_Search 
     */
    public function addField($field)
    { 
        
        $this->select(
                implode(',',
                        array_merge(
                            explode(',', $this->fl), 
                            array($field)
                        )
                )
        );
              
        return $this;
        
    } 

    /**
     * Setting sort
     * @see KoSolr_Server_Request_Search::order_by
     * @param type $sort_string
     * @return \KoSolr_Server_Request_Search 
     */
    public function setSort($sort_string)
    {
        return $this->order_by($sort_string);
    }
    /**
     * Enable/disables facets
     * @param bool $enable
     * @return \KoSolr_Server_Request_Search 
     */
    public function setFacet($enable)
    {
        if($enable)
            $this->facet='on';
        else
            $this->facet='off';
        
        return $this;
    }
    /**
     * setting the 
     * @see http://wiki.apache.org/solr/SimpleFacetParameters
     * @param type $field
     * @return \KoSolr_Server_Request_Search 
     */
    public function addFacetField($field)
    {
        $this->_request_data['facet.field'] =$field;
        return $this;
    }
    /**
     * Setting the minimum count
     * @see http://wiki.apache.org/solr/SimpleFacetParameters
     * @param type $min 
     */
    public function setFacetMinCount($min)
    {
        $this->_request_data['facet.mincount'] = array( $min );
        return $this;
                
    }
}

?>
