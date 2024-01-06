<?php

namespace App\Http\Controllers;


use App\Http\Model\Manager;
use App\Http\Model\Note;
use App\Http\Model\Notes;
use App\Http\Model\Category;

use App\Http\Model\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
class NoteController extends Controller
{
    //首页
    public function home(Request $request){
        $class = Category::where("owner",session("user"))->get();
        $notes = Notes::where(["owner"=>session("user"),"intrash"=>"0"])->orderBy('date', 'desc')->get();
        $commoncounts= Notes::where(["class"=>"common","owner"=>session('user'),"intrash"=>"0"])->count();
        return view("home",["noteclass"=>$class,"notes"=>$notes,"commoncounts"=>$commoncounts]);
    }
//写笔记
    public function writenote(Request $request){
        if ($request->method() === 'POST'){
            if ($request->input('content')==""||$request->input('title')==""){
                return redirect("/write")->with("error","内容或这标题不能为空!");
            }
            $class = $request->input('class');
            $title = htmlspecialchars($request->input('title'),ENT_QUOTES);
            $content = htmlspecialchars($request->input('content'),ENT_QUOTES);
            $noteinsert = new Notes();
            $noteinsert->title = $title;
            $noteinsert->content = $content;
            $noteinsert->class = $class;
            $noteinsert->date = time();
            $noteinsert->owner = session('user');
            $insertres = $noteinsert->save();
            //更新统计数目
            if ($class=="common"){
                if ($insertres){
                    echo "<script type='text/javascript'>alert('保存成功');</script>";
                    return  redirect("/home");
                }else{
                    echo "<script type='text/javascript'>alert('保存失败,请重试');</script>";
                }
            }else{
                $counts = Notes::where(["class"=>$class,"owner"=>session('user')])->count();
                $category = Category::where(["name"=>$class,"owner"=>session('user')])->update(["counts"=>$counts]);
                if ($insertres&&$category){
                    return  redirect("/home");
                }else{
                    echo "<script type='text/javascript'>alert('保存失败,请重试');</script>";
                }
            }

        }else{
            $class = Category::where("owner",session("user"))->get();
            return view("write",['class'=>$class]);
        }

    }
//分类管理
    public function manageclass(Request $request){
        if ($request->method() === 'POST'){
            if ($request->input('classname')==""||$request->input('classname')=="common"){
                if ($request->input('classname')==""){
                    return redirect("/class")->with("info","分类不能为空");
                }else{
                    return redirect("/class")->with("info","系统已存在分类common,请不要重复创建");
                }

            }else{
                $newclass = $request->input("classname");
                $counts = Category::where(["name"=>$newclass,"owner"=>session("user")])->count();
                if ($counts==1){
                    return redirect("/class")->with("info","已存在相同名字的分类");
                }else{
                    $classinsert  = new Category();
                    $classinsert->name = $newclass;
                    $classinsert->owner = session("user");
                    if ($classinsert->save()){
                        return redirect("/class")->with("info","添加成功");
                    }else{
                        return redirect("/class")->with("info","添加失败，请重试");
                    }
                }
            }

        }else{
            $class = Category::where("owner",session("user"))->get();
            return view("class",["class"=>$class,"ordernumber"=>1]);
        }

    }
    //删除分类
    public function deleteclass($id,$classname){
        $ifnull = Notes::where(["class"=>$classname,"owner"=>session('user'),"intrash"=>"0"])->count();
        if ($ifnull==0){
            $deleteclass = Category::where(["id"=>$id,"owner"=>session("user")])->delete();
            if ($deleteclass){
                return redirect("/class")->with("info","删除成功");
            }else{
                return redirect("/class")->with("info","删除分类时出现错误");
            }
        }else{
            $updatenote = Notes::where(["class"=>$classname,"owner"=>session("user")])->update(["intrash"=>"1"]);
            if ($updatenote){
                $deleteclass = Category::where(["id"=>$id,"owner"=>session("user")])->delete();
                if ($deleteclass){
                    return redirect("/class")->with("info","删除成功");
                }else{
                    return redirect("/class")->with("info","删除分类时出现错误");
                }
            }else{
                return redirect("/class")->with("info","删除分类笔记时出现错误");
            }
        }

    }
    //回收站
    public function trash(Request $request){
        $noteclass = Category::where("owner",session('user'))->get();
        if ($request->method() === 'POST'){
            $keyword = $request->input('name');
            $notesdeleted = Notes::where("title","like","%".$keyword."%")->where(["owner"=>session('user'),"intrash"=>"1"])->orderBy('date', 'desc')->get();
        }else{
            $notesdeleted = Notes::where(["intrash"=>"1","owner"=>session("user")])->get();
        }

        return view("trash",["notes"=>$notesdeleted,"noteclass"=>$noteclass]);
    }
//通过分类搜索
    public function searchbc($cla){
        $class = Category::where("owner",session("user"))->get();;
        $notes = Notes::where(["class"=>$cla,"owner"=>session('user'),"intrash"=>"0"])->orderBy('date', 'desc')->get();
        $commoncounts= Notes::where(["class"=>"common","owner"=>session('user'),"intrash"=>"0"])->count();
        return view("home",["noteclass"=>$class,"notes"=>$notes,"commoncounts"=>$commoncounts,"formclass"=>$cla]);
    }
    //通过id获取笔记内容
    public function getcontent($id){
        $notes = Notes::where(["id"=>$id,"owner"=>session('user')])->orderBy('date', 'desc')->first();
        $content = htmlspecialchars_decode($notes->content);
        echo $content;
    }

    //模糊搜索
    public function search(Request $request){
        $keyword = $request->input('keywords');
        $class = Category::where("owner",session("user"))->get();
        $notes = Notes::where("title","like","%".$keyword."%")->where(["owner"=>session('user'),"intrash"=>"0"])->orderBy('date', 'desc')->get();
        $commoncounts= Notes::where(["class"=>"common","owner"=>session('user'),"intrash"=>"0"])->count();
        return view("home",["noteclass"=>$class,"notes"=>$notes,"keywords"=>$keyword,"commoncounts"=>$commoncounts]);
    }

    //保存修改
    public function savemodify(Request $request){
        if ($request->method() === 'POST'){
            $id = $request->input('id');
            $content = htmlspecialchars($request->input('content'));
            $savenote =Notes::where("id",$id)->update(["content"=>$content]);
            if ($savenote){
                echo "保存成功";
            }else{
                 echo "保存失败";
            }

        }else{
            echo "未收到提交数据";
        }
    }

    //删除笔记
    public function throwtogarbage($id){
        $noteid = $id;
        $updatestatus = Notes::where(["id"=>$noteid,"owner"=>session('user')])->update(["intrash"=>"1"]);
        if ($updatestatus){
            $toclass = Notes::where(['id'=>$noteid,"owner"=>session("user")])->first();
            $class = $toclass->class;
            $counts = Notes::where(["class"=>$class,"owner"=>session("user"),"intrash"=>"0"])->count();
            if($class=="common"){
                echo "删除成功！";
            }else{
                $category = Category::where(["name"=>$class,"owner"=>session('user')])->update(["counts"=>$counts]);
                if ($category){
                    echo "删除成功";
                }else{
                    echo "删除失败";
                }
            }


        }else{
            echo "删除失败";
        }
    }


    //永久删除笔记
    public function foreverdelnote($id){
        $noteid = $id;
        $delres = Notes::where(["id"=>$noteid,"owner"=>session("user")])->delete();
        if ($delres){
            return redirect("/trash")->with("info","删除成功");
        }else{
            return redirect("/trash")->with("info","删除失败");
        }
    }
    //移动笔记
    public function movetoclass(Request $request){
        if ($request->method() === 'POST'){
            if ($request->input('id')!=""&&$request->input('oldclass')!==""&&$request->input('toclass')!=""){
                if ($request->input('toclass')==$request->input('oldclass')){
                    echo "对不起,您移动的分类相同";
                }else{
                    if ($request->input('toclass')=="common"||$request->input('oldclass')=="common"){
                        if ($request->input('toclass')=="common"){
                            $upclass = Notes::where(["id"=>$request->input('id'),"owner"=>session('user')])->update(["class"=>$request->input('toclass')]);
                            $counts1 = Notes::where(["class"=>$request->input('oldclass'),"owner"=>session('user'),"intrash"=>"0"])->count();
                            $category1 = Category::where(["name"=>$request->input('oldclass'),"owner"=>session('user')])->update(["counts"=>$counts1]);
                            if ($upclass&&$category1){
                                echo "移动成功";
                            }else{
                                echo "移动失败";
                            }

                        }else{
                            $upclass = Notes::where(["id"=>$request->input('id'),"owner"=>session('user')])->update(["class"=>$request->input('toclass')]);
                            $counts = Notes::where(["class"=>$request->input('toclass'),"owner"=>session('user'),"intrash"=>"0"])->count();
                            $category = Category::where(["name"=>$request->input('toclass'),"owner"=>session('user')])->update(["counts"=>$counts]);
                            if ($upclass&&$category){
                                echo "移动成功";
                            }else{
                                echo "移动失败";
                            }
                        }

                    }else{
                        $upclass = Notes::where(["id"=>$request->input('id'),"owner"=>session('user')])->update(["class"=>$request->input('toclass')]);
                        if ($upclass){
                            $counts = Notes::where(["class"=>$request->input('toclass'),"owner"=>session('user'),"intrash"=>"0"])->count();
                            $category = Category::where(["name"=>$request->input('toclass'),"owner"=>session('user')])->update(["counts"=>$counts]);
                            $counts1 = Notes::where(["class"=>$request->input('oldclass'),"owner"=>session('user'),"intrash"=>"0"])->count();
                            $category1 = Category::where(["name"=>$request->input('oldclass'),"owner"=>session('user')])->update(["counts"=>$counts1]);
                            if ($category&&$category1){
                                echo "移动成功";
                            }else{
                                echo "移动失败";
                            }
                        }else{
                            echo "移动失败";
                        }
                    }

                }

            }else{
                echo "请求数据不完整,移动失败";
            }
        }else{
            echo "请求数据为空，移动失败";
        }
    }

    //恢复笔记
    public function recovertoclass(Request $request){

        if ($request->method() === 'POST'){
            $id = $request->input('id');
            $toclass = $request->input('toclass');
            if ($id==""||$toclass==""){
                echo "提交的数据不完整,恢复失败";
            }else{
              if ($toclass=="common"){
                  $recover = Notes::where(["id"=>$id,"owner"=>session('user')])->update(["intrash"=>"0","class"=>$toclass]);
                  if ($recover){
                      echo "恢复成功";
                  }else{
                      echo "恢复失败";
                  }
              }else{
                  $recover = Notes::where(["id"=>$id,"owner"=>session('user')])->update(["intrash"=>"0","class"=>$toclass]);
                  $counts = Notes::where(["class"=>$toclass,"owner"=>session('user'),"intrash"=>"0"])->count();
                  $category = Category::where(["name"=>$toclass,"owner"=>session('user')])->update(["counts"=>$counts]);
                  if ($recover&&$category){
                      echo "恢复成功";
                  }else{
                      echo "恢复失败";
                  }
              }
            }
        }else{
            echo "提交的数据为空,恢复失败";
        }
    }

    //注销退出
    public function logout(){
        session(["user"=>null]);
        return redirect("/login");
    }

    //修改密码
    public function modifypassword(Request $request){
        if ($request->method() === 'POST'){
            $oldpassword = $request->input('oldpassword');
            $newpassword = $request->input('password');
            $cfpassword = $request->input('cfpassword');
            if ($cfpassword!=$newpassword){
                return redirect("/mdpassword")->with("info","您输入两次的密码不相同");
            }else{
                $users = Users::where(["email"=>session('user')])->first();
                $dbpassword = Crypt::decrypt($users->password);
                if ($dbpassword==$oldpassword){
                    $updatepassword = Users::where("email",session('user'))->update(["password"=>Crypt::encrypt($newpassword)]);
                    if ($updatepassword){
                        return redirect("/mdpassword")->with("info","修改成功,请下次用新密码登录");
                    }else{
                        return redirect("/mdpassword")->with("info","修改失败,请重试");
                    }
                }else{
                    return redirect("/mdpassword")->with("info","您输入的原密码错误");
                }
            }


        }else{
            return view("/modifypassword");
        }
    }
    //管理用户
    public function manage(Request $request){

            $userdata = Users::where("id","<",1000000)->orderBy('regtime', 'desc')->paginate(9);
            $users = Users::all();
            foreach ($users as $user){
                $arr[$user->email] = Notes::where(["owner"=>$user->email])->count();
            }
            return view("manageusers",["userdata"=>$userdata,"ordernumber"=>"1","usernote"=>$arr]);



    }
    //搜索用户
    public function searchuser(Request $request)
    {
        if ($request->method() === 'POST') {
            $keyword = $request->input('useremail');
            $userdata = Users::where("email", "like", "%".$keyword."%")->orderBy('regtime', 'desc')->get();
            $users = Users::all();
            foreach ($users as $user) {
                $arr[$user->email] = Notes::where(["owner" => $user->email])->count();
            }
            return view("searchuser", ["userdata" => $userdata, "ordernumber" => "1", "usernote" => $arr,"keyword"=>$keyword]);
        }else{
            return redirect('/manage');
        }
    }
    //修改用户状态
    public function changeuserstatus(Request $request){
        if ($request->method() === 'POST'){
            if ($request->input('id')!=""&&$request->input('status')!=""){
                $userid = $request->input('id');
                $userstatus = $request->input('status');
                $change = Users::where(["id"=>$userid])->update(["status"=>$userstatus]);
                if ($change){
                    echo "修改成功";
                }else{
                    echo "修改失败，请重试";
                }
            }else{
                echo "提交的数据不完整！";
            }
        }
    }

    //删除用户
    public function deleteuser(Request $request){
        if ($request->method() === 'POST'){
            $id = $request->input(['id']);
            $email = $request->input('email');
            $userclassnum = Category::where(["owner"=>$email])->count();
            $usernotenum = Notes::where(["owner"=>$email])->count();
            if ($userclassnum<1&&$usernotenum<1){
                $deleteuser = Users::where(["id"=>$id])->delete();
                if ($deleteuser){
                    echo "删除成功";
                }else{
                    echo "删除失败";
                }
            }else{
                $deleteuser = Users::where(["id"=>$id])->delete();
                if ($deleteuser){
                    $deleteusernoteres = "";
                    $deleteuserclassres = "";
                    if ($userclassnum>=1){
                        $deleteuserclass = Category::where(["owner"=>$email])->delete();
                        if ($deleteuserclass){
                            $deleteuserclassres = true;
                        }else{
                            $deleteuserclassres = false;
                        }
                    }
                    if ($usernotenum>=1){
                        $deleteusernote = Notes::where(["owner"=>$email])->delete();
                        if ($deleteusernote){
                            $deleteusernoteres = true;
                        }else{
                            $deleteusernoteres = false;
                        }
                    }
                    if ($deleteuserclassres||$deleteusernoteres){
                        echo "删除成功";
                }else{
                        echo "删除失败";
                    }

                }else{
                    echo "删除失败";
                }


            }

//            echo $request->input('id').$request->input('email');

        }else{
            echo "提交的数据不完整";
        }
    }

    //管理员注销
    public function managerlogout(){
        session(["root"=>null]);
        return redirect("/managelogin");
    }
    //修改管理员密码
    public function modifyadminpass(Request $request){
        if ($request->method() === 'POST'){
            $oldpassword = $request->input('oldpassword');
            $newpassword = $request->input('password');
            $cfpassword = $request->input('cfpassword');
            if ($cfpassword!=$newpassword){
                return redirect("/modifyadminpass")->with("info","您输入两次的密码不相同");
            }else{
                $admin = Manager::where(["name"=>"root"])->first();
                $dbpassword = Crypt::decrypt($admin->password);
                if ($dbpassword==$oldpassword){
                    $updatepassword = Manager::where("name","root")->update(["password"=>Crypt::encrypt($newpassword)]);
                    if ($updatepassword){
                        return redirect("/modifyadminpass")->with("info","修改成功,请下次用新密码登录");
                    }else{
                        return redirect("/modifyadminpass")->with("info","修改失败,请重试");
                    }
                }else{
                    return redirect("/modifyadminpass")->with("info","您输入的原密码错误");
                }
            }
        }else{
            return view("modifyadminpass");
        }
    }

}

