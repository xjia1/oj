<?php
class UserController extends ApplicationController
{
  /**
   * Authors Ranklist
   */
  public function ranklist()
  {
    $this->cache_control('private', 300);
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
  
  public function showLoginPage()
  {
    $this->cache_control('public', 300);
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
          fMessaging::create('success', '登录成功.');
          fURL::redirect(fAuthorization::getRequestedURL(TRUE, Util::getReferer()));
        } else {
          throw new fValidationException('密码错误.');
        }
      } else if (fRequest::get('action') == '注册') {
        if (strlen($username) < 4) {
          throw new fValidationException('用户名过短.');
        }
        if (strlen($username) > 20) {
          throw new fValidationException('用户名过长.');
        }
        if (strlen($password) < 6) {
          throw new fValidationException('密码过短.');
        }
        if (Util::contains('`~!@#$%^&*()-+=[]\\;\',/{}|:"<>?', $username) or preg_match('/\s/', $username)) {
          throw new fValidationException('用户名包含非法字符.');
        }
        try {
          $user = new User($username);
          throw new fValidationException('用户已存在.');
        } catch (fNotFoundException $e) {
          $user = new User();
          $user->setUsername($username);
          $user->setPassword($password_hash);
          $user->store();
          fAuthorization::setUserToken($user->getUsername());
          fMessaging::create('success', '注册成功.');
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
    fMessaging::create('success', '注销成功.');
    if (strstr(Util::getReferer(), 'contest')) {
      fURL::redirect(SITE_BASE);
    } else {
      fURL::redirect(Util::getReferer());
    }
  }
  
  public function changeInfo()
  {
    $this->cache_control('private', 300);
    $this->render('user/change_info');
  }
  
  public function updateInfo()
  {
    try {
      $realname = trim(fRequest::get('realname'));
      $class_name = trim(fRequest::get('class_name'));
      $phone_number = trim(fRequest::get('phone_number'));
      
      try {
        $profile = new Profile(fAuthorization::getUserToken());
      } catch (fNotFoundException $e) {
        $profile = new Profile();
        $profile->setUsername(fAuthorization::getUserToken());
      }
      $profile->setRealname($realname);
      $profile->setClassName($class_name);
      $profile->setPhoneNumber($phone_number);
      $profile->store();
      
      fMessaging::create('success', '信息更新成功.');
      fURL::redirect(Util::getReferer());
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
      Util::redirect('/change/info');
    }
  }
  
  public function changePassword()
  {
    $this->cache_control('private', 300);
    $this->render('user/change_password');
  }
  
  public function updatePassword()
  {
    try {
      $old_password = fRequest::get('old_password');
      $new_password = fRequest::get('new_password');
      $repeat_password = fRequest::get('repeat_password');
      if (strlen($old_password) < 6) {
        throw new fValidationException('旧密码过短.');
      }
      if (strlen($new_password) < 6) {
        throw new fValidationException('新密码过短.');
      }
      if ($new_password != $repeat_password) {
        throw new fValidationException('两次输入密码不相同.');
      }
      $user = new User(fAuthorization::getUserToken());
      $old_password_hash = static::hashPassword($old_password);
      if ($user->getPassword() != $old_password_hash) {
        throw new fValidationException('旧密码错误.');
      }
      $new_password_hash = static::hashPassword($new_password);
      $user->setPassword($new_password_hash);
      $user->store();
      fMessaging::create('success', '密码更改成功.');
      fURL::redirect(Util::getReferer());
    } catch (fException $e) {
      fMessaging::create('error', $e->getMessage());
      Util::redirect('/change/password');
    }
  }
  
  public function emailVerify()
  {
    $this->cache_control('private', 300);
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
        throw new fValidationException('邮箱地址不可用.');
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
        "你好 {$username},

        请点击以下链接来验证你的邮箱地址： ({$email})
        
        {$verilink}

        ---

        祝好,

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
    $this->cache_control('private', 300);
    $this->render('user/email/vericode_sent');
  }
  
  public function checkVericode($id, $vericode)
  {
    try {
      $v = new Vericode($id);
      if ($v->getUsername() != fAuthorization::getUserToken() or $v->getVericode() != $vericode) {
        throw new fValidationException('无效验证码.');
      }
      
      $ue = new UserEmail();
      $ue->setUsername($v->getUsername());
      $ue->setEmail($v->getEmail());
      $ue->store();
      
      fMessaging::create('success', '你的邮箱已成功验证.');
      $referer = fMessaging::retrieve('referer', SITE_BASE . '/email/verify');
      if ($referer == NULL) $referer = SITE_BASE;
      fURL::redirect($referer);
    } catch (fException $e) {
      fMessaging::create('error', '邮箱验证失败: ' . $e->getMessage());
      Util::redirect('/email/verify');
    }
  }
}


