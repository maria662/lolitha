<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
class IndexController extends AppController
{

    public function index()
    {
        if(Auth::is_valid()){
            if (Input::hasPost("signout")){
                Auth::destroy_identity();
                return Redirect::to("login/");
            }
        }else{
            return Redirect::to("login/");
        }
    }
}
