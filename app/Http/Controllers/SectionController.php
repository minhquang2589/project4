<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Sections;
use Illuminate\Support\Facades\DB;



class SectionController extends Controller
{
   //
   public function getSection()
   {
      $Sections = Sections::where('status', 1)->first();
      return response()->json([
         'success' => true,
         'section' =>  $Sections,
      ]);
   }
   ////
   public function HandleUpload(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'title' => 'required|string|max:200',
         'description' => 'required|string|max:500',
         'link_url' => 'required|string|max:500',
         'status' => 'required',
         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
      ]);
      if ($validator->fails()) {
         return response()->json([
            'success' => false,
            'error' => $validator->errors()->all(),
         ]);
      }
      $status = $request->status;
      $section = new Sections();
      $section->name = $request->title;
      if ($request->hasFile('image')) {
         $image = $request->file('image');
         $imageName = time() . '_' . $image->getClientOriginalName();
         $image->move(public_path('images'), $imageName);
         $section->image = $imageName;
      }
      $section->status = $status;
      $section->link_url =  $request->link_url;
      $section->description = $request->description;
      $section->save();
      return response()->json([
         'success' => true,
         'message' => 'Upload section successfully!',
      ]);
   }
   ///
   public function view()
   {
      $section = Sections::all();
      return response()->json([
         'success' => true,
         'dataSection' => $section,
      ]);
   }
   ///
   public function editSectionView($id)
   {
      $section = Sections::find($id);
      return response()->json([
         'success' => true,
         'dataSection' =>  $section
      ]);
   }
   ///
   public function editSection(Request $request)
   {

      // return response()->json([
      //    'success' => true,
      //    'section' =>  $request->all(),
      // ]);
      $validator = Validator::make($request->all(), [
         'link_url' => 'required|string',
         'status' => 'required',
         'name' => 'required|string|max:100',
         'description' => 'required|string|max:300',
      ]);

      if ($validator->fails()) {
         return response()->json([
            'success' => false,
            'error' => $validator->errors()->all(),
         ]);
      }
      try {
         DB::beginTransaction();
         $section = Sections::find($request->sectionId);
         if (!$section) {
            return response()->json([
               'success' => false,
               'erroe' => ['Section not found.']
            ]);
         }
         if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $fileName);
            $section->image = $fileName;
         }
         $section->name = $request->name;
         $section->status = $request->status;
         $section->link_url = $request->link_url;
         $section->description = $request->description;
         $section->save();

         DB::commit();
         return response()->json([
            'success' => true,
            'message' => ['Section updated successfully!']
         ]);
      } catch (\Exception $e) {
         DB::rollBack();
         return response()->json([
            'success' => false,
            'error' => ['Failed to updated section.']
         ]);
      }
   }
   ////
   public function removeSection($id)
   {
      try {
         DB::beginTransaction();
         $section = Sections::find($id);
         if (!$section) {
            return response()->json([
               'success' => false,
               'error' => ['Section not found.']
            ]);
         }

         $section->delete();

         DB::commit();
         return response()->json([
            'success' => true,
            'message' => ['Section deleted successfully.']
         ]);
      } catch (\Exception $e) {
         DB::rollBack();
         return response()->json([
            'success' => false,
            'error' => ['Failed to delete section.']
         ]);
      }
   }
}
