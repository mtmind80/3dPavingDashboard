<?php

use App\Models\WorkorderTimesheets;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
//Route::get('/createusers', 'DashboardController@createUsers');

/***********************************  Ajax  ***********************************/

Route::group(['prefix' => 'ajax'], function() {
    Route::post('ajax-get-zips-per-city', 'DashboardController@ajaxGetZipsPerCity')->name('ajax_get_zips_per_city');
    Route::post('ajax-get-zips-per-county', 'DashboardController@ajaxGetZipsPerCounty')->name('ajax_get_zips_per_county');
    Route::post('ajax-get-leads', 'DashboardController@ajaxGetLeads')->name('ajax_get_leads');
});
/** END Ajax */

Route::group(['middleware' => ['auth']], function() {
    //main dashboard
    Route::get('/', 'DashboardController@dashboard')->name('base');
    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::get('/dashboard2', 'DashboardController@dashboard2')->name('dashboard_2');
    Route::get('/dashboard3', 'DashboardController@dashboard3')->name('dashboard_3');
    Route::get('/dashboard4', 'DashboardController@dashboard4')->name('dashboard_4');
    Route::get('/dashboard5', 'DashboardController@dashboard5')->name('dashboard_5');
    Route::get('/dashboard6', 'DashboardController@dashboard6')->name('dashboard_6');
    Route::get('/dashboard7', 'DashboardController@dashboard7')->name('dashboard_7');

    // lock screen
    Route::get('/lockout', 'LockoutController@lockout')->name('lockout');
    Route::post('/lockout', 'LockoutController@endlockout')->name('endlockout');

    //change language
    Route::get('index/{locale}', 'LocaleController@lang')->name('language');

    //abandon calendar
    Route::get('/calendar', 'CalendarController@index')->name('calendar');
    Route::get('/create_event', 'GoogleController@store')->name('create_event');

    Route::get('generate-pdf', 'PDFController@generatePDF')->name('generatepdf');


    /*************** WorkOrders  ***************/

    Route::group(['prefix' => 'workorders'], function() {

        Route::get('/', 'WorkOrderController@index')->name('workorders');
        Route::match(['get', 'post'], '/search', 'WorkOrderController@search')->name('workorder_search');

        Route::get('/{id}/changeorder', 'WorkOrderController@changeorder')->name('create_changeorder');
        Route::get('/{id}/show', 'WorkOrderController@show')->name('show_workorder');
        Route::get('/{id}/{detail_id}/assignmanager', 'WorkOrderController@assignmanager')->name('assignmanager');
        Route::post('/{id}/{detail_id}/doassignmanager', 'WorkOrderController@doassignmanager')->name('doassignmanager');
        Route::post('/{work_order}/add-note', 'WorkOrderController@storeNote')->name('workorder_note_store');
        Route::get('/view_service/{proposal_id}/{id}', 'WorkOrderController@view_service')->name('view_service');


        Route::get('/edit_workorder/{id}', 'WorkOrderController@edit')->name('edit_workorder');
        Route::post('/update_workorder/{id}', 'WorkOrderController@update')->name('update_workorder');

        Route::post('/add_payments/', 'WorkOrderController@add_payments')->name('add_payment');
        Route::get('/{id}/make_payments', 'WorkOrderController@payments')->name('create_payment');
        Route::get('/delete_payment/{proposal_id}/{id}', 'WorkOrderController@delete_payment')->name('delete_payment');

        Route::get('/{id}/manage_permits', 'WorkOrderController@permits')->name('manage_permits');


        /*************** Timesheets  ***************/

        Route::group(['prefix' => 'timesheets'], function() {
            Route::get('/{proposal_detail_id}/list', 'WorkOrderTimesheetsController@index')->name('workorder_timesheet_list');
            Route::match(['get', 'post'], '/search', 'WorkOrderTimesheetsController@search')->name('workorder_timesheet_search');

            Route::get('/{proposal_detail_id}/entry-form', 'WorkOrderTimesheetsController@entryForm')->name('workorder_timesheet_entry_form');
            Route::post('/', 'WorkOrderTimesheetsController@store')->name('workorder_timesheet_store');
            Route::delete('/{workorder_timesheet_id}', 'WorkOrderTimesheetsController@destroy')->name('workorder_timesheet_destroy');
        });
        /** END Timesheets */


        /*************** Equipment  ***************/

        Route::group(['prefix' => 'equipment'], function() {
            Route::get('/{proposal_detail_id}/list', 'WorkOrderEquipmentController@index')->name('workorder_equipment_list');
            Route::match(['get', 'post'], '/search', 'WorkOrderEquipmentController@search')->name('workorder_equipment_search');

            Route::get('/{proposal_detail_id}/entry-form', 'WorkOrderEquipmentController@entryForm')->name('workorder_equipment_entry_form');
            Route::post('/', 'WorkOrderEquipmentController@store')->name('workorder_equipment_store');
            Route::delete('/{workorder_equipment_id}', 'WorkOrderEquipmentController@destroy')->name('workorder_equipment_destroy');
        });
        /** END Equipment */


        /*************** Materials  ***************/

        Route::group(['prefix' => 'materials'], function() {
            Route::get('/{proposal_detail_id}/list', 'WorkOrderMaterialsController@index')->name('workorder_material_list');
            Route::match(['get', 'post'], '/search', 'WorkOrderMaterialsController@search')->name('workorder_material_search');

            Route::get('/{proposal_detail_id}/entry-form', 'WorkOrderMaterialsController@entryForm')->name('workorder_material_entry_form');
            Route::post('/', 'WorkOrderMaterialsController@store')->name('workorder_material_store');
            Route::delete('/{workorder_material_id}', 'WorkOrderMaterialsController@destroy')->name('workorder_material_destroy');
        });
        /** END Materials */


        /*************** Vehicles  ***************/

        Route::group(['prefix' => 'vehicles'], function() {
            Route::get('/{proposal_detail_id}/list', 'WorkOrderVehiclesController@index')->name('workorder_vehicle_list');
            Route::match(['get', 'post'], '/search', 'WorkOrderVehiclesController@search')->name('workorder_vehicle_search');

            Route::get('/{proposal_detail_id}/entry-form', 'WorkOrderVehiclesController@entryForm')->name('workorder_vehicle_entry_form');
            Route::post('/', 'WorkOrderVehiclesController@store')->name('workorder_vehicle_store');
            Route::delete('/{workorder_vehicle_id}', 'WorkOrderVehiclesController@destroy')->name('workorder_vehicle_destroy');
        });
        /** END Vehicles */


        /*************** Subcontractors  ***************/

        Route::group(['prefix' => 'subcontractors'], function() {
            Route::get('/{proposal_detail_id}/list', 'WorkOrderSubcontractorsController@index')->name('workorder_subcontractor_list');
            Route::match(['get', 'post'], '/search', 'WorkOrderSubcontractorsController@search')->name('workorder_subcontractor_search');

            Route::get('/{proposal_detail_id}/entry-form', 'WorkOrderSubcontractorsController@entryForm')->name('workorder_subcontractor_entry_form');
            Route::post('/', 'WorkOrderSubcontractorsController@store')->name('workorder_subcontractor_store');
            Route::delete('/{workorder_subcontractor_id}', 'WorkOrderSubcontractorsController@destroy')->name('workorder_subcontractor_destroy');
        });
        /** END Contractors */


        /*************** Details  ***************/

        Route::group(['prefix' => 'details'], function() {
            Route::get('/{proposal_detail_id}', 'WorkOrderDetailsController@details')->name('workorder_details');

            // timesheet:
            Route::post('/ajax-timesheet-store', 'WorkOrderDetailsController@ajaxTimeSheetStore')->name('ajax_workorder_timesheet_store');
            Route::post('/ajax-timesheet-destroy', 'WorkOrderDetailsController@ajaxTimeSheetDestroy')->name('ajax_workorder_timesheet_destroy');

            // equipment:
            Route::post('/ajax-equipment-store', 'WorkOrderDetailsController@ajaxEquipmentStore')->name('ajax_workorder_equipment_store');
            Route::post('/ajax-equipment-destroy', 'WorkOrderDetailsController@ajaxEquipmentDestroy')->name('ajax_workorder_equipment_destroy');

            // material:
            Route::post('/ajax-material-store', 'WorkOrderDetailsController@ajaxMaterialStore')->name('ajax_workorder_material_store');
            Route::post('/ajax-material-destroy', 'WorkOrderDetailsController@ajaxMaterialDestroy')->name('ajax_workorder_material_destroy');

            // vehicle:
            Route::post('/ajax-vehicle-store', 'WorkOrderDetailsController@ajaxVehicleStore')->name('ajax_workorder_vehicle_store');
            Route::post('/ajax-vehicle-destroy', 'WorkOrderDetailsController@ajaxVehicleDestroy')->name('ajax_workorder_vehicle_destroy');

            // subcontractor:
            Route::post('/ajax-subcontractor-store', 'WorkOrderDetailsController@ajaxSubcontractorStore')->name('ajax_workorder_subcontractor_store');
            Route::post('/ajax-subcontractor-destroy', 'WorkOrderDetailsController@ajaxSubcontractorDestroy')->name('ajax_workorder_subcontractor_destroy');
        });
        /** END Details */
    });
});
/** END Workorders */


/*************** Permits  ***************/


Route::group(['prefix' => 'permits'], function() {
    Route::get('/', 'PermitsController@index')->name('permits');
    Route::match(['get', 'post'], '/search', 'PermitsController@search')->name('permit_search');
    Route::get('/{permit}/show', 'PermitsController@edit')->name('permit_show');
    Route::get('/{id}/remove', 'PermitsController@destroy')->name('remove_permit');
    Route::post('/{permit}/add-note', 'PermitsController@storeNote')->name('permit_note_add');
    Route::post('/{permit}/change-status', 'PermitsController@changeStatus')->name('permit_status_change');
    Route::get('/{id}/add_permit', 'PermitsController@create')->name('add_permit');
    Route::get('/{permit}/edit', 'PermitsController@edit')->name('permit_edit');
    Route::patch('/{permit}', 'PermitsController@update')->name('permit_update');
    Route::post('ajax-note-list', 'PermitsController@noteList')->name('ajax_permit_note_list');
    Route::post('/create_permit', 'PermitsController@store')->name('create_permit');

    Route::post('ajax-fetch-cities', [\App\Http\Controllers\PermitsController::class, 'ajaxFetchCities'])->name('ajax_fetch_cities');
});
/** END Permits */


//users
Route::group(['prefix' => 'users'], function() {


    //User manager
    Route::get('', 'UserController@index')->name('users');
    //show all active and inactive users
    Route::get('/allusers', 'UserController@indexAll')->name('allusers');
    //present new user form
    Route::get('/newuser', 'UserController@new')->name('new_user');
    //search active users
    Route::post('/searchuser', 'UserController@search')->name('search_user');
    //search all users
    Route::post('/searchuserall', 'UserController@searchall')->name('search_userall');
    //show user
    Route::get('/getuser/{id}', 'UserController@show')->name('show_user');
    //show edit the user form
    Route::get('/edituser/{id}', 'UserController@edit')->name('edit_user');
    //delete user
    Route::post('/deleteeuser{id}', 'UserController@destroy')->name('destroy_user');
    //add new user
    Route::post('/createuser', 'UserController@store')->name('create_user');
    //update user
    Route::post('/updateuser/{id}', 'UserController@update')->name('update_user');

});
/** END users */
//contractors
Route::group(['prefix' => 'contractors'], function() {
    //grid
    Route::get('', 'ContractorController@index')->name('contractor_list');
    //show form
    Route::get('/new', 'ContractorController@new')->name('new_contractor');
    //get record show form
    Route::get('/{id}', 'ContractorController@edit')->name('edit_contractor');
    //save or update
    Route::post('/{id}', 'ContractorController@store')->name('save_contractor');
    //search active contractors
    Route::match(['get', 'post'], '/searchcontractor', 'ContractorController@search')->name('search_contractor');

});
/** END contractors */

//vendors
Route::group(['prefix' => 'vendors'], function() {
    //grid
    Route::get('', 'VendorController@index')->name('vendor_list');
    //show form
    Route::get('/new', 'VendorController@new')->name('new_vendor');
    //get record show form
    Route::get('/edit_vendor/{id}', 'VendorController@edit')->name('edit_vendor');
    //save or update
    Route::post('/save/{id}', 'VendorController@store')->name('save_vendor');
    //search active Vendors
    Route::match(['get', 'post'], '/searchVendor', 'VendorController@search')->name('search_vendor');

});
/** END Vendors */

//Proposals Details
Route::group(['prefix' => 'proposaldetails'], function() {

    // get details to  display or edit
    Route::get('/edit/{proposal_id}/{id}', 'ProposalDetailController@edit')->name('edit_service');
    // Schedule a service
    Route::get('/scehdule/{service_id}', 'ProposalDetailController@schedule')->name('schedule_service');
    Route::post('/creeate_scehdule/{proposal_detail}', 'ProposalDetailController@createschedule')->name('create_schedule');
    Route::get('/remove_schedule/{schedule}', 'ProposalDetailController@removeschedule')->name('remove_schedule');

    // Select service type
    Route::post('/checkform', 'ProposalDetailController@checkform')->name('checkform');

    Route::post('/save_striping', 'ProposalDetailController@savestriping')->name('save_striping');



    Route::get('/new_service/{proposal_id}', 'ProposalDetailController@newservice')->name('new_service');
    // Start New Service with a service type
    Route::post('/create_service/{proposal_id}', 'ProposalDetailController@create')->name('create_detail');

    Route::post('/save_service/{proposal_id}', 'ProposalDetailController@saveservice')->name('save_detail');

    Route::post('/update_service/{service_id}', 'ProposalDetailController@update')->name('update_detail');

    //Route::get('/remove_service/{service_id}', 'ProposalDetailController@destroy')->name('remove_detail');
    Route::delete('/', 'ProposalDetailController@destroy')->name('service_delete');

    Route::post('/header-calculate-combined-costing', 'ProposalDetailController@ajaxCalculateCombinedCosting')->name('ajax_header_calculate_combined_costing');

    Route::post('/ajax-vehicle-add-new', 'ProposalDetailController@ajaxVehicleAddNew')->name('ajax_vehicle_add_new');
    Route::post('/ajax-vehicle-add-or-update', 'ProposalDetailController@ajaxVehicleAddOrUpdate')->name('ajax_vehicle_add_or_update');
    Route::post('/ajax-vehicle-remove', 'ProposalDetailController@ajaxVehicleRemove')->name('ajax_vehicle_remove');
    Route::post('/ajax-equipment-add-or-update', 'ProposalDetailController@ajaxEquipmentAddOrUpdate')->name('ajax_equipment_add_or_update');
    Route::post('/ajax-equipment-remove', 'ProposalDetailController@ajaxEquipmentRemove')->name('ajax_equipment_remove');
    Route::post('/ajax-labor-add-or-update', 'ProposalDetailController@ajaxLaborAddOrUpdate')->name('ajax_labor_add_or_update');
    Route::post('/ajax-labor-remove', 'ProposalDetailController@ajaxLaborRemove')->name('ajax_labor_remove');

    Route::post('/ajax-additional-cost-add-or-update', 'ProposalDetailController@ajaxAdditionalCostAddOrUpdate')->name('ajax_additional_cost_add_or_update');
    Route::post('/ajax-additional-cost-remove', 'ProposalDetailController@ajaxAdditionalCostRemove')->name('ajax_additional_cost_remove');

    Route::post('/ajax-subcontractor-add-new', 'ProposalDetailController@ajaxSubcontractorAddNew')->name('ajax_subcontractor_add_new');
    Route::post('/ajax-subcontractor-remove', 'ProposalDetailController@ajaxSubcontractorRemove')->name('ajax_subcontractor_remove');

    Route::post('/ajax-proposal-details-update', 'ProposalDetailController@ajaxUpdate')->name('ajax_proposal_details_update');
});
/** END Proposal Details */



//Printing PDF
Route::group(['prefix' => 'print'], function() {

    //Print report
    Route::post('/proposal/', 'PrintingController@proposal')->name('print_proposal');

    //Print report
    Route::get('/printcoversheet', 'PrintingController@coversheet')->name('print_coversheet');

    /**** example of printing a pdf with background image */

    Route::get('/print-example-pdf', 'PrintingController@printExamplePdfWithBAckgroundImage');


});
/** END Print */


Route::group(['prefix' => 'proposals'], function() {

    Route::get('/new', 'ProposalController@new')->name('new_proposal');

    Route::get('', 'ProposalController@index')->name('proposals');

    Route::match(['get', 'post'], '/{proposal_id}/changeproposalclient', 'ProposalController@changeclient')->name('change_proposal_client');

    //update proposal materials pricing
    Route::get('/selectclient/{contact_id}/{proposal_id}', 'ProposalController@selectclient')->name('selectclient');


    //update proposal materials pricing
    Route::get('/MaterialPricing/{id}', 'ProposalController@refreshMaterialPricing')->name('refresh_material');

    // close get ready to bill specific proposal
    Route::get('/close/{id}', 'ProposalController@closeproposal')->name('close_proposal');

    Route::get('/change_client/{proposal_id}', 'ProposalController@changeclient')->name('change_client');

    Route::get('/clone_proposal/{id}', 'ProposalController@clone')->name('clone_proposal');

    Route::get('/fromlead/{lead}', 'ProposalController@startWithLead')->name('start_from_lead');

    Route::get('/fromcontact/{contact}', 'ProposalController@startWithContact')->name('start_from_contact');

    Route::post('/createproposal', 'ProposalController@create')->name('create_proposal');

    Route::get('/inactive_proposals', 'ProposalController@inactive')->name('inactive_proposals');

    Route::post('/inactive_search_proposals', 'ProposalController@inactivesearch')->name('inactiveproposal_search');
    // get a specific proposal

    Route::post('/search_proposals', 'ProposalController@search')->name('search_proposals');
    // get a specific proposal

    Route::get('/{id}/show_proposal', 'ProposalController@show')->name('show_proposal');

    Route::post('/change_status', 'ProposalController@changestatus')->name('change_status');

    Route::post('/reorder-services', 'ProposalController@reorderServices')->name('services_reorder');

    Route::get('/start_proposal/{id}', 'ProposalController@start')->name('start_proposal');

    //  show alert
    Route::get('/alert/{id}', 'ProposalController@alertproposal')->name('alert_proposal');

    // set alert
    Route::post('set-alert', 'ProposalController@setAlert')->name('proposal_alert_set');
    Route::get('/{proposal_id}/reset-alert', 'ProposalController@resetAlert')->name('proposal_alert_reset');

    //  bill specific proposal
    Route::get('/bill/{id}', 'ProposalController@billproposal')->name('bill_proposal');

    Route::post('/{proposal}/add-note', 'ProposalController@storeNote')->name('proposal_note_store');

    // get a specific proposal
    Route::get('/edit_proposal/{id}', 'ProposalController@edit')->name('edit_proposal');
    // update a specific proposal
    Route::post('/update_proposal/{id}', 'ProposalController@update')->name('update_proposal');


});
/** END Proposals */

//uplaod  create: role_id 1,2,3
Route::group(['prefix' => 'upload'], function() {
    Route::get('/{proposal_id}/{proposal_detail_id}', 'UploadController@showuploadform')->name('media');

    Route::get('/workordermedia/{proposal_id}/{proposal_detail_id}', 'UploadController@showworkorderuploadform')->name('workordermedia');

    Route::post('/doupload', 'UploadController@doupload')->name('doupload');

    Route::post('/ajaxupload/{proposal}', 'UploadController@ajaxupload')->name('proposal_media_store');

    Route::post('/workorderupload', 'UploadController@doworkorderupload')->name('doworkorderupload');


});
// END Upload


/**  Reports */

Route::group(['prefix' => 'reports'], function() {
    Route::prefix('sales')
        ->name('sales_report_')
        ->group(function() {
            Route::get('/', 'Reports\SalesReportController@index')->name('index');
            Route::post('/ajax-view', 'Reports\SalesReportController@ajaxView')->name('ajax_view');
            Route::post('/export', 'Reports\SalesReportController@export')->name('export');
        });

    Route::prefix('activity-by-status')
        ->name('activity_by_status_report_')
        ->group(function() {
            Route::get('/', 'Reports\ActivityByStatusReportController@index')->name('index');
            Route::post('/ajax-view', 'Reports\ActivityByStatusReportController@ajaxView')->name('ajax_view');
            Route::post('/export', 'Reports\ActivityByStatusReportController@export')->name('export');
        });

    Route::prefix('activity-by-contact-type')
        ->name('activity_by_contact_type_report_')
        ->group(function() {
            Route::get('/', 'Reports\ActivityByContactTypesReportController@index')->name('index');
            Route::post('/ajax-view', 'Reports\ActivityByContactTypesReportController@ajaxView')->name('ajax_view');
            Route::post('/export', 'Reports\ActivityByContactTypesReportController@export')->name('export');
        });

    Route::prefix('leads')
        ->name('leads_report_')
        ->group(function() {
            Route::get('/', 'Reports\LeadsReportController@index')->name('index');
            Route::post('/ajax-view', 'Reports\LeadsReportController@ajaxView')->name('ajax_view');
            Route::post('/export', 'Reports\LeadsReportController@export')->name('export');
        });

    Route::get('/', 'Reports\ReportsController@index')->name('reports');
    Route::get('/{name}', 'Reports\ReportsController@showForm')->name('showreport');
});
/** END Reports */


//Resources
Route::group(['prefix' => 'resources'], function() {

    Route::get('', 'ResourceController@index')->name('resources');

    Route::get('/getmodel/{model}', 'ResourceController@getmodel')->name('getmodel');
    //edit resource
    Route::get('/{model}/{id}', 'ResourceController@editresource')->name('edit_resource');
    //new resource
    Route::get('/{model}', 'ResourceController@newresource')->name('new_resource');
    //save resource
    Route::post('/{model}/{id}', 'ResourceController@store')->name('save_resource');

    Route::get('/destroy/{model}/{id}', 'ResourceController@destroy')->name('destroy_resource');

    Route::get('/restore_config/', 'ResourceController@restore_web_config')->name('restore_config');


});
/** END Resources */


/*************** Contacts  ***************/

Route::group(['prefix' => 'contacts'], function() {

    //Route::model('contact', \App\Models\Contact::class);

    Route::get('/change_contact/{id}', 'ContactsController@changeclient')->name('change_contact');

    Route::match(['get', 'post'], '/search', 'ContactsController@search')->name('contact_search');

    Route::get('/', 'ContactsController@index')->name('contact_list');

    // create new contact form
    Route::get('/create', 'ContactsController@create')->name('contact_create');

    Route::get('/createforproposal/{proposal_id}', 'ContactsController@createforproposal')->name('createforproposal');

    // show contact details
    Route::get('/{contact}', 'ContactsController@details')->name('contact_details');
    //edi contact form
    Route::get('/{contact}/edit', 'ContactsController@edit')->name('contact_edit');

    Route::get('/{contact}/restore', 'ContactsController@restore')->name('contact_restore');
    Route::get('/{contact}/toggle', 'ContactsController@toggleStatus')->name('contact_toggle_status');

    //save contact update
    Route::patch('/{contact}', 'ContactsController@update')->name('contact_update');
    //create contact for a proposal
    Route::post('/contact_for_proposal', 'ContactsController@storeforproposal')->name('contact_proposal');

    //save new contact
    Route::post('/contact_store', 'ContactsController@store')->name('contact_store');

    Route::post('/{contact}/update-note', 'ContactsController@updateNote')->name('contact_field_note_update');
    Route::post('/{contact}/add-note', 'ContactsController@addNote')->name('contact_note_add');

    Route::post('/ajax-check-if-contact-exists', 'ContactsController@ajaxCheckIfContactExists')->name('ajax_check_if_contact_exists');

    Route::post('/ajax-check-if-contact-exists2', 'ContactsController@ajaxCheckIfContactExists2')->name('ajax_check_if_contact_exists2');

    Route::post('/detach-from-company', 'ContactsController@detachFromCompany')->name('contact_detach_from_company');
    Route::post('/{contact}/add-staff', 'ContactsController@addStaff')->name('contact_add_staff');
    Route::post('/{contact}/add-newstaff', 'ContactsController@addNewStaff')->name('contact_add_new_staff');
    Route::post('/{contact}/remove-staff', 'ContactsController@removeStaff')->name('contact_remove_staff');

    //soft delete contact
    Route::delete('/', 'ContactsController@destroy')->name('contact_delete');

    /***************  Staff ***************/

    Route::group(['prefix' => 'staff'], function() {
        Route::get('/{contact_id}', 'StaffController@index')->name('staff_list');
        Route::match(['get', 'post'], '/search/{contact_id}', 'StaffController@search')->name('staff_search');
        Route::get('/{contact_id}/add', 'StaffController@add')->name('staff_add');
        Route::get('/{contact_id}/remove', 'StaffController@remnove')->name('staff_remove');
    });
    /** END Staff */
});
/** END Contacts */


/*************** ContactNotes  ***************/

Route::group(['prefix' => 'contact-notes'], function() {
    Route::get('/', 'ContactNotesController@index')->name('contact_note_list');
    Route::match(['get', 'post'], '/search', 'ContactNotesController@search')->name('contact_note_search');
    Route::get('/create', 'ContactNotesController@create')->name('contact_note_create');
    Route::post('/', 'ContactNotesController@store')->name('contact_note_store');
    Route::get('/{contact_note}/edit', 'ContactNotesController@edit')->name('contact_note_edit');
    Route::get('/{contact_note}/toggle', 'ContactNotesController@toggleStatus')->name('contact_note_toggle_status');
    Route::patch('/{contact_note}', 'ContactNotesController@update')->name('contact_note_update');
    Route::delete('/', 'ContactNotesController@destroy')->name('contact_note_delete');
    /** END Staff */
});
/** END ContactNotes */


/*************** Leads  ***************/

Route::group(['prefix' => 'leads'], function() {
    Route::model('lead', \App\Models\Lead::class);

    Route::get('/', 'LeadsController@index')->name('lead_list');
    Route::match(['get', 'post'], '/search', 'LeadsController@search')->name('lead_search');
    Route::get('/{lead}/details', 'LeadsController@details')->name('lead_details');
    Route::get('/create', 'LeadsController@create')->name('lead_create')->middleware('admin');
    Route::post('/', 'LeadsController@store')->name('lead_store')->middleware('admin');
    Route::get('/{lead}/edit', 'LeadsController@edit')->name('lead_edit');
    Route::get('/{lead}/toggle', 'LeadsController@toggleStatus')->name('lead_toggle_status');
    Route::patch('/{lead}', 'LeadsController@update')->name('lead_update');
    Route::post('/{lead}/add-note', 'LeadsController@storeNote')->name('lead_field_note_store');
    Route::post('/{lead}/assign-to', 'LeadsController@assignTo')->name('lead_assign_to');
    Route::get('/{lead}/archive', 'LeadsController@archive')->name('lead_archive');
    Route::post('/ajax-check-if-lead-exists', 'LeadsController@ajaxCheckIfLeadExists')->name('ajax_check_if_lead_exists');
});
/** END Leads */




