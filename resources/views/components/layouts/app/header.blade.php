<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
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

                <flux:navbar.item href="#">
                    Leaderboard
                </flux:navbar.item>

                <flux:navbar.item href="#">
                    Announcements
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <div class="rounded-full bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-500 p-1 flex items-center p-1 pr-2.5 gap-2">
                <div class="relative rounded-full size-6">
                    <img src="/img/prime.png" class="size-full rounded-full" />
                    <div class="absolute -bottom-px -right-px rounded-full size-2 border-2 border-red-50 dark:border-red-950 bg-red-600 dark:bg-red-500"></div>
                </div>

                <div class="text-sm font-medium text-red-600 dark:text-white">Live</div>
            </div>

            <flux:separator vertical variant="subtle" class="my-4 mx-3"/>

            <!-- Desktop User Menu -->
            <flux:dropdown position="top" align="end">
                <flux:profile class="cursor-pointer" avatar="/img/teej.png" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <flux:avatar src="/img/teej.png" size="sm" class="shrink-0" />

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
                    <flux:navlist.item href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">
                        Questions
                    </flux:navlist.item>

                    <flux:navlist.item href="#">
                        Leaderboard
                    </flux:navlist.item>

                    <flux:navlist.item href="#">
                        Announcements
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />
        </flux:sidebar>

        {{ $slot }}

        <flux:toast />

        @fluxScripts
    </body>
</html>
