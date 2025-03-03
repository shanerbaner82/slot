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
        Schema::create('spins', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class);

            $table->unsignedBigInteger('origin_id')->nullable();
            $table->string('origin_type')->nullable();

            $table->json('results')->nullable();
            $table->string('prize')->nullable();
            $table->boolean('is_win')->default(false);
            $table->dateTime('spun_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spins');
    }
};
