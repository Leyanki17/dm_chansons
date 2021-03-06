<?php
    
    require_once("view/View.php");
    require_once("view/PrivateView.php");
    require_once("view/AdminView.php");
    require_once("control/Controller.php");
    require_once("control/ControllerAccount.php");
    require_once("model/ChansonStorageMysql.php");
    require_once("model/AccountStorageMysql.php");
    
    
    /**
     * Ici on va analyser les requetes HTTP afin de pouvoir rediriger vers une vue
    */
    class Router{
        
        private $view;
        private $action;
        public function __construct(){
            $this->action =array('supprimer','modifier');
        }

        public function main(ChansonStorageMySQL $chansonStorage, AccountStorageMysql $accountStorage){
            // lancement de la session
            session_start();
            
            // verifiacation s'il n'existe pas des information à afficher 
            $feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
            $_SESSION['feedback'] = '';

            // on passe le routeur à la vue
            if(!key_exists("user",$_SESSION)){
                $this->view= new View($this,$feedback);
            }else{
               if($_SESSION["user"]->getStatut()==="admin" ){
                $this->view= new AdminView($this,$_SESSION["user"],$feedback);
               }else{
                $this->view= new PrivateView($this,$_SESSION["user"],$feedback);
               }
            }

            

            // on instancie notre controller
            $controllerChansons= new Controller($this->view, $chansonStorage,$accountStorage);
            $controllerAccount= new ControllerAccount($this->view,$accountStorage);

            /**
             * gestion des urls afin de pouvoir afficher les pages correspondantes
             */
            // si le parametre ou action son passe en url
             if(key_exists("PATH_INFO" , $_SERVER)){

                $tab= explode('/', $_SERVER["PATH_INFO"]);
                $size=count($tab);
                $i=1;

                if($size===2){
                    $action=htmlspecialchars($tab[1]);
                    switch ($action) {
                        case 'apropos':
                         $this->view->makeApropos();
                         break;
                        case 'liste':
                            $controllerChansons->showList();
                        break;
                        case 'listUser':
                            if(key_exists("user",$_SESSION)){
                                if($_SESSION["user"]->getStatut()==="admin"){
                                    $controllerAccount->showUserList();
                                }else{
                                    $this->view->displayAdminPageFeedBack(".","Vous de ver etre admin pour voir la liste des utilisateur ");
                                }
                                
                            }else{
                                $this->view->displayConnexionFeedback("Connetez vous pour voir la liste des utilisateur attention vous devez être egalement de statut admin"); 
                            }
                                
                        break;

                        case 'nouveau':
                            if(key_exists("user",$_SESSION)){
                                $controllerChansons->getView()->makeCreationFormChansonPage($controllerChansons->newChanson());
                            }else{
                                $this->view->displayConnexionFeedback("Connetez vous pour voir la page personnaliser de la chanson");
                            } 
                        break;

                        case 'sauverNouveau':
                            if($_SERVER["REQUEST_METHOD"] === "POST"){
                                if(key_exists("user",$_SESSION)){
                                    $controllerChansons->saveNewChanson($_POST);
                                }else{
                                    $this->view->displayConnexionFeedback("Vous devez etre connecter pour enregistrer une nouvelle chanson");
                                } 
                            }else{
                                $this->view->makePageUnaccessible();
                            }
                        break;
                        case '':
                            $this->view->welcomePage();
                        break;
                        case 'connexion':
                            if(!key_exists("user",$_SESSION)){
                                $this->view->makeLoginFormPage();
                            }else{
                                $this->view->displayAlreadyConnectSuccess();
                            }      
                        break;
                        case 'deconnexion':
                            if(key_exists("user",$_SESSION)){
                                $controllerChansons->logout();
                            }else{
                                $this->view->displayConnexionFeedback("Vous devez etre connecter pour experer vous deconnecter");
                            }    
                        break;
                        case 'inscription':
                                $this->view->makeSignInFormPage($controllerAccount->newAccount());
                        break;
                        // à changer pour modification ie controller des account à part;
                        case "confirmConnexion":
                            if($_SERVER["REQUEST_METHOD"] === "POST"){
                                $controllerChansons->login($_POST);
                            }else{
                                $this->view->makePageUnaccessible();
                            }
                        break;
                        case 'confirmInscription':
                            if($_SERVER["REQUEST_METHOD"] === "POST"){
                                $controllerAccount->saveAccount($_POST);
                            }else{
                                $this->view->makePageUnaccessible();
                            }
                        default:
                            if(key_exists("user",$_SESSION)){
                                $controllerChansons->showInformation($action);
                            }else{
                                $this->view->displayConnexionFeedback("Connetez vous pour voir la page personnaliser de la chanson");
                            }  
                        break;
                    }
                }

                
                if($size===3){
                    for($i; $i<$size ; $i++){
                        $action= $tab[$i];
                        switch ($action){
                            case 'supprimer':
                                if(key_exists("user",$_SESSION)){
                                    $id= htmlspecialchars($tab[$i-1]);
                                    $controllerChansons->askChansonDeletion($id);
                                }else{
                                    $this->view->displayConnexionFeedback("Vous devez être connecter pour supprimer des chansons");
                                }
                            break;
                            case 'confirmerSuppression':
                                if($_SERVER["REQUEST_METHOD"] === "POST"){
                                    if(key_exists("user",$_SESSION)){
                                        $id= htmlspecialchars($tab[$i-1]);
                                        $controllerChansons->deleteChanson($id);
                                    }else{
                                        $this->view->displayConnexionFeedback("Vous devez être connecter pour supprimer des chansons");
                                    }
                                }else{
                                    $this->view->makePageUnaccessible();
                                }
                            break;
                            case 'confirmerModification':
                                if($_SERVER["REQUEST_METHOD"] === "POST"){
                                    if(key_exists("user",$_SESSION)){
                                        $id= htmlspecialchars($tab[$i-1]);
                                        // echo "pas dedans";
                                        $controllerChansons->updateChanson($_POST,$id);
                                    }else{
                                        $this->view->displayConnexionFeedback("Vous devez être connecter pour modifier des chansons");
                                    }
                                }else{
                                    $this->view->makePageUnaccessible();
                                }
                            break;
                            case "modifier":
                                if(key_exists("user",$_SESSION)){
                                    $id= htmlspecialchars($tab[$i-1]);
                                    $controllerChansons->askChansonModification($id);
                                }else{
                                    $this->view->displayConnexionFeedback("Vous devez être connecter pour modifier des chansons");
                                }
                        
                            break;
                            break;
                            case 'supprimerUser':
                                if(key_exists("user",$_SESSION)){
                                    if($_SESSION["user"]->getStatut()==="admin"){
                                        $id= htmlspecialchars($tab[$i-1]);
                                       $controllerAccount->askDeletionUser($id);
                                    }else{
                                        $this->view->displayAdminPageFeedBack("Vous de ver etre admin pour supprimer un ");
                                    }
                                }else{
                                    $this->view->displayConnexionFeedback("Connetez vous pour pouvoir supprimer un comptes");  
                                }
                            break;
                            case 'confirmSuppressionUser':
                                if($_SERVER["REQUEST_METHOD"] === "POST"){
                                    if(key_exists("user",$_SESSION)){
                                        if($_SESSION["user"]->getStatut()==="admin"){
                                            $id= htmlspecialchars($tab[$i-1]);
                                        $controllerAccount->DeleteAccount($id);
                                        }else{
                                            $this->view->displayAdminPageFeedBack("Vous devez etre admin pour supprimer un utilisateur");
                                        }
                                    }else{
                                        $this->view->displayConnexionFeedback("Connetez vous pour pouvoir supprimer un comptes");  
                                    }
                                }else{
                                    $this->view->makePageUnaccessible();
                                }
                            break;

                            default:
                                $this->view->makePageUnaccessible();
                            break;
                        }
            
                      
                    }
                }

                if($size >4){
                    //chargement de la page d'erreur 

                }


             }else{
                // affichage de la page principale avec menu 
                $this->view->welcomePage();
             }



            $this->view->render();

        }







        // ensemble d'url du site web 
        /**
         * Permet de renvoyer le lien de la du site
         */
        public function rootUrl(){
            return "http://localhost/dashboard/dm_chansons/chanson.php/";
        }

        /**
         * Permet charger le liens des style 
         */
        public function getStyleUrl(){
            return "http://localhost/dashboard/dm_chansons/src/view/style.css";
        }
        /**
         * renvoie la page de l'identifiant 
         * @param int $id identifiant de la chanson;
         */
        public function getChansonUrl($id){
            //  return "Chanson.php/".$id;  
            return $this->rootUrl()."".$id;  
        }
         /**
         * retourne Lien vers la page Apropos
         */
        public function getAproposUrl(){
            //  return "chanson.php/".$id;  
            return $this->rootUrl()."apropos";  
            }
        
        /**
         * retourne la liste des chansons de la bd
         */
        public function getChansonList(){
        //  return "chanson.php/".$id;  
        return $this->rootUrl()."liste";  
        }
    
        /**
         * Renvoie le lien de la page de l'id de la chanson passé en paramettre
         * @param String identifient d'une chanson
         */
        public function getChansonCreationUrl(){
        return $this->rootUrl()."nouveau";  
        }
    
        /**
         * Renvoie le lien de la page de l'id de la chanson passé en paramettre
         * @param String identifient d'une chanson
         */
        public function getChansonSaveUrl(){
        return $this->rootUrl()."sauverNouveau";
        }
        /**
         * Renvoie le lien vers la page de suppresion de l'identifiant 
         * @param int $id identifiant de la chanson à supprimer
         */
        public function getChansonAskDeletionURL($id){
        return $this->rootUrl().$id."/supprimer";
        }
        /**
         *  Renvoie le lien de suppression definitive 
         *  @param int identifiant de la chanson à supprimer
         */
        public function getChansonDeletionURL($id){
        return  $this->rootUrl().$id."/confirmerSuppression";
        }
        /**
         * Retourne le lien de modification des urls
         * @param int identifiant de la chanson à supprimer 
         */
        public function getChansonAskUpdateURL($id){
        return $this->rootUrl()."".$id."/modifier";
        }
        
        /**
         * Retourne le lien de confimatation de la modification 
         * @param int identifiant de la chanson à modifier
         */
        public function getChansonUpdateURL($id){
        return $this->rootUrl()."".$id."/confirmerModification";
        }
        
        /**
         * Retourne le lien vers la page de connection
         */
        public function getConnexionURL(){
            return $this->rootUrl()."connexion";
        }

        /**
         * Confirme la connexion
         */
        public function getConfirmConnexionURL(){
            return $this->rootUrl()."confirmConnexion";
        }
        public function getDeconnexionURL(){
            return $this->rootUrl()."deconnexion";
        }
        /**
         * Retourne le lien vers la page d'inscription
         */
        public function getInscriptionURL(){
            return $this->rootUrl()."inscription";
        }

        /**
         * Retourne le lien vers la page d'inscription
         */
        public function getConfirmInscriptionURL(){
            return $this->rootUrl()."confirmInscription";
        }
        public function getUserList(){
            return $this->rootUrl()."listUser";
        }

        /**
         * Retourne le lien vers la page de suppression d'un utilisateur
         */
        public function getAskDeletionUser($id){
            return $this->rootUrl().$id.'/supprimerUser';
        }
        /**
         * retourne le lien de suppression effective (qui traite la suppression) des utilisateurs
         */
        public function getDeletionUser($id){
            return $this->rootUrl().$id.'/confirmSuppressionUser';
        }

        /**
         * retourne le lien vers la page de modification d'un utilisateur
         */
        public function  getAskUpdateUser($id){
            return $this->rootUrl().'updateUser';
        }
        /**
         * retourne le lien vers la page de modification effective (qui traite la modification) d'un utilisateur
         */
        public function  getUpdateUser($id){
            return $this->rootUrl().'confirmUpdateUser';
        }

        

        /**
         * Redirige vers une autre page
         * @param String url de la page sur la quelle on doit être redirigé
         * @param String message à afficher sur la page redirigée
         */
        public function PostRedirect($url,$feedback){
            $_SESSION['feedback'] = $feedback;
            header("Location: ".htmlspecialchars_decode($url), true, 303);
        }

    }


?>