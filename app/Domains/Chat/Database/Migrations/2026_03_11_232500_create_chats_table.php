<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    private string $tableName = 'chats';

    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('first_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('second_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['first_id', 'second_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
