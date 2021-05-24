<?php
declare(strict_types=1);

/**
 * Controller por defecto si no se usa el routes
 *
 */

require './../../vendor/autoload.php';
class LoginController extends AppController
{

    public function index()
    {
        if(Input::hasPost("nuevo_usuario")){
            $data = Input::post("nuevo_usuario");
            $usuario  = new Usuarios();
            if(isset($data['name']) && isset($data['pass'])){
                $usuario->nombre = $data['name'];
                $usuario->password = md5($data['pass']);

                $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
                $usuario->secret =  $g->generateSecret();
                if( $usuario->save() ){
                    echo("<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>Registro completado correctamente!</strong>
              </div>");
                }else {
                    echo("<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>Ocurrio un error!</strong> Intentalo nuevamente.
              </div>");
                }
            }

        }


        if (Input::hasPost("login")){
            $login = Input::post("login");
            $pwd = md5($login['password']);
            $usuario=$login['usuario'];

            $auth = new Auth("model", "class: Usuarios", "nombre: $usuario", "password: $pwd");
            if ($auth->authenticate()) {
                return Redirect::to("login/TWOFA/".Auth::get('id'));
            } else {
                echo("<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>Ocurrio un error!</strong> Intentalo nuevamente.
              </div>");
            }
        }

    }

    public function TWOFA($id)
    {
        if(Auth::is_valid()){
            if(Auth::get('id')==$id){
                $usuario = (new Usuarios())->find($id);
                if($usuario->id>0){
                    $secret = $usuario->secret;
                    $img_code = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($usuario->nombre, $secret, 'GoogleAuthenticatorExample');
                }

                if(Input::hasPost("validar")){
                    $data = Input::post("validar");
                    $code = $data['code'];
                    if($usuario->id>0){

                        $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
                        $secret = $usuario->secret;
                        if ($g->checkCode($secret, $code)) {
                            return Redirect::to("/");
                        } else {
                            echo("<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>Ocurrio un error!</strong> Intentalo nuevamente.
              </div>");
                        }
                    }
                }
                $this->img = $img_code;
            }else{
                return Redirect::to("login/");
            }

        }else{
            return Redirect::to("login/");
        }


    }
}
