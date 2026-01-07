<?php

namespace Tests\Http\Resources;

use App\Http\Resources\UserResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(UserResource::class)]
class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function failingTest()
    {
        // Given
        $user = User::factory()->create();
        Team::factory()->forEachSequence(['name' => 'hello'], ['name' => 'goodbye'])->for($user)->createMany();

        // And get the user resource as an array
        $userResource = UserResource::make($user->loadMissing(['teams']));


        $userResourceAsArray = $userResource->toArray(Request::capture());

        // When we convert the teams sub-resource to an array
        $teamsAsArray = $userResourceAsArray['teams']->toArray(Request::capture());

        // Then we get back an array
        self::assertIsArray($teamsAsArray);
        self::assertCount(2, $teamsAsArray);
        // And the array is
        self::assertEqualsCanonicalizing([['name' => 'hello'], ['name' => 'goodbye']], $teamsAsArray);
    }
}
