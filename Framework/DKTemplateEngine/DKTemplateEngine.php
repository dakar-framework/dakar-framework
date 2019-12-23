<?php 
    class DKTemplateEngine {
        public function __constructor(){}
        # TODO : 
        # unless
        # #json => json_encode
        # comments 
        private static $KEYWORDS = [
            "for",
            "while",
            "if",
            "elseif",
            "esle",
            "end",
            "break",
            "continue",
        ];

        // Will translate tdk files to php file .
        public static function compile($tdk_file,$output_destination)  {
            
            // Get the file into array
            $t_file = explode("\n",file_get_contents($tdk_file));
            
            // The rendered template array
            $rendered_template = [];
            
            // Walk the array
            foreach($t_file as &$line) {
                self::replace_include_DKTemplate($line);
                self::replace_echo_DKTemplate($line);
                self::replace_directive_DKTemplate($line);
                self::replace_directive_without_paren_DKTemplate($line);
                array_push($rendered_template,$line);
            }

            $rendered_template = implode("\n",$rendered_template);
            echo $rendered_template . "\n";
            // $tdk_file = explode(".",$tdk_file)[0];
            // $rendered_file = fopen("$output_destination" . "/" . "$tdk_file.php", 'w');
            // fwrite($rendered_file,$rendered_template);
            // fclose($rendered_file);
        }
       
        // replace echo statements #()
        private static function replace_echo_DKTemplate(&$line) {
            $regex = '/\#\(.*\)/';
            preg_match($regex,$line,$expression_found);
            if($expression_found){
                $to_evaluate = $expression_found[0];
                $to_evaluate = ltrim($to_evaluate,"#(");
                $to_evaluate = substr($to_evaluate, 0, -1);
            
            // detect functions
            $function_regex = "/\w+\(.*\)/";
            preg_match($function_regex,$to_evaluate,$evalute_function);
            // if a function found 
            if($evalute_function){
                if($evalute_function[0]){
                $evalute_function[0] = substr($evalute_function[0], 0, -1);
                $eval_array = explode("(",$evalute_function[0]);
                       // params
                        if($eval_array[1]){
                            // multiple params
                            if(strpos($eval_array[1],",")){
                                $params_string = [];
                                $func_params = explode(",",$eval_array[1]);
                                foreach($func_params as $param){
                                    array_push($params_string,"$$param");
                                }
                                $params_string = implode(",",$params_string);
                                $to_evaluate = "$eval_array[0]($params_string)";
                                $line = str_replace($expression_found[0],"<?= $to_evaluate ?>" ,$line);
                            } else {
                                $to_evaluate = "$eval_array[0]($$eval_array[1])";
                                $line = str_replace($expression_found[0],"<?= $to_evaluate) ?>",$line);
                            }
                        }
                        else {
                            // if there is no params
                            $to_evaluate = $evalute_function[0] . ")";
                        }
                    }
               
                } 
                else {
                $echo_params = [];
                // multiple params to echo
                if(strpos($to_evaluate,",")){
                    $to_evaluate = explode(",",$to_evaluate);
                    foreach($to_evaluate as $v){
                        array_push($echo_params,"$$v");
                    }
                    $echo_params = implode(",",$echo_params);

                $to_evaluate = "$echo_params";
              }else {
                $to_evaluate = str_replace($to_evaluate,"$".$to_evaluate,$to_evaluate); 
              }
        }
            
            $line = str_replace($expression_found[0],"<?= $to_evaluate ?>",$line);
        }
    } 
    
    //
    private static function replace_directive_DKTemplate(&$line) {
        $regex = '/\#\w+\(.*\)/';
        preg_match($regex,$line,$expression_found);
        if($expression_found){
            $rendered_expr = "";
            $expr = $expression_found[0];
            $expr = explode("(",$expr);
            $directive = $expr[0];
            $directive =  ltrim($directive,"#");
            $to_evaluate = $expr[1];
            $to_evaluate = str_replace($to_evaluate[-1],"",$to_evaluate);
            if($directive == "for"){
                if(strpos($to_evaluate,"in")){
                    // foreach or for range loop 
                    $to_evaluate  = explode("in",$to_evaluate);
                    $to_evaluate[0] = trim($to_evaluate[0]);
                    $to_evaluate[1] = trim($to_evaluate[1]); 
                    if(trim($to_evaluate[1][0]) == "{" && trim($to_evaluate[1][-1]) == "}"){
                        $to_evaluate[1] = ltrim($to_evaluate[1],"{");
                        $to_evaluate[1] = rtrim($to_evaluate[1],"}");
                        if(strpos($to_evaluate[1],"..")) {
                            
                            $range = explode("..",$to_evaluate[1]);
                            $rendered_evaluation = "($$to_evaluate[0]=$range[0];$$to_evaluate[0]<=$range[1];$$to_evaluate[0]++){";
                            $rendered_expr = "<?php $directive $rendered_evaluation ?>";
                        } else {
                            echo "bad range supplied!" . "\n";
                        }

                    } else {
                        $rendered_evaluation ="($$to_evaluate[1] as $$to_evaluate[0])";
                        $rendered_expr = "<?php $directive"."each" . "$rendered_evaluation ?>";
                    }
                }
            }
            if($directive == "if" || $directive == "elseif" || $directive == "while"){
                $to_evaluate_array = explode(" ",$to_evaluate);
                foreach($to_evaluate_array as $v){
                    preg_match("/[a-zA-Z]/",$v,$var_found);
                    if($var_found){
                       $to_evaluate_array = str_replace($v,"$$var_found[0]",$to_evaluate_array);                    
                    }
                }
                $to_evaluate_array = implode("",$to_evaluate_array);
                $rendered_expr = "<?php $directive($to_evaluate_array) { ?>";
            }
           
            $line = str_replace($expression_found,$rendered_expr,$line);
        }
    }

    //
    private static function replace_directive_without_paren_DKTemplate(&$line){
        $regex = '/\#\w+\s*(?!.+)/';
        preg_match($regex,$line,$expression_found);
        if($expression_found) {
            $directive = trim($expression_found[0]);
            $directive = ltrim($directive,"#");
            $returned_expr = "";
            switch($directive){
                case "end": 
                    $returned_expr = "}";
                break ; 
                case "continue":
                    $returned_expr = $directive.";";
                break ; 
                case "break":
                    $returned_expr = $directive.";";
                break ;
                case "else":
                    $returned_expr = "} else {";
                break ; 
                default:
            }
            $line = str_replace($expression_found[0],"<?php $returned_expr ?>",$line); 
        }
    }

    //
    private static function replace_include_DKTemplate(&$line){
        $regex = "/\#\{.*\}/";
        preg_match($regex,$line,$expression_found);
        if($expression_found){
            $expr = ltrim($expression_found[0],"#{");
            $expr = rtrim($expr,"}");
            $line = str_replace($expression_found[0],"<?php include(".$expr."); ?>",$line);
        }

    }
}
    
        DKTemplateEngine::compile("file.tdk",".");
        
        
?>