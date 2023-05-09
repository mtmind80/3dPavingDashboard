<?php


use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('role', 50);
        });

        $data =  array(
            [
                'role' => 'Super Admin',

            ],
            [
                'role' => 'Admin',

            ],
            [
                'role' => 'Sales Manager',

            ],
            [
                'role' => 'Pavement Consultant',

            ],
            [
                'role' => 'Field Manager',

            ],
            [
                'role' => 'Labor',

            ],
            [
                'role' => 'Developer',

            ],
        );
        foreach ($data as $datum){
            $role = new Role();
            $role->role =$datum['role'];
            $role->save();
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
