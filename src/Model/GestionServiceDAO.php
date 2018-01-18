<?php

    namespace Hotel\Model ;
    use Doctrine\DBAL\Connection ;

    class GestionServiceDAO
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

        // fonction permettant de récupérer les informations de la table service
        public function selectService(int $id_services) :array 
        {
            $sql = "SELECT id_services , nom_service , prix_service  FROM user WHERE id_services = ?";
            $service = $this->db->fetchAssoc($sql, array((int) $id_services));
            return $service;
           
        }

       

    }