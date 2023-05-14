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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->nullable(true);
            $table->string("phonenumber")->nullable(true);
            $table->string("password");
            $table->string("gender");
            $table->string("birthdate")->nullable(true);
            $table->string("department");
            $table->string("photo")->nullable(true);
            $table->foreignId("branch_id")->nullable()->constrained("branches");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
