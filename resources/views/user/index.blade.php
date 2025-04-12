<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(!auth()->user()->is_admin)
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Add Post</a>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-3">
                <div class="p-6 text-gray-900">

                    <div class="d-flex justify-between align-items-center mb-3">
                        {{-- Header --}}
                        @if (request()->routeIs('top.posts'))
                            <h4 class="font-semibold text-xl text-gray-800 leading-tight mb-3">Top 10 Posts</h4>
                        @elseif (request()->routeIs('my.posts'))
                            <h4 class="font-semibold text-xl text-gray-800 leading-tight mb-3">My Posts</h4>
                        @else
                            <h4 class="font-semibold text-xl text-gray-800 leading-tight mb-3">Posts</h4>
                        @endif
                        {{-- Button --}}
                        @if (request()->routeIs('posts.index') && !auth()->user()->is_admin)
                            <a href="{{ route('top.posts') }}" class="btn btn-primary">Top 10 Posts</a>
                        @endif
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered p-4 rounded-3 " style="width: 100%;">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th>Sr #</th>
                                    <th>Ttile</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>User Name</th>
                                    <th>Votes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach (@$posts as $post)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ $post->content }}</td>
                                        <td>
                                            @if ($post->type == 1)
                                                Request
                                            @elseif($post->type == 2)
                                                Complaint
                                            @elseif($post->type == 3)
                                                Improvement
                                            @endif
                                        </td>
                                        <td>{{ $post->user->name }}</td>
                                        <td>{{ $post->votes_count }}</td>
                                        <td>
                                            @php
                                                $hasVoted = $post->votes->contains('user_id', auth()->id()); // Check if user has voted
                                            @endphp
                                            @if(!auth()->user()->is_admin)
                                                @if (Auth::id() !== $post->user_id)
                                                    <!-- Check if the user has voted -->
                                                    @if ($hasVoted)
                                                        <!-- Display "Voted" button if the user has already voted -->
                                                        <button class="btn btn-secondary" disabled>Voted</button>
                                                    @else
                                                        <!-- Display Vote button if the user hasn't voted -->
                                                        <form action="{{ route('votes.store', $post->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary">Vote</button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <!-- If it's the user's own post, display the Edit button -->
                                                    <a href="{{ route('posts.edit', $post->id) }}"
                                                        class="btn btn-primary">Edit</a>
                                                @endif
                                            @else
                                                <!-- Admin actions -->
                                                <form method="POST" action="{{ route('posts.update', $post->id) }}" style="display: inline">
                                                    @csrf
                                                    @method('PUT')
                                                    @if($post->is_hidden)
                                                        <input type="hidden" name="is_hidden" value="0">
                                                        <button type="submit" class="btn btn-success">Unhide</button>
                                                    @else
                                                        <input type="hidden" name="is_hidden" value="1">
                                                        <button type="submit" class="btn btn-warning">Hide</button>
                                                    @endif
                                                </form>

                                               <!-- Delete Button -->
                                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- More rows go here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
