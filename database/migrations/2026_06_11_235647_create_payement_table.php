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
        Schema::create('payements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->enum('methode_paiement', ['carte_bancaire', 'virement', 'digital_wallet']);
            $table->string('marque_carte'); // ex: Visa, Mastercard
            $table->decimal('montant_paye', 10, 2);
            $table->string('dernier_4_chiffres', 4);
            $table->string('code_confirm', 4);
            $table->string('id_transaction_externe')->nullable()->unique(); // ID reçu de Stripe ou du CMI
            $table->enum('statut_transaction', ['succes', 'echec', 'pendding', 'encaissed'])->default('pendding');
            $table->timestamp('date_paiement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payements');
    }
};
