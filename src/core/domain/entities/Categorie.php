<?php

namespace chaudiere\core\domain\entities;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'categorie';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    public function evenements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Evenement::class, 'categorie_id');
    }
}