<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments - Blogging Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Comments for Post: {{ $post->title }}</h2>
    <a href="/posts" class="btn btn-secondary mb-3">Back to Posts</a>
    <form method="POST" action="/posts/{{ $post->id }}/comments">
        @csrf
        <div class="mb-3">
            <label>Add Comment</label>
            <textarea name="body" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Comment</button>
    </form>
    @if(!Auth::check())
        <div class="alert alert-warning mt-2">You must be logged in to add a comment.</div>
    @endif
    <hr>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Comment</th>
                <th>Author</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
            <tr>
                <td>{{ $comment->body }}</td>
                <td>{{ $comment->user->name ?? 'N/A' }}</td>
                <td>
                    @if(Auth::check() && Auth::id() === $comment->user_id)
                        <a href="/comments/{{ $comment->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                        <form action="/comments/{{ $comment->id }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
