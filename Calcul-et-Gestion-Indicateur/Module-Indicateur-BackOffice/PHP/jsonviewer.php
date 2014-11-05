<?php
    require 'common.php';
    /*
        a part of this class is from  JSON Viewer 1.6 : Copyright (c) 2008-2009, Hay Kranen <http://www.haykranen.nl/projects/jsonviewer/>
    */

    function make_tree($var,$ID_Transf) {
        global $tree;
        global $script;
         
        
        foreach ($var as $key => $value) {
    
        if (is_array($value)) {
        
                // Check if the value is empty, show 'empty' arrow then
                if (empty($value)) {
                    $arrow = "arrow_open";
                    $title = "This node has no children";
                    $class = 'arrow empty';
                } else {
                    $arrow = "arrow";
                    $title = "Click on the arrow to view its children";
                    $class = 'arrow children';
                }
                
                if (is_numeric($key)){
                $tree .= '<li><ul>';
                make_tree($value,null);
                }
                else if ($key==="graph_model_Source"){
                    $tree .= '<li><img src="images/' . $arrow . '.png" class="' . $class .'" ' .
                              'alt="+" title="' . $title .'" />'.$key."\t<ul>";
                    $ID_DIV='source_Model'.$ID_Transf;
                    $ID_Model='Msource_Model'.$ID_Transf;
                    $tree .= '<li><div id="'.$ID_DIV.'"></div></li> <br></ul>';
                    $script .= '<script>'.$ID_Model.'= new Samotraces.KTBS.Model('.JSON_encode($value).');'
                                .$ID_DIV.'= new Samotraces.UI.Widgets.DisplayModel ("'.$ID_DIV.'",'.$ID_Model.',options);</script>';
                    }
                else if ($key==="graph_model_Resultat"){
                    $tree .= '<li><img src="images/' . $arrow . '.png" class="' . $class .'" ' .
                              'alt="+" title="' . $title .'" />'.$key."\t<ul>";
                    $ID_DIV='Resultat_Model'.$ID_Transf;
                    $ID_ModelR='Mresultat_Model'.$ID_Transf;
                    $tree .= '<li><div id="'.$ID_DIV.'"></div></li> <br></ul>';
                    $script .= '<script>'.$ID_ModelR.'= new Samotraces.KTBS.Model('.JSON_encode($value).');'
                            .$ID_DIV.'= new Samotraces.UI.Widgets.DisplayModel ("'.$ID_DIV.'",'.$ID_ModelR.',options);</script>';                                       
                    }
               else if ($key=== "Transformation"){
                    $tree .= '<li><img src="images/' . $arrow . '.png" class="' . $class .'" ' .'alt="+" title="' . $title .'" /><label  class="control-label">'.$key."</label>\t<ul>";
                    make_treeTranformation($value);
                   
         
                }
                
                else
               {
                    $tree .= '<li><img src="images/' . $arrow . '.png" class="' . $class .'" ' .
                         'alt="+" title="' . $title .'" /><label  class="control-label">'.$key."</label>\t<ul>";
                          make_tree($value,null);
                    }
                
            } else {
            
                        if ($key=="name"){
                        $tree .='<li>'.$value.' : ';
                        
                        }
                        else if ($key=="label"){
                        $tree .= $value."\n";
                        }
                         else if (($key=="@type")or($key=="@id")){
                        }
                        else if ($key=== "ComputedTraceURI"){
                          
                        
                        $tree .= '<li><img src="images/' . $arrow . '.png" class="' . $class .'" ' .
                              'alt="+" title="' . $title .'" /><label  class="control-label">'.$key."</label>\t<ul>";  
                        $tree .= '
                        <li><div id="T_time_form'.$ID_Transf.'"> </div> </br> 
                        <div id="T_scale'.$ID_Transf.'"> </div> 
                        <div id="T_trace'.$ID_Transf.'"> </div> 
                        <div id="T_traceZoom'.$ID_Transf.'"></div> 
                        <div id="T_scaleZoom'.$ID_Transf.'"> </div>
                        <div id="T_TraceText'.$ID_Transf.'"> </div></li> <br></ul>';
                        $script .= 
                        '<script>SamoTraceMeTransf("'.$value.'/","T_time_form'
                        .$ID_Transf.'","T_scale'
                        .$ID_Transf.'","T_trace'
                        .$ID_Transf.'","T_traceZoom'
                        .$ID_Transf.'","T_scaleZoom'
                        .$ID_Transf.'","T_TraceText'
                        .$ID_Transf.'");</script>';
                      
                        }
                        else {
                        $tree .= '<li><label  class="control-label">'.$key." </label> : ".$value."</li>\n";
                      
                        }
            }
            
        }
        $tree .= "</ul></li>";
        return $tree.$script;
    }
    
    function make_treeTranformation($var) {
        global $tree;
        foreach ($var as $key => $value) {
      
            if (is_array($value)) {
                // Check if the value is empty, show 'empty' arrow then
                if (empty($value)) {
                    $arrow = "arrow_open";
                    $title = "This node has no children";
                    $class = 'arrow empty';
                } else {
                    $arrow = "arrow";
                    $title = "Click on the arrow to view its children";
                    $class = 'arrow children';
                }
               
               $tree .= '<li><img src="images/' . $arrow . '.png" class="' . $class .'" ' .
                         'alt="+" title="' . $title .'" />'.'T'.$value["id"]."\t<ul>";
                         
                make_tree($value,$value["id"]);
                
            } else {
           
             make_tree($value,$value["id"]);
               // $tree .= '<li><img src="images/mark.png" alt="-" />'.$key."<br />$value</li>\n";
            
            
        }
        
        }
        $tree .= "</ul></li>";
        return $tree;
    }
  
    function json_viewer($json) {
     
        $curly  = strpos($json, "{");
        $square = strpos($json, "[");

        // No curly or square bracket means this is not JSON data
        if ( ($curly === false) && ($square === false) ) {
            return "Invalid JSON data (no '{' or '[' found)";
        } else {
            // There is a case when you have a feed with [{
            // so get the first one
            if (($curly !== false) && ($square !== false)) {
                if ($curly < $square) {
                    $square = false;
                } else {
                    $curly  = false;
                }
            }

            // get the last curly or square brace
            if($curly !== false) {
                $firstchar = $curly;
                $lastchar  = strrpos($json, "}");
            } else if ($square !== false) {
                $firstchar = $square;
                $lastchar  = strrpos($json, "]");
            }

            if ($lastchar === false) {
                return "Invalid JSON data (no closing '}' or ']' found)";
            }

            // Give warning if $firstchar is not the first character
            if ($firstchar > 0) {
                $warning  = "---WARNING---\n";
                $warning .= "Invalid JSON data that does not begin with '{' or '[' might give unexpected results\n";
            }
        }
        // get the JSON data between the first and last curly or square brace
        $json = substr($json, $firstchar, ($lastchar - $firstchar) + 1);

        // decode json data
        $data = json_decode($json, true);

        if (!$data) {
            if (isset($_POST['showinvalidjson'])) {
                // Show invalid JSON anyway, do sanitize some stuff
                return "This JSON data is invalid, but we show it anyway: <br />" .
                        htmlentities($json);
            } else {
                return "Invalid JSON data (could not decode JSON data)";
            }
        }

        if (isset($warning)) {
            echo $warning;
        }

        // Check for 'raw output'
        if(isset($_POST['rawoutput'])) {
            die(print_r($data,false));
        }

        // we need to make the first 'root' tree element
      //  $out  = '<ul id="root"><li><img src="images/arrow.png" class="arrow" alt="+" />ROOT<ul id="first">';
        $out='';
        $out .= make_tree($data,null);
        $out .= "</ul></li></ul>";

        $tree = '';
        return $out;
    }

    // call function
    if (isset($_POST)) {
        $tree = '';

        // Check if we need to catch an URL or if we can simply pass the data
        // directly
         $json = $_POST['data'];
        
        // Remove magic quotes if available
        if (get_magic_quotes_gpc()) {
            $json = stripslashes($json);
        }

        echo json_viewer($json);
    }
?>
