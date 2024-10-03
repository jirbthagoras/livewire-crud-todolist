<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;

class TodoList extends Component
{
    public string $name;

    public string $search;

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

    public function render()
    {
        return view('livewire.todo-list');
    }
}
