<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingResult extends Model
{
    protected $table = 'announced_pu_results';
    public $timestamps = false;


    use HasFactory;
}
