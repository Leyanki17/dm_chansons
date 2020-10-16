<?php
    $apropos ='

    <section class="presentation m-auto">
    <h3> Numero etudiant des membres du groupe</h3>
    <p>
        Nous etions deux étudiant à relialiser ce projet les
        <ul>
            <li>Etudiant N°1 <em>21913373</em></li>
            <li>Etudiant N°1 <em>21913373</em></li>
        </ul>
        <h3>Sous la SuperVision de Mr <strong>Alexandre NIVEAU</strong></h3>
    </p>
</section>
<div class="flex">
    
        <section class="presentation">
            <h3> But</h3>
            <p>
                Dans ce projet il nous etait demander de realiser un petit site web fait en PHP en utilisant la structure 
                MVCR vue en cours. Le site devais porter sur un produit listable dans notre cas nous avons utiliser 
                <strong>Les chansons</strong> 
                <ul>
                    <h5></h5>Comme model de notre application on a:</h5>
                    <li>Les chansons</li>
                    <li>Les user</li>
                </ul>
                <ul>
                    <h5></h5>Comme controller de notre application on a:</h5>
                    <li>Les controlller qui gerer les chansons</li>
                    <li>Les controller qui gerer les users</li>
                </ul>
                <ul>
                    <h5></h5>Comme view de notre application on a:</h5>
                    <li>view qui  commune à tout les utilisateur meme ce qui ne son pas connecter</li>
                    <li>private view qui est propre à un utilisateur qui s\'est connecter</li>
                    <li>Admin view qui est reserver au utilisateur connecter et dont le status est admin</li>
                </ul>
                <h5> Nous avons egalement un routeur</h5>
            </p>
        </section>
        <section class="presentation">
            <h3> Comment l\'application fonctionne</h3>
            <p>
                <h5>Pour un utilisateur non connecter</h5>
                Quand il arrive sur le site, il peute:
                <ol>
                    <li>Consulter la liste des chansons</li>
                    <li>S\'inscrire</li>
                    <li>Se connecter</li>
                    <li> Voir la page a propos</li>
                    <li>Acceder à la page d\'acceuil</li>
                </ol>
                Par contre , celui sera incapable de
              
                    <p>Consulter consulter,modifier, supprimer,ajouter une chanson <br /> S\'il essaie il sera renvoyé vers la page de connexion   </p>

            </p>


            <p>
                <h5>Pour un utilisateur  connecter</h5>
                Quand il arrive sur le site, il peut:
                <ol>
                    <li>Consulter la liste des chansons</li>
                    <li>Consulter consulter,ajouter une chanson</li>
                    <li>modifier, supprimer uniquement de modifier les chansons qu\'il à ajouter
                         s\'il veut effectuer ses action sur une chanson qu\'il n\'a pas inserer il sera rediriger vers l\'acceuil avec un petit message  </li>
                    <li>Se deconnecter</li>
                    <li> Voir la page a propos</li>
                    <li>Acceder à la page d\'acceuil</li>
                </ol>
                Par contre , si celui ci est juste de statut il sera incapable de
                <p>Consulter consulter, supprimer,ajouter des utilisateurs <br /> S\'il essaie il sera renvoyé vers la page de connexion   </p>
            </p>
            <h5>Un admin fait tout ce qu\'il veut dans le site Sauf modifier 
                    et supprimer une chanson qui ne lui appartient pas <i>pas encore gérer</i></h5>
        </section>

       
            
      
        <section class="presentation">
            <ul>
                <li>Les pages on été toute valider comme HTML5 conforme grace à <a href="#">validateur HTML5</a></li>
                <li>Base de donnée utilisée MySQL</li>
                <li>Un peu de CSS ajouter pour que ça ne parait pas trop moche même ci c\'est toujours moche :)</li>
                <li></li>
            </ul>
        </section>
    </section>
</div>
  
    '


?>