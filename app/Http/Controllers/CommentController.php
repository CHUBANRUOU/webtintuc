<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\TinTuc;

class CommentController extends Controller
{
    //Chubanruou
    public function getXoa($id, $idTinTuc){
        $comment = Comment::find($id);
        $comment->delete();

        return redirect('admin/tintuc/sua/'.$idTinTuc)->with('thongbao',' Xóa Comment Thành Công ');
    }

    public function postComment($id, Request $request){
        $idTinTuc = $id;
        $tintuc= TinTuc::find($id);
        $comment = new Comment;
        $comment->idTinTuc = $idTinTuc;
        $comment->idUser = Auth::user()->id;
        $comment->NoiDung = $request->noidung;
        $comment->save();
        return redirect("tintuc/$id/".$tintuc->TieuDeKhongDau.".html")->with('thongbao','Đăng bình luận thành công');
        
    }
}
