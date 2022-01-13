<?php

namespace App\Http\Controllers;

use App\Models\LoaiTin;
use Illuminate\Http\Request;
use App\Models\TheLoai;
use Illuminate\Support\Facades\Redis;

class TheLoaiController extends Controller
{
    //Chubanruou
    public function getDanhSach(){

        $theloai = TheLoai::all();

        return view('admin.theloai.danhsach',['theloai'=>$theloai]);
    }

    public function getThem(){
        $theloai =  TheLoai::all();

        return view('admin.theloai.them',['theloai'=>$theloai]);
    }

    public function postThem(Request $request){
        
        $this->validate($request,
        [
            'Ten' => 'required|min:3|max:100|unique:TheLoai,Ten'
        ],
        [
            'Ten.required' =>'Bạn chưa nhâp tên thể loại',
            "Ten.unique"=> "Tên thể loại đã tồn tại",
            'Ten.min' => "Tên thể loại phải có độ da ì từ 3-100 ký tự",
            'Ten.max' => "Tên thể loại phải có độ daì từ 3-100 ký tự"
        ]);
        
        $theloai = new TheLoai;
        $theloai->Ten = $request->Ten;
        $theloai->TenKhongDau =changeTitle($request->Ten) ;
        
        $theloai->save();
        
        return redirect('admin/theloai/them')->with('thongbao','Thêm Thành Công');
    }

    public function getSua($id){
        $theloai = TheLoai::find($id);
        return view('admin.theloai.sua',['theloai'=>$theloai]);
    }

    public function postSua(Request $request, $id){
        $theloai = TheLoai::find($id);
        $this->validate($request,
        [
            'Ten'=> "required|unique:TheLoai,Ten|min:3|max:100"
            
        ],
        [
            'Ten.require'=>"Bạn chưa nhập tên thể loại",
            "Ten.unique"=> "Tên thể loại đã tồn tại",
            'Ten.min' => "Tên thể loại phải có độ da ì từ 3-100 ký tự",
            'Ten.max' => "Tên thể loại phải có độ da ì từ 3-100 ký tự"
        ]);

        $theloai->Ten = $request->Ten;
        $theloai->TenKhongDau = changeTitle($request->Ten);
        $theloai->save();

        return  redirect('admin/theloai/sua/'.$id)->with('thongbao','Sửa thành công');
    }

    public function getXoa($id){
        $theloai = TheLoai::find($id);
        $theloai->delete();

        return redirect('admin/theloai/danhsach')->with('thongbao','Bạn đã xóa thành công');

    }
}
