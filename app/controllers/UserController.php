<?php
class UserController extends ApplicationController
{
  /**
   * Authors Ranklist
   */
  public function ranklist()
  {
    $this->page = fRequest::get('page', 'integer', 1);
    if ($this->page <= 0) $this->page = 1;
    
    $this->user_stats = fRecordSet::build('UserStat', array(), array(
      'solved' => 'desc',
      'tried' => 'asc',
      'submissions' => 'asc'
    ), Variable::getInteger('users-per-page', 50), $this->page);
    
    $this->page_url = SITE_BASE . '/ranklist?page=';
    $this->page_records = $this->user_stats;
    $this->nav_class = 'ranklist';
    $this->render('user/ranklist');
  }
  
  public function showSignUpPage()
  {
    $this->render('user/signup');
  }

  public function showLoginPage()
  {
    $this->render('user/login');
  }
  
  /**
   * @see http://httpd.apache.org/docs/2.2/misc/password_encryptions.html
   */
  private static function hashPassword($password)
  {
    return '{SHA}' . base64_encode(sha1($password, TRUE));
  }
  
  public function login()
  {
    $username = trim(fRequest::get('username', 'string'));
    $password = fRequest::get('password', 'string');
    $password_hash = static::hashPassword($password);
    try {
      if (fRequest::get('action') == '登录') {
        $user = new User($username);
        if ($user->getPassword() == $password_hash) {
          fAuthorization::setUserToken($user->getUsername());
          fMessaging::create('success', 'Logged in successfully.');
          fURL::redirect(fAuthorization::getRequestedURL(TRUE, Util::getReferer()));
        } else {
          throw new fValidationException('Password mismatch.');
        }
      } else if (fRequest::get('action') == '注册') {
        if (strlen($username) < 4) {
          throw new fValidationException('Username is too short.');
        }
        if (strlen($username) > 20) {
          throw new fValidationException('Username is too long.');
        }
        if (strlen($password) < 6) {
          throw new fValidationException('Password is too short.');
        }
        if (Util::contains('`~!@#$%^&*()-+=[]\\;\',/{}|:"<>?', $username) or preg_match('/\s/', $username)) {
          throw new fValidationException('Username is illegal.');
        }

        $realname = trim(fRequest::get('realname', 'string'));
        $gender = trim(fRequest::get('gender', 'string'));
        $school = trim(fRequest::get('school', 'string'));
        $major = trim(fRequest::get('major', 'string'));
        $grade = trim(fRequest::get('grade', 'integer', NULL));
        $phone = trim(fRequest::get('phone', 'string'));
        $qq = trim(fRequest::get('qq', 'string'));

        if (strlen($realname) < 1) throw new fValidationException('请填写真实姓名');
        if (strlen($gender) < 1) throw new fValidationException('请选择性别');
        if (strlen($phone) < 1) throw new fValidationException('请填写手机号码');

        try {
          $user = new User($username);
          throw new fValidationException('User already exists.');
        } catch (fNotFoundException $e) {
          $user = new User();
          $user->setUsername($username);
          $user->setPassword($password_hash);
          $user->store();

          try {
            $profile = new Profile($username);
          } catch (fNotFoundException $e) {
            $profile = new Profile();
            $profile->setUsername($username);
          }
          $profile->setRealname($realname);
          $profile->setGender($gender);
          $profile->setSchool($school);
          $profile->setMajor($major);
          $profile->setGrade($grade);
          $profile->setPhoneNumber($phone);
          $profile->setQq($qq);
          $profile->store();

          fAuthorization::setUserToken($user->getUsername());
          fMessaging::create('success', 'Registered successfully.');
          Util::redirect('/email/verify');
        }
      }
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
      fURL::redirect(fAuthorization::getRequestedURL(TRUE, Util::getReferer()));
    }
  }
  
  public function logout()
  {
    fAuthorization::destroyUserInfo();
    fMessaging::create('success', 'Logged out successfully.');
    if (strstr(Util::getReferer(), 'contest')) {
      fURL::redirect(SITE_BASE);
    } else {
      fURL::redirect(Util::getReferer());
    }
  }
  
  public function changeInfo()
  {
    $this->render('user/change_info');
  }
  
  public function updateInfo()
  {
    try {
      $realname = trim(fRequest::get('realname', 'string'));
      $gender = trim(fRequest::get('gender', 'string'));
      $school = trim(fRequest::get('school', 'string'));
      $major = trim(fRequest::get('major', 'string'));
      $grade = trim(fRequest::get('grade', 'integer', NULL));
      $phone = trim(fRequest::get('phone', 'string'));
      $qq = trim(fRequest::get('qq', 'string'));

      if (strlen($realname) < 1) throw new fValidationException('请填写真实姓名');
      if (strlen($gender) < 1) throw new fValidationException('请选择性别');
      if (strlen($phone) < 1) throw new fValidationException('请填写手机号码');

      try {
        $profile = new Profile(fAuthorization::getUserToken());
      } catch (fNotFoundException $e) {
        $profile = new Profile();
        $profile->setUsername(fAuthorization::getUserToken());
      }
      $profile->setRealname($realname);
      $profile->setGender($gender);
      $profile->setSchool($school);
      $profile->setMajor($major);
      $profile->setGrade($grade);
      $profile->setPhoneNumber($phone);
      $profile->setQq($qq);
      $profile->store();
      
      fMessaging::create('success', 'Information updated successfully.');
      fURL::redirect(Util::getReferer());
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
      Util::redirect('/change/info');
    }
  }
  
  public function changePassword()
  {
    $this->render('user/change_password');
  }
  
  public function updatePassword()
  {
    try {
      $old_password = fRequest::get('old_password');
      $new_password = fRequest::get('new_password');
      $repeat_password = fRequest::get('repeat_password');
      if (strlen($old_password) < 6) {
        throw new fValidationException('Old password is too short.');
      }
      if (strlen($new_password) < 6) {
        throw new fValidationException('New password is too short.');
      }
      if ($new_password != $repeat_password) {
        throw new fValidationException('Repeat password mismatch.');
      }
      $user = new User(fAuthorization::getUserToken());
      $old_password_hash = static::hashPassword($old_password);
      if ($user->getPassword() != $old_password_hash) {
        throw new fValidationException('Old password mismatch.');
      }
      $new_password_hash = static::hashPassword($new_password);
      $user->setPassword($new_password_hash);
      $user->store();
      fMessaging::create('success', 'Password updated successfully.');
      fURL::redirect(Util::getReferer());
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
      Util::redirect('/change/password');
    }
  }
  
  public function emailVerify()
  {
    fMessaging::create('referer', '/email/verify', Util::getReferer());
    $this->render('user/email/verify');
  }
  
  public function sendVericode()
  {
    $username = fAuthorization::getUserToken();
    $vericode = md5(uniqid($username, TRUE));
    
    try {
      $email = fRequest::get('email', 'string');
      if (filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE) {
        throw new fValidationException('Invalid email address.');
      }
      
      $v = new Vericode();
      $v->setUsername($username);
      $v->setVericode($vericode);
      $v->setEmail($email);
      $v->store();
      $id = $v->getId();
      $verilink = HOST_URL . SITE_BASE . "/email/vericode/{$id}/{$vericode}";
      
      Util::sendTextMail(
        'SJTU Online Judge', 'noreply@acm.sjtu.edu.cn',
        $email,
        'Email Verification',
        "Hi {$username},

        Please verify that you own this email address ({$email}) by clicking this link:
        
        {$verilink}

        ---

        Regards,

        SJTU Online Judge",
        $username, $email
      );
      Util::redirect('/email/verify/sent');
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
      Util::redirect('/email/verify');
    }
  }
  
  public function vericodeSent()
  {
    $this->render('user/email/vericode_sent');
  }
  
  public function checkVericode($id, $vericode)
  {
    try {
      $v = new Vericode($id);
      if ($v->getUsername() != fAuthorization::getUserToken() or $v->getVericode() != $vericode) {
        throw new fValidationException('Invalid verification code.');
      }
      
      $ue = new UserEmail();
      $ue->setUsername($v->getUsername());
      $ue->setEmail($v->getEmail());
      $ue->store();
      
      fMessaging::create('success', 'Your email address is verified successfully.');
      $referer = fMessaging::retrieve('referer', SITE_BASE . '/email/verify');
      if ($referer == NULL) $referer = SITE_BASE;
      fURL::redirect($referer);
    } catch (fException $e) {
      fMessaging::create('error', 'Email verification failed: ' . $e->getMessage());
      Util::redirect('/email/verify');
    }
  }
}
