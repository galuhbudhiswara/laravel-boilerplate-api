<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    use SoftDeletes;

    /**
     * @var string Custom table name
     */
    protected $table = 'ref_roles';

    /**
     * Role constants
     */
    public const ROLE_ADMIN = 'admin';

    /**
     * @var int Auto increments integer key
     */
    public $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];
}
