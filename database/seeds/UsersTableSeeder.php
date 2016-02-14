<?php

use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\User\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 50)->create()->each(function (User $user) {

            $timesheets = factory(TimeSheet::class, rand(2, 50))->make();
            $user->timesheets()->saveMany($timesheets);
        });
    }

}