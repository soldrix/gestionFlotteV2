<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fournisseur extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'fournisseurs';

    protected $fillable =[
        'name',
        'id_users'
    ];
}
