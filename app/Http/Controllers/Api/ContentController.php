<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    //
    private function findChildren($category) {
        $ret = array();

        $children = $category->children()->get();
        foreach($children as $child) {
            $ret[$child->category] = $child->toArray();
            $ret[$child->category]['children'] = $this->findChildren($child);
        }
        return $ret;
    }
    public function getCategory(Request $request) {

        DB::connection()->enableQueryLog();
        $categories = Category::orderBy('parent_id', 'asc')
            ->where('disabled', '=', false)
            ->orderBy('priority', 'asc')
            ->get();

        if ($categories) {

            $cat = [];
            foreach($categories as $category) {
                $cat[$category->category] = $category->toArray();
                // $cat[$category->category]['children'] = $this->findChildren($category);
            }
            // var_dump($cat);
            $sql = DB::getQueryLog();
            return response($cat, 200);

        } else {
            $response = null;
            return response($response, 422);
        }

    }
}
