<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class agence extends Model
{
    use HasFactory;

    protected $table = 'agence';

    protected $primaryKey = 'id';

    protected $fillable =[
      "ville",
      "rue",
      "codePostal"
    ];

}
