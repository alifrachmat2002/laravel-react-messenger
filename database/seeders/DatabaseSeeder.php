<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(10)->create();

        for($i = 0; $i <= 5; $i++) {
            $group = Group::factory()->create([
                'owner_id' => 1,
            ]);

            $user = User::inRandomOrder()->limit(rand(2,5))->pluck('id');

            $group->users()->attach(array_unique([1, ...$user]));
        }

        Message::factory(1000)->create();
        $messages = Message::whereNull('group_id')->orderBy('created_at')->get();

        // create conversation instance for each message
        $conversations = $messages->groupBy(function($message) {
            // sort the sender and receiver id to create a unique conversation key
            return collect([$message->sender_id, $message->receiver_id])->sort()->implode('_');
        })->map(function($groupedMessage) {
            // return conversation instance from the grouped messages
            return [
                'user_id1' => $groupedMessage->first()->sender_id,
                'user_id2' => $groupedMessage->first()->receiver_id,
                'last_message_id' => $groupedMessage->last()->id,
                'created_at' => new Carbon(),
                'updated_at' => new Carbon(),
            ];
        })->values();

        Conversation::insertOrIgnore($conversations->toArray());
    }
}
