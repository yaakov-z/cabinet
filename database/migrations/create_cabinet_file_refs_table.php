<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cabinet:file_refs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuidMorphs('attached_to');
            $table->string('attached_as');
            $table->unsignedInteger('attached_order')
                ->nullable();

            $table->string('source');
            $table->nullableUuidMorphs('model');
            $table->string('disk')->nullable();
            $table->string('path', 1024)
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabinet:file_refs');
    }
};
