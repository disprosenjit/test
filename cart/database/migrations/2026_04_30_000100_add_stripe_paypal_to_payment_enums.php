<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend enums for modern payment gateways.
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('bank_transfer','upi','credit_card','debit_card','stripe','paypal') NULL");
        DB::statement("ALTER TABLE payments MODIFY method ENUM('bank_transfer','upi','credit_card','debit_card','stripe','paypal') NOT NULL");
    }

    public function down(): void
    {
        // Delete or update rows with stripe/paypal before reducing enum
        DB::statement("DELETE FROM payments WHERE method IN ('stripe', 'paypal')");
        DB::statement("DELETE FROM orders WHERE payment_method IN ('stripe', 'paypal')");
        
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('bank_transfer','upi','credit_card','debit_card') NULL");
        DB::statement("ALTER TABLE payments MODIFY method ENUM('bank_transfer','upi','credit_card','debit_card') NOT NULL");
    }
};
