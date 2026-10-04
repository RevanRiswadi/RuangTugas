<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_proyek', 'mata_pelajaran', 'deskripsi', 'deadline', 'status'])]
class Project extends Model
{
    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
