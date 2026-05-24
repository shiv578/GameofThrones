<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionPool extends Model
{
    protected $table = 'question_pools';

    protected $fillable = [
        'category',
        'difficulty',
        'question',
        'options',
        'correct_answer',
        'explanation',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope: only active questions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
