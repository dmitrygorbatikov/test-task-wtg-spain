<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    private string $tableName = 'authentication_codes';

    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table): void {
            $table->id();
            $table->string('code');
            $table->uuid('identifier');
            $table->string('purpose');
            $table->morphs('authenticatable', 'authenticatable_index');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
