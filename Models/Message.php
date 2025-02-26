<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['conversation_id', 'sender_id', 'receiver_id', 'content', 'is_read', 'media_path'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'receiver_id', 'id')->latestOfMany();
    }
  
}
