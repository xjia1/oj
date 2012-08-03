<?php
class UserController extends ApplicationController
{
  public function showLoginPage()
  {
    $this->render('user/login');
  }
  
  /**
   * @see http://httpd.apache.org/docs/2.2/misc/password_encryptions.html
   */
  public function login()
  {
    $username = fRequest::get('username', 'string');
    $password = fRequest::get('password', 'string');
    $password_hash = '{SHA}' . base64_encode(sha1($password, TRUE));
    try {
      $user = new User($username);
      if ($user->getPassword() == $password_hash) {
        fAuthorization::setUserToken($user->getUsername());
        fMessaging::create('success', 'Logged in successfully.');
        fURL::redirect(fAuthorization::getRequestedURL(TRUE, $_SERVER["HTTP_REFERER"]));
      } else {
        throw new fValidationException('Password mismatch.');
      }
    } catch (fExpectedException $e) {
      fMessaging::create('error', $e->getMessage());
      fURL::redirect(fAuthorization::getRequestedURL(TRUE, $_SERVER["HTTP_REFERER"]));
    }
  }
  
  public function logout()
  {
    fAuthorization::destroyUserInfo();
    fMessaging::create('success', 'Logged out successfully.');
    fURL::redirect(fAuthorization::getRequestedURL(TRUE, $_SERVER["HTTP_REFERER"]));
  }
  
  public function changePassword()
  {
    //
  }
  
  public function updatePassword()
  {
    //
  }
}
