<?php 
require APPPATH . '/libraries/JWT/JWT.php';

class CreatorJwt
{
    

    /*************This function generate token private key**************/ 

    PRIVATE $key = "CAMBIAR_CLAVE_PONER_MAS_DE_20_CARACTERES"; 
    /**
     * @param data Datos a añadir en el JWT.
     */
    public function GenerateToken($data)
    {          
        $jwt = JWT::encode($data, $this->key);
        header("Authorization: bearer " . $jwt);
        return $jwt;
    }
    

    /*************This function DecodeToken token **************/

    public function DecodeToken($token)
    {          
        $decoded = JWT::decode($token, $this->key, array('HS256'));
        $decodedData = (array) $decoded;
        return $decodedData;
    }
}