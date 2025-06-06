<?php
namespace chaudiere\core\domain\entities;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'string';
    protected $guarded = ['est_superadmin'];
    public $timestamps = false;

    public function evenements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Box::class, 'createur_id');
    }
}