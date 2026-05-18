<?php

$models = [
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
    
    $content = "<?php\n\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass {$name} extends Model\n{\n    protected \$fillable = {$data['fillable']};\n{$data['relations']}\n}\n";
    
    file_put_contents($file, $content);
    echo "Fixed {$name}.php\n";
}
