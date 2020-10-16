<?
    class Chanson{
        private $titre;
        private $artiste;
        private $annee;
        private $album;
        private $style;
        private $user;

        public function __construct($titre,$artiste,$annee,$album,$style,$user=null){
            $this->titre=$titre;
            $this->artiste=$artiste;
            $this->annee=$annee;
            $this->album=$album;
            $this->style=$style;
            $this->user=$user;
        }

        public function getUser(){
            return $this->user;
        }
        public function getTitre(){
            return $this->titre;
        }
        public function getArtiste(){
            return $this->artiste;
        }
        public function getAnnee(){
            return $this->annee;
        }
        public function getAlbum(){
            return $this->album;
        }
        public function getStyle(){
            return $this->style;
        }

        public function setTitre($titre){
            $this->titre= $titre;
        }
        public function setArtiste($artiste){
            $this->artiste=$artiste;
        }
        public function setAnnee($annee){
            $this->annee= $annee;
        }
        public function setAlbum($album){
            $this->album= $album;
        }
        public function setStyle($style){
            $this->style= $tyle;
        }
        public function setUser($user){
            $this->user=$user;
        }
        public function getArray(){
            return get_object_vars($this);
        }



    }


?>