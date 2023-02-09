<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class voitureFournisseur extends Model
{
    use HasFactory;

    protected $table = 'voitures_fournisseur';

    protected $primaryKey = 'id';

    protected $fillable =[
        "image",
        "marque",
        "model",
        "statut",
        "puissance",
        "carburant",
        "type",
        "nbPorte",
        "nbPlace",
        'id_fournisseur'
    ];

}
