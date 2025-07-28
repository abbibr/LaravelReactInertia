<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Task\Models\Task;
use Modules\User\Models\User;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
