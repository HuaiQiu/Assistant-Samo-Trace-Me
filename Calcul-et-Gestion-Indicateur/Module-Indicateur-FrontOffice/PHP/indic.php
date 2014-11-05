<?php 
    require_once dirname (dirname(dirname(dirname( __FILE__ ))))."/Global.php" ;
require_once $path_Moteur_de_Calcul;
require_once $path_gestion_indicateur;
require_once $path_IndicatorStore; 


if (isset($_POST['reason']))
{
if($_POST['reason'] == 'list'){


	if (isset($_POST['trace'])) {$manager = new Indicator_Manager($_POST['trace']);}

	$a = $manager->getIndicators();
	$res = json_encode($a);
	echo $res;

	
}
if($_POST["reason"] =='addindic'){
	
	if (isset($_POST['data'])) {$ind = $_POST['data'];}

	if (isset($_POST['trace'])) {  $indic = new Indicator($_POST['trace']);}
	$res = $indic->generatefromJson($ind);
	echo json_encode($res["val"]);
	

}

if($_POST['reason'] == 'getin'){

	if (isset($_POST['nameind'])) {$name = $_POST['nameind'];}

	if (isset($_POST['trace'])) {$manager = new Indicator_Manager($_POST['trace']);}
	$a = $manager->getIndicator($name);
	
	if (isset($_POST['trace'])) 
	{
	
	$ind = new Indicator($_POST['trace']);
	}

	$res = $ind->generatefromJson($a);
	$r = json_encode($res);
	//$r = json_encode($a);

	echo $r;

}

if($_POST['reason'] == 'deleteInd'){

	if (isset($_POST['nameind'])) {$name = $_POST['nameind'];}
    if (isset($_POST['trace']))  {$manager = new Indicator_Manager($_POST['trace']);}
	$manager->deleteIndicator($name);	


}


if($_POST['reason'] == 'getInfo'){

	if (isset($_POST['nameind'])) {$name = $_POST['nameind'];}
    if (isset($_POST['trace'])){ $manager = new Indicator_Manager($_POST['trace']);}
	$ind = $manager->getIndicator($name);	
	echo json_encode($ind);


}


if($_POST['reason'] == 'refresh'){

	if (isset($_POST['nameind'])) {$name = $_POST['nameind'];}
	if (isset($_POST['after']))   {$after = $_POST['after'];}
	if (isset($_POST['before'])) {$before = $_POST['before'];}

	if (isset($_POST['trace'])) {$manager = new Indicator_Manager($_POST['trace']);}
	$a = $manager->getIndicator($name);
	if (isset($_POST['trace'])) {$ind = new Indicator($_POST['trace']);}

	$res = $ind->refresh($a,$after,$before);
	$r = json_encode($res);


	echo $r;

}

if($_POST['reason'] == 'share'){

if (isset($_POST['trace'])){
	$store = new Indicator_Store($_POST['trace']);
	$manager = new Indicator_Manager($_POST['trace']);
if (isset($_POST['nameind'])){$ind = $manager->getIndicator($_POST['nameind']);}

	$res = $store->addToStore($ind);}
	echo json_encode($res);

}


if($_POST['reason'] == 'getForView'){

	if (isset($_POST['trace'])) {$manager = new Indicator_Manager($_POST['trace']);}
    if (isset($_POST['nameind'])) {$ind = $manager->getIndicatorForView($_POST['nameind']);}

	echo json_encode($ind);

}


}


?>
