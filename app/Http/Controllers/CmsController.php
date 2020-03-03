<?php

namespace App\Http\Controllers;

use App\Category;
use App\CmsPage;
use App\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;

class CmsController extends Controller
{
    public function create(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            if(empty($data['meta_title'])){
                $meta_title = "";
            }
            else{
                $meta_title = $data['meta_title'];
            }
            if(empty($data['meta_description'])){
                $meta_description = "";
            }
            else{
                $meta_description = $data['meta_description'];
            }
            if(empty($data['meta_keywords'])){
                $meta_keywords = "";
            }
            else{
                $meta_keywords = $data['meta_keywords'];
            }
            $cmspage = new CmsPage;
            $cmspage->title=$data['title'];
            $cmspage->description=$data['description'];
            $cmspage->meta_title=$meta_title;
            $cmspage->meta_description=$meta_description;
            $cmspage->meta_keywords=$meta_keywords;
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
            if(empty($data['meta_title'])){
                $meta_title = "";
            }
            else{
                $meta_title = $data['meta_title'];
            }
            if(empty($data['meta_description'])){
                $meta_description = "";
            }
            else{
                $meta_description = $data['meta_description'];
            }
            if(empty($data['meta_keywords'])){
                $meta_keywords = "";
            }
            else{
                $meta_keywords = $data['meta_keywords'];
            }
            CmsPage::where('id',$id)->update(['title'=>$data['title'],
                'url'=>$data['url'],'description'=>$data['description'],
                'status'=>$status,'meta_title'=>$meta_title,'meta_description'=>$meta_description,
                'meta_keywords' => $meta_keywords]);
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
            $meta_title = $cmsPageDetails->meta_title;
            $meta_description = $cmsPageDetails->meta_description;
            $meta_keywords = $cmsPageDetails->meta_keywords;
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
        return view('pages.cms_page')->with(compact('cmsPageDetails','categories_menu','categories',
        'meta_title','meta_description','meta_keywords'));
    }

    public function contact(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();

            //Validation for Contact-Us Form
            $validator = Validator::make($request->all(), [
               'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
               'email' => 'required|email',
               'subject' =>'required',
            ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            //Send Contact Email
            $email = "sultankaman93@gmail.com";
            $messageData = [
                'name'=>$data['name'],
                'email'=>$data['email'],
                'subject'=>$data['subject'],
                'comment'=>$data['message']
                ];
            Mail::send('emails.enquiry',$messageData,function($message)use($email){
                $message->to($email)->subject('Enquiry from E-com Website');
            });
            return redirect()->back()->with('flash_message_success','Thanks for your enquiry. We will get back to you soon!');
        }

        // Get all Categories with subCategorie
        $categories_menu = "";
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        foreach ($categories as $cat) {
            $categories_menu .= "
            <div class='panel-heading'>
                <h4 class='panel-title'>
                    <a data-toggle='collapse' data-parent='#accordian' href='#" . $cat->id . "'>
                        <span class='badge pull-right'><i class='fa fa-plus'></i></span>
                        " . $cat->name . "
                     </a>
                 </h4>
            </div>
            <div id='" . $cat->id . "' class='panel-collapse collapse'>
                 <div class='panel-body'>
                 <ul>";
            $sub_categories = Category::where(['parent_id' => $cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                $categories_menu .= "<li><a href='#'>" . $sub_cat->name . "</a></li>";
            }
            $categories_menu .= "</ul>
                </div>
            </div>
            ";
        }
        //Meta  Tags
        $meta_title= "Contact Us - E-shop Sample Website";
        $meta_description = "Contact us for any queries related to our products";
        $meta_keywords ="contact us, queries";
        return view('pages.contact')->with(compact('categories_menu','categories',
            'meta_title','meta_description','meta_keywords'));
    }

    public function addPost(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $enquiry = new Enquiry;
            $enquiry->name = $data['name'];
            $enquiry->email = $data['email'];
            $enquiry->subject = $data['subject'];
            $enquiry->message = $data['message'];
            $enquiry->save();
            echo "Thanks for contacting us. We will get back to you soon.";die;
        }

        // Get all Categories with subCategorie
        $categories_menu = "";
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        foreach ($categories as $cat) {
            $categories_menu .= "
            <div class='panel-heading'>
                <h4 class='panel-title'>
                    <a data-toggle='collapse' data-parent='#accordian' href='#" . $cat->id . "'>
                        <span class='badge pull-right'><i class='fa fa-plus'></i></span>
                        " . $cat->name . "
                     </a>
                 </h4>
            </div>
            <div id='" . $cat->id . "' class='panel-collapse collapse'>
                 <div class='panel-body'>
                 <ul>";
            $sub_categories = Category::where(['parent_id' => $cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                $categories_menu .= "<li><a href='#'>" . $sub_cat->name . "</a></li>";
            }
            $categories_menu .= "</ul>
                </div>
            </div>
            ";
        }
        return view('pages.post')->with(compact('categories_menu','categories'));
    }

    public function getEnquiries(){
        $enquiries = Enquiry::orderBy('id','Desc')->get();
        return $enquiries;
    }
    public function viewEnquiries(){
        $enquiries = Enquiry::orderBy('id','Desc')->get();
        return view('admin.enquiries.view_enquiries')->with(compact('enquiries'));
    }
}
