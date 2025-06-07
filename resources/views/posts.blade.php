<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts - Blogging Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <nav class="mb-4">
        <a href="/login" class="btn btn-outline-secondary me-2">Login</a>
        <a href="/register" class="btn btn-outline-secondary me-2">Register</a>
        <form action="/logout" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Logout</button>
        </form>
    </nav>
    <div class="mb-3">
        <strong>Current user:</strong>
        @if(Auth::check())
            {{ Auth::user()->name }} ({{ Auth::user()->email }})
        @else
            <span class="text-danger">Not logged in</span>
        @endif
    </div>
    <h2>All Posts</h2>
    @if(Auth::check())
        <a href="/posts/create" class="btn btn-primary mb-3">Create New Post</a>
    @else
        <div class="alert alert-warning mb-3">You must be logged in to create a post or comment.</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Body</th>
                <th>Author</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td>{{ $post->title }}</td>
                <td>{{ $post->body }}</td>
                <td>{{ $post->user->name ?? 'N/A' }}</td>
                <td>
                    @if(Auth::check() && Auth::id() === $post->user_id)
                        <a href="/posts/{{ $post->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                        <form action="/posts/{{ $post->id }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    @endif
                    @if(Auth::check())
                        <a href="/posts/{{ $post->id }}/comments" class="btn btn-info btn-sm">Comments</a>
                    @else
                        <span class="text-muted">Login to comment</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
