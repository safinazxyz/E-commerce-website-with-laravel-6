<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Support\Facades\Input;
use Image;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $banner = new Banner;
            $banner->title = $data['title'];
            $banner->link = $data['link'];
            if(empty($data['status'])){
                $status='0';
            }else{
                $status='1';
            }
            if ($request->hasFile('image')) {
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    $extension = $image_tmp->getClientOriginalExtension();
                    $fileName = rand(111,99999).'.'.$extension;
                    $banner_path = 'images/frontend_images/banners/'.$fileName;
                    Image::make($image_tmp)->resize(1140,340)->save($banner_path);
                    $banner->image = $fileName;
                }

            }
            $banner->status = $status;
            $banner->save();
            return redirect()->back()->with('flash_message_success', 'Banner Images Has Been Added Successfully!');
        }
        return view('admin.banners.add_banner');
    }
    public function edit(Request $request, $id = null)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            if (empty($data['title'])) {
                $data['title'] = "";
            }
            if (empty($data['link'])) {
                $data['link'] = "";
            }
            if (empty($data['status'])) {
                $status = 0;
            } else {
                $status = 1;
            }
            if ($request->hasFile('image')) {
                $image_tmp = $request->file('image');
                if($image_tmp->isValid()){
                    $extension = $image_tmp->getClientOriginalExtension();
                    $fileName = rand(111,99999).'.'.$extension;
                    $banner_path = 'images/frontend_images/banners/'.$fileName;
                    Image::make($image_tmp)->resize(1140,340)->save($banner_path);
                }
            }
            else if (!empty($data['current_image'])) {
                $fileName = $data['current_image'];
            } else {
                $fileName = "";
            }

            Banner::where(['id' => $id])->update(['image' => $fileName,'title' => $data['title'], 'link' => $data['link'], 'status' => $status]);
            return redirect()->action('BannersController@view')->with('flash_message_success', 'Banner Has Been Updated Successfully!');
        }
        //Get Product Details
        $bannersDetails = Banner::where('id',$id)->first();
        return view('admin.banners.edit_banner')->with(compact('bannersDetails'));
    }
    public function delete($id = null)
    {
        //GET Banner Image
        $bannerImage = Banner::where(['id' => $id])->first();
        $image_path = 'images/frontend_images/banners/';


        //Delete Banner Image if not exists inFolder
        if (file_exists($image_path . $bannerImage->image)) {
            unlink($image_path . $bannerImage->image);
        }
        if(!empty($id)) {
            Banner::where(['id' => $id])->update(['image' => ""]);
            Banner::where(['id' => $id])->delete();
            return redirect()->back()->with('flash_message_success', 'Banner Deleted Successfully!');
        }

    }
    public function view()
    {
        $banners = Banner::orderby('id', 'DESC')->get();
        return view('admin.banners.view_banners')->with(compact('banners'));
    }
}
