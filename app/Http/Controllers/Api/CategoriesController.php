<?php

namespace App\Http\Controllers\Api;

use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Models\Category;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    use GeneralTrait;
    public function index(){
        $categories = Category::select('id', 'name_' . app()->getLocale() . ' as name')->get();
        //return response()->json($categories);
        return $this->returnData('Categories',$categories);

    }
    public function getCategoryById(Request $request){
        $category = Category::find($request->id);
        if(!$category){
             return $this->returnError('001','Notfound');
        }

        return $this->returnData('category',$category,"found..!");
    }
    public function changeStatus(Request $request){
        $category=Category::where('id',$request->id)->update(['status'=>$request->status]);
        if($category){
            return $this->returnSuccessMessage('Updated Successfully');
        }
        return $this->returnError('001','Notfound');
    }
    
}
