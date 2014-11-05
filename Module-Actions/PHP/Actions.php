<?php
    //require_once  dirname( __FILE__ )."/CreateComputedTrace.php";
    require_once dirname (dirname(dirname( __FILE__ )))."/Global.php" ;
    require_once $path_Trace;
    require_once $path_CreateComputedTrace;

if ($_POST['ActionName'] == "ForumActions" ){
 $trace = new Trace ($_POST['BaseURI'].$_POST['TraceName'].$_POST['ActionName']) ;
        if (! ($trace->exist()) ) 
        {
        
        $TypeObsel = array ("#Click-Lien-Categorie","#Click-Sur-Sujet","#resource-claroline_forum-create_message");
        $AttributeCond = array ("resource-read/hasTool_ResourceType");
        $attributeCondition[]=array('index'=>'1','value'=>'claroline_forum');
        $condition[]=array ('type'=>'#resource-read','attribute'=>$attributeCondition);
        $ComputedTraceAction = new CreateComputedTrace ($_POST['BaseURI'],$_POST['TraceName'].$_POST['ActionName'],$_POST['TraceName'],null);
        $ComputedTraceAction->CreateComputedTrace_sparql($TypeObsel,$AttributeCond,$condition);
        }
        exit;
       // CreateComputedTraceAction ($_POST['ActionName'],$TypeObsel,$AttributeCond,$condition);
}
if ($_POST['ActionName'] == "BlogActions" ){ 
$trace = new Trace ($_POST['BaseURI'].$_POST['TraceName'].$_POST['ActionName']) ;  
 if (! ($trace->exist()) ) 
 {
        $TypeObsel = array ("#resource-icap_blog-configure","#Click-Configurer-options");
        $AttributeCond = array ("resource-create/hasTool_ResourceType","resource-read/hasTool_ResourceType");
        $attributeCondition1[]=array('index'=>'1','value'=>'icap_blog');
        $condition[]=array ('type'=>'#resource-create','attribute'=>$attributeCondition1);
        $attributeCondition2[]=array('index'=>'2','value'=>'icap_blog');
        $condition[]=array ('type'=>'#resource-read','attribute'=>$attributeCondition2);
        $ComputedTraceAction = new CreateComputedTrace ($_POST['BaseURI'],$_POST['TraceName'].$_POST['ActionName'],$_POST['TraceName'],null);
        $ComputedTraceAction->CreateComputedTrace_sparql($TypeObsel,$AttributeCond,$condition);
      
      }  
      exit;
}
if ($_POST['ActionName'] == "QuizActions" ){
$trace = new Trace ($_POST['BaseURI'].$_POST['TraceName'].$_POST['ActionName']) ;
 if (! ($trace->exist()) ) 
 {
        $TypeObsel = array ("#resource-ujm_exercise-exercise_evaluated","#Click-Page-Quiz");
        $AttributeCond = array ("resource-read/hasTool_ResourceType");
        $attributeCondition1[]=array('index'=>'1','value'=>'Ujm_exercise');
        $condition[]=array ('type'=>'#resource-read','attribute'=>$attributeCondition1);
        $ComputedTraceAction = new CreateComputedTrace ($_POST['BaseURI'],$_POST['TraceName'].$_POST['ActionName'],$_POST['TraceName'],null);
        $ComputedTraceAction->CreateComputedTrace_sparql($TypeObsel,$AttributeCond,$condition);

   }     exit;
    }
if ($_POST['ActionName'] == "ClickLinkActions" ){
$trace = new Trace ($_POST['BaseURI'].$_POST['TraceName'].$_POST['ActionName']) ;
 if (! ($trace->exist()) ) 
 {
        $TypeObsel = array ("#Click-Lien");
        $AttributeCond=array();
        $condition=array();
        $ComputedTraceAction = new CreateComputedTrace ($_POST['BaseURI'],$_POST['TraceName'].$_POST['ActionName'],$_POST['TraceName'],null);
        $ComputedTraceAction->CreateComputedTrace_sparql($TypeObsel,$AttributeCond,$condition);
       
        }
         exit;
    }
    
if ($_POST['ActionName'] == "ClickButtonActions" ){
 $trace = new Trace ($_POST['BaseURI'].$_POST['TraceName'].$_POST['ActionName']) ;
     if (! ($trace->exist()) ) 
     {
        $TypeObsel = array ("#Click-Button");
        $AttributeCond=array();
        $condition=array();
       $ComputedTraceAction = new CreateComputedTrace ($_POST['BaseURI'],$_POST['TraceName'].$_POST['ActionName'],$_POST['TraceName'],null);
        $ComputedTraceAction->CreateComputedTrace_sparql($TypeObsel,$AttributeCond,$condition);
     }  exit;
    }

if ($_POST['ActionName'] == "GoogleActions" )
    {
    $trace = new Trace ($_POST['BaseURI'].$_POST['TraceName'].$_POST['ActionName']) ;
      if (! ($trace->exist()) ) 
      {
        $TypeObsel = array ("#Click-lien-google","#Recherche-Google");
        $AttributeCond=array();
        $condition=array();
        $ComputedTraceAction = new CreateComputedTrace ($_POST['BaseURI'],$_POST['TraceName'].$_POST['ActionName'],$_POST['TraceName'],null);
        $ComputedTraceAction->CreateComputedTrace_sparql($TypeObsel,$AttributeCond,$condition);
      
        }
          exit;
    }
if ($_POST['ActionName'] == "You-TubeActions" )
    {
    $trace = new Trace ($_POST['BaseURI'].$_POST['TraceName'].$_POST['ActionName']) ;
     if (! ($trace->exist()) ) 
     {
       $TypeObsel = array ("#Abonner-Video","#Annuler-Abonnement","#dislike-video","#Aime-Video");
       $AttributeCond=array();
       $condition=array();
       $ComputedTraceAction = new CreateComputedTrace ($_POST['BaseURI'],$_POST['TraceName'].$_POST['ActionName'],$_POST['TraceName'],null);
       $ComputedTraceAction->CreateComputedTrace_sparql($TypeObsel,$AttributeCond,$condition);
     
        
        }
        exit;
    }
    
?>
