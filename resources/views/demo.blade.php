<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <a href="{{ route('dashboard') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0">
            <x-app-logo class="size-8" href="#"></x-app-logo>
        </a>

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">
                Questions
            </flux:navbar.item>

            <flux:navbar.item>
                Leaderboard
            </flux:navbar.item>

            <flux:navbar.item>
                Announcements
            </flux:navbar.item>
        </flux:navbar>

        <flux:spacer />

        <div class="rounded-full bg-red-50 border border-red-200 p-1 flex items-center p-1 pr-2.5 gap-2">
            <div class="relative rounded-full size-6">
                <img src="/img/prime.png" class="size-full rounded-full" />
                <div class="absolute -bottom-px -right-px rounded-full size-2 border-2 border-red-50 bg-red-600"></div>
            </div>

            <div class="text-sm font-medium text-red-600">Live</div>
        </div>

        <flux:separator vertical variant="subtle" class="my-4 mx-3"/>

        <!-- Desktop User Menu -->
        <flux:dropdown position="top" align="end">
            <flux:profile
                class="cursor-pointer"
                :initials="auth()->user()->initials()"
            />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                >
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item href="/settings/profile" icon="cog">Settings</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <!-- Mobile Menu -->
    <flux:sidebar stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="ml-1 flex items-center space-x-2">
            <x-app-logo class="size-8" href="#"></x-app-logo>
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group heading="Platform">
                <flux:navlist.item icon="layout-grid" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">
                    Dashboard
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                Repository
            </flux:navlist.item>

            <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                Documentation
            </flux:navlist.item>
        </flux:navlist>
    </flux:sidebar>

    <div>
        <div class="-mx-8 -mt-8 border-b border-zinc-200">
            <div class="max-w-7xl px-8 py-3 mx-auto flex items-center gap-2">
                <div class="flex items-baseline gap-3">
                    <flux:heading size="lg" class="text-lg">Questions</flux:heading>

                    <flux:text>8</flux:text>
                </div>

                <flux:spacer />

                <flux:button size="sm" variant="ghost" square wire:click="$js.refreshAndAnimate($el)">
                    <flux:icon.arrow-path variant="mini" class="size-4" data-animate-spin />
                </flux:button>
                <flux:select variant="listbox" wire:model.live="sortBy" class="max-w-fit">
                    <x-slot name="trigger">
                        <flux:select.button size="sm">
                            <flux:icon.funnel variant="micro" class="mr-2 text-zinc-400" />
                            <flux:select.selected />
                        </flux:select.button>
                    </x-slot>
                    <flux:select.option value="created_at">All</flux:select.option>
                    <flux:select.option value="votes">Unapproved</flux:select.option>
                    <flux:select.option value="votes">Approved</flux:select.option>
                </flux:select>
                <flux:select variant="listbox" wire:model.live="sortBy" class="max-w-fit">
                    <x-slot name="trigger">
                        <flux:select.button size="sm">
                            <flux:icon.arrows-up-down variant="micro" class="mr-2 text-zinc-400" />
                            <flux:select.selected />
                        </flux:select.button>
                    </x-slot>
                    <flux:select.option value="created_at">Most popular</flux:select.option>
                    <flux:select.option value="votes">Newest</flux:select.option>
                    <flux:select.option value="votes">Oldest</flux:select.option>
                </flux:select>
                <livewire:question.create @created="refresh" />
            </div>
        </div>

        <div class="min-h-10"></div>

        <div class="mx-auto max-w-lg">
            @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10] as $question)
                <div class="p-4 rounded-lg group/question {{ $question->is_approved ? '' : 'bg-zinc-50' }}">
                    <div class="flex items-center gap-2">
                        <flux:avatar src="https://randomuser.me/api/portraits/men/{{ $question->user->id }}.jpg" size="xs" />

                        <flux:heading>{{ $question->user->name }}</flux:heading>

                        @if ($question->user->role === 'moderator')
                            <flux:badge color="lime" size="sm" icon="check-badge">Moderator</flux:badge>
                        @endif

                        <flux:text class="text-sm">{{ $question->created_at->diffForHumans() }}</flux:text>
                    </div>

                    <div class="min-h-1"></div>

                    <div class="pl-8">
                        <flux:text variant="strong">{{ $question->content }}</flux:text>

                        <div class="min-h-2"></div>

                        @if ($question->is_approved)
                            <div class="flex items-center gap-0">
                                <flux:button wire:click="$js.optimisticVote($el)" variant="ghost" size="sm" inset="left" class="flex items-center gap-2" :loading="false">
                                    <flux:icon.hand-thumb-up wire:show="hasVoted" name="hand-thumb-up" variant="solid" class="size-4 text-accent-content" data-animate-wiggle />
                                    <flux:icon.hand-thumb-up wire:show="!hasVoted" name="hand-thumb-up" variant="outline" class="size-4 text-zinc-400 [&_path]:stroke-[2.25]" />
                                    <flux:text wire:show="hasVoted" wire:text="votes" class="text-sm text-accent-content tabular-nums"></flux:text>
                                    <flux:text wire:show="!hasVoted" wire:text="votes" class="text-sm text-zinc-500 tabular-nums"></flux:text>
                                </flux:button>

                                @if (auth()->user()->role === 'moderator')
                                    <flux:dropdown>
                                        <flux:button icon="ellipsis-horizontal" variant="subtle" size="sm" />

                                    <flux:menu class="min-w-0">
                                        <flux:menu.item icon="pencil-square">Edit</flux:menu.item>
                                        <flux:menu.item variant="danger" icon="trash">Delete</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <flux:button size="sm">Approve</flux:button>
                                <flux:button size="sm" variant="filled" class="!text-red-600">Delete</flux:button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @fluxScripts
</body>
</html>