<?php 

use \Firebase\JWT\JWT;

class Controller_Usuarios extends Controller_Rest
{
    private $key = "juf3dhu3hufdchv3xui3ucxj";

    private $getEmail = "";

                                    //Crear usuario
    public function post_create()
    {

     
        try {

               $roles = Model_Usuarios::find('all', array(
                    'where' => array(
                        array('id_rol', 1),
                       
                    )          
                ));

                if(empty($roles))
                {
                    $json = $this->response(array(
                    'code' => 400,
                    'message' => 'No se puede usar la app hasta que haya minimo un administrador',
                    'data' => []
                ));

                return $json;

                }
                else
                {
                    if ( ! isset($_POST['username']) && ! isset($_POST['password'])&& ! isset($_POST['repeatPassword']) && ! isset($_POST['email'])) 
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
                    if ($input['password'] != $input['repeatPassword'])
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'password y repeatPassword han de coincidir',
                            'data' => []
                        ));

                        
                    }



                    /*$password = ['password' => $input['password']];
                    $dataJwtPassword = JWT::decode($password, $this->key);*/

                    
                    
                    else
                    {
                        $user = new Model_Usuarios();
                        $user->username = $input['username'];
                        $user->password = $input['password'];
                        $user->email = $input['email'];
                        $user->coordenada_X = 0.00;
                        $user->coordenada_Y = 0.00;
                        $user->id_rol = 2;

                        $user->id_device = random_int(0, 1000000);



                        
                    
                    
                   
                        if ($user->username == "" || $user->email == "" || $user->password == "")
                        {
                            $json = $this->response(array(
                                'code' => 400,
                                'message' => 'Se necesita introducir todos los parametros',
                                'data' => []
                            ));
                        }
                        else
                        {


                            $user->save();
                        
                            $dataToken = array(
                                    "id" => $user->id,
                                    "username" => $user->username,
                                    "password" => $user->password,
                                    "email" => $user->email,
                                    "id_rol" => $user->id_rol,
                                    "coordenada_X"=>$user->coordenada_X,
                                    "coordenada_Y"=>$user->coordenada_Y
                                );

                            $token = JWT::encode($dataToken, $this->key);
                            $this->privacityDefault($user->id);
                            $this->listDefault($user->id);

                            $json = $this->response(array(
                                'code' => 200,
                                'message' => 'Usuario creado correctamente',
                                'data' => $token
                            ));

                            return $json;
                        }
                    }
                }
            
        } 
        catch (Exception $e) 
        {
            if($e->getCode() == 23000)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                //'message' => $e->getMessage(),
                ));

                return $json;

            }
            else
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
    }


     public function post_createAdmin()
    {

     
        try {

               
                    if ( ! isset($_POST['username']) && ! isset($_POST['password'])&& ! isset($_POST['repeatPassword']) && ! isset($_POST['email'])) 
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
                    if ($input['password'] != $input['repeatPassword'])
                    {
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'password y repeatPassword han de coincidir',
                            'data' => []
                        ));

                        
                    }



                    /*$password = ['password' => $input['password']];
                    $dataJwtPassword = JWT::decode($password, $this->key);*/

                    
                    
                    else
                    {
                        $user = new Model_Usuarios();
                        $user->username = 'admin';
                        $user->password = '1234';
                        $user->email = 'admin@admin.es';
                        $user->coordenada_X = 0.00;
                        $user->coordenada_Y = 0.00;
                        $user->id_rol = 1;

                        $user->id_device = random_int(0, 1000000);



                        
                    
                    
                   


                            $user->save();
                        
                            $dataToken = array(
                                    "id" => $user->id,
                                    "username" => $user->username,
                                    "password" => $user->password,
                                    "email" => $user->email,
                                    "id_rol" => $user->id_rol,
                                    "coordenada_X"=>$user->coordenada_X,
                                    "coordenada_Y"=>$user->coordenada_Y
                                );

                            $token = JWT::encode($dataToken, $this->key);
                           

                            $json = $this->response(array(
                                'code' => 200,
                                'message' => 'Admin creado correctamente',
                                'data' => $token
                            ));

                            return $json;
                        }
                    
                
            
        } 
        catch (Exception $e) 
        {
            if($e->getCode() == 23000)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                //'message' => $e->getMessage(),
                ));

                return $json;

            }
            else
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
    }
                                    //Mostrar usuarios
    public function get_users()
    {
        $input = $_GET;

    	 $decena = $input['decena_usuarios']-1;
        if($input['decena_usuarios'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }
        if($input['decena_usuarios'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }



       $users = Model_Usuarios::query()->where('id_rol',2)->offset( $decena * 10)->limit(10)->get();

       foreach ($users as $key => $user) 
       {
           # code...
        $privacidad = Model_Privacidad::query()->where('id_usuario',$user->id)->get();
        if(!empty($privacidad))
        {
            foreach ($privacidad as $key => $privado) 
            {
                
                


                # code...
                if ($privado->perfil == 1)
                {
                    $user->cumple = 'privado';

                    $user->descripcion = 'privado';

                    $user->ciudad = 'privado';
                }
                 # code...
                if ($privado->ubicacion == 1)
                {
                    $user->coordenada_X = 'privado';

                    $user->coordenada_Y = 'privado';

                    
                }
                if ($privado->notificaciones == 1)
                {
                    $user->id_device= 'privado';

                            
                }
            }
        }
       }


        $json = $this->response(array(
            'code' => 200,
            'message' => 'Esta es la lista de usuarios',
            'data' => $users

        ));

        return $json;

    	//return $this->response(Arr::reindex($users));

    }
    public function get_usersCercanos()
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

         $decena = $input['decena_usuarios']-1;
        if($input['decena_usuarios'] == '')
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'Introduce una decena',
                'data' => []
            ));
            return $json; 
        }
        if($input['decena_usuarios'] <= 0)
        {
           $json = $this->response(array(
                'code' => 400,
                'message' => 'La decena minima es 1',
                'data' => []
            ));
            return $json; 
        }



       $users = Model_Usuarios::query()->where('id_rol',2)->get();
       foreach ($users as $key => $user) {
           # code...
       

           # code...
        if(abs($dataJwtUser->coordenada_X - $user->coordenada_X)<= 30.0 && abs($dataJwtUser->coordenada_Y - $user->coordenada_Y)<= 30.0 && $dataJwtUser->id != $user->id)      

        {
             $privacidad = Model_Privacidad::query()->where('id_usuario',$user->id)->get();
                  
                if(!empty($privacidad))
                {
                    foreach ($privacidad as $key => $privado) 
                    {

                        
                        


                        # code...
                        if ($privado->perfil == 1)
                        {
                            $user->cumple = 'privado';

                            $user->descripcion = 'privado';

                            $user->ciudad = 'privado';
                        }
                         # code...
                        if ($privado->ubicacion == 1)
                        {
                            $user->coordenada_X = 'privado';

                            $user->coordenada_Y = 'privado';

                            
                        }
                        if ($privado->notificaciones == 1)
                        {
                            $user->id_device= 'privado';

                            
                        }
                    }
                }
                

            $cercanos[] = $user;
        }


       }
       if(empty($cercanos))
        {
            $json = $this->response(array(
                'code' => 400,
                'message' => 'No hay usuarios encontrados',
                'data' => []
            ));
            return $json; 

        }
       $salida = array_slice($cercanos, $decena*10,($decena+1)*10);
        


        $json = $this->response(array(
            'code' => 200,
            'message' => 'Esta es la lista de usuarios cercanos',
            'data' => $salida

        ));

        return $json;

        //return $this->response(Arr::reindex($users));

    }
    /*public function get_user()
    {

            $header = apache_request_headers();
            if (isset($header['Authorization'])) 
                {
                    $token = $header['Authorization'];
                    $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));
                }
        $user = Model_Users::find($dataJwtUser->id);

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Este es el usuario',
            'data' => $user

        ));

        return $json;

        //return $this->response(Arr::reindex($users));

    }*/
    private function privacityDefault($id)
    {
       


        $privacidad= new Model_Privacidad();
        $privacidad->id_usuario = $id;
        $privacidad->perfil = 2;
        $privacidad->amigos = 2;
        $privacidad->listas = 2;
        $privacidad->notificaciones = 2;
        $privacidad->ubicacion = 2;
        $privacidad->save();
       
        






    }  
     private function listDefault($id)
    {
       


        $listaNoEscuchadas= new Model_Listas();
        $listaNoEscuchadas->id_usuario = $id;
        $listaNoEscuchadas->editable = 2;
        $listaNoEscuchadas->titulo = 'Canciones no escuchadas';
        
        $listaNoEscuchadas->save();

        $canciones = Model_Canciones::find('all');

        foreach ($canciones as $key => $cancion) {

            $añadir= new Model_Anyadir();
            $añadir->id_lista = $listaNoEscuchadas->id;
            $añadir->id_cancion = $cancion->id;
            $añadir->save();



       
        }


        
        





        $ultimasEscuchadas= new Model_Listas();
        $ultimasEscuchadas->id_usuario = $id;
        $ultimasEscuchadas->editable = 2;
        $ultimasEscuchadas->titulo = 'Ultimas escuchadas';
        
        $ultimasEscuchadas->save();
       
        






    }  


                                      //Eliminar usuario
    public function post_delete()
    {
        $user = Model_Users::find($_POST['id']);
        $userName = $user->name;
        $user->delete();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'Usuario borrado',
            'name' => $userName
        ));

            return $json;
    }
                                    //login del usuario
    public function get_login()
    {
    try {

     

            if ( empty($_GET['username']) || empty($_GET['password']))
            {
                return $this->response(array(
                    'code' => 400,
                    'message' => 'Hay campos vacios',
                    'data' => []
                ));
            }
            $input = $_GET;

            //$password = ['password' => $users->Password];

          
           // $passwordDecode = JWT::decode($password, $this->key, array('HS256'));

            

            $users = Model_Usuarios::find('all', array(
                        'where' => array(
                            array('username', $input['username']),
                            array('password', $input['password']),
                        )          
                    ));

            

           

            
            
            if ( ! empty($users) )
            {
                

                foreach ($users as $key => $value)
                {
                    $id = $users[$key]->id;
                    $name = $users[$key]->username;
                    $password = $users[$key]->password;
                    $id_rol = $users[$key]->id_rol;
                    
                }
                foreach ($users as $key => $user) 
                {
               
                    $user-> save();
                }
            }
            else
            {
                return $this->response(array(
                    'code' => 400,
                    'message' => 'Usuario y password no coinciden o son incorrectos',
                    'data' => []
                    ));
            }
                
            $dataToken = array(
                "id" => $id,
                "username" => $name,
                "password" => $password,
                "id_rol" => $id_rol,
                "coordenada_X"=>$user->coordenada_X,
                "coordenada_Y"=>$user->coordenada_Y
                );

            $token = JWT::encode($dataToken, $this->key);

            return $this->response(array(
                'code' => 200,
                'message'=> 'Login correcto',
                'data' => $token
                ));
                        
        } 
        catch (Exception $e)
            {
                $json = $this->response(array(
                    'code' => 500,
                    'message' => 'Error de servidor',
                    'data' => $e->getMessage()
                    //'message' => $e->getMessage(),
                ));
                return $json;
            }
        }
                                     //Cambiar la contraseña
    public function get_recoveryPassword()
    {
        try {
            if ( empty($_GET['email'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' =>  'Parametro email no introducido',
                    'data' => []
                ));
                return $json;
            }
            // Validación de e-mail
            $input = $_GET;
            $users = Model_Usuarios::find('all', array(
                'where' => array(
                    array('email', $input['email'])
                )
            ));
            if ( ! empty($users) )
            {
                foreach ($users as $key => $value)
                {
                    $id = $users[$key]->id;
                    $email = $users[$key]->email;

                }
            }
            else
            {
                return $this->response(array(
                    'code' => 400,
                    'message' => 'El email introducido no existe',
                    'data' => []
                    ));
            }
            
                $tokendata = array(
                    'id' => $id,
                    'email' => $email
                   
                );
                $token = JWT::encode($tokendata, $this->key);
                $json = $this->response(array(
                    'code' => 200,
                    'message' => 'Email validado',
                    'data' => $token
                ));
                return $json;
            
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
    }

    public function post_changePassword()
    {

        try
        {
            $header = apache_request_headers();
            if (isset($header['Authorization'])) 
                {
                    $token = $header['Authorization'];
                    $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));


                }

            if (empty($_POST['password'])) 
                {
                    $json = $this->response(array(
                        'code' => 400,
                        'message' =>  'No puede haber campos vacios',
                        'data' => []
                    ));
                    return $json;
                }

            if (($_POST['password']) != ($_POST['repeatPassword'])){
                    $json = $this->response(array(
                        'code' => 400,
                        'message' => 'Password y repeatPassword han de ser iguales',
                        'data' => []
                    ));

                    return $json;

                }

                $input = $_POST;
                $user = Model_Usuarios::find($dataJwtUser->id);
                $user->password = $input['password'];
               
                $user->save();
                                
                $json = $this->response(array(
                    'code' => 200,
                    'message' =>  'Password cambiada correctamente',
                    'data' => []
                ));
                return $json;
              
           
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
    }
    function post_modifyUser()
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

            

                    $input = $_POST;

                    $users = Model_Usuarios::find('all', array(
                            'where' => array(
                                array('id', $dataJwtUser->id)
                            )
                     ));

                    

                    $user = Model_Usuarios::find($dataJwtUser->id);
                    if(empty($user)){
                        $json = $this->response(array(
                            'code' => 400,
                            'message' => 'Usuario no encontrado',
                            'data' => []
                        //'message' => $e->getMessage(),
                        ));

                        return $json;
                    }

                    if($input['password'] != '')
                    {  $user->password = $input['password'];

                    }
                     if($input['coordenada_Y'] != '')
                    {

                    $user->coordenada_Y = $input['coordenada_Y'];

                    }
                     if($input['coordenada_X'] != '')
                    {
                        $user->coordenada_X = $input['coordenada_X'];

                    }
                     if($input['cumple'] != '')
                    {
                         $user->cumple = $input['cumple'];

                    }
                     if($input['ciudad'] != '')
                    {
                         $user->ciudad = $input['ciudad'];

                    }
                    if($input['descripcion'] != '')
                    {
                         $user->descripcion = $input['descripcion'];

                    }
                  
                     if($input['password'] != '')
                    {
                        $this->uploadImage();

                    }
                     
                    
                   
                   
                    
                   
                   
                    
                    
               
                    $user->save();
                                
                    $json = $this->response(array(
                        'code' => 200,
                        'message' =>  'Cambios realizados correctamente',
                        'data' => $users
                     ));
                    return $json;

                
        } catch (Exception $e) {
            if($e->getCode() == 23000)
            {
                return $this->response(array(
                    'code' => 500,
                    'message' => $e->getMessage(),
                    'data' => []
                    ));
            }
        }
    }

    public function uploadImage()
    {

        try{
            $header = apache_request_headers();

            if (isset($header['Authorization'])) 
                {
                    $token = $header['Authorization'];
                    $dataJwtUser = JWT::decode($token, $this->key, array('HS256'));
                }


        // Custom configuration for this upload
        $config = array(
            'path' => DOCROOT . 'assets/img',
            'randomize' => true,
            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
        );

        // process the uploaded files in $_FILES
        Upload::process($config);

        // if there are any valid files
        if (Upload::is_valid())
        {
            
            // save them according to the config
            Upload::save();

            foreach(Upload::get_files() as $file)
            {
                $user = Model_Usuarios::find($dataJwtUser->id);
                $user->foto_perfil = 'http://' . $_SERVER['SERVER_NAME'] . '/appmusicfinal/public/assets/img/' . $file['saved_as'];
                //$user->picture = 'http://' . $_SERVER['SERVER_NAME'] . '/shigui/Shigui/public/assets/img/' . $file['saved_as'];
                $user->save();
               // $this->updatePhoto($user->picture);
                

            }
        }

        return $this->response(array(
            'code' => 200,
            'message' => 'Datos actualizados',
            'data' => [$user]
        ));


        // and process any errors
        

        foreach (Upload::get_errors() as $file)
        {
            return $this->response(array(
                'code' => 500,
                'message' => 'No se ha podido subir la imagen',
                'data' => []
            ));
        }
      
        }catch (Exception $e){
            return $this->response(array(
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ));

        }

    }



   
/*
        function decodeToken()
    {

        $jwt = apache_request_headers()['Authorization'];
        $token = JWT::decode($jwt, $this->key , array('HS256'));
        return $token;
    }

    function userNotRegistered($email)
    {

        $users = Model_Users::find('first', array(
            'where' => array(
                array('email', $email),
                array('is_registered', 0)
                )
            )); 

        if($users != null){
            return true;
        }else{
            return false;
        }
    }
    

    function validateToken($jwt)
    {
        $token = JWT::decode($jwt, $this->key, array('HS256'));

        $email = $token->data->email;
        $password = $token->data->password;
        $id = $token->data->id;

        $users = Model_Users::find('all', array(
            'where' => array(
                array('email', $email),
                array('password', $password),
                array('id',$id)
                )
            ));
        if($users != null){
            return true;
        }else{
            return false;
        }
    }
*/
    



       /*public function post_editProfile()
        {


    //nombre email y foto

        }*/

}    
