<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'house',
        'character_class',
        'xp',
        'coins',
        'level',
        'theme_preference'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scores() { return $this->hasMany(Score::class); }
    public function achievements() { return $this->belongsToMany(Achievement::class, 'user_achievements')->withPivot('unlocked_at'); }
    public function leaderboard() { return $this->hasOne(Leaderboard::class); }
    public function settings() { return $this->hasOne(Setting::class); }
    public function notifications() { return $this->hasMany(Notification::class); }
    public function gameSessions() { return $this->hasMany(GameSession::class); }
    public function activityLogs() { return $this->hasMany(ActivityLog::class); }
}
