<?php
class DashboardController extends ApplicationController
{
  public function index()
  {
    if (!User::can('manage-site')) {
      fMessaging::create('error', 'You are not allowed to view the dashboard.');
      fURL::redirect(Util::getReferer());
    }
    
    $this->permissions = fRecordSet::build('Permission');
    $this->variables = fRecordSet::build('Variable');
    
    if (strlen($edit = fRequest::get('edit'))) {
      $this->setvar_name = $edit;
      $this->setvar_value = Variable::getString($edit);
      $this->setvar_remove = FALSE;
    } else if (strlen($remove = fRequest::get('remove'))) {
      $this->setvar_name = $remove;
      $this->setvar_value = Variable::getString($remove);
      $this->setvar_remove = TRUE;
    } else {
      $this->setvar_name = '';
      $this->setvar_value = '';
      $this->setvar_remove = FALSE;
    }
    
    $this->render('dashboard/index');
  }
}
