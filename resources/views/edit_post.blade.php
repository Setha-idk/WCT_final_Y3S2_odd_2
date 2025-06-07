<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Edit Post</h2>
    <form method="POST" action="/posts/{{ $post->id }}/edit">
        @csrf
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ $post->title }}" required>
        </div>
        <div class="mb-3">
            <label>Body</label>
            <textarea name="body" class="form-control" required>{{ $post->body }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/posts" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
