<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class MainCategoriesController extends Controller
{

    public function index()
    {
        $default_lang = get_default_lang();
        $categories = MainCategory::where('translation_lang', $default_lang)->selection()->get();
        return view('admin.maincategories.index', compact('categories'));

    }

    public function create()
    {
        return view('admin.maincategories.create');

    }

    public function save(MainCategoryRequest $request)
    {
        try {


            $main_categories = collect($request->category);
            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] == get_default_lang();
            });
            $default_category = array_values($filter->all()) [0];
            $filepath = "";
            if ($request->has('photo')) {
                $filepath = uploadImage('maincategories', $request->photo);
            }
            DB::beginTransaction();

            $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $filepath,

            ]);

            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != get_default_lang();
            });
            if (isset($categories) && $categories->count() > 0) {
                $categories_arr = [];
                foreach ($categories as $categorie) {
                    $categories_arr[] = [
                        'translation_lang' => $categorie['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $categorie['name'],
                        'slug' => $categorie['name'],
                        'photo' => $filepath,
                    ];
                }

                MainCategory::insert($categories_arr);
            }
            DB::commit();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحقظ بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }


    }

    public function edit($id)
    {
        $maincategory = MainCategory::with('categories')->find($id);
        if (!$maincategory)
            return redirect()->route('admin.maincategories')->with(['error' => 'عفوا القسم غير موجود']);

        return view('admin.maincategories.edit', compact('maincategory'));


    }

    public function update($id, MainCategoryRequest $request)
    {

        try {
            $maincategory = MainCategory::find($id);
            if (!$maincategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'عفوا القسم غير موجود']);


            $category = array_values($request->category) [0];
            if (!$request->has('category.0.active')) {
                $request->request->add(['active' => 0]);
            } else
                $request->request->add(['active' => 1]);

            MainCategory::where('id', $id)->update([

                'name' => $category['name'],
                'active' => $request->active,

            ]);
            $filepath = $maincategory->photo;
            if ($request->has('photo')) {
                $filepath = uploadImage('maincategories', $request->photo);
                MainCategory::where('id', $id)->update([
                    'photo' => $filepath,
                ]);


            }


            return redirect()->route('admin.maincategories')->with(['success' => 'تم التحديث بنجاح']);

        } catch (\Exception $ex) {

            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }


    }


    public function destroy($id)
    {
        try {
            $maincategory = MainCategory::find($id);
            if (!$maincategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'عفوا القسم غير موجود']);

            $vendors = $maincategory->vendors();
            if (isset($vendors) && $vendors->count() > 0) {
                return redirect()->route('admin.maincategories')->with(['error' => 'عفوا لا يمكن حذف القسم']);
            }

            $image = Str::after($maincategory->photo, 'assets/');
            $image = base_path('public/assets/' . $image);
            unlink($image); //delete from folder

            $maincategory->delete();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحذف بنجاح']);


        } catch (\Exception $e) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }
    }

    public function changestatus($id)
    {
        try {
            $maincategory = MainCategory::find($id);
            if (!$maincategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'عفوا القسم غير موجود']);

            $status = $maincategory->active == 0 ? 1 : 0;


            $maincategory -> update(['active'=>$status]);
            return redirect()->route('admin.maincategories')->with(['success' => 'تم تغيير الحالة بنجاح']);

        } catch (\Exception $e) {

            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }
    }


}
