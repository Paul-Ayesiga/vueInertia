<?php

namespace App\Console\Commands;

use App\Models\Artist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DebugArtistPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:debug-artist-photos {artist_id? : The ID of the artist to check} {--set-test-cover : Set a test cover photo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug artist photos and optionally set a test cover photo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $artistId = $this->argument('artist_id');
        $setTestCover = $this->option('set-test-cover');
        
        if ($artistId) {
            $artist = Artist::find($artistId);
            
            if (!$artist) {
                $this->error("Artist with ID {$artistId} not found");
                return 1;
            }
            
            $this->info("Artist: {$artist->stage_name}");
            $this->info("Profile Photo: " . ($artist->profile_photo ?? 'None'));
            $this->info("Cover Photo: " . ($artist->cover_photo ?? 'None'));
            
            // Check if the files exist
            if ($artist->profile_photo) {
                $exists = Storage::disk('public')->exists($artist->profile_photo);
                $this->info("Profile Photo Exists: " . ($exists ? 'Yes' : 'No'));
            }
            
            if ($artist->cover_photo) {
                $exists = Storage::disk('public')->exists($artist->cover_photo);
                $this->info("Cover Photo Exists: " . ($exists ? 'Yes' : 'No'));
            }
            
            // Option to set a test cover photo
            if ($setTestCover) {
                // Copy the profile photo to use as a test cover photo
                if ($artist->profile_photo && Storage::disk('public')->exists($artist->profile_photo)) {
                    // Get the profile photo content
                    $profilePhotoContent = Storage::disk('public')->get($artist->profile_photo);
                    
                    // Create new path for cover photo
                    $coverPhotoPath = 'cover-photos/' . time() . '_test-cover.jpg';
                    
                    // Store as cover photo
                    Storage::disk('public')->put($coverPhotoPath, $profilePhotoContent);
                    
                    // Update artist
                    $artist->cover_photo = $coverPhotoPath;
                    $artist->save();
                    
                    $this->info("Test cover photo set successfully: {$coverPhotoPath}");
                } else {
                    $this->error("Cannot set test cover photo: Profile photo not found");
                }
            }
        } else {
            // List all artists
            $artists = Artist::all();
            $this->info("All Artists:");
            
            foreach ($artists as $artist) {
                $this->info("{$artist->id}: {$artist->stage_name}");
                $this->info("  Profile Photo: " . ($artist->profile_photo ?? 'None'));
                $this->info("  Cover Photo: " . ($artist->cover_photo ?? 'None'));
            }
        }
        
        return 0;
    }
}
