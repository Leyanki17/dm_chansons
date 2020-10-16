<?
    require_once ("ChansonStorage.php");
    class ChansonStorageMysql implements ChansonStorage{
        private $bd;
        public function getBd(){   
            return $this->bd;
        }
         public function __construct($bd){
              $this->bd=$bd;
        }


        public function reinit(){
          echo "Je suis bien entre dans mon fichier cette fois";
          $this->deleteAll();
          $this->create(new Chanson("Here","Lucas Graham","2019"));
        }
        public function getNumberOfElement(){
            if($this->bd){
                 $req= "SELECT COUNT(*) as nbElement  FROM chansons";
                 $statement=$this->bd->query($req);
                 $data=$statement->fetch(PDO::FETCH_OBJ);
               return $data->nbElement;
             }else{
                echo "Pas de connection à la base de donnée";
             }
          }
  
        public function getLastInsertElement(){
            if($this->bd){
                    $req ="SELECT LAST_INSERT_ID () as lastElement FROM chansons";
                    $statement=$this->bd->query($req);
                    $data = $statement->fetch(PDO::FETCH_OBJ);
                    return $data->lastElement;
            }else{
                    return null;
            }
        }
  
  
        
      
        /**
         * Affiche la chanson qui possede l'id  passé en paramètre
         * @param String identifiant d'une chanson
         * retourne une chanson;
         */
        public function read($id){
            if($this->bd){
                $req= "SELECT *,count(id) as nb FROM chansons WHERE id= :id";
                $statement= $this->bd->prepare($req);
                $statement->bindValue(":id", $id);
                $statement->execute();
                $resultat=$statement->fetch(PDO::FETCH_OBJ);
                if($resultat->nb>0){
                    return  new Chanson($resultat->titre, $resultat->artiste,
                     $resultat->date,$resultat->album, $resultat->style,$resultat->id_user);
                }
            }
            return null;

        }
        /**
         * Permert de creer une chanson dans notre base;
         * @param Chanson  à ajouter dans notre base
         */
        public function create(Chanson $a){
            if($this->bd){
                $req= "INSERT INTO chansons (titre,album,date,style, artiste
                 ,id_user) VALUES(:titre,:album,
                :date,:style , :artiste,:id_user)";
                $statement=$this->bd->prepare($req);
                $statement->bindParam(':titre', $a->getTitre());
                $statement->bindParam(':artiste' ,$a->getArtiste());
                $statement->bindParam(':album',$a->getAlbum());
                $statement->bindParam(':date', $a->getAnnee());
                $statement->bindParam(':style' ,$a->getStyle());
                $statement->bindParam(':id_user' ,$a->getUser());
                // $statement->bindParam(':album',$a->geAlbum());
                if($statement->execute()){
                    return $this->getLastInsertElement();
                }else{
                    return -1;
                }
                
                
                
            }else{
                return null;
            }

        }


        /**
         * Permert de modifier une chanson dans notre base;
         * @param Chanson Chanson à ajouter dans notre base
         */
        public function update($id,Chanson $a){
            if($this->bd && $this->exists($id)){
                $req= "UPDATE chansons set 
                 titre= :titre,
                 album= :album,
                 date= :date,
                 style= :style,
                 artiste= :artiste
                WHERE id=:id"  ;
                $statement=$this->bd->prepare($req);
               $statement->bindParam(':titre', $a->getTitre());
               $statement->bindParam(':artiste' ,$a->getArtiste());
               $statement->bindParam(':album',$a->getAlbum());
               $statement->bindParam(':date', $a->getAnnee());
               $statement->bindParam(':style' ,$a->getStyle());
               $statement->bindParam(':id', $id);
                $statement->execute();
           }

        }


        /**
         * Permet de  supprimer la chansons ayant cet id
         * @param String chaine representant l'id de la chansons à supprimer
         */ 
        public function delete($id){
            if($this->bd && $this->exists($id)){
               $req = "DELETE FROM chansons WHERE id=:id";// AND id_user=: id_user";
               $statement= $this->bd->prepare($req);
               $statement->bindParam(":id",$id);
            //    $statement->bindParam(":id",$id);
               $statement->execute();
            }
        }
        public function deleteAll(){
            if($this->bd){
                $req = "DELETE FROM chansons ";
                $statement= $this->bd->prepare($req);
                $statement->execute();
            }
        }

        public function exists($id){
            if($this->bd){
                if($this->bd){
                    $req= "SELECT count(id) as nb FROM chansons WHERE id= :id";
                    $statement= $this->bd->prepare($req);
                    $statement->bindParam(":id",$id);
                    $statement->execute();
                    $occurence= $statement->fetch(PDO::FETCH_OBJ);
                    if($occurence->nb>0){
                         return true;
                    }
               }
               return false;
            }

        }

        public function isOwner($id,$id_user){
            if($this->bd){
                $req= "SELECT count(*) as nb FROM chansons WHERE id=:id AND id_user=:id_user";
                $statement= $this->bd->prepare($req);
                $statement->bindParam(":id",$id);
                $statement->bindParam(":id_user",$id_user);
                $statement->execute();
                $occurence= $statement->fetch(PDO::FETCH_OBJ);
                if($occurence->nb>0){
                        return true;
                } 
            }
            return false;
        }

        /**
         * Affiche tous les chansons
         * retourne tous les  chansons;
         */
        public function readAll(){
            if($this->bd){
                $req = "SELECT * FROM chansons";
                $statement = $this->bd->prepare($req);
                $statement->execute();
                $data=array();
                while($resultat= $statement->fetch(PDO::FETCH_OBJ)){
                    $data[$resultat->id]= new Chanson($resultat->titre, $resultat->artiste,
                     $resultat->date,$resultat->album, $resultat->style,$resultat->id_user);
               }

               if(count($data)>0){
                 return $data;  
               }    
            }

            return null;
        }


    }

?>
