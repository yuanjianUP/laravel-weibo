<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index(){
        CategoryResource::wrap('data');
        return CategoryResource::collection(Category::all());
    }
}
