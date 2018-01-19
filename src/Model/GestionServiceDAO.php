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

        public function selectService()
        {
            $sql = "SELECT * FROM services";
            $service = $this->db->fetchAll($sql);
            return $service ;
        }
    }