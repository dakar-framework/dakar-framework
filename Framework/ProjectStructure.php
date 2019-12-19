<?php 

class ProjectStructure {

    public function __construct($dirname){
        // Checks if a directory exists
        
        if(!is_dir(__DIR__.'/' .$dirname)) {
              
            // Creates the project structure

              mkdir($dirname);
              mkdir($dirname.'/'.'App');
              mkdir($dirname.'/'.'App/.php');
              mkdir($dirname.'/'.'App/views');
              mkdir($dirname.'/'.'App/backend');
              touch($dirname.'/'.'router.dk');
              touch($dirname.'/'.'dakar.json');
              mkdir($dirname.'/'.'Database');
              touch($dirname.'/'.'config.toml');
              shell_exec("cp -R ~/.composer/vendor/dakar-framework/dakar-framework/Framework $dirname/");
              $initial_informations = [
                   "project" => [
                     "name" => "$dirname"
                   ]
                 ];
              
              $json_data = json_encode($initial_informations);
              file_put_contents("dakar.json",$json_data);
              
              echo("Project $dirname created!");
            } else {
              echo "ERROR => directory exists".PHP_EOL;
            }
    }
}

?>