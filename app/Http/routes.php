<?php
use Illuminate\Support\Facades\DB;
use App\Http\Model\Users;
use Illuminate\Support\Facades\Crypt;
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('index');
});
Route::post('/checkemail',"LoginController@checkemail");
Route::get('/regsuccess',function (){
    return view('regsuccess');
});
Route::post('/sendvadiemail','LoginController@sendemail');
//激活邮箱
Route::get('/active/{email}/{activecode}/',function ($email,$activecode){
    $userdata = Users::where('email',$email)->first();
    $dbcode = $userdata->randomcode;
    $lastrdctime = $userdata->lastrdctime;
    $interval = time()-$lastrdctime;
    if ($activecode==$dbcode){
        if ($interval>600){
           $str = "邮件已失效，请到登录界面输入账号密码重新发送！";
            return view('vadsuccess',['message'=>$str,'info'=>'到登录界面输入密码登录将提示验证']);
        }else{
            $userdata->status = 'normal';
            $userdata->save();
            return view('vadsuccess',['message'=>'验证成功！','info'=>'欢迎使用mynote']);
        }

    }else{
        echo "验证失败,所提交的数据有误,请确认是否正确打开邮件中的验证地址！";
    }


});
//找回密码
Route::any("/findpassword","LoginController@findpassword");
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::get("/time",function (){
    echo time();
});
Route::group(['middleware' => ['web','userstatus']], function () {
    Route::any('/home','NoteController@home');//笔记主页
    Route::any('/write','NoteController@writenote');//写笔记页面
    Route::any('/class','NoteController@manageclass');//分类页面
    Route::any('/searchbyclass/{class}','NoteController@searchbc');//通过分类查找
    Route::get('/getcontent/{id}','NoteController@getcontent');//获取笔记内容
    Route::get('/search','NoteController@search');//搜索笔记
    Route::any('/savemodify','NoteController@savemodify');//保存修改
    Route::get('/throwtogarbage/{id}','NoteController@throwtogarbage');//扔到回收站
    Route::any('/trash','NoteController@trash');//回收站
    Route::get('/deleteclass/{id}/{classname}','NoteController@deleteclass');//删除分类
    Route::get('/foreverdelnote/{id}','NoteController@foreverdelnote');//永久删除笔记
    Route::get('/loginout','NoteController@logout');//注销登录
    Route::any('/movetoclass','NoteController@movetoclass');//移动到其他分类
    Route::any('recovertoclass','NoteController@recovertoclass');//恢复到其他分类
    Route::any('/mdpassword','NoteController@modifypassword');//修改密码


});

Route::group(['middleware' => ['web','rootstatus']], function () {
    Route::any('/manage','NoteController@manage');//管理用户
    Route::any('/changeuserstatus','NoteController@changeuserstatus');//修改用户状态
    Route::get('/managerloginout','NoteController@managerlogout');//注销登录
    Route::get('/deleteuser','NoteController@deleteuser');//删除用户
    Route::any('/modifyadminpass','NoteController@modifyadminpass');//修改管理员密码
    Route::any('searchuser','NoteController@searchuser');//查找用户
});
Route::group(['middleware' => ['web']], function () {

    Route::any('/login','LoginController@login');
    Route::any('/reg','LoginController@reg');
    Route::get('/makecode','LoginController@makecode');
    Route::get('/about',function (){
       return view("about");
    });
    Route::any('/managelogin','LoginController@managelogin');
});
