<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\Category;
use Yajra\Datatables\Datatables;
use DB;

class CategoryController extends Controller
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
    public function index(Request $request)
    {
        /*$this->categoryRepository->pushCriteria(new RequestCriteria($request));
        $categories = $this->categoryRepository->paginate(20);

        return view('categories.index')
            ->with('categories', $categories);*/

        return view('categories.index');
    }

    /**
     * Show the form for creating a new category.
     *
     * @return Response
     */
    public function create()
    {
        $categories = Category::pluck('description', 'id');
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
        $categories = Category::pluck('description', 'id');
        
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


    public function search()
    {
        // Gets the query string from our form submission 
        //$query = $request['search'];
        // Returns an array of articles that have the query string located somewhere within 
        // our articles titles. Paginates them so we can break up lots of search results.
        //$categories = DB::table('categories')->where('description', 'LIKE', '%' . $query . '%')->paginate(20);
            
        // returns a view and passes the view the list of articles and the original query.
        //return view('categories.index', compact('categories', 'query'));

        //return redirect(route('categories.index'));

        //$categories = Category::with('categorySuperior')->select('categories.*');

        $categories = Category::from( DB::raw('categories as categories') )
        ->leftJoin( DB::raw('categories as sup'), DB::raw( 'sup.id' ), '=', DB::raw( 'categories.category_superior_id' ))
        ->select(DB::raw('categories.*, sup.description as category_superior'))
        ->orderBy('categories.description', 'asc')
        ->get();

        

        //categories.id, categories.description, categories.created_at, categories.updated_at, categories.user_id
        //, superior.id as id_superior, superior.description as description_superior
        
        return Datatables::of($categories)
        ->addColumn('actions', function ($category) {
            return '<a href="categories/'.$category->id.'/edit/" 
                        class="btn btn-default btn-xs">
                        <i class="glyphicon glyphicon-edit"></i>Edit</a>';
        })
        ->addColumn('delete', function ($category) {
            return '<a href="categories/'.$category->id.'/destroy/" 
                        class="btn btn-danger btn-xs">
                        <i class="glyphicon glyphicon-trash"></i>Delete</a>';
        })

        ->editColumn('id', 'ID: {{$id}}')
        ->make(true);

        /*->addColumn('actions','<a href="{{ URL::route( \'categories.edit\', array( \'edit\',$id )) }}">edit</a>
                    <a href="{{ URL::route( \'categories.destroy\', array( \'destroy\',$id )) }}">delete</a>')*/

        /*return Datatables::of($categorires)
            ->editColumn('created_at', function($data)
                { 
                    return $data->created_at->toDateTimeString()
                })
            ->make();*/

        //return Datatables::of($categorires)->make(true);


        /*return Datatables::of($categorires)        ->filter(function($query){
            if (Input::get('id')) {
                $query->where('id','=',Input::get('id'));
            }
        })->make(true);
*/
        /*->filter(function($query){
            if (Input::get('description')) {
                $query->where('description','=',Input::get('description'));
            }
        })->make();*/
     }
}
