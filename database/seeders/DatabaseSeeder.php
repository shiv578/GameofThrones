<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Game;
use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Aegon Targaryen',
            'email' => 'admin@winter.com',
            'password' => Hash::make('password'),
            'house' => 'Targaryen',
            'character_class' => 'Warrior',
            'xp' => 5000,
            'coins' => 10000,
            'level' => 10,
            'theme_preference' => 'fire'
        ]);

        // Games Seeding (15 Games)
        $games = [
            // Brain Games
            ['slug' => 'iq-challenge', 'name' => 'IQ Challenge', 'category' => 'brain', 'description' => 'Test your pattern recognition and logical thinking.', 'difficulty' => 'hard'],
            ['slug' => 'pattern-solver', 'name' => 'Pattern Solver', 'category' => 'brain', 'description' => 'Solve complex numerical and visual patterns.', 'difficulty' => 'medium'],
            ['slug' => 'logic-master', 'name' => 'Logic Master', 'category' => 'brain', 'description' => 'Connect logical gates to complete the circuit.', 'difficulty' => 'hard'],
            
            // Puzzle Arena
            ['slug' => 'maze-escape', 'name' => 'Maze Escape', 'category' => 'puzzle', 'description' => 'Navigate through procedurally generated mazes.', 'difficulty' => 'medium'],
            ['slug' => 'block-puzzle', 'name' => 'Block Puzzle', 'category' => 'puzzle', 'description' => 'Fit the blocks into the grid to clear rows.', 'difficulty' => 'easy'],
            ['slug' => 'treasure-unlock', 'name' => 'Treasure Unlock', 'category' => 'puzzle', 'description' => 'Slide the blocks to free the key.', 'difficulty' => 'medium'],
            
            // Quiz Kingdom
            ['slug' => 'history-quiz', 'name' => 'History Quiz', 'category' => 'quiz', 'description' => 'Test your knowledge of the realm\'s history.', 'difficulty' => 'medium'],
            ['slug' => 'science-quiz', 'name' => 'Science Quiz', 'category' => 'quiz', 'description' => 'Questions on alchemy and natural sciences.', 'difficulty' => 'hard'],
            ['slug' => 'coding-quiz', 'name' => 'Coding Quiz', 'category' => 'quiz', 'description' => 'Runic programming and logic syntax.', 'difficulty' => 'hard'],
            
            // Strategy Lab
            ['slug' => 'kingdom-defense', 'name' => 'Kingdom Defense', 'category' => 'strategy', 'description' => 'Defend your castle from incoming waves.', 'difficulty' => 'hard'],
            ['slug' => 'chess-war', 'name' => 'Chess War', 'category' => 'strategy', 'description' => 'Solve tactical checkmate scenarios.', 'difficulty' => 'hard'],
            ['slug' => 'empire-builder', 'name' => 'Empire Builder', 'category' => 'strategy', 'description' => 'Manage resources to build your kingdom.', 'difficulty' => 'medium'],
            
            // Memory Challenge
            ['slug' => 'memory-flip', 'name' => 'Memory Flip', 'category' => 'memory', 'description' => 'Match pairs of sigils and dragons.', 'difficulty' => 'easy'],
            ['slug' => 'sequence-recall', 'name' => 'Sequence Recall', 'category' => 'memory', 'description' => 'Remember the growing sequence of colors.', 'difficulty' => 'medium'],
            ['slug' => 'hidden-object', 'name' => 'Hidden Object', 'category' => 'memory', 'description' => 'Find the items hidden in the grand hall.', 'difficulty' => 'medium'],

            // Toys Games
            ['slug' => 'toys-game-1', 'name' => 'Toys Game 1', 'category' => 'toys', 'description' => 'First toys game.', 'difficulty' => 'easy'],
            ['slug' => 'toys-game-2', 'name' => 'Toys Game 2', 'category' => 'toys', 'description' => 'Second toys game.', 'difficulty' => 'medium'],
            ['slug' => 'toys-game-3', 'name' => 'Toys Game 3', 'category' => 'toys', 'description' => 'Third toys game.', 'difficulty' => 'hard'],
            ['slug' => 'car-racing', 'name' => 'Car Racing', 'category' => 'toys', 'description' => 'Race the toy car to the finish line.', 'difficulty' => 'medium'],
            ['slug' => 'find-difference', 'name' => 'Find Difference', 'category' => 'toys', 'description' => 'Spot the differences between the two scenes.', 'difficulty' => 'medium'],
        ];

        foreach ($games as $game) {
            Game::create([
                'slug' => $game['slug'],
                'name' => $game['name'],
                'description' => $game['description'],
                'category' => $game['category'],
                'difficulty' => $game['difficulty'],
                'max_score' => 100,
            ]);
        }

        // Achievements Seeding
        $achievements = [
            ['name' => 'First Blood', 'description' => 'Complete your first game.', 'xp' => 100, 'coin' => 50, 'type' => 'games_played', 'val' => 1],
            ['name' => 'Centurion', 'description' => 'Play 100 games.', 'xp' => 1000, 'coin' => 500, 'type' => 'games_played', 'val' => 100],
            ['name' => 'Brain Lord', 'description' => 'Score 100 on any Brain Game.', 'xp' => 500, 'coin' => 200, 'type' => 'score_brain', 'val' => 100],
            ['name' => 'Quiz Master', 'description' => 'Answer 50 quiz questions correctly.', 'xp' => 500, 'coin' => 200, 'type' => 'quiz_correct', 'val' => 50],
            ['name' => 'Strategist', 'description' => 'Win 10 Strategy games.', 'xp' => 500, 'coin' => 200, 'type' => 'strategy_wins', 'val' => 10],
            ['name' => 'XP Hunter', 'description' => 'Earn 10,000 total XP.', 'xp' => 2000, 'coin' => 1000, 'type' => 'total_xp', 'val' => 10000],
            ['name' => 'Coin Hoarder', 'description' => 'Earn 50,000 coins.', 'xp' => 2000, 'coin' => 1000, 'type' => 'total_coins', 'val' => 50000],
        ];

        foreach ($achievements as $ach) {
            Achievement::create([
                'name' => $ach['name'],
                'description' => $ach['description'],
                'xp_reward' => $ach['xp'],
                'coin_reward' => $ach['coin'],
                'requirement_type' => $ach['type'],
                'requirement_value' => $ach['val'],
            ]);
        }
    }
}
