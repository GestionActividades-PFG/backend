<?php
/**
 * M_General
 * 
 * Clase para realizar las consultas de toda la aplicación.
 * 
 * @author Abraham Núñez Palos y Daniel Torres Galindo
 */

class M_General extends CI_Model
{
	/**
	 * Guarda el valor de carga de la base de datos.
	 * 
	 * @var undefined
	 *
	 */
	
	var $bd = null;

	/**
	 * __construct
	 * 
	 * Carga la base de datos en la variable bd.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this -> bd = $this -> load -> database('default',true);
	}

	/**
	 * insertar
	 * 
	 * Función que permite insertar datos en la base de datos.
	 * 
	 * @param string $tabla Nombre de la tabla en la base de datos.
	 * @param mixed $datos  Datos para añadir a la base de datos.
	 * 
	 * @return void
	 */

	public function insertar($tabla,$datos)
	{
		if($this -> bd -> insert($tabla,$datos))
			return $this -> bd -> insert_id();
		else
			echo $this -> bd -> error();
	}

	/**
	 * borrar
	 * 
	 * Función que permite eliminar elemento por id.
	 * 
	 * @param string $tabla Nombre de la tabla en la base de datos.
	 * @param mixed $id Identificador de la tabla.
	 * @param string $nombreId Nombre del identificador de la tabla.
	 * 
	 * @return void
	 */

	public function borrar($tabla,$id,$nombreId)
	{
		$this -> bd -> delete($tabla,array($nombreId => $id));
	}
	
	/**
	 * borrarCompuesta
	 * 
	 * Funcion que permite eliminar elemento con más de un id.
	 *
	 * @param  string $tabla Nombre de la tabla en la base de datos.
	 * @param  mixed $id1 Identificador de la tabla.
	 * @param  mixed $id2 Identificador de la tabla.
	 * @param  string $nombreId1 Nombre del identificador de la tabla.
	 * @param  string $nombreId2 Nombre del identificador de la tabla.
	 * 
	 * @return void
	 */
	public function borrarCompuesta($tabla,$id1,$id2,$nombreId1,$nombreId2)
	{
		$this -> bd -> delete($tabla,array($nombreId1 => $id1, $nombreId2 => $id2));
	}
	
	/**
	 * modificar
	 * 
	 * Función que permite actualizar datos de la base de datos.
	 *
	 * @param  string $tabla Nombre de la tabla en la base de datos.
	 * @param  mixed $datos  Datos para añadir a la base de datos.
	 * @param  mixed $id Identificador de la tabla.
	 * @param  string $nombreId Nombre del identificador de la tabla.
	 * 
	 * @return void
	 */
	public function modificar($tabla,$datos,$id,$nombreId)
	{
		foreach($datos as $indice => $valor)
			$this -> bd -> set($indice, $valor);
		$this -> bd -> where($nombreId, $id);
		$this -> bd -> update($tabla);
	}
	
	/**
	 * seleccionar
	 * 
	 * Función que permite realizar consulta que recupera datos de la base de datos.
	 *
	 * @param  string $tabla Nombre de la tabla en la base de datos.
	 * @param  string $campos Nombre de los campos de la base de datos.
	 * @param  mixed $condicion Parametro para con la condicion de la consulta (equivalente WHERE).
	 * @param  array|null $tabla_relacion Nombre de la tablas con la que quieres asociar tablas (Por defecto: null).
	 * @param  array|null $relacion Nombre del los campos con los que quieres realizar la relacion (Por defecto: null).
	 * @param  array $tipo_relacion Tipo de relación (Por defecto: ['join]').
	 * @param  string $ordenacion  Nombre del campo por el que quieres ordenar.
	 *  
	 * @return void
	 */
	public function seleccionar($tabla, $campos, $condicion = null,$tabla_relacion = null, $relacion = null, $tipo_relacion = ['join'], $ordenacion = null)
	{
		$this -> bd -> select($campos);
		$this -> bd -> from($tabla);
		if(isset($relacion) && isset($tabla_relacion) && sizeof($tabla_relacion) == sizeof($relacion) && sizeof($tabla_relacion) == sizeof($tipo_relacion))
			for($i = 0; $i < sizeof($tabla_relacion); $i++)
				$this -> bd -> join($tabla_relacion[$i],$relacion[$i],$tipo_relacion[$i]);
		if(isset($condicion))
			$this -> bd -> where($condicion);
		if(isset($ordenacion))
			$this -> bd -> order_by($ordenacion);
		$query = $this -> bd -> get();
		$rows = $query -> result_array();
		$query -> free_result();
		return $rows;
	}
	
	/**
	 * obtenerIdUsuario
	 * 
	 * Función que permite obtener el id del usuario a partir de un correo.
	 *
	 * @param  string $correo Correo del usuario.
	 * 
	 * @return void
	 */
	public function obtenerIdUsuario($correo)
	{
		$this -> bd -> select('idUsuario');
		$this -> bd -> from('Usuarios');
		$this -> bd -> where('correo='.$this->bd->escape($correo));
		$query = $this -> bd -> get();
		if($query -> num_rows() > 0) 
		{
			$rows = $query -> result_array();
			$idUsuario = $rows[0]['idUsuario'];
		}
		else
			return false;
		$query -> free_result();
		return $idUsuario;
	}
}

?>
