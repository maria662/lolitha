<?php


class SensoresController extends RestController{

    public function getAll()
    {
        $datos =  (new Sala())->find();
        $json = [];
        foreach ($datos as $d){
            $estado = intval($d->estado);
            $class = "";
            if($estado==0){
                $class = "b-primary";
            }else{
                $class = "bg-primary";
            }

            $data = [
                "id"=>$d->id,
                "nombre"=> $d->asiento,
                "estado" => $estado,
                "class" => $class
            ];

            array_push($json,$data);

        }
        $this->data = $json;
    }

    public function post_update($id)
    {
        $dato =  (new Sala())->find($id);
        $data = $this->param();
        if($dato->id>0){
            $dato->estado = $data['estado'];
            $dato->save();
        }
        $this->data = $dato;
    }

}