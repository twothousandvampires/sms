<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Task extends Model implements HasMedia
{

    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'status_id',
        'assigned_id',
        'completion_date',
    ];

    protected $appends = ['attachment_url'];
    protected $hidden = ['media'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('attachments');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_id');
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }
}
