<?php 
    class DKTemplateEngine {
        public function __constructor(){}
        # TODO : 
        # unless
        # #json => json_encode
        # comments 
        private static  $KEYWORDS = [
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

               self::replace_echo_DKTemplate($line);
               self::replace_directive_DKtemplate($line);
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
        public static function replace_echo_DKTemplate(&$line) {
            $regex = '/\#\(.*\)/';
            preg_match($regex,$line,$expression_found);
           
            if($expression_found){
                echo $expression_found[0] . "\n";
                if($expression_found[0][1] == "(") {
                        $expr = $expression_found[0];
                        $expression_found[0] = str_replace("#("," ",$expression_found[0]);
                        $expression_found[0] = str_replace(")"," ",$expression_found[0]);
                        $expression_found[0] = trim($expression_found[0]);

                    if(strpos($expression_found[0],",")) {
                        $vars = explode(",",$expression_found[0]);
                        foreach($vars as $v) {
                        $expression_found[0]= str_replace($v,"$".$v,$expression_found[0]);
                        }
                    } else {
                        $expression_found[0] = str_replace($expression_found[0],"$".$expression_found[0],$expression_found[0]); 
                    }
                    $line = str_replace($expr,"<?= $expression_found[0] ?>",$line);
           
                    
                echo("(-------------------------)\n");

                // switch($funcOrOperator[0]){
                //     case "for":
                //         echo "will be evaluated : $to_evaluate\n";
                //     break ; 
                // }            
                // if(in_array($funcOrOperator,self::$KEYWORDS)){
                    
                //     echo " >> " . $funcOrOperator . "\n";
                //     echo " >> " .  $expression_found[0]. "\n";
                // }
            }
        
        } 
     
        
    }
    public static function replace_directive_DKtemplate(&$line) {
        $regex = '/\#\w+\(.*\)/';
        preg_match($regex,$line,$expression_found);
        if($expression_found){
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
                            $line = str_replace($expression_found,$rendered_expr,$line);
                        } else {
                            echo "bad range supplied!" . "\n";
                        }

                    } else {
                        echo "bad range supplied" , "\n";
                    }
                }
            }
            //$rendered_expr = $directive . " " . $to_evaluate; 
            //$line = str_replace($expression_found,$rendered_expr,$line);
        }
    }
}
    DKTemplateEngine::compile("file.tdk",".");




?>