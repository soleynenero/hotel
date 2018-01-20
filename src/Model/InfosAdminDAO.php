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
            $sql = "SELECT COUNT(*) AS totalUser FROM user WHERE statut != 'admin'";
            $totalUser  = $this->db->fetchAssoc($sql);
            return $totalUser;
        }

        public function selectAllRoomVacancy()
        {
            $sql ="SELECT COUNT(*) AS totalRoomVac FROM chambres WHERE id_statut = 1";
            $totalRoom = $this->db->fetchAssoc($sql);
            return $totalRoom;
        }

        public function selectAllRoomNoVacancy()
        {
            $sql ="SELECT COUNT(*) AS totalRoomNoVac FROM chambres WHERE id_statut = 2";
            $totalRoomNoVac = $this->db->fetchAssoc($sql);
            return $totalRoomNoVac;
        }
        
    }