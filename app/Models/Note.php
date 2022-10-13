<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $guarded = [];
       // we want to use UUID instead of ID as the RouteKey
    //If you would like model binding to always use a database column other than id when retrieving a given model class
    // you  override the getRouteKeyName() method - that's what we are doing here.
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
