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
            $sql = "SELECT c.numero_chambre AS 'Numero chambre' , cat.type_categorie AS 'Categorie', cap.capacite , c.telephone ,c.prix , c.statut
            FROM capacite_chambre cap , categorie_chambre cat , chambres c
            WHERE cat.id_categorie = c.id_categorie
            AND cap.id_capacite = c.id_capacite ";
            $chambre = $this->db->fetchAll($sql);
            return $chambre ;
        }
    }