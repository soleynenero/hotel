<?php

    namespace Hotel\Model ;
    use Doctrine\DBAL\Connection ;

    class GestionMembreDAO
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

        public function selectMembre()
        {
            $sql = "SELECT prenom , nom , telephone , email , adresse , ville , code_postal  FROM user WHERE statut = 'standard' ";
            $membre = $this->db->fetchAll($sql);
            return $membre ;
        }
    }