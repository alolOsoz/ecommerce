<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorCreated;

class VendorsController extends Controller
{
    public function index()
    {
        $vendors = Vendor::selection()->paginate(page);
        return view('admin.vendors.index', compact('vendors'));

    }

    public function create()
    {
        $categories = MainCategory::where('translation_of', 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }

    public function save(VendorRequest $request)
    {
        if (!$request->has('active')) {
            $request->request->add(['active' => 0]);
        } else
            $request->request->add(['active' => 1]);

        $filepath = "";
        if ($request->has('logo')) {
            $filepath = uploadImage('vendors', $request->logo);
        }

        try {

            $vendor = Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'active' => $request->active,
                'address' => $request->address,
                'password' => $request->password,
                'logo' => $filepath,
                'category_id' => $request->category_id,


            ]);

            Notification::send($vendor, new VendorCreated($vendor));

            return redirect()->route('admin.vendors')->with(['success' => 'تم الحقظ بنجاح']);


        } catch (\Exception $ex) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }
    }

    public function edit($id)
    {
        try {
            $vendor = Vendor::selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'عفوا المتجر غير موجود ']);

            $categories = MainCategory::where('translation_of', 0)->active()->get();

            return view('admin.vendors.edit', compact('vendor', 'categories'));


        } catch (\Exception $e) {

            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }
    }

    public function update($id, VendorRequest $request)
    {
        try {


            $vendor = Vendor::selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'عفوا المتجر غير موجود ']);
            DB::beginTransaction();

            $filepath = $vendor->logo;
            if ($request->has('logo')) {
                $filepath = uploadImage('vendors', $request->logo);
                Vendor::where('id', $id)->update([
                    'logo' => $filepath,
                ]);
            }
            $data = $request->except('_token', 'id', 'logo', 'password');
            if ($request->has('password')) {
                $data['password'] = $request->password;
            }

            Vendor::where('id', $id)->update($data);
            DB::commit();
            return redirect()->route('admin.vendors')->with(['success' => 'تم التحديث بنجاح']);


        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }

    }

    public function delete()
    {

    }


}
