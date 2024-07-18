<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\File;
use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;

class CreateRandomUsersWithPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-random-users-with-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Creating randoms users");

        try {
            User::factory(5)->create();

            $this->info("5 users created");

            User::all()->each(function($user, $index){

                $followers = User::where('id', '!=', $user->id)->inRandomOrder()->limit(rand(1, 3))->get();

                $user->following()->attach($followers->pluck('id'));

                $totalPosts = rand(1, 4);
                
                $this->info("Creating {$totalPosts} posts for user {$index}");

                Post::factory( $totalPosts )->make()->each(function($post, $index) use ($user) {
                    $totalFiles = rand(1, 3);
                    
                    $this->info("Downloading {$totalFiles} random images for post {$index}");
                    
                    $files = File::factory($totalFiles)->create();
                    
                    $this->info("Random images downloaded");

                    sleep(2);
                    
                    $user->posts()->save($post);
                    
                    //
                    $post->files()->attach($files->pluck('id'));
                    $post->comments()->saveMany(
                        Comment::factory(rand(5, 15))->make()
                    );
                });
            });

            $this->info("Randoms users finished");
        } catch (\Throwable $th) {
            $this->error("Something went wrong creating users: {$th->getMessage()}");
        }
    }
}
