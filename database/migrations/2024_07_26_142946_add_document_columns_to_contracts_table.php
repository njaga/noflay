    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class AddDocumentColumnsToContractsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('contracts', function (Blueprint $table) {
                $table->string('contract_signed_path')->nullable();
                $table->string('insurance_path')->nullable();
                $table->string('inventory_path')->nullable();
                $table->string('other_documents_path')->nullable();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('contracts', function (Blueprint $table) {
                $table->dropColumn('contract_signed_path');
                $table->dropColumn('insurance_path');
                $table->dropColumn('inventory_path');
                $table->dropColumn('other_documents_path');
            });
        }
    }
