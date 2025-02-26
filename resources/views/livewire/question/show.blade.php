<?php

use Livewire\Volt\Component;
use App\Models\Question;

new class extends Component {
    public Question $question;

    public int $votes;

    public bool $hasVoted = false;

    public function mount()
    {
        $this->votes = $this->question->votes()->count();
        $this->hasVoted = $this->question->hasVoted(auth()->user());
    }

    public function vote()
    {
        if ($this->question->hasVoted(auth()->user())) {
            $this->question->unvote(auth()->user());
        } else {
            $this->question->vote(auth()->user());
        }

        $this->votes = $this->question->votes()->count();
        $this->hasVoted = $this->question->hasVoted(auth()->user());
    }

    public function approve()
    {
        $this->question->approve();
    }
}; ?>

<div class="p-3 sm:p-4 rounded-lg group/question {{ $question->is_approved ? '' : 'bg-zinc-50 dark:bg-zinc-700/50' }}">
    <div class="flex flex-row sm:items-center gap-2">
        <flux:avatar src="https://randomuser.me/api/portraits/men/{{ $question->user->id }}.jpg" size="xs" class="shrink-0" />

        <div class="flex flex-col gap-0.5 sm:gap-2 sm:flex-row sm:items-center">
            <div class="flex items-center gap-2">
                <flux:heading>{{ $question->user->name }}</flux:heading>

                @if ($question->user->role === 'moderator')
                    <flux:badge color="lime" size="sm" icon="check-badge" inset="top bottom">Moderator</flux:badge>
                @endif
            </div>

            <flux:text class="text-sm">{{ $question->created_at->diffForHumans() }}</flux:text>
        </div>
    </div>

    <div class="min-h-2 sm:min-h-1"></div>

    <div class="pl-8">
        <flux:text variant="strong">{{ $question->content }}</flux:text>

        <div class="min-h-2"></div>

        @if ($question->is_approved)
            <div class="flex items-center gap-0">
                <flux:button wire:click="$js.optimisticVote($el)" variant="ghost" size="sm" inset="left" class="flex items-center gap-2" :loading="false">
                    <flux:icon.hand-thumb-up wire:cloak wire:show="hasVoted" name="hand-thumb-up" variant="solid" class="size-4 text-accent-content" data-animate-wiggle />
                    <flux:icon.hand-thumb-up wire:cloak wire:show="!hasVoted" name="hand-thumb-up" variant="outline" class="size-4 text-zinc-400 [&_path]:stroke-[2.25]" />

                    <flux:text wire:cloak wire:show="hasVoted" wire:text="votes" class="text-sm text-accent-content tabular-nums"></flux:text>
                    <flux:text wire:cloak wire:show="!hasVoted" wire:text="votes" class="text-sm text-zinc-500 tabular-nums"></flux:text>
                </flux:button>

                @if (auth()->user()->role === 'moderator')
                    <flux:dropdown>
                        <flux:button icon="ellipsis-horizontal" variant="subtle" size="sm" />

                        <flux:menu class="min-w-0">
                            <flux:menu.item icon="pencil-square">Edit</flux:menu.item>
                            <flux:menu.item variant="danger" icon="trash" wire:click="$parent.delete({{ $question->id }})" wire:confirm="Are you sure you want to delete this question?">Delete</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                @endif
            </div>
        @else
            <div class="flex items-center gap-2">
                <flux:button wire:click="approve" size="sm">Approve</flux:button>
                <flux:button wire:click="$parent.delete({{ $question->id }})" wire:confirm="Are you sure you want to delete this question?" size="sm" variant="filled" class="text-red-600! dark:text-red-500!">Delete</flux:button>
            </div>
        @endif
    </div>
</div>

@script
<script>
    $js('optimisticVote', (button) => {
        // Optimistically update Livewire properties...
        $wire.hasVoted ? $wire.votes-- : $wire.votes++

        $wire.hasVoted = ! $wire.hasVoted

        // Animate the thumbs up button...
        button.querySelector('[data-animate-wiggle]').animate([
            { transform: 'rotate(0deg) scale(1)' },
            { transform: 'rotate(-30deg) scale(1.3)' },
            { transform: 'rotate(0deg) scale(1)' },
        ], { duration: 400, easing: 'ease-in-out' })

        // Update the votes count on the server...
        $wire.vote()
    })
</script>
@endscript