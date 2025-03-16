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
        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->integer('order')->default(0);
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });

        // Modification de la table projects pour rendre image_path nullable
        // puisque nous allons maintenant stocker les images dans la table project_images
        Schema::table('projects', function (Blueprint $table) {
            $table->string('image_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_images');

        // Si nécessaire, remettre image_path comme obligatoire
        // Schema::table('projects', function (Blueprint $table) {
        //     $table->string('image_path')->nullable(false)->change();
        // });
    }
};
