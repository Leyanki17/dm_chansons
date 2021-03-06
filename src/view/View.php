<?php

    class View{
        protected $menu;
        protected $title;
        protected $content;
        protected $router;
        // private $feedback

        public function __construct($router,$feedback){
            $this->router=$router;
            $this->menu = $this->getMenu();
            $this->feedback=$feedback;
        }

        public function getMenu(){
         return ' 
          <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href='.$this->router->rootUrl().'> Acceuil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href='.$this->router->getChansonList().'> Liste des chansons</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href='.$this->router->getChansonCreationUrl().'> Ajout chanson</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href='.$this->router->getAproposUrl().'> A propos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn btn-blue" href='.$this->router->getConnexionURL().'>connexion</a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-green" href='.$this->router->getInscriptionURL().'>inscription</a>
            </li>
          </ul>';
        }

        /**
        * Permet de modifier le titre de la page
        * @param $title String nouveau titre;
        */
        public function setTitle($newTitle){
          $this->title=$newTitle;
        }

        /**
        * Permet de modifier le titre de la page
        * @param $content String nouveau titre;
        */
        public function setContent($newContent){
          $this->content=$newContent;
        }
        
        public function render(){
            include_once("index.php");
        }

        /**
         * Page d'acceuil 
         */
        public function welcomePage(){
          $this->setTitle("acceuil");
          $this->setContent("Bienvenue sur le catalogue de chanson");
        }

        /**
         * Creer la page d'une chanson
         * @param Objet chanson en represanter
         * @param id de la chanson
         */
        public function makeChansonPage($chanson,$id=null){
            $this->title= $chanson->getTitre();
            $this->content='<div class="card">'.$chanson->getTitre().' est un chanson produite par  '.$chanson->getArtiste().'Cette 
            chanson fait partie de son album '.$chanson->getAlbum().' <br />'
            .$chanson->getTitre().' est sortie officiellement en l\'an '. $chanson->getAnnee().'Cette chanson est du registre 
            '.$chanson->getStyle().'<br />';
            ;

            $this->content.= '<p>
              <a class="btn btn-red " href="'.$this->router->getchansonAskDeletionUrl($id).'">Supprimer la chanson </a>
                 <a class="btn btn-green" href="'.$this->router->getchansonAskUpdateURL($id).'">modifier  la chanson </a> 
            </p></div>';          

        }

        /**
         * Formulaire d'inscription
         */
        public function makeSignInFormPage(AccountBuilder $account){    
          
          $nomValue= key_exists($account::NOM_REF ,$account->getData()) ? $account->getData()[$account::NOM_REF] : "";
          $loginValue= key_exists($account::LOGIN_REF,$account->getData()) ? $account->getData()[$account::LOGIN_REF] : "";
          $statutValue= key_exists($account::STATUT_REF,$account->getData()) ? $account->getData()[$account::STATUT_REF] :"";
          $passwordValue= key_exists($account::PASSWORD_REF,$account->getData()) ? $account->getData()[$account::PASSWORD_REF] : "";

          $nomError= key_exists($account::NOM_REF,$account->getError()) ? $account->getError()[$account::NOM_REF] : "";
          $loginError= key_exists($account::LOGIN_REF,$account->getError()) ? $account->getError()[$account::LOGIN_REF] : "";
          $statutError= key_exists($account::STATUT_REF,$account->getError()) ? $account->getError()[$account::STATUT_REF] : "";
          $passwordError= key_exists($account::PASSWORD_REF,$account->getError()) ? $account->getError()[$account::PASSWORD_REF] : "";
          $confirmPasswordError= key_exists($account::CONFIRMPASSWORD_REF,$account->getError()) ? $account->getError()[$account::CONFIRMPASSWORD_REF] : "";

          $form='<form method="POST" class="formulaire" action="'.$this->router->getConfirmInscriptionURL().'">
            <div class="form-input">
              <p class="error">'. $loginError .'</p>
              <label>Login<input class="form-input "type="text" name="'.$account::LOGIN_REF.'"  value="'.$loginValue.'" /></label>
            </div>
            <div class="form-input">
              <p class="error">'. $nomError .'</p>
              <label>Nom<input class="form-input "type="text" name="'.$account::NOM_REF.'" placeholder="Enter votre nom" value="'.  $nomValue.'" /></label>
            </div>
            <div class="form-input">
              <p class="error">'. $passwordError .'</p>
              <label>Password<input class="form-input "type="password" name="'.$account::PASSWORD_REF.'"  value="'.$passwordValue.'" /></label>
            </div>
            <div class="form-input">
            <p class="error">'. $confirmPasswordError .'</p>
              <label>confirmPassword<input class="form-input "type="password" name="'.$account::CONFIRMPASSWORD_REF.'" placeholder="entrer son espéce" value="" /></label>
            </div>
            <div class="form-input">
            <p class="error">'. $statutError .'</p>
              <label>statut<input class="form-input "type="text" name="'.$account::STATUT_REF.'" placeholder="Quelle est votre statut" value="'.  $statutValue.'" /></label>
            </div>
           <div class="form-group center">
              <button type="submit" class="btn btn-blue bnt-form">inscription</button>
           </div>
          </form>';
          $this->title = "Inscription";
          $this->content=$form;
        }
        /**
         * Page d'erreur 
         * @param id de la page que l'on souhaite avoir
         */
        public function makeUnknownChansonPage($idChanson){
          $this->content="<h2>404 Page Introuvable</h4> 
            La page de l'identifiant".$idChanson." ne fais pas partie des nôtres
          ";
        }

        /**
         * Affiche la liste de chanson
         */
        public function makeListPageChanson($list){
          $this->title= "Liste de chansons";
          if($list!=null){
            $contenu=null;
            $taille= count($list);
            foreach($list as $key => $chanson) { 
              $contenu.='<div class="card" >Voir la page de: <a href='.$this->router->getChansonUrl($key).'>' 
              .$chanson->getTitre().' de  '. $chanson->getArtiste().'</a></div>' ;
            }
            $this->content =$contenu;
          }else{
            $this->content="<h4 center > La liste est vide </h4>"; 
          }

        }


        /**
         * creer un formulaire d'ajout d'une chanson;
         */
        public function makeCreationFormChansonPage(ChansonBuilder $chansons){

          
          $titreValue= key_exists($chansons::TITRE_REF ,$chansons->getData()) ? $chansons->getData()[$chansons::TITRE_REF] : "";
          $artisteValue= key_exists($chansons::ARTISTE_REF,$chansons->getData()) ? $chansons->getData()[$chansons::ARTISTE_REF] : "";
          $albumValue= key_exists($chansons::ALBUM_REF,$chansons->getData()) ? $chansons->getData()[$chansons::ALBUM_REF] :"";
          $styleValue= key_exists($chansons::STYLE_REF,$chansons->getData()) ? $chansons->getData()[$chansons::STYLE_REF] : "";
          $anneeValue= key_exists($chansons::ANNEE_REF,$chansons->getData()) ? $chansons->getData()[$chansons::ANNEE_REF] :"";

          $titreError= key_exists($chansons::TITRE_REF,$chansons->getError()) ? $chansons->getError()[$chansons::TITRE_REF] : "";
          $artisteError= key_exists($chansons::ARTISTE_REF,$chansons->getError()) ? $chansons->getError()[$chansons::ARTISTE_REF] : "";
          $albumError= key_exists($chansons::ALBUM_REF,$chansons->getError()) ? $chansons->getError()[$chansons::ALBUM_REF] : "";
          $styleError= key_exists($chansons::STYLE_REF,$chansons->getError()) ? $chansons->getError()[$chansons::STYLE_REF] : "";
          $anneeError= key_exists($chansons::ANNEE_REF,$chansons->getError()) ? $chansons->getError()[$chansons::ANNEE_REF] : "";


          $form='<form method="POST" class="formulaire" action="'.$this->router->getChansonSaveUrl().'">
            <div class="form-input">
              <p class="error">'. $titreError .'</p>
              <label>Titre<input class="form-input "type="text" name="'.$chansons::TITRE_REF.'"  value="'.$titreValue.'" /></label>
            </div>
            <div class="form-input">
              <p class="error">'. $artisteError .'</p>
              <label>Artiste<input class="form-input "type="text" name="'.$chansons::ARTISTE_REF.'" placeholder="entrer son espéce" value="'.  $artisteValue.'" /></label>
            </div>
            <div class="form-input">
              <p class="error">'. $albumError .'</p>
              <label>Album<input class="form-input "type="text" name="'.$chansons::ALBUM_REF.'"  value="'.$albumValue.'" /></label>
            </div>
            <div class="form-input">
              <p class="error">'. $styleError .'</p>
              <label>Style<input class="form-input "type="text" name="'.$chansons::STYLE_REF.'" placeholder="entrer son espéce" value="'.  $styleValue.'" /></label>
            </div>
            <div class="form-input">
            <p class="error">'. $anneeError .'</p>
              <label>annee<input class="form-input "type="text" name="'.$chansons::ANNEE_REF.'" placeholder="entrer son annee" value="'.  $anneeValue.'" /></label>
            </div>
           <div class="form-group center">
              <button type="submit" class="btn btn-blue bnt-form">Ajouter une nouvelle chanson</button>
           </div>
          </form>';
          $this->title = "create de la chanson";
          $this->content=$form;
        }

        /**
         * Redirige vers la page de la chanson qui à été bien inserer
         */
        public function displayChansonCreationSuccess($id){
          $this->router->PostRedirect($this->router->getChansonUrl($id),"Votre chanson à été bien ajouter à la liste");
        }

        /**
         * Redirige vers la page du formulaire de creation
         */
        public function displayChansonCreationFailure(){
          $this->router->PostRedirect($this->router->getChansonCreationUrl(),"Votre chansons n'est pas valide ");
        }



        
        /**
        * Creer un formulaire de modifiacation d'une chanson;
         */
        public function makeChansonUpdatePage(ChansonBuilder $chansons,$id){

          $titreValue= key_exists($chansons::TITRE_REF,$chansons->getData()) ? $chansons->getData()[$chansons::TITRE_REF] : "";
          $artisteValue= key_exists($chansons::ARTISTE_REF,$chansons->getData()) ? $chansons->getData()[$chansons::ARTISTE_REF] : "";
          $albumValue= key_exists($chansons::ALBUM_REF,$chansons->getData()) ? $chansons->getData()[$chansons::ALBUM_REF] :"";
          $styleValue= key_exists($chansons::STYLE_REF,$chansons->getData()) ? $chansons->getData()[$chansons::STYLE_REF] : "";
          $anneeValue= key_exists($chansons::ANNEE_REF,$chansons->getData()) ? $chansons->getData()[$chansons::ANNEE_REF] :"";

          $titreError= key_exists($chansons::TITRE_REF,$chansons->getError()) ? $chansons->getError()[$chansons::TITRE_REF] : "";
          $artisteError= key_exists($chansons::ARTISTE_REF,$chansons->getError()) ? $chansons->getError()[$chansons::ARTISTE_REF] : "";
          $albumError= key_exists($chansons::ALBUM_REF,$chansons->getError()) ? $chansons->getError()[$chansons::ALBUM_REF] : "";
          $styleError= key_exists($chansons::STYLE_REF,$chansons->getError()) ? $chansons->getError()[$chansons::STYLE_REF] : "";
          $anneeError= key_exists($chansons::ANNEE_REF,$chansons->getError()) ? $chansons->getError()[$chansons::ANNEE_REF] : "";


          $form='<form method="POST" action="'.$this->router->getChansonUpdateUrl($id).'">
            <div class="form-input">
              <p class="error">'. $titreError .'</p>
              <label>Titre<input class="form-input "type="text" name="'.$chansons::TITRE_REF.'"  value="'.$titreValue.'" /></label>
            </div>
            <div class="form-input">
              <p class="error">'. $artisteError .'</p>
              <label>Artiste<input class="form-input "type="text" name="'.$chansons::ARTISTE_REF.'" placeholder="entrer son espéce" value="'.  $artisteValue.'" /></label>
            </div>
            <div class="form-input">
              <p class="error">'. $albumError .'</p>
              <label>Album<input class="form-input "type="text" name="'.$chansons::ALBUM_REF.'"  value="'.$albumValue.'" /></label>
            </div>
            <div class="form-input">
              <p class="error">'. $styleError .'</p>
              <label>Style<input class="form-input "type="text" name="'.$chansons::STYLE_REF.'" placeholder="entrer son espéce" value="'.  $styleValue.'" /></label>
            </div>
            <div class="form-input">
            <p class="error">'. $anneeError .'</p>
              <label>annee<input class="form-input "type="text" name="'.$chansons::ANNEE_REF.'" placeholder="entrer son annee" value="'.  $anneeValue.'" /></label>
            </div>
            <div class="form-center center">
              <button type="submit" class="btn btn-green bnt-form">Ajouter une nouvelle chanson</button>
            </div>
          </form>';
          $this->title = "create de la chanson";
          $this->content=$form;

        }

        /**
         * Redirige vers la page de la chanson qui à été bien inserer
         */
        public function displayUpdateChansonSuccess($id){
          $this->router->PostRedirect($this->router->getChansonUrl($id),"Votre chanson à été bien modifiée ");
        }

        /**
         * Redirige vers la page du formulaire de modification
         */
        public function displayUpdateChansonFailure($id){

          $this->router->PostRedirect($this->router->getChansonAskUpdateURL($id),"Erreur lors de la modification de la chansons");
        }

        /**
         * Creer une page de suppression 
         */
        public function makeDeletionPageChanson($id){
          $this->title= "Suppression de la Chanson d'identifiant : ".$id;
          $this->content='<h3 class="center"> Confimer la suppression de la chanson d\'identifiant '.$id.'</h3><br>
            <form method="POST" class="center" action="'.$this->router->getChansonDeletionURL($id).'">
                <button type="submit btn btn-red"> Supprimer la chanson </button>
            </form>
          ';
        }  

        public function makeApropos(){
          $this->title= "A propos";
          include ("apropos.php");
          $this->content=$apropos;
        }

        /**
         * Renvoie  vers la page d'erreur 
         */
        public function makePageUnaccessible(){
          $this->title="404 Page Introuvable";
          $this->content="<h2>404 Page Introuvable</h4> 
          le serveur n'a pas pu trouver cette page;
        ";
        }        
        /**
         * 
         */

        /**
         * Redirige vers la liste de chanson en cas de suppression 
         */
        public function displayChansonDeletionSuccess($id){
          $this->router->PostRedirect($this->router->getChansonList(),"Votre Chanson d'identifiant ".$id." à été bien supprimer");
        }

        /**
         * Redirige vers la page de suppression de la chanson en, cas d'echec de suppression
         */
        public function displayChansonDeletionFailure(){
          $this->router->PostRedirect($this->router->getChansonDeletionUrl(),"La suppression n'a pas fonctionner");
        }



        /**
         * Affiche la page de connexion 
         */
        public function  makeLoginFormPage(){
          $this->title= "Connexion ";
          $this->content='<h3 class="center"> connectez vous</h3><br>
            <form method="POST" action='.$this->router->getConfirmConnexionURL().'>
                <div class="form-group">
                  <label>Login <input class="form-input "type="text" name="login"> </label>
                </div>
                <div class="form-group">
                  <label>Password <input class="form-input "type="password" name="password"> </label>
                </div>
                <div class="form-group center">
                  <button type="submit" class="btn btn-form btn-green"> connection </button>
                </div>
               
            </form>
          ';
        }
        /**
         * Redirige vers la page  en cas de success d'inscription
         */
        public function displayCreationAccountSuccess(){
          $this->router->PostRedirect($this->router->getConnexionURL(),"Felicitation votre inscription c'est bien passé");
        }
        /**
         * Redirige vers la page d'inscription en cas d'echec d'inscription
         */
        public function displayCreationAccountFailure(){
          $this->router->PostRedirect($this->router->getInscriptionURL(),"Impossible de soumettre le formulaire veuiller corriger les erreurs");
        }
        /**
         * Redirige vers la page d'acceuil de chanson en cas de connexion reussie
         */
        public function displayConnexionSuccess(){
          $this->router->PostRedirect($this->router->rootUrl(),"Vous êtes maintenant connecter vous pouver effectuer des ajouts et modifications");
        }

        /**
         * Redirige vers la page d'acceuil de chanson en cas de connexion deja effective
         */
        public function displayAlreadyConnectSuccess(){
          $this->router->PostRedirect($this->router->rootUrl(),"Vous êtes deja connecter vous pouver effectuer des ajouts et modifications");
        }

        /**
         * Redirige vers la page de connexion , cas d'echec de connexion
         */
        public function displayConnexionfailure(){
          $this->router->PostRedirect($this->router->getConnexionURL(),"Login ou mot de passe incorrect");
        }


        /**
         * Redirige vers la page d'acceuil de chanson en cas de connexion reussie
         */
        public function displayDeconnexionSuccess(){
          $this->router->PostRedirect($this->router->getConnexionURL(),"Vous êtes maintenant deconnecter");
        }

        /**
         * Redirige vers la page courante , en cas d'echec de deconnexion
         */
        public function displayDeConnexionfailure(){
          $this->router->PostRedirect(".","Echec de la deconnexion");
        }

        public function displayAccessDenied($id){
          $this->router->PostRedirect($this->router->getChansonUrl($id),"Action Impossible. Vous n'êtes pas propriétaire de cet élément donc, 
          Vous n'avez pas les droit suffisant pour le modifier ou le supprimer ");
        }

        /**
         * Permet de rediriger sur la page de connexion avec un feed back;
         * @param String correspond au feedback à renvoyer;
         */
        public function displayConnexionFeedback($feedback){
          $this->router->PostRedirect($this->router->getConnexionURL(),$feedback);
        }

        public function displayAdminPageFeedBack($feedback){
          $this->router->PostRedirect($this->router->rootUrl(),$feedback);
        }
    }


?>