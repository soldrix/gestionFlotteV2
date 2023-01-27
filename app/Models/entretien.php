<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class entretien extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'entretiens';

    protected $fillable = [
        "type",
        "date",
        "nom",
        "montant",
        "note",
        "id_voiture"
    ];
}
