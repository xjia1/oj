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
      if (fRequest::get('action') == 'Sign In') {
        $user = new User($username);
        if ($user->getPassword() == $password_hash) {
          fAuthorization::setUserToken($user->getUsername());
          fMessaging::create('success', 'Logged in successfully.');
          fURL::redirect(fAuthorization::getRequestedURL(TRUE, Util::getReferer()));
        } else {
          throw new fValidationException('Password mismatch.');
        }
      } else if (fRequest::get('action') == 'Register') {
        try {
          $user = new User($username);
          throw new fValidationException('User already exists.');
        } catch (fNotFoundException $e) {
          $user = new User();
          $user->setUsername($username);
          $user->setPassword($password_hash);
          $user->store();
          fAuthorization::setUserToken($user->getUsername());
          fMessaging::create('success', 'Registered successfully.');
          fURL::redirect(fAuthorization::getRequestedURL(TRUE, Util::getReferer()));
        }
      }
    } catch (fExpectedException $e) {
      fMessaging::create('error', $e->getMessage());
      fURL::redirect(fAuthorization::getRequestedURL(TRUE, Util::getReferer()));
    }
  }
  
  public function logout()
  {
    fAuthorization::destroyUserInfo();
    fMessaging::create('success', 'Logged out successfully.');
    fURL::redirect(fAuthorization::getRequestedURL(TRUE, Util::getReferer()));
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
