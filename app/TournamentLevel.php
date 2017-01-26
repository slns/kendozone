<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class TournamentLevel extends Model
{

    protected $table = 'tournamentLevel';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
    ];
    public function getNameAttribute($name)
    {

        return Lang::get($name);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournaments()
    {
        return $this->hasMany('App\Tournament', 'level_id', 'id');
    }


}