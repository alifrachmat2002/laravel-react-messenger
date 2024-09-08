<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id2', 'user_id1', 'last_message_id'];

    public function user1() {
        return $this->belongsTo(User::class, 'user_id1');
    }

    public function user2() {
        return $this->belongsTo(User::class, 'user_id2');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function lastMessage() {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    
}
