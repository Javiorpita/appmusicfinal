<?php 

use \Firebase\JWT\JWT;

class Controller_Anyadir extends Controller_Rest
{
    private $key = "juf3dhu3hufdchv3xui3ucxj";
   

                                    //Crear usuario
    public function post_add()
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
                    'message' => 'Solo los usuarios pueden añadir listas modificables a canciones',
                    'data' => []
                ));
                return $json;

            }
            else
            {   



                if (  ! isset($_POST['id_lista'])|| ! isset($_POST['id_cancion'])) 
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
                
                    $listasCanciones = new Model_Anyadir();
                    $listasCanciones->id_lista = $input['id_lista'];
                    $listasCanciones->id_cancion = $input['id_cancion'];

                   
                    
                   


                    
                
                
               
                    if ($listasCanciones->id_lista == "" || $listasCanciones->id_cancion == ""   )
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Se necesita introducir todos los parametros',
                            'data' => []
                        ));
                    }



                    else
                    {
                        $listas = Model_Listas::find('all', array(
                            'where' => array(
                                array('id', $input['id_lista']),
                               
                       
                            )
                         ));


                        

                        

                        if(empty($listas))
                        {
                             $json = $this->response(array(
                                'code' => 400,
                                'message' => 'lista no encontrada',
                                'data' => []
                            ));

                        }
                        foreach ($listas as $key => $lista) {
                           
                            # code...
                        }

                        if ($lista->editable == 2 || $lista->id_usuario != $dataJwtUser->id)
                        {
                            $json = $this->response(array(
                                'code' => 400,
                                'message' => 'lista no editable',
                                'data' => []
                            ));

                        }
                        else
                        {



                            $listasCanciones->save();
                            
                            

                            $json = $this->response(array(
                                'code' => 200,
                                'message' => 'Cancion añadida a '. $lista->titulo. ' correctamente',
                                'data' => $listasCanciones
                            ));

                            return $json;
                        }
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

    public function get_canciones()
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
                                'message' => 'El usuario tiene las canciones de las listas en privado',
                                'data' => []
                            ));
                            return $json; 
                        }
                         # code...
                     
                    }
                }
        $input = $_GET;


         $decena = $input['decena_canciones']-1;
        if($input['decena_canciones'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }
        if($input['decena_canciones'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }


        $añadir = Model_Anyadir::find('all', array(
                        'where' => array(
                            array('id_lista', $input['id_lista']),


                            
                            
                   
                        )
                     ));
        if (empty($añadir))
        {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'lista vacia',
                'data' => []
            ));
            return $json;

        }
        else
        {
           



            foreach ($añadir as $key => $añadido) {

                  $canciones= Model_Canciones::query()->where('id',$añadido->id_cancion)->get();
                  foreach ($canciones as $key => $cancion) {
                      # code...
                  }
                 
                 
                  $bonito[] = $cancion->titulo;
                # code...
            }

            $salida = array_slice($bonito, $decena*10,($decena+1)*10);
             
             $listas= Model_Listas::query()->where('id',$añadido->id_lista)->get();
                  foreach ($listas as $key => $lista) {
                      # code...
                  }
                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Conjunto de canciones de la lista '.$lista->titulo,
                    'data' => $salida
                ));

                return $json;
            
        }

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
        $input = $_POST;

        if (  ! isset($_POST['id_cancion'])|| ! isset($_POST['id_lista']) ) 
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Error en las credenciales, prueba otra vez',
                            'data' => []
                        ));

                        return $json;
                    }

        $listas = Model_Listas::find('all', array(
                        'where' => array(
                            
                            array('id', $input['id_lista']),


                            
                   
                        )
                     ));
        if (empty($listas))
        {
            $json = $this->response(array(
                                'code' => 400,
                                'message' => 'lista no encontrada ',
                                'data' => []
                            ));


        } 
        else
        {


            foreach ($listas as $key => $lista) {
                # code...
            }
            if ($lista->editable == 2 || $lista->id_usuario != $dataJwtUser->id)
                            {
                                $json = $this->response(array(
                                    'code' => 400,
                                    'message' => 'lista no editable',
                                    'data' => []
                                ));

                            }
            else
            {
                 
             



                $añadir = Model_Anyadir::find('all', array(
                                'where' => array(
                                    array('id_lista', $lista->id),
                                    array('id_cancion', $input['id_cancion']),
                                    
                           
                                )
                             ));
                if(! empty($añadir))
                {

                    foreach ($añadir as $key => $añade) {
                        
                        # code...
                    }

                    Model_Anyadir::find($añade);
                    try
                    {
                        $canciones = Model_Canciones::find('all', array(
                                'where' => array(
                                    
                                    array('id', $input['id_cancion']),
                                    
                           
                                )
                             ));
                        foreach ($canciones as $key => $cancion) 
                        {
                            # code...
                            $datoCancion = $cancion->titulo;
                        }

                        $datoLista = $lista->titulo;

                        $añade->delete(); 
                    }
                    catch (Exception $e)
                    {

                    }


                    $json = $this->response(array(
                        'code' => 200,
                        'message' => $datoCancion. ' borrada de '. $datoLista,
                        'data' => []
                    ));

                    return $json;
                }
                else
                {
                    $json = $this->response(array(
                                'code' => 400,
                                'message' => 'Cancion no encontrada',
                                'data' => []
                            ));

                            return $json;
                }
            } 
        }   

        //return $this->response(Arr::reindex($users));

    }

    public function get_mostViewed()
    {
         $canciones = Model_Canciones::query()->order_by('reproducciones', 'desc')->limit(10)->get();
         
         foreach ($canciones as $key => $cancion) {
            $cancionFormated[] = $cancion;
             # code...
         }



        $json = $this->response(array(
            'code' => 200,
            'message' => 'Esta es la lista de canciones mas escuchadas',
            'data' => $cancionFormated
        ));

        return $json;

        //return $this->response(Arr::reindex($users));

    } 


    public function get_cancionesSinEscuchar()
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
        $input = $_GET;


        $añadir = Model_Anyadir::find('all', array(
                        'where' => array(
                            array('id_lista', $input['id_lista']),


                            
                            
                   
                        )
                     ));
        if (empty($añadir))
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'lista no encontada',
                'data' => []
            ));
            return $json;

        }
        else
        {
            $listas = Model_Listas::find('all', array(
                        'where' => array(
                            array('id', $input['id_lista']),
                            array('id_usuario', $dataJwtUser->id),



                            
                            
                   
                        )
                     ));

            if (empty($listas))
            {
                $json = $this->response(array(
                'code' => 500,
                'message' => 'la lista no pertenece al usuario',
                'data' => []
            ));
            return $json;

            }
            else
            {




                foreach ($añadir as $key => $añade) {
                    $añadido[] = $añade;
                    # code...
                }

                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Conjunto de canciones',
                    'data' => $añadido
                ));

                return $json;
            }
        }

        //return $this->response(Arr::reindex($users));

    }//Mostrar usuarios
    

    

}    


