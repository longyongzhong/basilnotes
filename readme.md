# basilnotes在线笔记



basilnotes是一款简洁的在线笔记，基于laravel框架开发，该笔记包含写笔记、保存笔记、修改笔记、分类管理、删除恢复、用户注册等功能。可以安装在配置了php运行环境的个人电脑上使用，也可以部署在服务器上供多人使用。此笔记是作者在学习laravel框架时开发出来的，不足之处，敬请指出改正。


## 环境要求
+ PHP版本 >= 5.6.9
+ PHP扩展：OpenSSL
+ PHP扩展：PDO
+ PHP扩展：Mbstring
+ PHP扩展：Tokenizer
+ mysql版本 >=5.5
+ nginx
## 安装
以Linux deepin为例（已配置好php运行环境）
+ 下载zip或者使用git克隆到/var/www/html目录
+ 建立名为note(也可以其它名字)的mysql数据库，notes.sql数据
+ 修改项目根目录下.env配置文件：
<pre>
//配置数据库
DB_HOST=127.0.0.1
DB_DATABASE=notes
DB_USERNAME=root
DB_PASSWORD=123456
//配置邮箱服务器（以qq邮箱为例，需要开通stmp服务）
MAIL_DRIVER=smtp
MAIL_HOST=smtp.qq.com
MAIL_PORT=587
MAIL_USERNAME=123456@qq.com
MAIL_PASSWORD=123456
MAIL_ENCRYPTION=tls
</pre>
+ nginx设置伪静态
```
location / {
if (!-e $request_filename){
    rewrite  ^(.*)$  /index.php?s=$1  last;   break;
    }
}
```
+ 首页地址为（以本地为例）：http://localhost/basilnotes
  登录账号11111111@qq.com,密码123456
+ 后台用户管理地址及账号密码：http://localhost/basilnotes/public/index.php/managelogin 账号：root 密码:123456
## 交流联系方式

Email：15813246678@163.com
