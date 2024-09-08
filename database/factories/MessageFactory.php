<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // GENERATE MESSAGES FOR CONVERSATION BETWEEN USERS
        // generate random senderId
        $senderId = $this->faker->randomElement([0,1]);

        if ($senderId == 0) {
            // if generated senderId is 0, then receiverId should be 1, and senderId should be a random user
            $senderId = $this->faker->randomElement(User::where('id', '!=', 1)->pluck('id')->toArray());
            $receiverId = 1;
        } else {
            // if generated senderId is 1, then receiverId should be a random user
            $receiverId = $this->faker->randomElement(User::where('id', '!=', 1)->pluck('id')->toArray());;
        }

        // GENERATE MESSAGES FOR GROUP CHAT
        $groupId = null;

        // 50% chance of generating group chat messages
        if ($this->faker->boolean(50)) {

            // if true, then generate random groupId
            $groupId = $this->faker->randomElement(Group::pluck('id')->toArray());

            // select group based on groupId
            $group = Group::find($groupId);

            // select random senderId from group users
            $senderId = $this->faker->randomElement($group->users->pluck('id')->toArray());

            // set receiverId to null
            $receiverId = null;
        }

        return [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'group_id' => $groupId,
            'message' => $this->faker->realText(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
