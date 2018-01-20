<?php

    namespace Hotel\Model; 

    use Doctrine\DBAL\Connection;
    use \DateTime;

    class ReservDAO {

        private $db;
        private $idFacture;
        private $idReserv;
        private $idChbre1;
        private $idChbre2;
        private $nuits1 = 1;
        private $nuits2 = 1;

        function __construct($connect) {
            $this->db = $connect;
        }


        protected function getDb() { // connexion à la bdd
            return $this->db;
        }


        public function recupEmail(int $idUser, string $email) {

            $bdd = $this->getDb(); 

            // on récupère le mail de l'user avec son id dans la table user
            $sql = "SELECT * FROM user WHERE user_id = ? AND email = ?";
            $result = $bdd->fetchAssoc($sql, array(0 => $idUser, 1 => $email));
            return $result;
        }


        public function insertReserv(int $idUser, int $nbPerson1, string $debut1, string $fin1, int $cat1, array $idserv) {

            $bdd = $this->getDb();

            // on vérifie la disponibilité d'une chambre en fonction de la catégorie, du nombre de personne et du statut des chambres

            $sql1 = "SELECT * FROM chambres WHERE id_categorie = ? AND id_capacite = ? AND id_statut = 1 LIMIT 0,1";
            $result = $bdd->fetchAssoc($sql1, array(0 => $cat1 , 1 => $nbPerson1));
            
                if(!empty($result)) { // si une chambre est dispo

                    // on créé l'id de la future facture pour faire la liaison entre les tables
                    $bdd->insert('factures', array(
                        'user_id' => $idUser
                    ));
                    $this->idFacture = $bdd->lastInsertId();

                    // on créé la réservation avec le n° de facture correspondant
                    $bdd->insert('reservation', array(
                        'user_id' => $idUser,
                        'id_factures' => $this->idFacture,
                        'date_debut' => $debut1,
                        'date_fin' => $fin1,
                        'nb_personne' => $nbPerson1 
                        )
                    );
                    $this->idReserv = $bdd->lastInsertId();

                    // on calcule le nombre de nuits réservées
                    $debut1 = new DateTime($debut1);
                    $fin1 = new DateTime($fin1);

                    $this->nuits1 = intval($debut1->diff($fin1)->format('%d')); // différence de jours entre le jour d'arrivée et le jour de départ

                    // on complète la prestation avec les services demandé
                    if(!empty($idserv)){
                        foreach($idserv as $key => $val){

                            $bdd->insert('prestation', array(
                                'id_reservation' => $this->idReserv,
                                'id_services' => $val
                            ));
                        }
                    }

                    // on insère la chambre dans la prestation
                    $this->idChbre1 = $result['id_chambres']; // en récupèrant l'id de la chambre
                    $bdd->insert('prestation', array(
                        'id_reservation' => $this->idReserv,
                        'id_chambres' => $result['id_chambres']
                    ));

                   // et on change le statut de la chambre en occupée
                    $bdd->executeUpdate("UPDATE chambres SET id_statut = '2' WHERE id_chambres = ". $this->idChbre1 ."");
                
                }
                 
                else // si pas de chambre dispo
                    return "erreur";
              
                
            } // fin insertReserv()
        

        public function factureReserv(){

            $bdd = $this->getDb();

            // on fait le détail de la facture à partir des prestations réservées par l'utilisateur (via le n° de facture) :

            // 1) en commençant par la chambre
            $sql = "SELECT * FROM detail_prestation_chambre WHERE id_factures = ? AND id_chambres = ?";
            $result = $bdd->fetchAssoc($sql, array($this->idFacture, $this->idChbre1));

            $nbNuits = $this->nuits1;
            $prixTotal = $nbNuits * $result['prix']; // calcul du prix du séjour

            $bdd->insert('detail_factures', array( // remplissage de la table
                'id_factures' => $result['id_factures'],
                'id_prestation' => $result['id_prestation'],
                'prix_unitaire' => $result['prix'],
                'quantite' => $nbNuits,
                'prix_total' => $prixTotal)
            ); 

            // 2) puis en ajoutant les services
            $sql = "SELECT * FROM detail_prestation_services WHERE id_factures = ?";
            $result = $bdd->fetchAll($sql, array($this->idFacture)); // on récupère les services commandés dans un array multidimensionnel...

            for($i=0; $i< count($result); $i++){ //... qu'on explore pour en extraire les lignes une par une et les insérer dans la table

                $bdd->insert('detail_factures', array(
                    'id_factures' => $result[$i]['id_factures'],
                    'id_prestation' => $result[$i]['id_prestation'],
                    'prix_unitaire' => $result[$i]['prix_service'],
                    'quantite' => 1, // les services sont des forfaits uniques
                    'prix_total' => $result[$i]['prix_service'])
                ); 
            }

            // on calcule enfin le total chambre + service et la TVA du séjour

            $sql = $bdd->fetchAssoc("SELECT SUM(prix_total) AS 'totalSejour' FROM detail_factures WHERE id_factures =". $this->idFacture ."");

            $totalSejour = intval($sql['totalSejour']); // on récupère le prix total
            $ht = $totalSejour - ($totalSejour/(1+(20.6/100)));
            $tva = round($ht, 2, PHP_ROUND_HALF_UP); // on calcule la tva

            // on complète la table factures, le client n'a plus qu'à payer !
            $bdd->executeQuery("UPDATE factures SET prix_total = ".$totalSejour.", tva = ". $tva ." WHERE id_factures = ". $this->idFacture .""); 

            return true;

        } // fin de facturReserv()

        public function recapReserv() {
           
            $bdd = $this->getDb();

            $prestaChbre = $bdd->fetchAll("SELECT dp.*, df.prix_total, df.quantite, df.prix_unitaire, r.date_debut, r.date_fin FROM detail_prestation_chambre dp, detail_factures df, reservation r WHERE dp.id_prestation = df.id_prestation AND dp.id_reservation = r.id_reservation AND dp.id_reservation =". $this->idReserv ."");

            $prestaService = $bdd->fetchAll("SELECT * FROM detail_prestation_services WHERE id_reservation =". $this->idReserv ."");

            $prestaCout = $bdd->fetchAll("SELECT tva, prix_total FROM factures WHERE id_factures = ". $this->idFacture ."");

            
            $reserv = array('chambres' => array(), 'services' => array(), 'cout' => array(), 'identifiants' => array('nom' => $_SESSION['user']['nom'], 'prenom' => $_SESSION['user']['prenom']));

            for($i=0; $i < count($prestaChbre); $i++){
                $datedebut = new DateTime($prestaChbre[$i]['date_debut']);
                $datefin = new DateTime($prestaChbre[$i]['date_fin']);
                $reserv['chambres'][$i] = array(
                "idReserv" => $prestaChbre[$i]['id_reservation'],
                "catChambre" => $prestaChbre[$i]['type_categorie'],
                "capChambre" => $prestaChbre[$i]['capacite'],
                "debut" => $datedebut->format('d/m/Y'),
                "fin" => $datefin->format('d/m/Y'),
                "nuits" => $prestaChbre[$i]['quantite'],
                "prixUnit" => $prestaChbre[$i]['prix_unitaire'],
                "prixChbre" => $prestaChbre[$i]['prix_total']
                );
            }

            for($i=0; $i< count($prestaService); $i++){
                $reserv['services'][$i] = array(
                "service" => $prestaService[$i]['nom_service'],
                "prixService" => $prestaService[$i]['prix_service'],
                );
            }

            for($i=0; $i< count($prestaCout); $i++){
                $reserv['cout'] = array(
                "tva" => $prestaCout[$i]['tva'],
                "prixTTC" => $prestaCout[$i]['prix_total'],
                "prixHT" => $prestaCout[$i]['prix_total']-$prestaCout[$i]['tva']
                );
            }


            return $reserv;
        }

        
        
        
    } // fin de la classe
