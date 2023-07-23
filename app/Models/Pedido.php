<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'produtos',
    ];

    protected $casts = [
        'produtos' => 'array'
    ];

    public function cliente()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
