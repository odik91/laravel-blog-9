<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Role extends Model
{
    use HasFactory, Notifiable, SoftDeletes;
    
    protected $guarded = [];
    
    public function Role() {
        return $this->hasMany(User::class);
    }
    
    public function permission() {
        //relation made to permission table
        return $this->hasOne(Permission::class);
    }
}
