<?php
/***************************************************************************************************
 * File Description:
 *  服务器生成cookie的集成类.
 *    使用本类时,要传入5个参数,分别表示cookieName, value, 过期时间 以及两个属性的bool值示例:
 *    new MyCookie("PHPSESSID", "xxxx", 3600, 0, 1);
 *    相当于在response的header部分写入: set-cookie: PHPSESSID=xxxx;expire=time()+3600 GMT;secure=0; httponly=1;
 *    调用setrawcookie时,值为null的value表示采用php.ini文件中的默认值
 * Updated History:
 * Author       Date            Content
/**************************************************************************************************/

class MyCookie {
  
  private $cookie_name;     // cookie名称
  private $cookie_value;    // cookie值
  private $cookie_expire;   // cookie过期时间
  private $cookie_secure;   // 是否有secure属性
  private $cookie_http;     // 是否有http-only属性

  /**
   * Method Description:
   *  构造函数
   */
  function __construct() {
    if (func_num_args() > 0 ) {
      $args = func_get_args();

      // 获取参数并赋值
      $this->cookie_name = $args[0];
      $this->cookie_value = $args[1];
      $this->cookie_expire = $args[2];
      $this->cookie_secure = $args[3];
      $this->cookie_http = $args[4];

      $this->cookieMake();
    }
  }
  
  /**
   * Method Description:
   *  set cookie
   */
  private function cookieMake() {
    try {
      if($this->cookie_name != "" && $this->cookie_value != "" && $this->cookie_expire!= ""
          && $this->cookie_secure != "" && $this->cookie_http != "") {
        //创建Cookie，设置Cookie名字,值,有效期以及两个属性
        setrawcookie($this->cookie_name, $this->cookie_value, time() + $this->cookie_expire, null
            , $this->cookie_secure, $this->cookie_http);
      } else {
        throw new exception("cookie参数需要填写完成");
      }
    } catch(exception $e) {
      echo $e->getmessage();
    }
  }


  /**
   * Method Description:
   *  修改当前cookie的值
   * @param   string
   */
  public function changeCookie($newValue) {
    $_COOKIE["$this->cookie_name"] = $newValue;
  }

  
  /**
   * Method Description: 
   *  获取当前cookie的value
   */
  public function getCookieValue() {
    return $_COOKIE["$this->cookie_name"];
  }

  /**
   * Method Description:
   *  删除指定cookie
   */
  public function removeCookie() {
    setcookie($this->cookie_name,$this->cookie_value,time()-3600);
  }

}