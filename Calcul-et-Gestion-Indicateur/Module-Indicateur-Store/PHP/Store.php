<?php 
    require_once dirname (dirname(dirname(dirname( __FILE__ ))))."/Global.php" ;
    require_once $path_Trace;
    require_once $path_ComputedTrace;
    require_once $path_TraceModel;
    require_once $path_GenerateModel;
    require_once $path_CreateComputedTrace;
    require_once $path_gestion_indicateur;
    require_once $path_eval_math; 



    class Indicator_Store{


    	function __construct($trace_uri){


    		$system = new MongoClient();

			// sélection d'une base de données
			$database = $system->ManagmentSystem;

			// sélectionne une collection (analogue à une table de base de données relationnelle)
			$this->StoreDB = $database->StoreDB;

			$this->trace = new Trace($trace_uri);
			$this->user = $this->trace->getBaseName();
			
    	}

        function getToShow($id){

            $ind = $this->StoreDB->findOne(array('_id' => $id));

            unset($ind['_id']);
            unset($ind['owner']);
            unset($ind['oldId']);
            unset($ind['from']);
            unset($ind['createdOn']);

            $input = $ind["input"];
            $newInput = array();
            foreach ($input as $key => $value) {
                
                $sparql = $value['operation'];

                $s = urldecode($sparql);

                $value["operation"] = htmlentities(str_replace('MODELREPLACE',$this->trace->getModel(), $s));

                $newInput[] = $value;


            }

            $ind["input"] = $newInput;



            return $ind;
        }


    	function addToStore($ind){


    		$curs = $this->StoreDB->findOne(array("Name" => $ind['Name'] , "owner" => $this->user));

            $curs2 = null;
            if(isset ($ind['oldId']) ){

                $curs2 = $this->StoreDB->findOne(array('oldId' => $ind['oldId']));
            }

    		
    		if($curs == null && $curs2 == null){

    			$userid = $ind['_id'];

				$ind['_id'] = uniqid();


                if( isset ($ind['oldId']) ){

                    $ind['oldId'] = $userid;
                
                }

				$input = $ind["input"];
				$newInput = array();
				foreach ($input as $key => $value) {
				
					$sparql = $value['operation'];

					$s = urldecode($sparql);

					$value["operation"] = urlencode(str_replace($this->trace->getModel(), 'MODELREPLACE', $s)); 


					$newInput[] = $value;


			}
			$ind['input'] = $newInput;


				$this->StoreDB->insert($ind);


                return array("Mess" => "Shared");
	    	

    		}
    		else {

    			return array("Mess" => "already shared" );
    		}
    	}
			


    	function Delete($id){


    		$ind = $this->StoreDB->findOne(array('_id' => $id));

    		if($ind != null){

    			if($ind['owner'] == $this->user){

    				$this->StoreDB->remove(array('_id' => $id));

    			}
    			else{

    				return array("ERROR" => "PERMISION DENIED");
    			}
    		}

    		
    	}

    	function getIndicator($id){

    		$doc = $this->StoreDB->findOne(array('_id' => $id));
    		return $doc;

    	}

    	function getAll(){
            $manager = new Indicator_Manager($this->trace->getUri());

    		$cursor = $this->StoreDB->find();
			$res = array();

			foreach ($cursor as $document){

                if($manager->exist($document["Name"])){

                    $document['got'] = "yes";
                }
                else{

                     $document['got'] = "no";

                }

			   $res[] =  $document;
			}

			return $res;

    	}


    	function search($field,$var){

          


    		$cursor = $this->StoreDB->find(array($field => array('$regex'=> $var)));
			$res = array();

			foreach ($cursor as $document){

			   $res[] =  $document;
			}

			return $res;


    	}






    	}
    
?>
