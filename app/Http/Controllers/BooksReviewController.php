<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BooksReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.admin');
    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        // @TODO implement
        $book = Book::findOrFail($bookId);
        $bookReview = new BookReview();
        $bookReview->book_id = $book->id;
        $bookReview->user_id = Auth::user()->id;
        $bookReview->fill($request->all());
        $bookReview->save();

        return new BookReviewResource($bookReview);
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        // @TODO implement
        $book = Book::findOrFail($bookId);
        $book->delete();
        return response()->noContent();
    }
}
