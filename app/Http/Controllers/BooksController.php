<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.basic')->except('index');
        $this->middleware('auth.admin')->only('store');
    }

    public function index(Request $request)
    {
        $sortDirection = $request->sortDirection ?? 'ASC';

        $record = Book::with('reviews','authors')->when($sortColumn = $request->sortColumn, function($q) use($sortColumn, $sortDirection) {
                if($sortColumn == 'avg_review')
                {
                    $q->leftJoin('book_reviews', 'book_reviews.book_id', '=', 'books.id')
                    ->select('books.*', \DB::raw('avg(book_reviews.review)'))
                    ->groupBy('books.id')
                    ->orderByRaw('avg(book_reviews.review) '. $sortDirection);
                }else{
                    $q->orderBy($sortColumn, $sortDirection);
                }
            })->when($title = $request->title, function($q) use($title) {
                $q->where('title','LIKE','%'.$title.'%');
            })->when($authors = $request->authors, function($q) use($authors) {
                $q->whereHas('authors', function($qq) use($authors){
                    $authorsArray = explode(',', $authors);
                    $qq->whereIn('author_id',$authorsArray);
                });
            })->paginate(15);

        return BookResource::collection($record);
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement
        $book = new Book();
        $book->fill($request->all());
        $book->save();
        $book->authors()->attach($request->authors);

        return new BookResource($book);
    }
}
