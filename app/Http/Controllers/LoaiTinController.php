<?php

namespace App\Http\Controllers;

use App\Models\LoaiTin;
use Illuminate\Http\Request;
use App\Models\TheLoai;
use Illuminate\Support\Facades\Redis;

class LoaiTinController extends Controller
{
    //Chubanruou
    public function getDanhSach(){

        $loaitin = LoaiTin::all();

        return view('admin.loaitin.danhsach',['loaitin'=>$loaitin]);
    }

    public function getThem(){
        $theloai = TheLoai::all();

        return view('admin.loaitin.them',['theloai'=>$theloai]);
    }

    public function postThem(Request $request){
        $this->validate($request,
        [
            'Ten'=>'required|unique:LoaiTin,Ten|min:1|max:100',
            'TheLoai'=>'required'
        ],
        [
            'Ten.required'=> 'Bạn chưa nhập tên loại tin',
            'Ten.uninque' =>'Tên loại tin đã tồn tại',
            'Ten.min'=>'Độ dài tên phải từ 1 tới 100 ký tự',
            'Ten.max'=>'Độ dài tên phải từ 1 tới 100 ký tự',
            'TheLoai.required'=>'Bạn chưa chọn thể loại'
        ]);

        $loaitin = new LoaiTin;
        $loaitin->Ten = $request->Ten;
        $loaitin->TenKhongDau =changeTitle($request->Ten);
        $loaitin->idTheLoai = $request->TheLoai;
        $loaitin->save();
        return redirect('admin/loaitin/them')->with('thongbao','Thêm Thành Công');
        
    }

    public function getSua($id){
        $theloai = TheLoai::all();
        $loaitin = LoaiTin::find($id);
        return view('admin.loaitin.sua',['loaitin'=>$loaitin,'theloai'=>$theloai]);
    }

    public function postSua(Request $request, $id){
        $this->validate($request,
        [
            'Ten'=>'required|unique:LoaiTin,Ten|min:1|max:100',
            'TheLoai'=>'required'
        ],
        [
            'Ten.required'=> 'Bạn chưa nhập tên loại tin',
            'Ten.uninque' =>'Tên loại tin đã tồn tại',
            'Ten.min'=>'Độ dài tên phải từ 1 tới 100 ký tự',
            'Ten.max'=>'Độ dài tên phải từ 1 tới 100 ký tự',
            'TheLoai.required'=>'Bạn chưa chọn thể loại'
        ]);

        $loaitin =LoaiTin::find($id);
        $loaitin->Ten = $request->Ten;
        $loaitin->TenKhongDau =changeTitle($request->Ten);
        $loaitin->idTheLoai = $request->TheLoai;
        $loaitin->save(); 

        return redirect('admin/loaitin/sua/'.$id)->with('thongbao','Bạn Đã Sửa Thành Công');
    }

    public function getXoa($id){
       $loaitin = LoaiTin::find($id);
       $loaitin->delete();
       return redirect('admin/loaitin/danhsach')->with('thongbao','Xóa Thành Công');
    }
}
