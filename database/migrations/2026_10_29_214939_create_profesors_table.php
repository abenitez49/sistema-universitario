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
        // Cambiar de 'profesors' a 'profesores'
        Schema::create('profesores', function (Blueprint $table) {
            $table->id();
            // Agregamos la relación obligatoria con la persona
            $table->foreignId('persona_id')->constrained('personas')->cascadeOnDelete();
            $table->string('titulo')->nullable(); // Ej: Ing., Lic., Dr.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profesores');
    }
};
