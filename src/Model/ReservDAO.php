<?php

    namespace Hotel\Model; 

    use Doctrine\DBAL\Connection;

    class ReservDAO {

        private $db;
        private $idFacture;
        private $idReserv;
        private $idChbre1;

        function __construct($connect) {
            $this->db = $connect;
        }

        protected function getDb() {
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

            $sql1 = "SELECT * FROM chambres WHERE id_categorie = ? AND id_capacite = ? AND statut = 'libre' LIMIT 0,1";
            $result = $bdd->fetchAssoc($sql1, array(0 => $cat1 , 1 => $nbPerson1));
            
                if(!empty($result)) { // si une chambre est dispo

                    // on créé l'id de la future facture
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
                    $this->idChbre1 = $result['id_chambres']; // on récupère l'id de la chambre
                    $bdd->insert('prestation', array(
                        'id_reservation' => $this->idReserv,
                        'id_chambres' => $result['id_chambres']
                    ));

                    // on change le statut de la chambre
                    $bdd->executeUpdate("UPDATE chambres SET statut = 'occupee' WHERE id_chambres = ". $this->idChbre1 ."");

                }
                 
                else // si pas de chambre dispo
                    echo 'y\'a pas de place !';
              
                return "";
            } // fin insertReserv()
         

        // public function factureReserv(){

        //     $bdd = $this->getDb();

        //     $sql = "SELECT * FROM prestation_prix_chambres WHERE id_reservation = ". $this->idReserv."";
        //     $chambre = $bdd->fetchAssoc($sql, array());

            

        //     // // on récupère la prestation de services
        //     // $sql = "SELECT * FROM prestation_prix_services WHERE id_reservation = ". $this->idReserv ."";
        //     // $services = $bdd->fetchAssoc($sql, array()); 
            
        //     // foreach($services as $key => $val){

        //     //     $bdd->insert('detail_factures', array(
        //     //                 'id_factures'=> $this->idFacture,
        //     //                 'prestation_id' => $val
        //     //             ));
        //     //     }

        //         //     $bdd->insert('detail_factures', array(
        //         //         'id_factures'=> $this->idFacture,
        //         //         'prestation_id' => $val
        //         //     ));
                
        //         // if($key == 'prix_service')
        //         //     $bdd->executeUpdate("UPDATE detail_factures SET prix_unitaire = ". $val ." WHERE prestation_id = ". $this->idChbre1 ."");
            

        // } // fin de facturReserv()



} // fin de la classe
?>