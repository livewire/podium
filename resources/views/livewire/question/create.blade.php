<?php

use Livewire\Volt\Component;

new class extends Component {
    public $content = '';

    public function save()
    {
        $data = $this->validate([
            'content' => 'required|string|max:255',
        ]);

        auth()->user()->questions()->create($data);

        $this->reset('content');

        Flux::modal('create-question')->close();

        $this->dispatch('created');
    }
}; ?>

<div class="contents">
    <flux:button x-on:click="$flux.modal('create-question').show()" icon="pencil-square" size="sm" variant="primary">New question</flux:button>

    <flux:modal name="create-question" class="min-w-lg">
        <form wire:submit="save" class="space-y-4">
            <flux:heading size="lg">Submit a new question</flux:heading>

            <flux:subheading>
                Remember: there are no good questions.
            </flux:subheading>

            <flux:textarea wire:model="content" label="Your question" placeholder="Ask a question" />

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary">Submit question</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
