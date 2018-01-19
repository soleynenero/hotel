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
        public function affichageServices($services)
        {
            $sql = $db->fetchAll('SELECT * FROM services');
           
            return $this->db->fetchAssoc($sql, array((string) $services));
           
        }

       

    }
    