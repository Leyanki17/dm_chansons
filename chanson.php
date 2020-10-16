<?
    set_include_path("./src");
    require_once("Router.php");
    require_once("model/ChansonStorageMysql.php");
    require_once("model/AccountStorageMysql.php");
    require_once("./private/mysql_config.php");
    

    /**
     * Connexion à la base de donnéé 
     */
    try{
        $bd= new PDO("mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DB.";charset=utf8",MYSQL_USER, MYSQL_PASSWORD);
        $bd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch(PDOEXCEPTION $e){
        die($e->getMessage("Echec la base de donné n'a pas pu se lancer"));
    }


    // instanciation de notre router
    $router= new Router();

    // passe la base de donnée en paramétre de main
    $router->main(new ChansonStorageMysql($bd),new AccountStorageMysql($bd));
     
?>
