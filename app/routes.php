<?php





Route::get( '/', array( 'uses' => 'LoginController@showLogin' ) );
Route::post( 'login', array( 'uses' => 'LoginController@doLogin' ) );
Route::get( 'logout', array( 'uses' => 'LoginController@doLogout' ) );

Route::get( 'home', array( 'uses' => 'HomeController@showHome' ) );





//====================================== admin user =================================//
Route::get( 'user', array( 'uses' => 'AdminController@user' ) );
Route::get( 'user/search', array( 'uses' => 'AdminController@user' ) );
Route::post( 'user/search', array( 'uses' => 'AdminController@user_post_search' ) );
Route::get( 'user/create', array( 'uses' => 'AdminController@user_create' ) );
Route::post( 'user/create', array( 'uses' => 'AdminController@post_new_user' ) );
Route::get( 'user/user_autocomplete', array( 'uses' => 'AdminController@user_autocomplete' ) );
Route::get( 'user/edit/{id}', array( 'uses' => 'AdminController@user_edit' ) );
Route::post( 'user/edit/{id}', array( 'uses' => 'AdminController@post_edit_user' ) );
Route::get( 'user/delete/{id}', array( 'uses' => 'AdminController@user_delete' ) );
Route::get( 'user/general_data', array( 'uses' => 'AdminController@user_general_data' ) );
Route::post( 'user/general_add', array( 'uses' => 'AdminController@post_general_add' ) );
Route::post( 'user/general_update/{id}', array( 'uses' => 'AdminController@post_general_update' ) );





//====================================== admin bank =================================//
Route::get( 'bank', array( 'uses' => 'AdminController@bank' ) );
Route::get( 'bank/search', array( 'uses' => 'AdminController@bank' ) );
Route::post( 'bank/search', array( 'uses' => 'AdminController@bank_post_search' ) );
Route::get( 'bank/create', array( 'uses' => 'AdminController@bank_create' ) );
Route::post( 'bank/create', array( 'uses' => 'AdminController@post_new_bank' ) );
Route::get( 'bank/edit/{id}', array( 'uses' => 'AdminController@bank_edit' ) );
Route::post( 'bank/edit/{id}', array( 'uses' => 'AdminController@post_edit_bank' ) );
Route::get( 'bank/delete/{id}', array( 'uses' => 'AdminController@bank_delete' ) );





//======================================admin userdep=================================//
Route::get( 'userdep', array( 'uses' => 'AdminController@userdep' ) );
Route::get( 'userdep/_autocomplete', array( 'uses' => 'AdminController@_autocomplete' ) );
Route::get( 'userdep/edituserdep/{depid}/{cid}', array( 'uses' => 'AdminController@edituserdep' ) );
Route::get( 'userdep/viewuserdep/{depid}', array( 'uses' => 'AdminController@viewuserdep' ) );
Route::get( 'userdep/delempdep/{cid}/{depid}', array( 'uses' => 'AdminController@delempdep' ) );
Route::get( 'usersort', array( 'uses' => 'AdminController@usersort' ) );
Route::get( 'usersort/depupdate/{id}/{num}', array( 'uses' => 'AdminController@depupdate' ) );
Route::get( 'userdep/edit_hasData/{cid}/{value}/{field}/{dep_id}', array( 'uses' => 'AdminController@edit_hasData' ) );
Route::get( 'sortrepay', array( 'uses' => 'AdminController@sortrepay' ) );
Route::get( 'sortrepay/list_userpay/{pay}', array( 'uses' => 'AdminController@list_userpay' ) );
Route::get( 'sortrepay/list_qpay/{pay}/{q}/{cid}', array( 'uses' => 'AdminController@list_qpay' ) );






//======================================admin up excel=================================//
Route::get( 'upexcel', array( 'uses' => 'AdminController@upexcel' ) );
Route::any( 'upexcel/upload', array( 'uses' => 'AdminController@excel_upload' ) );
Route::get( 'upexcelnth', array( 'uses' => 'AdminController@upexcelnth' ) );
Route::any( 'upexcelnth/upload', array( 'uses' => 'AdminController@excelnth_upload' ) );
Route::get( 'upexcelnth2', array( 'uses' => 'AdminController@upexcelnth2' ) );
Route::any( 'upexcelnth2/upload', array( 'uses' => 'AdminController@excelnth2_upload' ) );
Route::get( 'upexcelnth3', array( 'uses' => 'AdminController@upexcelnth3' ) );
Route::any( 'upexcelnth3/upload', array( 'uses' => 'AdminController@excelnth3_upload' ) );
Route::get( 'upexcelnth4', array( 'uses' => 'AdminController@upexcelnth4' ) );
Route::any( 'upexcelnth4/upload', array( 'uses' => 'AdminController@excelnth4_upload' ) );
Route::get( 'upexceltravel', array( 'uses' => 'AdminController@upexceltravel' ) );
Route::any( 'upexceltravel/upload', array( 'uses' => 'AdminController@exceltravel_upload' ) );







//====================================== พกส.(ปฏิบัติงาน) =================================//
//----- bank_acc emptype1 พกส.(ปฏิบัติงาน) -----//
Route::get( 'emptype1/bank_acc', array( 'uses' => 'BankAccController@bank_acc_type1' ) );
Route::get( 'emptype1/fromAcc/{id}', array( 'uses' => 'BankAccController@fromAcc_type1' ) );
Route::post( 'emptype1/addAcc', array( 'uses' => 'BankAccController@addAcc_type1' ) );
Route::get( 'emptype1/deleteAcc/{id}/{cid}', 'BankAccController@deleteAcc_type1' );
Route::get( 'emptype1/search', array( 'uses' => 'BankAccController@bank_acc_type1' ) );
Route::post( 'emptype1/search', array( 'uses' => 'BankAccController@bankacc1_post_search' ) );
//----- salary emptype1 พกส.(ปฏิบัติงาน) -------------//
Route::get( 'emptype1/salary', array( 'uses' => 'SalaryController@salary_type1' ) );
Route::get( 'emptype1/fromSalary1/{id}', array( 'uses' => 'SalaryController@fromSalary_type1' ) );
Route::post( 'emptype1/addSalary', array( 'uses' => 'SalaryController@addSalary_type1' ) );
Route::post( 'emptype1/salary1/{id}', array( 'uses' => 'SalaryController@salary_edit_type1' ) );
Route::get( 'emptype1/salary-search', array( 'uses' => 'SalaryController@salary_type1' ) );
Route::post( 'emptype1/salary-search', array( 'uses' => 'SalaryController@salary1_post_search' ) );
//----- salary_insert emptype1 พกส.(ปฏิบัติงาน) -------------//
Route::get( 'emptype1/salary_insert', array( 'uses' => 'SalaryController@salary_insert_type1' ) );
Route::get( 'emptype1/fromsalary_insert1/{id}', array( 'uses' => 'SalaryController@fromsalary_insert_type1' ) );
Route::post( 'emptype1/salary_add', array( 'uses' => 'SalaryController@salary_add_type1' ) );
Route::post( 'emptype1/salary_insert1/{id}/{orderdate}', array( 'uses' => 'SalaryController@salary_insert_edit_type1' ) );
Route::get( 'emptype1/salary_insert-search', array( 'uses' => 'SalaryController@salary_insert_type1' ) );
Route::post( 'emptype1/salary_insert-search', array( 'uses' => 'SalaryController@salary_insert1_post_search' ) );




//-------------------- เพิ่มข้อมูลเงินเดือน AUto พกส.(ปฏิบัติงาน)-ลูกจ้างชั่วคราว  -------------------------//
Route::get( 'emptype1/salary_auto', array( 'uses' => 'SalaryController@salary_auto_emptype1' ) );
Route::get( 'emptype1/salary_auto/emp1_addauto', array( 'uses' => 'SalaryController@salary_auto_add_emptype1' ) );





//====================================== ลูกจ้างประจำ =================================//
//----- bank_acc emptype2 ลูกจ้างประจำ -----//
Route::get( 'emptype2/bank_acc', array( 'uses' => 'BankAccController@bank_acc_type2' ) );
Route::get( 'emptype2/fromAcc2/{id}', array( 'uses' => 'BankAccController@fromAcc_type2' ) );
Route::post( 'emptype2/addAcc', array( 'uses' => 'BankAccController@addAcc_type2' ) );
Route::get( 'emptype2/deleteAcc2/{id}/{cid}', 'BankAccController@deleteAcc_type2' );
Route::get( 'emptype2/search', array( 'uses' => 'BankAccController@bank_acc_type2' ) );
Route::post( 'emptype2/search', array( 'uses' => 'BankAccController@bankacc2_post_search' ) );
//----- salary emptype2 ลูกจ้างประจำ -------------//
Route::get( 'emptype2/salary', array( 'uses' => 'SalaryController@salary_type2' ) );
Route::get( 'emptype2/fromSalary2/{id}', array( 'uses' => 'SalaryController@fromSalary_type2' ) );
Route::post( 'emptype2/addSalary', array( 'uses' => 'SalaryController@addSalary_type2' ) );
Route::post( 'emptype2/salary2/{id}', array( 'uses' => 'SalaryController@salary_edit_type2' ) );
Route::get( 'emptype2/salary-search', array( 'uses' => 'SalaryController@salary_type2' ) );
Route::post( 'emptype2/salary-search', array( 'uses' => 'SalaryController@salary2_post_search' ) );
//----- salary_insert emptype2 ลูกจ้างประจำ -------------//
Route::get( 'emptype2/salary_insert', array( 'uses' => 'SalaryController@salary_insert_type2' ) );
Route::get( 'emptype2/fromsalary_insert2/{id}', array( 'uses' => 'SalaryController@fromsalary_insert_type2' ) );
Route::post( 'emptype2/salary_add', array( 'uses' => 'SalaryController@salary_add_type2' ) );
Route::post( 'emptype2/salary_insert2/{id}/{orderdate}', array( 'uses' => 'SalaryController@salary_insert_edit_type2' ) );
Route::get( 'emptype2/salary_insert-search', array( 'uses' => 'SalaryController@salary_insert_type2' ) );
Route::post( 'emptype2/salary_insert-search', array( 'uses' => 'SalaryController@salary_insert2_post_search' ) );





//====================================== ข้าราชการ =================================//
//----- bank_acc emptype3 ข้าราชการ -----//
Route::get( 'emptype3/bank_acc', array( 'uses' => 'BankAccController@bank_acc_type3' ) );
Route::get( 'emptype3/fromAcc3/{id}', array( 'uses' => 'BankAccController@fromAcc_type3' ) );
Route::post( 'emptype3/addAcc', array( 'uses' => 'BankAccController@addAcc_type3' ) );
Route::get( 'emptype3/deleteAcc3/{id}/{cid}', 'BankAccController@deleteAcc_type3' );
Route::get( 'emptype3/search', array( 'uses' => 'BankAccController@bank_acc_type3' ) );
Route::post( 'emptype3/search', array( 'uses' => 'BankAccController@bankacc3_post_search' ) );
//----- salary emptype3 ข้าราชการ -------------//
Route::get( 'emptype3/salary', array( 'uses' => 'SalaryController@salary_type3' ) );
Route::get( 'emptype3/fromSalary3/{id}', array( 'uses' => 'SalaryController@fromSalary_type3' ) );
Route::post( 'emptype3/addSalary', array( 'uses' => 'SalaryController@addSalary_type3' ) );
Route::post( 'emptype3/salary3/{id}', array( 'uses' => 'SalaryController@salary_edit_type3' ) );
Route::get( 'emptype3/salary-search', array( 'uses' => 'SalaryController@salary_type3' ) );
Route::post( 'emptype3/salary-search', array( 'uses' => 'SalaryController@salary3_post_search' ) );
//----- salary_insert emptype3 ข้าราชการ -------------//
Route::get( 'emptype3/salary_insert', array( 'uses' => 'SalaryController@salary_insert_type3' ) );
Route::get( 'emptype3/fromsalary_insert3/{id}', array( 'uses' => 'SalaryController@fromsalary_insert_type3' ) );
Route::post( 'emptype3/salary_add', array( 'uses' => 'SalaryController@salary_add_type3' ) );
Route::post( 'emptype3/salary_insert3/{id}/{orderdate}', array( 'uses' => 'SalaryController@salary_insert_edit_type3' ) );
Route::get( 'emptype3/salary_insert-search', array( 'uses' => 'SalaryController@salary_insert_type3' ) );
Route::post( 'emptype3/salary_insert-search', array( 'uses' => 'SalaryController@salary_insert3_post_search' ) );





//====================================== ลูกจ้างชั่วคราว =================================//
//----- bank_acc emptype4 ลูกจ้างชั่วคราว -----//
Route::get( 'emptype4/bank_acc', array( 'uses' => 'BankAccController@bank_acc_type4' ) );
Route::get( 'emptype4/fromAcc4/{id}', array( 'uses' => 'BankAccController@fromAcc_type4' ) );
Route::post( 'emptype4/addAcc', array( 'uses' => 'BankAccController@addAcc_type4' ) );
Route::get( 'emptype4/deleteAcc4/{id}/{cid}', 'BankAccController@deleteAcc_type4' );
Route::get( 'emptype4/search', array( 'uses' => 'BankAccController@bank_acc_type4' ) );
Route::post( 'emptype4/search', array( 'uses' => 'BankAccController@bankacc4_post_search' ) );
//----- salary emptype4 ลูกจ้างชั่วคราว -------------//
Route::get( 'emptype4/salary', array( 'uses' => 'SalaryController@salary_type4' ) );
Route::get( 'emptype4/fromSalary4/{id}', array( 'uses' => 'SalaryController@fromSalary_type4' ) );
Route::post( 'emptype4/addSalary', array( 'uses' => 'SalaryController@addSalary_type4' ) );
Route::post( 'emptype4/salary4/{id}', array( 'uses' => 'SalaryController@salary_edit_type4' ) );
Route::get( 'emptype4/salary-search', array( 'uses' => 'SalaryController@salary_type4' ) );
Route::post( 'emptype4/salary-search', array( 'uses' => 'SalaryController@salary4_post_search' ) );
//----- salary_insert emptype4 ลูกจ้างชั่วคราว -------------//
Route::get( 'emptype4/salary_insert', array( 'uses' => 'SalaryController@salary_insert_type4' ) );
Route::get( 'emptype4/fromsalary_insert4/{id}', array( 'uses' => 'SalaryController@fromsalary_insert_type4' ) );
Route::post( 'emptype4/salary_add', array( 'uses' => 'SalaryController@salary_add_type4' ) );
Route::post( 'emptype4/salary_insert4/{id}/{orderdate}', array( 'uses' => 'SalaryController@salary_insert_edit_type4' ) );
Route::get( 'emptype4/salary_insert-search', array( 'uses' => 'SalaryController@salary_insert_type4' ) );
Route::post( 'emptype4/salary_insert-search', array( 'uses' => 'SalaryController@salary_insert4_post_search' ) );






//====================================== ลูกจ้างรายวัน =================================//
//----- bank_acc emptype5 ลูกจ้างรายวัน -----//
Route::get( 'emptype5/bank_acc', array( 'uses' => 'BankAccController@bank_acc_type5' ) );
Route::get( 'emptype5/fromAcc5/{id}', array( 'uses' => 'BankAccController@fromAcc_type5' ) );
Route::post( 'emptype5/addAcc', array( 'uses' => 'BankAccController@addAcc_type5' ) );
Route::get( 'emptype5/deleteAcc5/{id}/{cid}', 'BankAccController@deleteAcc_type5' );
Route::get( 'emptype5/search', array( 'uses' => 'BankAccController@bank_acc_type5' ) );
Route::post( 'emptype5/search', array( 'uses' => 'BankAccController@bankacc5_post_search' ) );
//----- salary emptype5 ลูกจ้างรายวัน -------------//
Route::get( 'emptype5/salary', array( 'uses' => 'SalaryController@salary_type5' ) );
Route::get( 'emptype5/fromSalary5/{id}', array( 'uses' => 'SalaryController@fromSalary_type5' ) );
Route::post( 'emptype5/addSalary', array( 'uses' => 'SalaryController@addSalary_type5' ) );
Route::post( 'emptype5/salary5/{id}', array( 'uses' => 'SalaryController@salary_edit_type5' ) );
Route::get( 'emptype5/salary-search', array( 'uses' => 'SalaryController@salary_type5' ) );
Route::post( 'emptype5/salary-search', array( 'uses' => 'SalaryController@salary5_post_search' ) );
//----- salary_insert emptype5 ลูกจ้างรายวัน -------------//
Route::get( 'emptype5/salary_insert', array( 'uses' => 'SalaryController@salary_insert_type5' ) );
Route::get( 'emptype5/fromsalary_insert5/{id}', array( 'uses' => 'SalaryController@fromsalary_insert_type5' ) );
Route::post( 'emptype5/salary_add', array( 'uses' => 'SalaryController@salary_add_type5' ) );
Route::post( 'emptype5/salary_insert5/{id}/{orderdate}', array( 'uses' => 'SalaryController@salary_insert_edit_type5' ) );
Route::get( 'emptype5/salary_insert-search', array( 'uses' => 'SalaryController@salary_insert_type5' ) );
Route::post( 'emptype5/salary_insert-search', array( 'uses' => 'SalaryController@salary_insert5_post_search' ) );







//=================== เงินเดือนย้อนหลัง =======================//
Route::get( 'empsalaryAll', array( 'uses' => 'SalaryController@empAll_list' ) );
Route::get( 'empsalaryAll/_autocomplete', array( 'uses' => 'AdminController@_autocomplete' ) );
Route::get( 'empsalaryAll/checktype_emp/{id}', array( 'uses' => 'SalaryController@checktype_emp' ) );
Route::get( 'empsalaryAll/get_empall/{id}/{id2}/{id3}/{id4}', array( 'uses' => 'SalaryController@get_empall' ) );
Route::post( 'empsalaryAll/salary_add_new', array( 'uses' => 'SalaryController@salary_add_new' ) );
Route::post( 'empsalaryAll/salary_add_new_ocsc', array( 'uses' => 'SalaryController@salary_add_new_ocsc' ) );






//=================== Auto add salary =======================//
Route::get( 'emptype2/salary_insert_auto', array( 'uses' => 'SalaryController@salary_insert_auto' ) );
Route::get( 'emptype2/salary_insert_auto/addauto', array( 'uses' => 'SalaryController@addauto' ) );





//============================ Report =======================================//
Route::get( 'report/salary', array( 'uses' => 'ReportController@salary_all' ) );
Route::get( 'report/salary_emp', array( 'uses' => 'ReportController@salary_emp' ) );
Route::get( 'report/salary_emp_card', array( 'uses' => 'ReportController@salary_emp_card' ) );
Route::post( 'report/salary_emp_cid', array( 'uses' => 'ReportController@salary_emp_cid' ) );
Route::get( 'report/salary_receivef1', array( 'uses' => 'ReportController@salary_receivef1' ) );
Route::post( 'report/salary_receive', array( 'uses' => 'ReportController@salary_receive' ) );

Route::get( 'report/salary_receivef1_day', array( 'uses' => 'ReportController@salary_receivef1_day' ) );
Route::post( 'report/salary_receive_day', array( 'uses' => 'ReportController@salary_receive_day' ) );


Route::get( 'report/salary_excel_home', array( 'uses' => 'ReportController@salary_excel_home' ) );
Route::post( 'report/salary_excel', array( 'uses' => 'ReportController@salary_excel' ) );

Route::get( 'report/salary_excel_home_day', array( 'uses' => 'ReportController@salary_excel_home_day' ) );
Route::post( 'report/salary_excel_day', array( 'uses' => 'ReportController@salary_excel_day' ) );


Route::get( 'report/salary_excel_pdf_home', array( 'uses' => 'ReportController@salary_excel_pdf_home' ) );
Route::post( 'report/salary_excel_pdf', array( 'uses' => 'ReportController@salary_excel_pdf' ) );


Route::get( 'report/salary_excel_pdf_home_day', array( 'uses' => 'ReportController@salary_excel_pdf_home_day' ) );
Route::post( 'report/salary_excel_pdf_day', array( 'uses' => 'ReportController@salary_excel_pdf_day' ) );


Route::get( 'report/salary_excel_2', array( 'uses' => 'ReportController@salary_excel_2' ) );
Route::post( 'report/salary_excel_2file', array( 'uses' => 'ReportController@salary_excel_2file' ) );
Route::get( 'report/salary_ocsc', array( 'uses' => 'ReportController@salary_ocsc_all' ) );
Route::get( 'report/salary_emp_ocsc', array( 'uses' => 'ReportController@salary_emp_ocsc' ) );
Route::get( 'report/salary_ocsc_receive', array( 'uses' => 'ReportController@salary_ocsc_receive' ) );
Route::get( 'report/salary_ocsc_ktb', array( 'uses' => 'ReportController@salary_ocsc_ktb' ) );
Route::get( 'report/special_excel', array( 'uses' => 'ReportController@special_excel' ) );
Route::post( 'report/special_excel_export', array( 'uses' => 'ReportController@special_excel_export' ) );
Route::get( 'report/special_ot_excel', array( 'uses' => 'ReportController@special_ot_excel' ) );
Route::post( 'report/special_ot_excel_export', array( 'uses' => 'ReportController@special_ot_excel_export' ) );
Route::get( 'report/sp_sa_excel', array( 'uses' => 'ReportController@sp_sa_excel' ) );
Route::post( 'report/sp_sa_excel_export', array( 'uses' => 'ReportController@sp_sa_excel_export' ) );
Route::get( 'report/salary_sso_home', array( 'uses' => 'ReportController@salary_sso_home' ) );
Route::post( 'report/salary_sso_pdf_export', array( 'uses' => 'ReportController@salary_sso_pdf_export' ) );
Route::get( 'report/salary_sso_home_excel', array( 'uses' => 'ReportController@salary_sso_home_excel' ) );
Route::post( 'report/salary_sso_excel_export', array( 'uses' => 'ReportController@salary_sso_excel_export' ) );


Route::get( 'report/support_excel', array( 'uses' => 'ReportController@support_excel' ) );
Route::post( 'report/support_excel_export', array( 'uses' => 'ReportController@support_excel_export' ) );

Route::get( 'report/support_excel_day', array( 'uses' => 'ReportController@support_excel_day' ) );
Route::post( 'report/support_excel_day_export', array( 'uses' => 'ReportController@support_excel_day_export' ) );


Route::get( 'report/support_ocsc_excel', array( 'uses' => 'ReportController@support_ocsc_excel' ) );
Route::post( 'report/support_ocsc_excel_export', array( 'uses' => 'ReportController@support_ocsc_excel_export' ) );



//=========================== TAX ========================================//
Route::get( 'tax1/continuous_home1', array( 'uses' => 'TaxController@continuous_home1' ) );
Route::post( 'tax1/continuous_home1_post', array( 'uses' => 'TaxController@tax_continuous_type1' ) );
Route::get( 'tax1/continuous/{id}/{year}', array( 'uses' => 'TaxController@tax_continuous_type1' ) );
Route::get( 'tax1/itpc_home1', array( 'uses' => 'TaxController@itpc_home1' ) );
Route::post( 'tax1/itpc', array( 'uses' => 'TaxController@tax_itpc_type1' ) );
Route::get( 'tax1/recomend', array( 'uses' => 'TaxController@tax_recomend_type1' ) );
Route::get( 'tax1/fromTax/{id}/{year}', array( 'uses' => 'TaxController@fromstax_type1' ) );
Route::post( 'tax1/updateTax/{id}/{date}/{tax}/{special}/{pts}/{ot}', array( 'uses' => 'TaxController@updatetax_type1' ) );
Route::get( 'tax1/search', array( 'uses' => 'TaxController@tax_recomend_type1' ) );
Route::post( 'tax1/search', array( 'uses' => 'TaxController@search_type1' ) );
Route::get( 'tax1/sumsalary_tax1', array( 'uses' => 'TaxController@sumsalary_tax1' ) );
Route::post( 'tax1/sumsalary_tax1_pdf', array( 'uses' => 'TaxController@sumsalary_tax1_pdf' ) );


Route::get( 'tax2/continuous_home2', array( 'uses' => 'TaxController@continuous_home2' ) );
Route::post( 'tax2/continuous_home2_post', array( 'uses' => 'TaxController@tax_continuous_type2' ) );
Route::get( 'tax2/continuous/{id}/{year}', array( 'uses' => 'TaxController@tax_continuous_type2' ) );
Route::get( 'tax2/itpc_home2', array( 'uses' => 'TaxController@itpc_home2' ) );
Route::post( 'tax2/itpc', array( 'uses' => 'TaxController@tax_itpc_type2' ) );
Route::get( 'tax2/recomend', array( 'uses' => 'TaxController@tax_recomend_type2' ) );
Route::get( 'tax2/fromTax/{id}/{year}', array( 'uses' => 'TaxController@fromstax_type2' ) );
Route::post( 'tax2/updateTax/{id}/{date}/{tax}/{special}/{rother}/{rpt}', array( 'uses' => 'TaxController@updatetax_type2' ) );
Route::get( 'tax2/search', array( 'uses' => 'TaxController@tax_recomend_type2' ) );
Route::post( 'tax2/search', array( 'uses' => 'TaxController@search_type2' ) );

Route::get( 'tax3/continuous_home3', array( 'uses' => 'TaxController@continuous_home3' ) );
Route::post( 'tax3/continuous_home3_post', array( 'uses' => 'TaxController@tax_continuous_type3' ) );
Route::get( 'tax3/continuous/{id}/{year}', array( 'uses' => 'TaxController@tax_continuous_type3' ) );
Route::get( 'tax3/continuous_home3_pts', array( 'uses' => 'TaxController@continuous_home3_pts' ) );
Route::post( 'tax3/continuous_home3_post_pts', array( 'uses' => 'TaxController@tax_continuous_type3_pts' ) );
Route::get( 'tax3/itpc_home3', array( 'uses' => 'TaxController@itpc_home3' ) );
Route::post( 'tax3/itpc', array( 'uses' => 'TaxController@tax_itpc_type3' ) );
Route::get( 'tax3/itpc_home3_pts', array( 'uses' => 'TaxController@itpc_home3_pts' ) );
Route::post( 'tax3/itpc_pts', array( 'uses' => 'TaxController@tax_itpc_type3_pts' ) );
Route::get( 'tax3/sumsalarytax', array( 'uses' => 'TaxController@sumsalarytax' ) );
Route::post( 'tax3/sumsalarytax_pdf', array( 'uses' => 'TaxController@sumsalarytax_pdf' ) );
Route::get( 'tax3/continuous_sp_home', array( 'uses' => 'TaxController@continuous_sp_home' ) );
Route::post( 'tax3/continuous_sp', array( 'uses' => 'TaxController@continuous_sp' ) );
Route::get( 'tax3/itpc_sp_home', array( 'uses' => 'TaxController@itpc_sp_home' ) );
Route::post( 'tax3/itpc_sp', array( 'uses' => 'TaxController@itpc_sp' ) );
Route::get( 'tax3/continuous_pts/{id}/{year}', array( 'uses' => 'TaxController@continuous_pts' ) );
//Route::get( 'tax3/itpc_pts', array( 'uses' => 'TaxController@itpc_pts' ) );
Route::get( 'tax3/recomend', array( 'uses' => 'TaxController@tax_recomend_type3' ) );
Route::get( 'tax3/fromTax/{id}/{year}', array( 'uses' => 'TaxController@fromstax_type3' ) );
Route::post( 'tax3/updateTax/{id}/{date}/{tax}/{special}/{rother}/{rpt}', array( 'uses' => 'TaxController@updatetax_type3' ) );
Route::get( 'tax3/search', array( 'uses' => 'TaxController@tax_recomend_type3' ) );
Route::post( 'tax3/search', array( 'uses' => 'TaxController@search_type3' ) );

Route::get( 'tax4/itpc_home4', array( 'uses' => 'TaxController@itpc_home4' ) );
Route::post( 'tax4/itpc', array( 'uses' => 'TaxController@tax_itpc_type4' ) );
Route::get( 'tax4/continuous_home4', array( 'uses' => 'TaxController@continuous_home4' ) );
Route::post( 'tax4/continuous_home4_post', array( 'uses' => 'TaxController@tax_continuous_type4' ) );



//================================= Add Special 1 =======================//
Route::get( 'special/add_special1', array( 'uses' => 'TaxController@add_special1' ) );
Route::get( 'special/add_special1/{year}/{month}/{pay}/{q_pay}', array( 'uses' => 'TaxController@viewspecial1' ) );
Route::get( 'special/add_special1/{year}/{month}/{all}', array( 'uses' => 'TaxController@viewspecial_all' ) );
//Route::get( 'special/add_special1/update_special/{cid}/{y}/{m}/{id}/{id1}/{id2}/{id3}', array( 'uses' => 'TaxController@update_special' ) );
Route::post( 'special/add_special1/update_special', array( 'uses' => 'TaxController@update_special' ) );
//Route::get( 'special/add_special1/update_special_all/{cid}/{y}/{m}/{id}/{id1}/{id2}/{id3}/{id4}/{id5}/{id6}', array( 'uses' => 'TaxController@update_special_all' ) );
Route::post( 'special/add_special1/update_special_all', array( 'uses' => 'TaxController@update_special_all' ) );





//============================== meter water =========================//
Route::get( 'special/unit_water', array( 'uses' => 'TaxController@unit_water' ) );
Route::post( 'special/general_update/{id}', array( 'uses' => 'TaxController@post_general_update' ) );
Route::get( 'special/add_meter', array( 'uses' => 'TaxController@add_meter' ) );
Route::post( 'special/add_meter_todb', array( 'uses' => 'TaxController@add_meter_todb' ) );
Route::get( 'special/del_meter/{id}', array( 'uses' => 'TaxController@del_meter' ) );
Route::get( 'special/add_emp_meter', array( 'uses' => 'TaxController@add_emp_meter' ) );
Route::get( 'special/_autocomplete', array( 'uses' => 'TaxController@_autocomplete' ) );
Route::get( 'special/view_empmeter/{id}', array( 'uses' => 'TaxController@view_empmeter' ) );
Route::get( 'special/update_empmeter/{id}/{id2}', array( 'uses' => 'TaxController@update_empmeter' ) );
Route::get( 'special/del_empmeter/{id}/{id2}', array( 'uses' => 'TaxController@del_empmeter' ) );






//============================== water =========================//
Route::get( 'special/add_water', array( 'uses' => 'TaxController@add_water' ) );
Route::get( 'special/view_water/{y}/{m}', array( 'uses' => 'TaxController@view_water' ) );
Route::post( 'special/savewater', array( 'uses' => 'TaxController@savewater' ) );





//============================== home elec =========================//
Route::get( 'special/add_home', array( 'uses' => 'TaxController@add_home' ) );
Route::post( 'special/add_home_todb', array( 'uses' => 'TaxController@add_home_todb' ) );
Route::get( 'special/del_home/{id}', array( 'uses' => 'TaxController@del_home' ) );
Route::get( 'special/add_emp_home', array( 'uses' => 'TaxController@add_emp_home' ) );
Route::get( 'special/view_emphome/{id}', array( 'uses' => 'TaxController@view_emphome' ) );
Route::post( 'special/update_emphome', array( 'uses' => 'TaxController@update_emphome' ) );
Route::get( 'special/del_emphome/{id}/{id2}', array( 'uses' => 'TaxController@del_emphome' ) );





//============================== elec =========================//
Route::get( 'special/add_elec', array( 'uses' => 'TaxController@add_elec' ) );
Route::get( 'special/view_elec/{y}/{m}', array( 'uses' => 'TaxController@view_elec' ) );
Route::post( 'special/saveelec', array( 'uses' => 'TaxController@saveelec' ) );





//================================== Unit Costs ====================================//
//---------menu 1
Route::get( 'unitcosts', array( 'uses' => 'CostsController@unitcosts' ) );
Route::get( 'unitcosts/create', array( 'uses' => 'CostsController@unitcosts_create' ) );
Route::post( 'unitcosts/create', array( 'uses' => 'CostsController@post_new_unitcosts' ) );
Route::get( 'unitcosts/edit/{id}', array( 'uses' => 'CostsController@unitcosts_edit' ) );
Route::post( 'unitcosts/edit/{id}', array( 'uses' => 'CostsController@post_edit_unitcosts' ) );
Route::get( 'unitcosts/delete/{id}', array( 'uses' => 'CostsController@unitcosts_delete' ) );
Route::get( 'unitcosts/search', array( 'uses' => 'CostsController@unitcosts' ) );
Route::post( 'unitcosts/search', array( 'uses' => 'CostsController@unitcosts_post_search' ) );
//---------menu 2
Route::get( 'unitcosts/add', array( 'uses' => 'CostsController@unitcosts_addemp' ) );
Route::get( 'unitcosts/_autocomplete', array( 'uses' => 'CostsController@_autocomplete' ) );
Route::get( 'unitcosts/viewempunit/{cid}', array( 'uses' => 'CostsController@viewempunit' ) );
Route::get( 'unitcosts/addemp/{unitid}/{cid}/{cal}', array( 'uses' => 'CostsController@addemp' ) );
Route::get( 'unitcosts/delemp/{id}/{unit_id}/{cid}', array( 'uses' => 'CostsController@delemp' ) );
//---------menu 3
Route::get( 'unitcosts/range_ot_sso', array( 'uses' => 'CostsController@range_ot_sso' ) );
Route::get( 'unitcosts/range_ot_sso/update_range/{name}/{r_start}/{r_end}', array( 'uses' => 'CostsController@update_range' ) );
//---------menu 4
Route::get( 'unitcosts/manager', array( 'uses' => 'CostsController@unitcosts_manager' ) );
Route::get( 'unitcosts/manager/view_manager/{y}/{m}/{u}', array( 'uses' => 'CostsController@view_manager' ) );
Route::get( 'unitcosts/manager/update_manager/{y}/{m}/{type}/{cid}/{u_travel1}/{u_other1}', array( 'uses' => 'CostsController@update_manager' ) );
//---------menu 5

Route::get( 'unitcosts/money_home_month', array( 'uses' => 'CostsController@unitcosts_money_home_month' ) );
Route::post( 'unitcosts/money_month', array( 'uses' => 'CostsController@unitcosts_money_month' ) );

Route::get( 'unitcosts/money_home', array( 'uses' => 'CostsController@unitcosts_money_home' ) );
Route::post( 'unitcosts/money', array( 'uses' => 'CostsController@unitcosts_money' ) );
Route::get( 'unitcosts/money_home_lc', array( 'uses' => 'CostsController@unitcosts_money_home_lc' ) );
Route::post( 'unitcosts/money_lc', array( 'uses' => 'CostsController@unitcosts_money_lc' ) );

