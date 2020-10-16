<?php


    class Account{
        private $id;
        private $nom;
        private $login;
        private $password;
        private $statut;

        public function __construct($id,$nom,$login,$statut,$password){
            $this->id=$id;
            $this->nom=$nom;
            $this->login= $login;
            $this->password=$password;
            $this->statut=$statut;
        }

        

        public function getId(){
            return $this->id;
        }
        public function getNom(){
            return $this->nom;
        }

        public function getStatut(){
            return $this->statut;
        }
        public function getPassword(){
            return $this->password;
        }

        public function getLogin(){
            return $this->login;
        }
    }
    

?>