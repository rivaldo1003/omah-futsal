<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function teams()
    {
        // Laravel akan otomatis mencari pivot table 'group_team'
        return $this->belongsToMany(Team::class, 'group_team');
    }
}
