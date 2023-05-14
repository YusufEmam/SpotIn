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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string("att_Latitude");
            $table->string("att_Longitude");
            $table->date("att_Date");
            $table->time("att_Time");
            $table->string("att_address")->nullable(true);
            $table->string("last_att_status")->default("Not defined");
            $table->string("att_comment")->nullable(true);
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
        Schema::dropIfExists('attendances');
    }
};
