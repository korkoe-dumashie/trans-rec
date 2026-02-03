<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('staff_id')->unique();
            // $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->string('password');
            $table->boolean('reset_password')->default(false);


            $table->timestamps();

            $table->index('staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auths');
    }
};
