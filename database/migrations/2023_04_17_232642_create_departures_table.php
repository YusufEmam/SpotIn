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
        Schema::create('departures', function (Blueprint $table) {
            $table->id();
            $table->string("dep_Latitude");
            $table->string("dep_Longitude");
            $table->date("dep_Date");
            $table->time("dep_Time");
            $table->string("dep_address")->nullable(true);
            $table->string("last_dep_status")->default("Not defined");
            $table->string("dep_comment")->nullable(true);
            $table->foreignId("branch_id")->nullable()->constrained("branches");
            $table->string("branch_name")->nullable();
            $table->foreign("branch_name")->references("name")->on("branches")->type("varchar");
            $table->foreignId("employee_id")->nullable()->constrained("employees");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departures');
    }
};
