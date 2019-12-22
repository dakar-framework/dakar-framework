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
            
            $regex = "/\#\w*\([a-zA-Z_\-\> ]+\)/"; 
            preg_match($regex,$line,$expression_found);
  
            if($expression_found){
                if($expression_found[0][1] == "(") {
                    $line = str_replace("#".$expression_found[0][1],"<?= ",$line);
                    $line = str_replace(")"," ?>",$line);
                    $vars = explode(",",$expression_found[0]);
                    print_r($vars);
                    // foreach($vars as $v){
                    //     $line = str_replace($v,"$".$v,$line);
                    // }

            }   
        }
        return $line ; 
     }
}
    DKTemplateEngine::compile("file.tdk");

?>