<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class NoteTest extends TestCase {
  use RefreshDatabase;

  public function test_list_notes_appear_in_notes_view() {
    $this->withExceptionHandling();

    User::factory()->create(['id' => 2]);
    $user = Auth::loginUsingId(2);
    $this->actingAs($user);

    $notes = Note::factory()->create([
      'excerpt' => 'This is a test note',
      'content' => 'This is the content of the test note',
    ]);
    
    $response = $this->get('/notes', [$notes]);
    $response->assertStatus(200);
    $response->assertSee('This is a test note');
  }

  public function test_note_appear_in_note_view() {
    $this->withExceptionHandling();

    User::factory()->create(['id' => 3]);
    $user = Auth::loginUsingId(3);
    $this->actingAs($user);

    $note = Note::factory()->create([
      'excerpt' => 'This is a test note',
      'content' => 'This is the content of the test note',
    ]);
    
    $response = $this->get('/notes/' . $note->id, [$note]);
    $response->assertStatus(200);
    $response->assertSee('This is a test note');
  }
  
  public function test_view_edit_form() {
    $this->withExceptionHandling();

    User::factory()->create(['id' => 4]);
    $user = Auth::loginUsingId(4);
    $this->actingAs($user);

    $note = Note::factory()->create([
      'excerpt' => 'This is a test note for editing',
      'content' => 'This is the content of the test note for editing',
    ]);
    
    $response = $this->get(route('notes.edit', $note->id, [$note]));
    $response->assertStatus(200);
    $response->assertSee('This is a test note for editing');
  }

  public function test_can_be_updated() {
    $this->withExceptionHandling();

    User::factory()->create(['id' => 5]);
    $user = Auth::loginUsingId(5);
    $this->actingAs($user);

    $note = Note::factory()->create();
    
    $response = $this->put(route('notes.update', $note->id, [$note]), [
      'excerpt' => 'Hola que tal',
      'content' => 'This is the content of the test note for editing',
    ]);
    $response->assertStatus(302);
    $this->assertDatabaseHas('notes', [
      'excerpt' => 'Hola que tal',
      'content' => 'This is the content of the test note for editing',
    ]);
    $response->assertRedirect('/notes');
  }

  public function test_view_create_form() {
    $this->withExceptionHandling();

    User::factory()->create(['id' => 6]);
    $user = Auth::loginUsingId(6);
    $this->actingAs($user);

    $response = $this->get(route('notes.create'));
    $response->assertStatus(200);
  }

  public function test_note_can_be_created() {
    $this->withExceptionHandling();

    User::factory()->create(['id' => 7]);
    $user = Auth::loginUsingId(7);
    $this->actingAs($user);

    $response = $this->post(route('notes.store'), [
      'excerpt' => 'Hola que tal',
      'content' => 'This is the content of the test note for editing',
    ]);
    $response->assertStatus(302);
    $this->assertDatabaseHas('notes', [
      'excerpt' => 'Hola que tal',
      'content' => 'This is the content of the test note for editing',
    ]);
    $response->assertRedirect('/notes');
  }

  public function test_note_can_be_deleted() {
    $this->withExceptionHandling();

    User::factory()->create(['id' => 8]);
    $user = Auth::loginUsingId(8);
    $this->actingAs($user);

    $note = Note::factory()->create([
      'excerpt' => 'This is a test note for deleting',
      'content' => 'This is the content of the test note for deleting',
    ]);
    
    $response = $this->delete(route('notes.destroy', $note->id, [$note]));
    $response->assertStatus(302);
    $this->assertDatabaseMissing('notes', [
      'excerpt' => 'This is a test note for deleting',
      'content' => 'This is the content of the test note for deleting',
    ]);
    $response->assertRedirect('/notes');
  }
}
