<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['project_id', 'judul_tugas', 'anggota_tim', 'deadline', 'prioritas', 'status'])]
class Task extends Model
{
    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
