<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\Category;
use Auth;

class CategoryController extends AppBaseController
{
    /** @var  categoryRepository */
    private $categoryRepository;

    public function __construct(categoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the category.
     *
     * @param Request $request
     * @return Response
     */
    public function index(CategoryDataTable $categoryDataTable)
    {
        return $categoryDataTable->render('categories.index');
    }

    /**
     * Show the form for creating a new category.
     *
     * @return Response
     */
    public function create()
    {
        $categories = Category::orderBy('description', 'asc')->pluck('description', 'id');
        return view('categories.create')->with('categories', $categories);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param CreatecategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();
        $category = $this->categoryRepository->create($input);

        Flash::success('Category saved successfully.');

        return redirect(route('categories.index'));
    }

    /**
     * Display the specified category.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        return view('categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);
        $categories = Category::orderBy('description', 'asc')->pluck('description', 'id');
        
        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        return view('categories.edit')->with('category', $category)->with('categories', $categories);
    }

    /**
     * Update the specified category in storage.
     *
     * @param  int              $id
     * @param UpdatecategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCategoryRequest $request)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        $category = $this->categoryRepository->update($request->all(), $id);

        Flash::success('Category updated successfully.');

        return redirect(route('categories.index'));
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        $this->categoryRepository->delete($id);

        Flash::success('Category deleted successfully.');

        return redirect(route('categories.index'));
    }


   /* public function search()
    {
        
        $categories = Category::from( DB::raw('categories as categories') )
        ->leftJoin( DB::raw('categories as sup'), DB::raw( 'sup.id' ), '=', DB::raw( 'categories.category_superior_id' ))
        ->select(DB::raw('categories.*, sup.description as category_superior'))
        ->orderBy('categories.description', 'asc')
        ->get();

       
        return Datatables::of($categories)
        ->addColumn('actions', function ($category) {
           return '<a href="categories/'.$category->id.'/edit/" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-edit"></i>Edit</a><a href="categories/'.$category->id.'/destroy/" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i>Delete</a>';
        })
        ->editColumn('id', 'ID: {{$id}}')
        ->editColumn('updated_at', function ($category) {
                return !empty($category->updated_at) ? $category->updated_at->format('d/m/Y H:m') : '';
        })
        ->editColumn('created_at', function ($category) {
                return !empty($category->created_at) ? $category->created_at->format('d/m/Y H:m') : '';
        })
        ->make(true);

    }*/
}
