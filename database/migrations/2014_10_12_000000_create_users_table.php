<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User as User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('fname',50);
            $table->string('lname',50);
            $table->string('email',200)->unique()->index();
            $table->string('phone',25)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('language',2)->default('en');            
            $table->float('rate_per_hour',8,2, true)->default(0);
            $table->integer('role_id')->default(99);
            $table->integer('sales_goals')->default(5000000)->nullable();
            $table->unsignedBigInteger('old_id')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        // create developers
        $data =  array(
            [
                'fname' => 'Michael',
                'lname' => 'Trachtenberg',
                'email' => 'mike.trachtenberg@gmail.com',
                'password' => Hash::make('3408EKwqPaving#*1'),
                'rate_per_hour' => 0,
                'phone' =>'7862675461',
                'status' => 1,
                'role_id' => 7,
                'language' => 'en',

            ],
            [
                'fname' => 'Jose',
                'lname' => 'Vidal',
                'email' => 'josei.vidal@yahoo.com',
                'password' => Hash::make('JoseL@Paving1@3'),
                'rate_per_hour' => 0,
                'phone' =>'7862675461',
                'status' => 1,
                'role_id' => 7,
                'language' => 'es',

            ],
        );
        foreach ($data as $datum){
            $user = new User();
            $user->fname =$datum['fname'];
            $user->lname =$datum['lname'];
            $user->email =$datum['email'];
            $user->password =$datum['password'];
            $user->language =$datum['language'];
            $user->role_id =$datum['role_id'];
            $user->save();
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
