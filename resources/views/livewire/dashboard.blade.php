<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use App\Models\Question;

new #[Layout('components.layouts.app')] class extends Component {
    #[Url]
    public $filter = 'all';

    #[Url]
    public $sort = 'popular';

    #[Computed]
    public function questions()
    {
        $query = Question::query();

        // Apply filters...
        if ($this->filter === 'unapproved') {
            $query->where('is_approved', false);
        } elseif ($this->filter === 'approved') {
            $query->where('is_approved', true);
        }

        // Apply sorting...
        switch ($this->sort) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'popular':
                $query->withCount('votes')->orderByDesc('votes_count');
                break;
        }

        return $query->get();
    }

    public function delete(Question $question)
    {
        // In a real app, you would authorize the user first...

        $question->delete();

        Flux::toast('Question deleted', variant: 'success');
    }
}; ?>

<div>
    <div class="-mx-8 -mt-6 lg:-mt-8 sm:border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
        <div class="max-w-7xl px-6 sm:px-8 py-3 mx-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-2">
            <div class="max-sm:hidden flex items-baseline gap-3">
                <flux:heading size="lg">Questions</flux:heading>

                <flux:text>{{ $this->questions->count() }}</flux:text>
            </div>

            <flux:spacer />

            <div class="flex items-center gap-2">
                <flux:select variant="listbox" wire:model.live="filter" class="sm:max-w-fit">
                    <x-slot name="trigger">
                        <flux:select.button size="sm">
                            <flux:icon.funnel variant="micro" class="mr-2 text-zinc-400" />

                            <flux:select.selected />
                        </flux:select.button>
                    </x-slot>

                    <flux:select.option value="all">All</flux:select.option>
                    <flux:select.option value="unapproved">Unapproved</flux:select.option>
                    <flux:select.option value="approved">Approved</flux:select.option>
                </flux:select>

                <flux:select variant="listbox" wire:model.live="sort" class="sm:max-w-fit">
                    <x-slot name="trigger">
                        <flux:select.button size="sm">
                            <flux:icon.arrows-up-down variant="micro" class="mr-2 text-zinc-400" />

                            <flux:select.selected />
                        </flux:select.button>
                    </x-slot>

                    <flux:select.option value="popular">Most popular</flux:select.option>
                    <flux:select.option value="newest">Newest</flux:select.option>
                    <flux:select.option value="oldest">Oldest</flux:select.option>
                </flux:select>
            </div>

            <livewire:question.create @created="$refresh" />
        </div>
    </div>

    <div class="min-h-4 sm:min-h-10"></div>

    <div class="mx-auto max-w-lg max-sm:-mx-2 grid grid-cols-1 gap-1">
        @foreach ($this->questions as $question)
            <livewire:question.show :question="$question" :key="$question->id" />
        @endforeach
    </div>
</div>
