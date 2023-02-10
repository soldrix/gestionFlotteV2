<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commande extends Model
{
    use HasFactory;

    protected $table = 'commandes';

    protected $primaryKey ='id';

    protected  $fillable = [
        'DateDebut',
        'DateFin',
        "id_voiture"
    ];

}
