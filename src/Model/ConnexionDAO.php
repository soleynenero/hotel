<?php

    namespace Hotel\Model;

    use Doctrine\Doctrine\DBAL\Connection;

    class ConnexionDAO {

        private $db;
        private $post;

        function __construct($connect){
            $this->db = $connect;
        }
        protected function getDb(){
            return $this->db;
        }

        public function selectUserConnexion($email, $mdp){
            $sql = "SELECT * FROM user WHERE email = ?";
            return $this->db->fetchAssoc($sql, array((string) $email));
            // var_dump($post);
        }
    }