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

                    if ($listas->titulo == "Canciones no escuchadas" || $listas->titulo == "Ultimas escuchadas"   )
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'No se pueden sobreescribir estos nombres',
                            'data' => []
                        ));
                    }
                        $modelo = Model_Listas::find('all', array(
                        'where' => array(
                            
                            array('titulo', $input['titulo'])
                   
                        )
                     ));
                    if(!empty($modelo))
                    {

                         $json = $this->response(array(
                                    'code' => 400,
                                    'message' => 'Ya hay un ejemplar',
                                    'data' => []
                                ));
                                return $json;
                    }


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

    public function get_listas()
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

         $privacidad = Model_Privacidad::query()->where('id_usuario',$dataJwtUser->id)->get();
                  
                if(!empty($privacidad))
                {
                    foreach ($privacidad as $key => $privado) 
                    {

                        
                        


                        # code...
                        if ($privado->listas == 1)
                        {
                           $json = $this->response(array(
                                'code' => 400,
                                'message' => 'El usuario tiene sus listas en privado',
                                'data' => []
                            ));
                            return $json; 
                        }
                         # code...
                     
                    }
                }

        $input = $_GET;
        $decena = $input['decena_lista']-1;
        if($input['decena_lista'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }
        if($input['decena_lista'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }



       $listas = Model_Listas::query()->where('id_usuario', $dataJwtUser->id)->order_by('titulo')->offset( $decena * 10)->limit(10)->get();
      

       foreach ($listas as $key => $lista) {
           $nomlista[] = $lista->titulo;
           
       }

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Conjunto de listas',
            'data' => $nomlista
        ));

        return $json;

        //return $this->response(Arr::reindex($users));

    }
    public function post_modifyList()
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
        if (  ! isset($_POST['titulo']) || ! isset($_POST['id'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

        $input = $_POST;



        $listas = Model_Listas::find('all', array(
                        'where' => array(
                            array('id_usuario', $dataJwtUser->id),
                            array('id', $input['id']),
                            
                   
                        )
                     ));
        

        if(empty($listas))
        {
            $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Lista no encontrada',
                        'data' => []
                    ));

                    return $json;

        }
      
        foreach ($listas as $key => $lista) 
        {
            if($lista->editable == 1)
            {


            $lista->titulo = $input['titulo'];
            $lista->save();
            }
            else
            {
                $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Lista no editable',
                        'data' => []
                    ));

                    return $json;

            }
        }

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Conjunto de listas',
            'data' => $listas
        ));

        return $json;

        //return $this->response(Arr::reindex($users));

    }

    public function post_delete()
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
        if (  ! isset($_POST['id'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Error en las credenciales, prueba otra vez',
                        'data' => []
                    ));

                    return $json;
                }

        $input = $_POST;




        $listas = Model_Listas::find('all', array(
                        'where' => array(
                            array('id_usuario', $dataJwtUser->id),
                            array('id', $input['id']),
                            
                   
                        )
                     ));
        if (! empty($listas))
        {
            foreach ($listas as $key => $lista) {
            $borrar = $lista;
            }

            $lista->delete();
          

            $json = $this->response(array(
                'code' => 200,
                'message' => 'Lista borrada',
                'data' => []
            ));

            return $json;


        }
        else
        {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'Id no encontrado',
                'data' => []
            ));

            return $json;


        }
        
    }
    

}    
