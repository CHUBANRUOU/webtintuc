<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TheLoai;
use App\Models\Slide;
use App\Models\LoaiTin;
use App\Models\TinTuc;
use Illuminate\Pagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Symfony\Contracts\Service\Attribute\Required;
use App\Models\User;

use function GuzzleHttp\Promise\all;

class PagesController extends Controller
{
    //Chubanrou
    function __construct()
    {
        $theloai = TheLoai::all();
        $slide = Slide::all();
        view()->share('theloai',$theloai);
        view()->share('slide',$slide);

        //lay du lieu nguoi dung dang nhap
        if(Auth::check()){
            $user = Auth::user();
            View::share('nguoidung',$user);
        }
    }

    function trangchu(){
        return view('pages.trangchu');
    }

    function lienhe(){
        return view('pages.lienhe');
    }

    function loaitin($id){
        $loaitin = LoaiTin::find($id);
        $tintuc = TinTuc::where('idLoaiTin',$id)->paginate(5);
        return view('pages.loaitin',['loaitin'=>$loaitin,'tintuc'=>$tintuc]);
    }

    function tintuc($id){
        $tintuc = TinTuc::find($id);
        $tinnoibat = TinTuc::where('NoiBat',1)->take(4)->get();
        $tinlienquan = TinTuc::where('idLoaiTin',$tintuc->idLoaiTin)->take(4)->get();
        return view('pages.tintuc',['tintuc'=>$tintuc, 'tinnoibat'=>$tinnoibat, 'tinlienquan'=>$tinlienquan]);
    }

    function getDangnhap(){
        return view('pages.dangnhap');
    }

    function postDangnhap(Request $request){
        $this->validate($request,
        [
            'email'=>'required',
            'password'=> 'required|min:6|max:32'
        ],[
            'email.required'=>'Bạn chưa nhập email',
            'password.required'=>'Bạn chưa nhập password',
            'password.min'=>'Password phải lớn hơn 6 ký tự',
            'password.max'=>'Password phải nhỏ hơn 32 ký tự',
        ]);

        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password]))
        {
            
            return redirect('trangchu');
        }
        else{
            return redirect('dangnhap')->with('thongbao','Đăng nhập không thành công');
        }
    }

    function getDangxuat(){
        Auth::logout();
        return redirect('trangchu');
    }

    function getNguoiDung(){
        return view('pages.nguoidung');
    }

    function postNguoidung(Request  $request){
        $this ->validate($request,
        [
          'name'=>'required|min:3'
          
        ],
        [
            'name.required'=>'Bạn chưa nhập tên người dùng',
            'name.min'=>'Tên người dùng ít nhất phải có 3 ký tự',
        ]);
  
        $user =  Auth::user();
        $user->name= $request->name;

        if($request->changePassword =="on"){
            $user->password= bcrypt($request->password);
            $this ->validate($request,
      [
        
        'password'=>'required|min:3|max:32',
        'passwordAgain'=>'required|same:password'
      ],
      [
          'password.required'=>'Bạn chưa nhập mật khẩu ',
          'password.min'=>'Mật khẩu  phải có ít nhất 3 ký tự',
          'password.max'=>'Mật khẩu tối đa là 32 ký tự',
          'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu ',
          'passwordAgain.same'=>'Mật khẩu nhập lại chưa khớp'
      ]);
      $user->password= bcrypt($request->password);

        }
  
        $user->save();
        return  redirect('nguoidung')->with('thongbao','Bạn đã sửa thành công');
    }

    function getDangky(){
        return view('pages.dangky');

    }
    function postDangKy(Request $request){
        $this ->validate($request,
      [
        'name'=>'required|min:3',
        'email' => 'required|email|unique:App\Models\User,email',
        'password'=>'required|min:3|max:32',
        'passwordAgain'=>'required|same:password'
      ],
      [
          'name.required'=>'Bạn chưa nhập tên người dùng',
          'name.min'=>'Tên người dùng ít nhất phải có 3 ký tự',
          'email.required'=>'Bạn chưa nhập email',
          'email.unique'=>'Email bạn nhập đã tồn tại',
          'password.required'=>'Bạn chưa nhập mật khẩu ',
          'password.min'=>'Mật khẩu  phải có ít nhất 3 ký tự',
          'password.max'=>'Mật khẩu tối đa là 32 ký tự',
          'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu ',
          'passwordAgain.same'=>'Mật khẩu nhập lại chưa khớp'
      ]);

      $user = new User;
      $user->name= $request->name;
      $user->email= $request->email;
      $user->password= bcrypt($request->password);
      $user->quyen =0;

      $user->save();
      return redirect('dangky')->with('thongbao','Đăng ký Thành Công, Xin Mời Đăng Nhập');
    }

    function timkiem(Request $request){
        $tukhoa = $request->tukhoa;
        $tintuc = TinTuc::where('TieuDe','like',"%$tukhoa%")->orWhere('TomTat','like',"%$tukhoa%")->take(20)->paginate(20);
        return view('pages.timkiem',['tintuc'=>$tintuc,'tukhoa'=>$tukhoa]);
    }
}
