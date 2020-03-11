<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Hash;
use Session;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        if (Session::get('adminDetails')['categories_full_access'] == 0) {
            return redirect('/admin/dashboard')->with('flash_message_error', 'You have no access for this module');
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            if (empty($data['status'])) {
                $status = 0;
            } else {
                $status = 1;
            }
            if (empty($data['meta_title'])) {
                $meta_title = "";
            } else {
                $meta_title = $data['meta_title'];
            }
            if (empty($data['meta_description'])) {
                $meta_description = "";
            } else {
                $meta_description = $data['meta_description'];
            }
            if (empty($data['meta_keywords'])) {
                $meta_keywords = "";
            } else {
                $meta_keywords = $data['meta_keywords'];
            }
            $category = new Category;
            $category->name = $data['category_name'];
            $category->parent_id = $data['parent_id'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->meta_title = $meta_title;
            $category->meta_description = $meta_description;
            $category->meta_keywords = $meta_keywords;
            $category->status = $status;
            $category->save();
            return redirect('/admin/view-categories')->with('flash_message_success', 'New Category Added!');
        }
        $levels = Category::where(['parent_id' => 0])->get();
        return view('admin.categories.add_category')->with(compact('levels'));
    }

    public function edit(Request $request, $id = null)
    {
        if (Session::get('adminDetails')['categories_edit_access'] == 0) {
            return redirect('/admin/dashboard')->with('flash_message_error', 'You have no access for this module');
        }
        if ($request->isMethod('post')) {
            $data = $request->all();
            if (empty($data['status'])) {
                $status = 0;
            } else {
                $status = 1;
            }
            if (empty($data['meta_title'])) {
                $meta_title = "";
            } else {
                $meta_title = $data['meta_title'];
            }
            if (empty($data['meta_description'])) {
                $meta_description = "";
            } else {
                $meta_description = $data['meta_description'];
            }
            if (empty($data['meta_keywords'])) {
                $meta_keywords = "";
            } else {
                $meta_keywords = $data['meta_keywords'];
            }
            Category::where(['id' => $id])->update(['name' => $data['category_name'], 'description' => $data['description'], 'url' => $data['url'], 'status' => $status,
                'meta_title' => $meta_title, 'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords]);
            return redirect('/admin/view-categories')->with('flash_message_success', 'Category Edited Succefully!');
        }
        $categoryDetails = Category::where(['id' => $id])->first();
        $levels = Category::where(['parent_id' => 0])->get();
        return view('admin.categories.edit_category')->with(compact('categoryDetails', 'levels'));
    }

    public function delete($id = null)
    {
        if (Session::get('adminDetails')['categories_full_access'] == 0) {
            return redirect('/admin/dashboard')->with('flash_message_error', 'You have no access for this module');
        }
        if (!empty($id)) {
            Category::where(['id' => $id])->delete();
            return redirect()->back()->with('flash_message_success', 'Category Deleted Successfully!');
        }

    }

    public function view()
    {
        if (Session::get('adminDetails')['categories_view_access'] == 0) {
            return redirect('/admin/dashboard')->with('flash_message_error', 'You have no access for this module');
        }
        $categories = Category::get();
        return view('admin.categories.view_categories')->with(compact('categories'));
    }

}
