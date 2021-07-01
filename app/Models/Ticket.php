<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['sort', 'name', 'amount', 'start_date', 'end_date'];
}
