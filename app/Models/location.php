<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class location extends Model
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $table = "locations";

    protected $fillable = [
        "DateDebut",
        "DateFin",
        "montant",
        "commandeNumber",
        "id_user",
        "id_voiture"
    ];
}
