<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    
    use RefreshDatabase;
    /** @test */
    function can_get_all_books()
    {
        $books = Book::factory(4)->create();
        $this->getJson(route('books.index'))
        ->assertJsonFragment([
            'title' => $books[0]->title
        ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);
    }

    /** @test */
    function can_get_one_book()
    {
        $book = Book::factory()->create();

        $this->getJson(route('books.show',$book))
        ->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test */
    function can_create_books(){

        $this->postJson(route('books.store'),[])
            ->assertStatus(422)
            ->assertJsonValidationErrors(
                ['title','autor','image']
            );

        $this->postJson(route('books.store'),[
            'title'=>"Nuevo Libro",
            'autor'=>"Su autor",
            "image"=>"suUrl.html"
        ])->assertJsonFragment([
            'title'=>"Nuevo Libro"
        ]);

        $this->assertDatabaseHas('books',[
            'title'=>"Nuevo Libro"
        ] );
    }

    /** @test */

    function can_update_books()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update',$book),[])
            ->assertStatus(422)
            ->assertJsonValidationErrors(
                ['title','autor','image']
            );

        $this->patchJson(route('books.update',$book),[
            'title'=>'Edited Book',
            'autor'=>'Edited Autor',
            'image'=>'Edited Url'
        ])->assertJsonFragment([
            'title'=>'Edited Book',
            'autor'=>'Edited Autor',
            'image'=>'Edited Url'  
        ]);

        $this->assertDatabaseHas('books',[
            'title'=>'Edited Book',
            'autor'=>'Edited Autor',
            'image'=>'Edited Url'  
        ]);
    }

    /** @test */

    function can_delete_books(){
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy',$book))
            ->assertNoContent();

        $this->assertDatabaseCount('books',0);
    }
}
