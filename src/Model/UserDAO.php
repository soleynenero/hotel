<?php

    namespace Hotel\Model ;
    use Doctrine\DBAL\Connection ;

    class UserDAO
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

        // fonction permettant de récupérer les informations de l'utilisateur
        public function selectUser(int $user_id) :array 
        {
            $sql = "SELECT prenom , nom , email , telephone , ville , code_postal , adresse FROM user WHERE user_id = ?";
            $user = $this->db->fetchAssoc($sql, array((int) $user_id));
            return $user;
        }

        // fonction permettant l'affichage des informations de l'utilisateur
        public function affichageForm (int $user_id) :array
        {
            $sql = "SELECT prenom , nom , email , telephone , ville , code_postal , adresse FROM user WHERE user_id = ?";
            $select = $this->db->fetchAssoc($sql, array((int) $user_id));
            return $select;
        } 

        // fonction permettant de modifier les informations de l'utilisateur en recupérant les donnees du formulaire
        public function modificationFormUser ($prenom , $nom , $email , $telephone , $ville , $code_postal , $adresse, $user_id)
        {
            $sql = "UPDATE user SET prenom = ?, nom = ?, email = ?, telephone = ?, ville = ?, code_postal = ?, adresse = ? WHERE user_id = ?";
            $usermodif = $this->db->executeUpdate($sql, array($prenom , $nom , $email , $telephone , $ville , $code_postal , $adresse, (int) $user_id));
            return $usermodif;
        }

        public function selectModifMdp(int $user_id)
        {
            $sql = "SELECT email , mdp FROM user WHERE user_id = ?";
            $select = $this->db->fetchAssoc($sql, array((int) $user_id));
            return $select;
        }

        public function modificationMdpUser ($mdp , int $user_id)
        {
            $sql = "UPDATE user SET mdp = ? WHERE user_id = ?";
            $usermdpmodif = $this->db->executeUpdate($sql, array($mdp , (int) $user_id));
            return $usermdpmodif;
        }
    }