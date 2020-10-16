<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_survey extends Model
{
    use HasFactory;
    public $table="data_survey";
    protected $fillable=[
        'data'
    ];
}
