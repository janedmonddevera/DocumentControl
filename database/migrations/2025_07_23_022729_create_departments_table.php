<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('unit');
            $table->string('code');
            $table->timestamps();
        });

        DB::table('departments')->insert([
            ['unit' => 'EXEC', 'code' => '00', 'created_at' => now(), 'updated_at' => now()],
            ['unit' => 'HR', 'code' => '01', 'created_at' => now(), 'updated_at' => now()],
            ['unit' => 'TECH', 'code' => '02', 'created_at' => now(), 'updated_at' => now()],
            ['unit' => 'QMS', 'code' => '03', 'created_at' => now(), 'updated_at' => now()],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
