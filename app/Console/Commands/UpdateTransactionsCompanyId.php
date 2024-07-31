<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Expense;

class UpdateTransactionsCompanyId extends Command
{
    protected $signature = 'transactions:update-company-id';
    protected $description = 'Update company_id for existing transactions';

    public function handle()
    {
        $this->info('Updating transactions...');

        // Mise à jour des transactions liées aux contrats
        Contract::chunk(100, function ($contracts) {
            foreach ($contracts as $contract) {
                Transaction::where('contract_id', $contract->id)
                    ->update(['company_id' => $contract->company_id]);
            }
        });

        // Mise à jour des transactions liées aux paiements
        Payment::chunk(100, function ($payments) {
            foreach ($payments as $payment) {
                Transaction::where('payment_id', $payment->id)
                    ->update(['company_id' => $payment->contract->company_id]);
            }
        });

        // Mise à jour des transactions liées aux dépenses
        Expense::chunk(100, function ($expenses) {
            foreach ($expenses as $expense) {
                Transaction::where('expense_id', $expense->id)
                    ->update(['company_id' => $expense->company_id]);
            }
        });

        $this->info('Transactions updated successfully!');
    }
}
