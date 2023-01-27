<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consommation extends Model
{
    use HasFactory;

    protected $table = 'consommations';

    protected $primaryKey = 'id';

    protected $fillable = [
        "litre",
        "montant",
        "id_voiture"
    ];
}
