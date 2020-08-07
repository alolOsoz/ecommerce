<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    //
    public function index()
    {
        $languages = Language::select()->paginate(page);
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function save(LanguageRequest $request)
    {
        try {
            //     $request ->request->add(['active'=>0]);

            Language::create($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success' => 'تم التسجيل بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطأ حاول مرة اخرى']);

        }


    }

    public function edit($id)
    {
        $language = Language::select()->find($id);
        if (!$language) {
            return redirect()->route('admin.languages')->with(['error' => 'اللغة غير موجودة']);
        }
        return view('admin.languages.edit', compact('language'));

    }

    public function update($id, LanguageRequest $request)
    {
        try {


            $language = Language::select()->find($id);
            if (!$language) {
                return redirect()->route('admin.languages.edit', $id)->with(['error' => 'اللغة غير موجودة']);
            }

            if (!$request->has('active')) {
                $request->request->add(['active' => 0]);
            }

            $language->update($request->except('_token'));
            return redirect()->route('admin.languages')->with(['success' => 'تم التحديث بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطأ حاول مرة اخرى']);

        }
    }

    public function destroy($id)
    {
        try {
            $language = Language::find($id);
            if (!$language) {
                return redirect()->route('admin.languages', $id)->with(['error' => 'اللغة غير موجودة']);
            }
            $language->delete();
            return redirect()->route('admin.languages')->with(['success' => 'تم الحذف بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطأ حاول مرة اخرى']);

        }
    }

}
