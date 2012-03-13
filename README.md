PHP Apache Solr Adapter
=======================

Faster way search with solr 
in advance way.


You also invited to my [Wiki][wik]


Installation
-----------

Put the files at classes dir (See bootstrap how it supposed to load the classes)

Configure `classes/KoSolr.php`: 
    private $_connection_configurations = array(
        'default-solr-configuration'=>array(
            'server'=>'solr',
            'port'=>8080,
            'app_path'=>'/apache-solr-3.5.0',
            'core'=>'members'
        ),
    );
    

Code Examples
-----------

Create Server Instance :
    
    $instance_KoSolr = KoSolr::getInstance();
    $KoSolr_Server_Instance = $instance_KoSolr->getServer();
    // OR //
    $KoSolr_Server_Instance = new KoSolr_Server('SOLR_SERVER',8080,'/apache-solr-3.5.0','CORENAME');

Delete (all=*:*) Documents
    
    $KoSolr_Server_Instance->execute($KoSolr_Server_Instance->create_delete_request('*:*'));
    
Adding/Updating document (note: each 1000 docs it will auto send them)

    $doc = new KoSolr_Document();            
    $doc->id = "id TEST";
    $doc->name = 'xxx TEST';
    $doc->Some_FIELD_NAME_OF_SOLR = 'some thing';
    $update_request = $KoSolr_Server_Instance->create_update_request();
    $update_request->add_document($doc);
    $response = $KoSolr_Server_Instance->execute($update_request);   

Search on solr :

    $search_request = $KoSolr_Server_Instance->create_search_request();  
    $search_request->select('*')->equals('id', '"id TEST"');
    $search_request->select('field1,field2,etc')
            ->equals('field1', 'should be equle')
            ->query('search string')
            ->limit(100)
            ->offset(100)
            ->setSort('field1 ASC');

    $search_request->fl='object_id';
    $search_request->defType='dismax';
    $search_request->qf = 'activity_title^100 high^50 medium^5 low^0.01';   

    $response = $server->execute($search_request);
    
    $response->response['docs'] <-- This is results

Commit Optimize

    $KoSolr_Server_Instance->commit();
    $KoSolr_Server_Instance->optimize();    


[wik]: http://wiki.korotkin.co.il/PHP_Apache_Solr_Adapter