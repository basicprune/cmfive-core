<?php

function index_GET(Web $w) {
	$w->setLayout('layout-bootstrap-5');

	$w->ctx('title', 'Form Applications');
	$applications = FormService::getInstance($w)->getFormApplications();

	$application_table_data = [];
	if (!empty($applications)) {
		foreach($applications as $application) {
			$application_table_data[] = [
				HtmlBootstrap5::a("/form-application/show/$application->id", $application->title),
				$application->description,
				$application->is_active == 1 ? 'Active' : 'Inactive',
				HtmlBootstrap5::buttonGroup(implode("", [
					HtmlBootstrap5::b("/form-application/edit/$application->id", 'Edit', null, null, false, "btn-sm btn-secondary") .
					HtmlBootstrap5::b("/form-application/export/$application->id", 'Export', null, null, false, "btn-sm btn-info") .
					HtmlBootstrap5::b("/form-application/delete/$application->id", 'Delete', 'Are you sure you want to delete this application? All references to already entered data will be lost!', null, false, "btn-sm btn-danger")
				]))
			];
		}
	}

	$w->ctx('application_table_header', ['Title', 'Description', 'Active', 'Actions']);
	$w->ctx('application_table_data', $application_table_data);
	
}