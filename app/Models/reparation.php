<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reparation extends Model
{
    use HasFactory;

    protected $table = 'reparations';

    protected $primaryKey = "id";

    protected $fillable = [
        "nom",
        "type",
        "date",
        "montant",
        "note",
        "id_voiture"
    ];
}
