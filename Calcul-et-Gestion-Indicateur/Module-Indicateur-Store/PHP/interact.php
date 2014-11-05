<?php 

require_once dirname (dirname(dirname(dirname( __FILE__ ))))."/Global.php" ;
require_once $path_Trace;
require_once $path_ComputedTrace;
require_once $path_TraceModel;
require_once $path_GenerateModel;
require_once $path_CreateComputedTrace;
require_once $path_gestion_indicateur;
require_once $path_eval_math; 
require_once $path_IndicatorStore; 

if ($_POST){

if($_POST['reason'] == 'getStore'){

    

	$id = $_POST['id'];

	$store = new Indicator_Store($_POST['trace']);
	$ind = $store->getIndicator($id);

	$manager = new Indicator_Manager($_POST['trace']);
    
	$res = $manager->addFromStore($ind);
	echo json_encode($res);

}
if($_POST['reason'] == 'listall'){

	$st = new Indicator_Store($_POST['trace']);
	$ind = $st->getAll();

	echo json_encode($ind);

	

}


if($_POST['reason'] == 'getToShow'){

	$st = new Indicator_Store($_POST['trace']);
	$ind = $st->getToShow($_POST['id']);

	echo json_encode($ind);

	

}

if($_POST['reason'] == 'deletefromstore'){

	$st = new Indicator_Store($_POST['trace']);

	$id = $_POST['id'];

	$st->Delete($id);

}

if($_POST['reason'] == 'search'){

	$st = new Indicator_Store($_POST['trace']);

	$keys = $_POST['Key'];
	$field = $_POST['field'];
	$res = $st->search($field,$keys);

	echo json_encode($res);

}

}



?>
