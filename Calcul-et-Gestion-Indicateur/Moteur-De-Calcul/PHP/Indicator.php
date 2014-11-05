<?php 
    require_once dirname (dirname(dirname(dirname( __FILE__ ))))."/Global.php" ;
    require_once $path_Trace;
    require_once $path_ComputedTrace;
    require_once $path_TraceModel;
    require_once $path_GenerateModel;
    require_once $path_CreateComputedTrace;
    require_once $path_gestion_indicateur;
    require_once $path_eval_math;


class Indicator{

	public $name;
	public $equation ;
	public $author ;
	public $trasformation = null;
	public $description = null;
	public $TraceOrigin ;
	public $ComputedTrace = null;
	public $output = null;

	function __construct($trace_uri){

		$this->TraceOrigin[] = new Trace($trace_uri);
	}

	function classification($after = null,$before = null){

		// $tr = $this->TraceOrigin[0];
	 // 	$comp = new ComputedTrace($tr->getBaseUri(),"classification".$tr->getName());
	 // 	$comp->config('filter',$this->TraceOrigin);
	 // 	$comp->setFilterParameter($after,$before);
	 // 	if($comp->exist()){

	 // 		$comp->Delete();
	 // 	}
	 // 	$comp->filter();
	 	$this->ComputedTrace = $this->TraceOrigin[0];

	 	$stat = $this->stat();
	 	$this->output = array();
	 	foreach ($stat as $key => $value) {
	 		$this->output[] = array("label" => $key , "val" => $value);
	 	}
	 	return $this->output;
	}

	function balance($after = null, $before = null, array $types){

		$tr = $this->TraceOrigin[0];
	 	$comp = new ComputedTrace($tr->getBaseUri(),"cmt".$tr->getName());
	 	$comp->config('filter',$this->TraceOrigin);
	 	$comp->setFilterParameter($after,$before,$types);
	 	if($comp->exist()){

	 		$comp->Delete();
	 	}

	 	$comp->filter();
	 	$this->ComputedTrace = $comp;
	 	$stat = $this->stat();
	 	$keys = $types;
	 	if($stat[$keys[1]] + $stat[$keys[0]] != 0){

	 		$res = ($stat[$keys[0]] -  $stat[$keys[1]]) / ($stat[$keys[1]] + $stat[$keys[0]]);
	 		$this->output = array("variale" => "X","val" => $res);
	 		return $this->output;
	 	}
	 	else{

	 		$this->output = array("Error" => "Zero Division");
	 		return $this->output;
	 	}

	 	// return $stat;
	}

	function stat(){

		$comp = $this->ComputedTrace;
		$sparql = 'SELECT ?typeA (COUNT ( distinct ?sobs0 ) AS ?num_sobs0) WHERE 
					 { 
					 ?sobs0 rdf:type ?typeA 
					
					 }GROUP BY ?typeA
					';
					$sparql = str_replace("\n", "", $sparql);
					$sparql = str_replace("\t", "", $sparql);
					$sparql = str_replace(" ", "%20", $sparql);

		$stat = array();

		$reponse = RestfulHelper::getInfo($comp->getUri()."@obsels?query=".$sparql);
		$rep = json_decode($reponse,true);
		$res = $rep['results'];
		$resultat = $res['bindings'];
		foreach ($resultat as $key => $value) {
			$n = $value['num_sobs0'];
			$nombre = $n['value'];
			$t = $value['typeA'];
			$type = $t['value'];
			if(strstr($type,"http://liris.cnrs.fr/silex/2009/ktbs#") ){

				continue;
			}

				$a = explode("#", $type);
				$stat[$a[1]] = intval($nombre);

		}


		return $stat;
	}

	function vAa($json){

		// verify And add 

		$trace = $this->TraceOrigin[0];
		$NewJson = $json;

		$input = $NewJson["input"];

		foreach ($input as $key => $value) {
			$value["operation"] = urlencode($value["operation"]);
			$input[$key] = $value;
		}
		$NewJson["input"] = $input;

		$name = $NewJson["Name"];

        
		$T = $NewJson["Transformation"];

		$i = 0;
		$b = $trace->getBaseUri();
		if (!empty ($T))
		{
		foreach ($T as $key => $value) {
			$tname = str_replace(' ', '_', $this->name." $i");
			$value["ComputedTraceURI"] = $b.$tname;
			$T[$key] = $value;
			$i++;
		}
		}

		$NewJson["Transformation"] = $T;


		$trace = $this->TraceOrigin[0];
		$manager = new Indicator_Manager($trace->getUri());

		$manager->addIndicator($NewJson);
	}

	function generatefromJson($json){
		$trace = $this->TraceOrigin[0];

		$this->name = $json["Name"];

		$formule = $json["formule"];
		$this->formule = $json["formule"];

		$input = $json["input"];
		$variable = array();


		$transformation = $json["Transformation"]; 

		// verification si la dernière transformation est faite ou non !!
		// si oui pas la peine de refaire toutes les transformation. avec KTBS les obsels se propagent !!!cool non ? 
		// si non on fait les transformations. 
		$s = sizeof($transformation) -1 ;
		$tname = str_replace(' ', '_', $this->name." $s");
		$b = $trace->getBaseUri(); 
		$trverif = new Trace($b.$tname); 
		$c = $json["createdOn"];
		if (isset($json['from'])){$f = $json['from'];}

		$encode = true;



		if(!$trverif->exist()){

			$encode = false;

			$this->vAa($json);

			$k = 0;

			$models = array();
			if($transformation == null && $c == null ){

				$encode = false;
				goto notransformation ; 
			}
			if($transformation == null && $c != null ){

				$encode = true;
				goto notransformation ; 
			}

			if($f != null){

				$encode = true;
			}

			foreach ($transformation as $key => $value) {
				$trans[] = $value;
				
				$method = $value["method"];
				$transnum = str_replace(' ', '_', $this->name." $k");

				if($method == 'sparql'){

					$condition = array();
					$AttributeCond = array();
					$TypeObsel = array();
					$param = $value["Parameter"];
					$before = $param["before"];
					$after = $param["after"];
					$OTypes = $param["ObselType"];
					$i = 1;
					if ($OTypes != null && sizeof($OTypes) != 0) {
						foreach ($OTypes as $key => $value) {
							if (isset($value["obselTypeId"])){ $t = $value["obselTypeId"];}
							else $t = null;
							if (isset($value["attribute"])) {$attribute = $value["attribute"];}
							else $attribute = null;
							if($attribute != null && $attribute !="null"){

								foreach ($attribute as $key => $value) {
								
									$AttributeCond[] = str_replace("#", "", $value["nameAttributeId"]);
									$constraint = $value["constraint"];
									
									if($constraint != null && $constraint != "null"){

										foreach ($constraint as $key => $value) {

											$AttributeCondition = array();
											$AttributeCondition[] = array("index" => "$i","value"=>$value);
											$condition[] = array('type' => $t , 'attribute'=>$AttributeCondition);
											$i++;
										}

									}
									else{

										$TypeObsel[] = $t;
									}


								}
							}

							else{

								$TypeObsel[] = $t;
							}
						
						}
					}
					else{
						continue;
					}
					$ComputedTraceAction = new CreateComputedTrace ($trace->getBaseUri(),$transnum,$trace->getName(),null);
					if(empty($AttributeCond)){

						$ComputedTraceAction->CreateComputedTrace_sparql($TypeObsel,$AttributeCond,$condition);

					}
					else{

						$ComputedTraceAction->CreateComputedTrace_sparql2($TypeObsel,$AttributeCond,$condition);

					}
						$trace = new Trace($ComputedTraceAction->getUri());
						$reponse = RestfulHelper::getInfo($trace->getUri());

					while ($reponse == null) {
						
						$reponse = RestfulHelper::getInfo($trace->getUri());
					}



					$k++;
					// method sparql automate
				}
				if($method == 'filter'){
					// method filter automate
				}
				if($method == 'fusion'){
					// method fusion automate
				}
			}
		}

		else{

			$trace = $trverif;
				
		}


		// for ($i=0; $i < $s ; $i++) { 

		// 		$tname = str_replace(' ', '_', $this->name." $i");
		// 		$trr = new Trace($b.$tname);
		// 		$trr->getObsels();

		// 	}


		notransformation: 
		$this->ComputedTrace = $trace;
		$reponse = RestfulHelper::getInfo($trace->getUri());

		while ($reponse == null ) {

			sleep(2);

			$reponse = RestfulHelper::getInfo($trace->getUri());
		}


		$values = array();

		foreach ($input as $key => $value) {
			$sparql = $value["operation"];

			if($encode == false){

				$sparql = urlencode($sparql);
			}

			

			$URL = $trace->getUri()."@obsels?query=".$sparql;

			$r =  RestfulHelper::httpInfo($URL); 
			while ($r == "409") {
				sleep(1);
				$r =  RestfulHelper::httpInfo($URL); 
			}


			$reponse = RestfulHelper::getInfo($URL); 
			$rep = json_decode($reponse,true);

			$res = $rep['results'];
			$resultat = $res['bindings'];
			$val = $resultat[0];

			$keys = array_keys($val);
			$a = $val[$keys[0]];

			$va = $a["value"];


			// if (gettype(intval($va)) != "integer"){

			// 	$va = 0;
			// }

			$values[$value['name']] = $va;


		}
		$f = $formule;
		foreach ($values as $key => $value) {
			$f = str_replace($key, $value, $f);
		}

		
		$m = new EvalMath;
		$m->suppress_errors = true;
		$equation = explode("=", $f);
		$res=$m->evaluate($equation[1]);
		return array("var" => $equation[0] , "val" => $equation[1]);


		if($res == false){

			$trace = $this->TraceOrigin[0];
			$manager = new Indicator_Manager($trace->getUri());	
			$manager->deleteIndicator($this->name);
			return array("var" => $equation[0] , "val" => "ERROR : something went wrong.");
		
		}
		else{

			return array("var" => $equation[0] , "val" => $res);
		}
	}

	function refresh($json , $after = null, $before = null){

		$trace = $this->TraceOrigin[0];

		$this->name = $json["Name"];

		$formule = $json["formule"];
		$this->formule = $json["formule"];

		$input = $json["input"];
		$variable = array();

		$transformation = $json["Transformation"]; 


		// verification si la dernière transformation est faite ou non !!
		// si oui pas la peine de refaire toutes les transformation. avec KTBS les obsels se propagent !!!cool non ? 
		// si non on fait les transformations. 
		$s = sizeof($transformation) -1 ;
		$tname = str_replace(' ', '_', $this->name." $s");
		$b = $trace->getBaseUri(); 
		$trverif = new Trace($b.$tname); 
		$c = $json["createdOn"];

		$encode = true;



		if(!$trverif->exist()){

			$encode = false;


			$k = 0;

			$models = array();
			if($transformation == null && $c == null ){

				$encode = false;
				goto notransformation ; 
			}
			if($transformation == null && $c != null ){

				$encode = true;
				goto notransformation ; 
			}

		}

		else{

			$trace = $trverif;
			
		}

		notransformation: 
		$this->ComputedTrace = $trace;
		$reponse = RestfulHelper::getInfo($trace->getUri());

		while ($reponse == null ) {

			sleep(2);

			$reponse = RestfulHelper::getInfo($trace->getUri());
		}


		$values = array();

		// $cond = array();

		// 	if($after != null){

		// 		$time =  strtotime($after)*1000;
		// 		$cond[] = 'minb='.$time;

		// 	}
		// 	if($before != null){

		// 		$time =  strtotime($before)*1000;
		// 		$cond[] = 'maxb='.$time;

		// 	}

		// 	$times = implode('&', $cond);

		$Tcomp = new ComputedTrace($trace->getBaseUri(),'time'.str_replace(' ', '_', $this->name));
		if($Tcomp->exist()){
			$Tcomp->Delete();
		}
		$Tcomp->config('filter',array($trace));
		$Tcomp->setFilterParameter($after,$before);
		$Tcomp->filter();

		$trace 	= new Trace($Tcomp->getUri());

		foreach ($input as $key => $value) {
			$sparql = $value["operation"];

			if($encode == false){

				$sparql = urlencode($sparql);
			}
			
			$URL = null;
			// if(strlen($times) == 0 ){

				$URL = $trace->getUri()."@obsels?query=".$sparql;
			// }
			// else{

			// 	$URL = $trace->getUri()."@obsels?".$times."&query=".$sparql;
			// }

			

			$r =  RestfulHelper::httpInfo($URL); 
			while ($r == "409") {
				sleep(1);
				$r =  RestfulHelper::httpInfo($URL); 
			}


			$reponse = RestfulHelper::getInfo($URL); 
			$rep = json_decode($reponse,true);

			$res = $rep['results'];
			$resultat = $res['bindings'];
			$val = $resultat[0];

			$keys = array_keys($val);
			$a = $val[$keys[0]];

			$va = $a["value"];


			if (gettype(intval($va)) != "integer"){

				$va = 0;
			}

			$values[$value['name']] = $va;


		}
		$f = $formule;
		foreach ($values as $key => $value) {
			$f = str_replace($key, $value, $f);
		}

		$m = new EvalMath;
		$m->suppress_errors = true;
		$equation = explode("=", $f);
		$res=$m->evaluate($equation[1]);


		$Tcomp->Delete();




		if($res == false){

			return array("var" => $equation[0] , "val" => "ERROR : Time intervale caused an error");
		}
		else{

			return array("var" => $equation[0] , "val" => $res);
		}
	}

}



function testind(){

	// $ind = new Indicator("http://localhost:8001/Nice1/t03/");
	// $file = "indic.json"; 
	// $JS = file_get_contents($file);
	// $json = json_decode($JS,true);

	// $ind->generatefromJson($json);


	// $Ind = new Indicator("http://localhost:8001/Nice1/trace1/");
	// $TypeObsel = array ("Ouverture_Page","Click-Lien");
	// $res = json_encode($Ind->balance('16-04-2014','17-04-2014',$TypeObsel));
	// $res = json_encode($Ind->classification());
	// var_export($res);
	$man = new Indicator_Manager('http://localhost:8001/Nice1/t03/');

	$ind = $man->getIndicator('Recherche Google');

	$I = new Indicator('http://localhost:8001/Nice1/t03/');

	$res = $I->refresh($ind,'04-04-2014');
	var_export($res);
} 

// testind();






?>
