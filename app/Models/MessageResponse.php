<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageResponse extends Model
{
    protected $fillable = [
        'message_id',
        'student_id',
        'response_body',
        'response_attachments'
    ];

    protected $casts = [
        'response_attachments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the message this response belongs to
     */
    public function message()
    {
        return $this->belongsTo(InternalMessage::class, 'message_id');
    }

    /**
     * Get the student who sent this response
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get all admin responses to this student response
     */
    public function adminResponses()
    {
        return $this->hasMany(AdminResponse::class, 'message_response_id');
    }
}
