<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkOrderReportIdFieldToWorkordersTables extends Migration
{
    public function up(): void
    {
        Schema::table('workorder_equipment', function (Blueprint $table) {
            $table->unsignedBigInteger('workorder_field_report_id')->index()->nullable()->after('proposal_detail_id');
            $table->dropColumn('report_date');
        });

        Schema::table('workorder_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('workorder_field_report_id')->index()->nullable()->after('proposal_detail_id');
            $table->dropColumn('report_date');
        });

        Schema::table('workorder_subcontractors', function (Blueprint $table) {
            $table->unsignedBigInteger('workorder_field_report_id')->index()->nullable()->after('proposal_detail_id');
            $table->dropColumn('report_date');
        });

        Schema::table('workorder_timesheets', function (Blueprint $table) {
            $table->unsignedBigInteger('workorder_field_report_id')->index()->nullable()->after('proposal_detail_id');
            $table->dropColumn('report_date');
        });

        Schema::table('workorder_vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('workorder_field_report_id')->index()->nullable()->after('proposal_detail_id');
            $table->dropColumn('report_date');
        });
    }

    public function down(): void
    {
        Schema::table('workorder_equipment', function (Blueprint $table) {
            $table->dropColumn('workorder_field_report_id');
            $table->date('report_date')->after('proposal_detail_id');
        });

        Schema::table('workorder_materials', function (Blueprint $table) {
            $table->dropColumn('workorder_field_report_id');
            $table->date('report_date')->after('proposal_detail_id');
        });

        Schema::table('workorder_subcontractors', function (Blueprint $table) {
            $table->dropColumn('workorder_field_report_id');
            $table->date('report_date')->after('proposal_detail_id');
        });

        Schema::table('workorder_timesheets', function (Blueprint $table) {
            $table->dropColumn('workorder_field_report_id');
            $table->date('report_date')->after('proposal_detail_id');
        });

        Schema::table('workorder_vehicles', function (Blueprint $table) {
            $table->dropColumn('workorder_field_report_id');
            $table->date('report_date')->after('proposal_detail_id');
        });
    }

}
