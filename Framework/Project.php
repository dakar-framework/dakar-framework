<?php 

class ProjectStructure {

    public function __construct($dirname){
        // Checks if a directory exists
        
        if(!is_dir(__DIR__.'/' .$dirname)) {
              
            // Creates the project structure

              mkdir($dirname);
              mkdir($dirname.'/'.'App');
              mkdir($dirname.'/'.'App/php');
              mkdir($dirname.'/'.'App/views');
              mkdir($dirname.'/'.'App/backend');
              touch($dirname.'/'.'router.dk');
              mkdir($dirname.'/'.'Database');
              touch($dirname.'/'.'config.toml');
              echo("Project $dirname created!");
            } else {
              echo "ERROR => directory exists".PHP_EOL;
            }
    }
}

?>