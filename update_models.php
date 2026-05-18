<?php

$models = [
    'User' => [
        'fillable' => "['name', 'email', 'password', 'avatar', 'house', 'character_class', 'xp', 'coins', 'level', 'theme_preference']",
        'relations' => "
    public function scores() { return \$this->hasMany(Score::class); }
    public function achievements() { return \$this->belongsToMany(Achievement::class, 'user_achievements')->withPivot('unlocked_at'); }
    public function leaderboard() { return \$this->hasOne(Leaderboard::class); }
    public function settings() { return \$this->hasOne(Setting::class); }
    public function notifications() { return \$this->hasMany(Notification::class); }
    public function gameSessions() { return \$this->hasMany(GameSession::class); }
    public function activityLogs() { return \$this->hasMany(ActivityLog::class); }"
    ],
    'Game' => [
        'fillable' => "['slug', 'name', 'description', 'category', 'difficulty', 'image', 'max_score']",
        'relations' => "
    public function scores() { return \$this->hasMany(Score::class); }
    public function sessions() { return \$this->hasMany(GameSession::class); }"
    ],
    'Score' => [
        'fillable' => "['user_id', 'game_id', 'score', 'time_taken', 'difficulty', 'xp_earned', 'coins_earned']",
        'relations' => "
    public function user() { return \$this->belongsTo(User::class); }
    public function game() { return \$this->belongsTo(Game::class); }"
    ],
    'Achievement' => [
        'fillable' => "['name', 'description', 'icon', 'xp_reward', 'coin_reward', 'requirement_type', 'requirement_value']",
        'relations' => "
    public function users() { return \$this->belongsToMany(User::class, 'user_achievements')->withPivot('unlocked_at'); }"
    ],
    'UserAchievement' => [
        'fillable' => "['user_id', 'achievement_id', 'unlocked_at']",
        'relations' => ""
    ],
    'Leaderboard' => [
        'fillable' => "['user_id', 'total_xp', 'total_coins', 'games_played', 'highest_score', 'win_count', 'rank', 'period']",
        'relations' => "
    public function user() { return \$this->belongsTo(User::class); }"
    ],
    'Notification' => [
        'fillable' => "['user_id', 'type', 'title', 'message', 'read_at']",
        'relations' => "
    public function user() { return \$this->belongsTo(User::class); }"
    ],
    'Setting' => [
        'fillable' => "['user_id', 'theme', 'volume', 'notifications_enabled', 'language']",
        'relations' => "
    public function user() { return \$this->belongsTo(User::class); }"
    ],
    'ActivityLog' => [
        'fillable' => "['user_id', 'action', 'details', 'ip_address']",
        'relations' => "
    public function user() { return \$this->belongsTo(User::class); }"
    ],
    'GameSession' => [
        'fillable' => "['user_id', 'game_id', 'started_at', 'ended_at', 'status', 'data']",
        'relations' => "
    public function user() { return \$this->belongsTo(User::class); }
    public function game() { return \$this->belongsTo(Game::class); }"
    ]
];

foreach ($models as $name => $data) {
    $file = __DIR__ . "/app/Models/{$name}.php";
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        $fillable = "    protected \$fillable = {$data['fillable']};";
        $relations = $data['relations'];
        
        // Use a different search pattern for User vs others
        if ($name === 'User') {
            $content = str_replace(
                "protected \$fillable = [", 
                "protected \$fillable = {$data['fillable']};\n    // old fillable [", 
                $content
            );
            $content = str_replace("];\n", "];\n{$relations}\n", $content);
        } else {
            $content = str_replace("{", "{\n{$fillable}\n{$relations}\n", $content);
            // fix double bracket issue if any, but replacing first { of class is safer
            $content = preg_replace('/class\s+'.$name.'\s+extends\s+Model\s*\{/', "class {$name} extends Model\n{\n{$fillable}\n{$relations}\n", $content, 1);
        }
        
        file_put_contents($file, $content);
        echo "Updated {$name}.php\n";
    }
}
