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
        Schema::create('connections', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

            $table->boolean('is_demo')->default(false);
            $table->boolean('is_active')->default(false);

            $table->enum('type', [
                'month',
                'six_month',
                'year'
            ])->default('month');

            $table->string('start_date');
            $table->string('end_date');

            $table->boolean('demo_activated')->default(false);

            $table->integer('buy_count')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};
