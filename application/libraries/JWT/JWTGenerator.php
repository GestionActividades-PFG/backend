<?php 
require APPPATH . '/libraries/JWT/JWT.php';

class CreatorJwt
{
    

    /*************This function generate token private key**************/ 

    PRIVATE $key = "1234567890abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ**^evg.es^*&*fundacionloyola.es"; 
    /**
     * @param data Datos a añadir en el JWT.
     */
    public function GenerateToken($data)
    {          
        return JWT::encode($data, $this->key);
    }
    

    /*************This function DecodeToken token **************/

    public function DecodeToken($token)
    {          
        $decoded = JWT::decode($token, $this->key, array('HS256'));
        $decodedData = (array) $decoded;
        return $decodedData;
    }
}