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

    public $memek = "mek";

    public $editingTodoId;
    public $editingTodoName;

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

    public function toggle($todoId)
    {
        $todo = Todo::query()
        ->find($todoId);

        $todo->completed = !$todo->completed;

        $todo->save();
    }

    public function edit($todoId)
    {
        $this->editingTodoId = $todoId;
        $this->editingTodoName = Todo::query()->find($todoId)->name;
    }

    public function cancelEdit()
    {
        $this->reset("editingTodoId", 'editingTodoName');
    }

    public function update()
    {
        $this->validateOnly("editingTodoName", [
            "editingTodoName" => [
                "required",
                "min:3"
            ]
        ]);

        $todo = Todo::query()
        ->find($this->editingTodoId)
        ->update([
            "name" => $this->editingTodoName,
        ]);

        $this->cancelEdit();
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
