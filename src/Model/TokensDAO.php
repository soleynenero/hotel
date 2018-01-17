<?php

    namespace Hotel\Model;

    use Doctrine\Doctrine\DBAL\Connections;


    class TokensDAO { // notre classe sera un class enfant de Doctrine pour accéder à la bdd sans avoir accès à $app (silex). Ce n'est pas le rôle du model d'avoir accès à tout le projet. Il doit seulement pouvoir manipuler la bdd

        private $db;



        function __construct( $connect){
            $this->db = $connect;
        }

        protected function getDb(){
            return $this->db;
        }


        public function createToken(int $idUser,string $expired, string $type = "email" ): ?string{

            $token = substr( md5( uniqid().mt_rand() ), 0, 22);

            $this->getDb()->insert('tokens', array(
                'token' => $token,
                'date_expiration' =>  $expired,
                'type' => $type,
                'user_id' => $idUser,
                 
                )
            );

            return $token; 

        }




    }