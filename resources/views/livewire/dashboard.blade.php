<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Question;

new #[Layout('components.layouts.app'), Lazy] class extends Component {
    #[Url]
    public $sortBy = 'votes';

    #[Url]
    public $sortDirection = 'desc';

    public $content = '';

    #[Computed]
    public function questions()
    {
        return Question::orderBy($this->sortBy, $this->sortDirection)->get();
    }

    public function vote(Question $question)
    {
        $question->increment('votes');
    }

    public function save()
    {
        $data = $this->validate([
            'content' => 'required|string|max:255',
        ]);

        auth()->user()->questions()->create($data);

        $this->reset('content');

        Flux::modals()->close();
    }
}; ?>

<div class="space-y-6">
    <flux:heading size="xl">Welcome!</flux:heading>
    <flux:subheading>Here's what's happening today</flux:subheading>
    <flux:separator text="Questions" />

    <div class="flex items-center gap-2">
        <flux:spacer />

        <flux:input.group class="max-w-46">
            <flux:input.group.prefix>Sort by</flux:input.group.prefix>

            <flux:select wire:model.live="sortBy" size="sm">
                <flux:select.option value="votes">Votes</flux:select.option>
                <flux:select.option value="created_at">Date</flux:select.option>
            </flux:select>
        </flux:input.group>

        <flux:select wire:model.live="sortDirection" size="sm" class="max-w-26">
            <flux:select.option value="asc">Asc</flux:select.option>
            <flux:select.option value="desc">Desc</flux:select.option>
        </flux:select>

        <flux:button x-on:click="$flux.modal('create-question').show()" icon="plus" size="sm" variant="primary">New Question</flux:button>
    </div>

    <div class="grid grid-cols-4 gap-4">
        @foreach ($this->questions as $question)
            <flux:card class="flex flex-col" wire:key="question-{{ $question->id }}">
                <flux:subheading size="sm">{{ $question->user->name }}</flux:subheading>
                <flux:heading>{{ $question->content }}</flux:heading>
                <flux:spacer class="min-h-4" />
                <flux:button wire:click="vote({{ $question->id }})">{{ $question->votes }} votes</flux:button>
            </flux:card>
        @endforeach
    </div>

    <flux:modal name="create-question" variant="flyout">
        <form wire:submit="save" class="space-y-4">
            <flux:heading size="lg">Submit a question</flux:heading>

            <flux:textarea wire:model="content" label="Question" />

            <flux:button type="submit" variant="primary">Submit</flux:button>
        </form>
    </flux:modal>
</div>
