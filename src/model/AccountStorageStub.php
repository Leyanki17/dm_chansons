<!-- <?php
    require_once("AccountStorage.php");
    /**
     * Construit une petite bd  que l'on va utilser pour nous animaux;
     */
    class AccountStorageStub implements AccountStorage{
        private $accounTab;
        public function __construct()
        {
            $this->accountTab= array(
                '1' => new Account("Medor","medor1","azerty","admin"),
                '2' => new Account('Félix', 'felix1',"totototo","user"),
                '3' => new Account('Denver', 'denver3',"tatatata", "admin")
            );

        }


         /**
         * Affiche l'Account qui possede l'id  passé en paramètre
         * @param String determine un Account
         * retourne un Account;
         */
        public function read($id){
            if(key_exists($id,$this->accountTab)){
                return $this->accountTab[$id];
            }else{
                return null;
            }
        }
        
        /** 
         * Permet de verifier si un utilateur es bien inscript;
         */
        public function checkAuth($login,$password){
            foreach ($this->accountTab as $key => $value) {
                if($value->getLogin()===$login){
                    if(password_verify($password,$value->getPassWord())){
                        return $this->read($key);
                    }
                }
                
            }
            return null;
        }

        /**
         * Affiche tous les utilisateurs
         * retourne tous les  utilisateur ;
         */
        public function readAll(){
            return $this->AccountTab;
        }
    }

?> 