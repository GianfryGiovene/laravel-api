<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'categories';

    // funzione per tabella secondaria "molti" per one to mamy
    public function post(){
        return $this->hasMany ('App\Post');
    }
}
