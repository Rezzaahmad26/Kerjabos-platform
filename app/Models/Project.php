<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Project extends Model
{
    use HasFactory, SoftDeletes, HasRoles;
    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'about',
        'category_id',
        'client_id',
        'budget',
        'skill_level',
        'has_started',
        'has_finished',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'client_id', 'id');
    } // function untuk mandakan user yang memiliki project ini

    public function tools() {
        return $this->belongsToMany(Tool::class, 'project_tools', 'project_id', 'tool_id')
        ->wherePivotNull('deleted_at')
        ->withPivot('id');
    }  // function untuk mendapatkan tools yang digunakan pada project ini

    public function applicants() {
        return $this->hasMany(ProjectApplicant::class);
    } // function untuk mendapatkan semua applicant/kandidat yang melamar pada project ini
}
