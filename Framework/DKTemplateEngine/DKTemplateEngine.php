<?php 
    class DKTemplateEngine {
        public function __constructor(){}
        private $KEYWORDS = [
            "for",
            "while",
            "if",
            "esle",
            "end",
            "db"
        ];
        // will translate tdk files to php file .
        public static function compile($tdk_file)  {
            
            // Get the file into array
            $template_rendered = explode("\n",file_get_contents($tdk_file));
            $line_number = 0 ; 
            // print_r($template_rendered);
            // Walk the array
            
            foreach($template_rendered as &$line){
                echo self::replace_DKTemplate($line) . "\n";
                        // $rendered_expression = str_replace("#(","<?= ",$expression_found[0]);
                }   
            }
                
            
        // replace characters
        public static function replace_DKTemplate(&$line) {
          
            $regex = "/\#\w*\([a-zA-Z_\-\> ,]+\)/"; 
            preg_match($regex,$line,$expression_found);
            
            if($expression_found){
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
                    } 
                    else {
                        $expression_found[0] = str_replace($expression_found[0],"$".$expression_found[0],$expression_found[0]); 
                    }
               
                    $line = str_replace($expr,"<?= $expression_found[0] ?>",$line);
            }   
        }
        return $line ; 
     }
}
    DKTemplateEngine::compile("file.tdk");

?>