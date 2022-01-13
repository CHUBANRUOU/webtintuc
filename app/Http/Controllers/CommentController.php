<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    //Chubanruou
    public function getXoa($id, $idTinTuc){
        $comment = Comment::find($id);
        $comment->delete();

        return redirect('admin/tintuc/sua/'.$idTinTuc)->with('thongbao',' Xóa Comment Thành Công ');
    }
}
