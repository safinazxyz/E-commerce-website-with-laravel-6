<?php

namespace App\Http\Controllers;

use App\Category;
use App\CmsPage;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $cmspage = new CmsPage;
            $cmspage->title=$data['title'];
            $cmspage->description=$data['description'];
            $cmspage->url=$data['url'];
            if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }
            $cmspage->status=$status;
            $cmspage->save();
            return redirect()->back()->with('flash_message_success','Cms Page has been added successfully!');
        }
        return view('admin.pages.add_cms_page');
    }

    public function edit(Request $request,$id){
        if($request->isMethod('post')){
            $data = $request->all();
            if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }
            CmsPage::where('id',$id)->update(['title'=>$data['title'],
                'url'=>$data['url'],'description'=>$data['description'],
                'status'=>$status]);
            return redirect()->back()->with('flash_message_success','CMS Page has been updated successfully!');
        }
        $cmsPages = CmsPage::where('id',$id)->first();
        return view('admin.pages.edit_cms_page')->with(compact('cmsPages'));
    }

    public function view(){
        $cmsPages = CmsPage::get();
        return view('admin.pages.view_cms_pages')->with(compact('cmsPages'));
    }

    public function delete($id){
        CmsPage::where('id',$id)->delete();
        return redirect('/admin/view-cms-pages')->with('flash_message_success','Cms Page has been deleted successfully!');
    }

    public function cmsPage($url){
        //Redirect to 404 if CMS Page is disabled or does not exists
        $cmsPageCount = CmsPage::where(['url'=>$url,'status'=>1])->count();
        if($cmsPageCount>0){
            //Get CMS Details
            $cmsPageDetails = CmsPage::where('url', $url)->first();
        }else{
            abort(404);
        }

        // Get all Categories with subCategories
        $categories_menu = "";
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        foreach ($categories as $cat){
            $categories_menu .="
            <div class='panel-heading'>
                <h4 class='panel-title'>
                    <a data-toggle='collapse' data-parent='#accordian' href='#".$cat->id."'>
                        <span class='badge pull-right'><i class='fa fa-plus'></i></span>
                        ".$cat->name."
                     </a>
                 </h4>
            </div>
            <div id='".$cat->id."' class='panel-collapse collapse'>
                 <div class='panel-body'>
                 <ul>";
            $sub_categories = Category::where(['parent_id' => $cat->id])->get();
            foreach($sub_categories as $sub_cat){
                $categories_menu .= "<li><a href='#'>".$sub_cat->name."</a></li>";
            }
            $categories_menu .="</ul>
                </div>
            </div>
            ";
        }
        return view('pages.cms_page')->with(compact('cmsPageDetails','categories_menu','categories'));
    }

}
