<?php

    namespace Hotel\Model ;

    use Doctrine\DBAL\Connection ;
    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;
    


    
    class InfosAdminDAO
    {
        private $db ;

       
        function __construct($connect)
        {
            $this->db = $connect ; 
        }

        // fonction de récupération de ma variable db car elle est en private
        protected function getDb()
        {
            return $this->db ;
        }


        public function selectAllUser()
        {
            $sql = "SELECT COUNT(*) FROM user WHERE statut != 'admin'";
            $totalUser  = $this->db->fetchAll($sql);
            return $totalUser;
        }


        // jointure permettant de selectionner les informations de reservations de l'utilisateur
        public function selectReservation($user_id)
        {
            $sql = "SELECT DATE_FORMAT( r.date_commande , '%d/%m/%Y') AS 'date commande', DATE_FORMAT(r.date_debut , '%d/%m/%Y') AS 'date debut ', DATE_FORMAT(r.date_fin , '%d/%m/%Y') AS 'date fin ', r.nb_personne AS 'nombre personne ', s.nom_service AS 'nom service', s.prix_service AS 'prix service', c.prix AS 'prix chambre', cap.capacite , cat.type_categorie AS 'categorie'
            FROM reservation r , services s , chambres c , capacite_chambre cap , categorie_chambre cat , prestation p
            WHERE  p.id_reservation = r.id_reservation 
            AND s.id_services = p.id_services 
            AND p.id_chambres = c.id_chambres 
            AND c.id_categorie = cat.id_categorie 
            AND c.id_capacite = cap.id_capacite 
            AND r.user_id = ?";
            $reservation = $this->db->fetchAll($sql, array((int) $user_id));
            return $reservation ;
        }
    }

    $info = new InfosAdminDAO($app['db']);
    $countUser = $info->selectAllUser();
    // return $app['twig']->render('index_admin.html.twig', array("AdminSelectUser" => $countUser));