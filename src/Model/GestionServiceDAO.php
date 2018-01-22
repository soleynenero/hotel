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

        // meme code que gestionChambreDAO

        public function selectService()
        {
            $sql = "SELECT * FROM services";
            $service = $this->db->fetchAll($sql);
            return $service ;
        }

        public function insertService($nom_service, $prix_service)
        {
            $bdd = $this->getDb() ;
            $service = $bdd->insert('services', array(
                "nom_service" => $nom_service , 
                "prix_service" => $prix_service ,));

            $id = $bdd->lastInsertId();

            return $id;
        }

        public function selectmodifService($id_services)
        {
            $sql = "SELECT * FROM services WHERE id_services = ? ";
            $service = $this->db->fetchAssoc($sql, array((int) $id_services));
            return $service ;
        }

        public function modifService($id_services,$nom_service, $prix_service)
        {
            $sql = "UPDATE services SET nom_service = ?, prix_service = ? WHERE id_services = ?";

            $servicemodif = $this->db->executeUpdate($sql, array($nom_service , $prix_service , (int) $id_services));
            return $servicemodif;
        }

        public function deleteService($id_services)
        {
            $bdd = $this->getDb() ;
            $deleteservice = $bdd->delete('services', array(
                "id_services" => $id_services)) ;
            return $deleteservice;
        }
    }