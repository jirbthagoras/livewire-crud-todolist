<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;

    public string $name;

    public string $search = '';

    public function create()
    {
        $validated = $this->validateOnly("name",[
                    "name" => ["required", "min:3", "max:100"]
                ]);

        Todo::query()
        ->create($validated);

        $this->reset("name");

        request()->session()->flash("success", "Success Created New Todo");
    }

    public function delete(Todo $todo)
    {
        $todo->delete();
    }

    public function render()
    {

        return view('livewire.todo-list', [
            "todos" => Todo::query()
            ->latest()
            ->where("name", "like", "%{$this->search}%")
            ->paginate(5),
        ]);
    }
}
