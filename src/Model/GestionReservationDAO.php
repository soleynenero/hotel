<?php

    namespace Hotel\Model ;
    use Doctrine\DBAL\Connection ;

    class GestionReservationDAO
    {
        private $db ;

        // mise en place de mon construct
        function __construct($connect)
        {
            $this->db = $connect ; 
        }

        // fonction de récupération de ma variable db car elle est en private
        protected function getDb()
        {
            return $this->db ;
        }

        public function selectReservation(bool $type = true)
        {
            if($type)
                $sql = "SELECT reservation.id_reservation AS 'N° commande', CONCAT(user.prenom, ' ' ,user.nom) AS 'Nom complet', reservation.id_reservation AS 'Numéro de facture', reservation.date_commande, reservation.date_debut, reservation.date_fin, reservation.nb_personne";
            else
                $sql = "SELECT reservation.id_reservation AS 'id', CONCAT(user.prenom, ' ' ,user.nom) AS 'Nom', reservation.id_reservation AS 'NbFacture', reservation.date_commande, reservation.date_debut, reservation.date_fin, reservation.nb_personne";
            $sql .= " FROM reservation, user
            WHERE reservation.user_id = user.user_id";

            $reservation = $this->db->fetchall($sql);
            return $reservation;
        }

        public function selectNumCommande()
        {
            $sql = "SELECT * FROM id_reservation";
            $numCommande = $this->db->fetchAll($sql);
            return $numCommande;
        }

        public function selectNomComplet()
        {
            $sql = "SELECT CONCAT(prenom, ' ', nom) FROM user";
            $nomComplet = $this->db->fetchAll($sql);
            return $nomComplet;
        }

        public function selectNumeroFacture()
        {
            $sql = "SELECT * FROM id_factures";
            $numeroFacture = $this->db->fetchAll($sql);
            return $numeroFacture;
        }
        

        public function selectmodifReservation($id_reservation)
        {
            $sql = "SELECT reservation.id_reservation AS 'id', CONCAT(user.prenom, ' ' ,user.nom) AS 'Nom', reservation.id_reservation AS 'NbFacture', reservation.date_commande, reservation.date_debut, reservation.date_fin, reservation.nb_personne FROM reservation, user
            WHERE reservation.user_id = user.user_id AND reservation.id_reservation = ?";
            $reservation = $this->db->fetchAssoc($sql, array((int) $id_reservation));
            return $reservation ;
        }

        public function modifReservation($id_reservation, $date_debut, $date_fin, $nb_personne)
        {
            $sql = "UPDATE reservation SET date_debut = ?, date_fin = ?, nb_personne= ? WHERE id_reservation = ?";

            $reservationModif = $this->db->executeUpdate($sql, array($date_debut, $date_fin, $nb_personne, (int)$id_reservation));
            return $reservationModif;
        }

    }