<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class RecoveryCode extends Model
{
    use HasFactory;
    protected $table='recovery_codes';
    protected $fillable=['user_id','code','expires_at','is_active'];
    public function user (){
        return$this->belongsTo(User::class);
    }
    //scope para obtener codigos validos
    public function scopeValid($query){
        return $query->where('is_active',1)
        ->where('expires_at','>',now());
    }
    //generador de codigos 
    public static function generateCode(){
        return rand (10000,999999);//codigo numerico de 6 digitos
    }
}

