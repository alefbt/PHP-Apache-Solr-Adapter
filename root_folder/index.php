<?
include_once 'bootstrap.php';


global $continue_ckeck;
$continue_ckeck = true;

function addTest($title,$testResult,$rightResult,$madatory=false)
{
    global $continue_ckeck;
    $out = '<li class="test"><span class="title">'.$title.'</span>';
    if(!$continue_ckeck)
    {
        $out .= '<span class="result skip">SKIPED</span>';
    }
    else
    {
        if($testResult!=$rightResult)
        {
            $out .= '<span class="result wrong">ERR';
            if($madatory)
            {
                $continue_ckeck=false;
                $out .= '!!!';
            }
            $out .= '</span>';
        }
        else
            $out .= '<span class="result right">OK</span>';
    }
    echo $out.'</li>';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>PHP Apache Solr Adapter</title>
        <style>
            body{
                font-family: arial;
                font-size: 10pt;
            }
            div.code{
                border: 1px #c0c0c0 solid;
                padding: 5px;
                margin: 5px;
                background-color: #f5f5f5;
            }
            div.bsd{
                position:absolute;
                left:0px;
                top:0px;
                z-index:-1;
                font-family: arial;
                font-size: 12px;
            }
            div.author{
                font-family: arial;
                font-size: 20px;
            }
            div.author > span{
                color: green;
            }
            .no-margin-bottom{
                margin-bottom: 0;
            }
            div.links{
                margin-bottom: 20px;
            }
            li.test{
                width:90%;
                margin: 5px;
            }
            li.test > span.title{
                float: left;
            }
            li.test > span.result{
                float: right;
                background: #f0f0f0;
                border: 1px solid #c0c0c0;
                padding-left: 2px;
                padding-right: 2px;
            }
            li.test > span.skiped{
                color: yellow;
            }
            li.test > span.right{
                color: green;
            }
            li.test > span.wrong{
                color: red;
            }     
            div.WORKS{
                font-size: 20px;
            }
            div.WORKS > span.KoSolr{
                color: green;
            }
            div.WORKS > span.KoSolr > span.rotkin{
                color: #a0a0a0;
            }
        </style>
    </head>
    <body>
        <div class="bsd" title="BSD = Be Siaat haDishmayya = With help of god ">B.S.D</div>
        <h1 class="no-margin-bottom">PHP Apache Solr Adapter</h1>
        <div class="author">Developed by <span>Yehuda-Daniel K.</span></div>
        <div class="links">Links : 
            <a href="https://github.com/Yehuda-Daniel/PHP-Apache-Solr-Adapter">GitHub</a>,
            <a href="http://wiki.korotkin.co.il/PHP_Apache_Solr_Adapter">Wiki</a>
            <a href="http://www.facebook.com/yehuda.il">Facebook</a>
            <a href="http://www.facebook.com/yehuda.il">linkedin</a>
        </div>
        <b>Must have SOLR Json Handler enabled <br/>See:<a href="http://wiki.apache.org/solr/UpdateJSON">UpdateJSON</a></b><br/><br/>
        The unit tests located at <div class="code"><?= dirname(dirname(__FILE__)) . '/tests_folder';?></div>
        Default Connection solr string <div class="code"><?= dirname(dirname(__FILE__)) . '/classes/kosolr.php';?></div>        
        Assume that solr schema is : 
        <div class="code">
&lt;schema name="Members core" version="1.1"&gt;&lt;types&gt;&lt;fieldtype name="string" class="solr.StrField" sortMissingLast="true" omitNorms="true"/&gt;&lt;/types&gt;&lt;fields&gt;&lt;!-- general --&gt;&lt;field name="id" type="string" indexed="true" stored="true" multiValued="false" required="true"/&gt;&lt;field name="type" type="string" indexed="true" stored="true" multiValued="false"/&gt;&lt;field name="name" type="string" indexed="true" stored="true" multiValued="false"/&gt;&lt;field name="core1" type="string" indexed="true" stored="true" multiValued="false"/&gt;&lt;/fields&gt;&lt;!-- field to use to determine and enforce document uniqueness. --&gt;&lt;uniqueKey&gt;id&lt;/uniqueKey&gt;&lt;!-- field for the QueryParser to use when an explicit fieldname is absent --&gt;&lt;defaultSearchField&gt;name&lt;/defaultSearchField&gt;&lt;!-- SolrQueryParser configuration: defaultOperator="AND|OR" --&gt;&lt;solrQueryParser defaultOperator="OR"/&gt;&lt;/schema&gt;
        </div>
        <ol>
        <?
            addTest('Check is class exists ?',class_exists('KoSolr'),true,true);
            
            $inst = KoSolr::getInstance();
            addTest('Check is server available ? (NOT IMPLEMENTED)',$inst->getServer()->is_available(),true,true);
            
            $server = $inst->getServer(); // Same as $server = new KoSolr_Server('SOLR_SERVER',8080,'/apache-solr-3.5.0','CORENAME');
            $server = new KoSolr_Server('solr',8080,'/apache-solr-3.5.0','members');
            addTest('Check is server object ? ',($server instanceof KoSolr_Server),true,true);

            $server->execute($server->create_delete_request('*:*'));
            $server->commit();
            $server->optimize();
            addTest('Delete all items',true,true);
            
            
            $doc = new KoSolr_Document();            
            $doc->id = "id TEST";
            $doc->name = 'xxx TEST';
            // You can add more fields of schena :
            // $doc->some_field_name = 'xxx'.$i;
            
            addTest('Creating new documet',(!$doc),false,true);
            
            $update_request = $server->create_update_request();
            addTest('Creating update request',(!$update_request),false,true);
            
            $update_request->add_document($doc);
            addTest('Adding document to request',true,true);
            
            $response = true;
            $test = true;
            try
            {                
                $response = $server->execute($update_request);                
            }  
            catch (KoSolr_Exception $ex)
            {                
                $test = false;
            }
            addTest('Sending request to solr',($test&&$response),true,true);
            $server->commit();
            $server->optimize();            
            addTest('Commit, Optimize',true,true);
            
            
            
            $search_request = $server->create_search_request();        

            $search_request->select('*')->equals('id', '"id TEST"');
            
            $test = true;
            $response = false;
            try
            {                
                $response = $server->execute($search_request);                
            }  
            catch (KoSolr_Exception $ex)
            {                
                $test = false;
            }
            addTest('Search',($test&&$response),true,true);
            echo '<li class="test"><div class="code">Response : <br/><pre>';
            echo print_r($response,true);
            echo '</pre></div></li>';
            
            addTest('Check response equals ',$response->response['docs'][0]['name'],'xxx TEST');
            
            
        ?>
        </ol>
        
            <?if($continue_ckeck):?>
        <div class="WORKS">Grate ! <span class="KoSolr">Ko<span class="rotkin">rotkin</span>Solr</span> is Works !</div>
            <?endif;?>
        
    </body>
</html>
