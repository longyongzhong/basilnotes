<?php
namespace App\Http\Controllers;
include "plugin/code/Code.class.php";
include "plugin/random/Random.class.php";
session_start();
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use App\Http\Model\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use App\Http\Model\Manager;

class LoginController extends Controller
{
    //用户登录
    public function login(Request $request){
        if ($request->method() === 'POST'){
            $data = $request->input();
            $useremail = $data['email'];
            $userpassword = $data['password'];
            $code = strtoupper($data['code']);
            $codemaker = new \Code();
            $vadicode = $codemaker->get();
            if ($code!=$vadicode){
                return view("login")->with("err","验证码错误");

            }else{
                $userdata = Users::where("email",$useremail)->first();
                $status = $userdata->status;
                if ($status=="frozen"){
                   return view("frozen");
                }elseif($status=="notyet"){
                    return view("notactiveinfo",["email"=>$userdata->email]);
                }else{
                    $password = Crypt::decrypt($userdata->password);
                    if ($password==$userpassword){
                        session(["user"=>$useremail]);
                        return redirect("/home");
                    }else{
                        return view("login")->with("errmessage","您输入的密码错误");
                    }
                }

            }
        }else{
            return view("login");
        }

    }
    //用户注册
    public function reg(Request $request){
        if ($request->method() === 'POST'){
            $regdata = $request->input();
            //自定义错误提示信息
            $messages  = [
                "required"=>":attribute 不能为空！",
                "email"=>"您输入的邮箱格式不正确",
                "max"=>"密码长度不能超过 :max",
                "min"=>"密码长度不能少于 :min "
            ];
            //验证表单
        $validator = Validator::make($regdata,['email'=>'required|email','password'=>'max:30|min:6'],$messages);
        if ($validator->fails()){   //验证失败时返回的错误信息
            return redirect("/reg")->withErrors($validator)->withInput();
                                }
        $codemaker = new \Code();   //实例化验证码类
        $authcode = $codemaker->get();  //获取验证码
         $code = strtoupper($regdata['code']);
         if ($code!=$authcode){
             return view("reg",["err"=>"验证码错误！"]);
                                }
        $rdcode  = new \Random(); //实例化随机码类
        $randomcode = $rdcode->getrdcode(8);    //获取随机码
        $email = $regdata['email']; //获取邮箱号码
        $password = Crypt::encrypt($regdata['password']);   //获取用户密码
        $status = "notyet"; //设置用户注册时的未验证状态
        $regtime = time(); //设置用户的注册时间
        $counts = Users::where("email",$email)->count();
            if ($counts>=1){
                return view('reg',['emailerr'=>'刚刚输入的邮箱已被注册，请重新填写或登录']);
            }else{
                //存入数据库
                $user = new Users();
                $user->email = $email;
                $user->password = $password;
                $user->regtime = $regtime;
                $user->lastrdctime = $regtime;
                $user->randomcode = $randomcode;
                $user->status = $status;
                $emaildata = ['email'=>$email,'randomcode'=>$randomcode];
                if ($user->save()){
                    //发送邮件
                    $content = url("/active/$email/$randomcode");
                    $flag = Mail::send('email', ['content' => $content,'info'=>'请点击以下链接进行激活：'], function ($message) use ($email) {
                        $message->from('1126089177@qq.com', 'mynote');
                        $to = $email;
                        $message->to($to)->subject('用户激活');
                    });
                    if ($flag) {
                        return view("regsuccess",$emaildata);
                    } else {
                        echo "发送失败！";
                    }
                }else{
                    return view('reg');
                }
            }
        }else{
            return view("reg");
        }

    }
    //创建验证码
    public function makecode(){
        $code = new \Code();
        $code->make();
    }
    //注册时检测数据库是否存在相同的邮箱
    public function checkemail(){
        $data = Input::all();
        $email = $data['email'];
        $counts = Users::where('email',$email)->count();
        if ($counts>=1){
            echo "havesame";
        }else{
            echo "no";
        }
    }

    //发送验证邮件
    public function sendemail(Request $request)
    {
        if ($input = Input::all()) {
            $rcode = new \Random();
            $activecode = $rcode->getrdcode(10);
            $email = $input['email'];
            $content = url("/active/$email/$activecode");
            $flag = Mail::send('email', ['content' => $content,'info'=>'请点击以下链接进行激活：'], function ($message) use ($email) {
                $message->from('1126089177@qq.com', 'mynote');
                $to = $email;
                $message->to($to)->subject('用户激活');
            });
            if ($flag) {
                $userdata = Users::where('email',$email)->first();
                $userdata->lastrdctime = time();
                $userdata->randomcode = $activecode;
                $result = $userdata->save();
                if ($result){
                    echo "已发送到邮箱：" . $email . "请查看！";
                }else{
                    echo "已发送到邮箱：" . $email . "但数据库更新出错，邮件无效，请重试！";
                }

            } else {
                echo "发送失败！";
            }
        } else {
            echo "没有提交数据";
        }
    }
//找回密码
public function findpassword(Request $request){
        if ($request->method() === 'POST'){
            $data = Input::all();
            $email = $data['email'];
            $counts = Users::where('email',$email)->count();
            $user = Users::where('email',$email)->first();
            $codemaker = new \Code();
            $code = $codemaker->get();
            $dbcode = strtoupper($data['code']);
            if ($dbcode!=$code){
                echo "<script type='text/javascript'>alert('验证码输入错误！')</script>";
                return view("findpassword");
            }else{
                if ($counts==1){
                    $userpassword = $user->password;
                    $content = "您的密码是：".Crypt::decrypt($userpassword);
                    $flag = Mail::send('email', ['content' => $content,'info'=>'找回密码如下，请妥善保管'], function ($message) use ($email) {
                        $message->from('1126089177@qq.com', 'mynote');
                        $to = $email;
                        $message->to($to)->subject('密码找回');
                    });
                    if ($flag){
                        $message = "密码已发送至您的邮箱{$email}，请查看";
                        return view("vadsuccess",['message'=>$message,'info'=>'欢迎使用mynote']);
                    }else{
                        echo "<script type='text/javascript'>alert('邮件发送失败，请重试！')</script>";
                        return view("findpassword");
                    }
                }else{
                    echo "<script type='text/javascript'>alert('您输入的邮件没有注册，请先注册！！')</script>";
                    return view("findpassword");
                }
            }

        }else{
            return view("findpassword");
        }

}

//管理员登录
public function managelogin(Request $request){
    if ($request->method() === 'POST'){
        $data = Input::all();
        $managername = $data['name'];
        $managerpass = $data['password'];
        $code = strtoupper($data['code']);
        $codemaker = new \Code();
        $vadicode = $codemaker->get();
        if ($code!=$vadicode){
            return view("managelogin")->with("err","验证码错误");

        }else{
            $managerdata = Manager::where(["id"=>"1"])->first();
            $name = $managerdata->name;
            $password = Crypt::decrypt($managerdata->password);
            if ($name==$managername&&$password==$managerpass){
                session(["root"=>$name]);
               return redirect('/manage');
            }else{
                return view("managelogin")->with("errmessage","您输入的密码错误");;
            }
        }
    }else{
        return view('managelogin');
    }
}

}
