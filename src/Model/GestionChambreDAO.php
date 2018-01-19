<?php

    namespace Hotel\Model ;
    use Doctrine\DBAL\Connection ;

    class GestionChambreDAO
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

        public function selectChambre()
        {
            $sql = "SELECT c.id_chambres ,c.numero_chambre , cat.type_categorie AS 'Categorie', cap.capacite , c.telephone ,c.prix , s.statut
            FROM capacite_chambre cap , categorie_chambre cat , chambres c , statut s
            WHERE cat.id_categorie = c.id_categorie
            AND cap.id_capacite = c.id_capacite
            AND s.id_statut = c.id_statut
            ORDER BY c.numero_chambre";
            $chambre = $this->db->fetchAll($sql);
            return $chambre ;
        }

        public function selectCapacite()
        {
            $sql = "SELECT * FROM capacite_chambre";
            $capacite = $this->db->fetchAll($sql);
            return $capacite;
        }

        public function selectCategorie()
        {
            $sql = "SELECT * FROM categorie_chambre";
            $categorie = $this->db->fetchAll($sql);
            return $categorie;
        }

        public function insertChambre($numero_chambre, $id_categorie, $id_capacite, $telephone, $prix)
        {
            $bdd = $this->getDb() ;
            $chambre = $bdd->insert('chambres', array(
                "id_categorie" => $id_categorie , 
                "id_capacite" => $id_capacite , 
                "numero_chambre" => $numero_chambre , 
                "telephone" => $telephone , 
                // "statut" => $statut , 
                "prix" => $prix ,));

            return $chambre ;
        }

        // public function selectmodifChambre($id_chambres)
        // {
        //     $sql = "SELECT c.id_chambres ,c.numero_chambre , cat.type_categorie AS 'Categorie', cap.capacite , c.telephone ,c.prix , c.statut
        //     FROM capacite_chambre cap , categorie_chambre cat , chambres c
        //     WHERE cat.id_categorie = c.id_categorie
        //     AND cap.id_capacite = c.id_capacite
        //     AND c.id_chambres = ? ";
        //     $chambre = $this->db->fetchAssoc($sql, array((int) $id_chambres));
        //     return $chambre ;
        // }

        // public function modifChambre($numero_chambre , $id_categorie , $id_capacite , $telephone , $prix , $statut, $id_chambres)
        // {
        //     $sql = "UPDATE chambres SET numero_chambre = ?, id_categorie = ?, id_capacite = ?, telephone = ?, prix = ?, statut  WHERE id_chambres = ?";

        //     $chambremodif = $this->db->executeUpdate($sql, array($numero_chambre , $id_categorie , $id_capacite , $telephone , $prix , $statut, (int) $id_chambres));
        //     return $chambremodif;
        // }

        // public function deleteChambre($id_chambres)
        // {
        //     $bdd = $this->getDb() ;
        //     $deletechambre = $bdd->delete('chambres', array(
        //         "id_chambres" => $id_chambres)) ;
        //     return $deletechambre;
        // }
    }