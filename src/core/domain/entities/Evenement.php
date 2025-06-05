<?php

namespace chaudiere\core\domain\entities;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    protected $table = 'evenement';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    public function createur(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function categorie(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

}
