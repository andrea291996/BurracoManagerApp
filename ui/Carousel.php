<?php

class Carousel extends PageElement{

    public function __construct($template="ui/carousel", $data=[]){
        parent::__construct($template, $data);
        $this->data['images']=[];
    }

        //prova a fare una classe immagine
    //public function addImage($immagine){
    //  $this->data['images'][] = $immagine;
    //}
    public function addImage($src, $alt, $active = false){
        $this->data['images'][] = [
            'src'=>$src,
            'alt'=>$alt,
            'active'=>$active
        ];
    }

    public function removeImage($src){
        foreach ($this->data['images'] as $indice => $immagine) {
            if ($immagine['src'] === $src) {
                unset($this->data['images'][$indice]);
                //questo array_values serve per non avere buchi nell'array
                $this->data['images'] = array_values($this->data['images']);
                return true;
            }
        }
        return false;
    }

    public function setId($id){
        $this->data['id'] = $id;
    }

    public function getId(){
        return $this->data['id'];
    }
}