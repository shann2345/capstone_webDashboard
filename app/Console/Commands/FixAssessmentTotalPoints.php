<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Assessment;

class FixAssessmentTotalPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assessment:fix-total-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix total_points for existing assessments by auto-calculating from question points for quizzes/exams';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix assessment total points...');
        
        // Get all assessments
        $assessments = Assessment::with('questions')->get();
        
        $fixed = 0;
        $skipped = 0;
        
        foreach ($assessments as $assessment) {
            $assessmentType = strtolower($assessment->type);
            
            // For quiz/exam types, auto-calculate if total_points is null or seems incorrect
            if (in_array($assessmentType, ['quiz', 'exam'])) {
                $calculatedPoints = $assessment->questions->sum('points');
                
                // If no total_points set, or if it's 1 (likely incorrect), update it
                if (is_null($assessment->total_points) || $assessment->total_points <= 1) {
                    $newTotalPoints = $calculatedPoints > 0 ? $calculatedPoints : 100;
                    
                    $assessment->update(['total_points' => $newTotalPoints]);
                    
                    $this->line("Fixed {$assessment->type} '{$assessment->title}': {$assessment->total_points} -> {$newTotalPoints} points");
                    $fixed++;
                } else {
                    $this->line("Skipped {$assessment->type} '{$assessment->title}': already has total_points = {$assessment->total_points}");
                    $skipped++;
                }
            } else {
                // For assignments, ensure total_points is set (should be from form validation)
                if (is_null($assessment->total_points)) {
                    $assessment->update(['total_points' => 100]); // Default fallback
                    $this->line("Fixed {$assessment->type} '{$assessment->title}': null -> 100 points (default)");
                    $fixed++;
                } else {
                    $skipped++;
                }
            }
        }
        
        $this->info("Completed! Fixed: {$fixed}, Skipped: {$skipped}");
        
        return 0;
    }
}
