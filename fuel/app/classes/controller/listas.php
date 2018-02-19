<?php 

use \Firebase\JWT\JWT;

class Controller_Listas extends Controller_Rest
{
    private $key = "juf3dhu3hufdchv3xui3ucxj";
   

                                    //Crear usuario
    public function post_create()
    {
        try {

            try
            {
                $headers = apache_request_headers();
                $token = $headers['Authorization'];
                $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));

        
      

                $users = Model_Usuarios::find('all', array(
                    'where' => array(
                        array('id', $dataJwtUser->id),
                        array('username', $dataJwtUser->username),
                        array('password', $dataJwtUser->password)
               
                    )
                 ));

            }    
            catch (Exception $e)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                ));
                return $json;
               
            }
            foreach ($users as $key => $user)
            {
                $rol = $user->id_rol;
            }
            
            if ($rol == 1)
            {

                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Solo los usuario pueden crear listas modificables',
                    'data' => []
                ));
                return $json;

            }
            else
            {    


                if (  ! isset($_POST['titulo'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

                /*if (strlen($_POST['password']) < 6 || strlen($_POST['password']) >12){
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Contraseña: entre 6 y 12 caracteres',
                        'data' => []
                    ));

                    return $json;

                }*/


              

                $input = $_POST;
                
                    $listas = new Model_Listas();
                    $listas->editable= 1;
                    $listas->id_usuario = $dataJwtUser->id;
                    $listas->titulo= $input['titulo'];
                    
                   


                    
                
                
               
                    if ($listas->id_usuario == "" || $listas->titulo == ""   )
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Se necesita introducir todos los parametros',
                            'data' => []
                        ));
                    }
                    else
                    {


                        $listas->save();
                        
                        

                        $json = $this->response(array(
                            'code' => 200,
                            'message' => 'Cancion creada correctamente',
                            'data' => $listas
                        ));

                        return $json;
                    }
            }
            
            
        } 
        catch (Exception $e) 
        {
           
            

                $json = $this->response(array(
                    'code' => 500,
               // 'message' => $e->getCode()
                    'message' => $e->getMessage(),
                    'data' => []
                ));

                return $json;

            
        }        
    }

    public function get_mostViewed()
    {
         $canciones= Model_Canciones::find('all', array(
          
            'order_by' => array('reproducciones' => 'desc')
        ));

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Esta es la lista de canciones mas escuchadas',
            'data' => $canciones
        ));

        return $json;

        //return $this->response(Arr::reindex($users));

    }
    public function get_reproduceSong()
    {
        try
            {
                $headers = apache_request_headers();
                $token = $headers['Authorization'];
                $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));

        
      

                $users = Model_Usuarios::find('all', array(
                    'where' => array(
                        array('id', $dataJwtUser->id),
                        array('username', $dataJwtUser->username),
                        array('password', $dataJwtUser->password)
               
                    )
                 ));

            }    
        catch (Exception $e)
        {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                ));
                return $json;
               
        }
        foreach ($users as $key => $user)
        {
            $rol = $user->id_rol;
        }
            
        if ($rol != 1)
        {

                $input = $_GET;
                $canciones = Model_Canciones::find('all', array(
                            'where' => array(
                                array('id', $input['id']),

                                
                       
                            )
                         ));
                foreach ($canciones as $key => $cancion) {
                    $cancion->reproducciones += 1;
                    $cancion->save();
                    # code...
                }

                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Cancion escuchada',
                    'data' => $canciones
                ));

                return $json;
        }
        else
        {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'Solo pueden escuchar los usuarios',
                    'data' => []
                ));


        }

        //return $this->response(Arr::reindex($users));

    }
                                    //Mostrar usuarios
    

    }    