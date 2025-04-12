<div class="form-group d-flex gap-3">
    <div class="flex-grow-1">
        <label for="search" class="form-label">
            <b>Title</b>
        </label>
        <input type="text" name="title" class="form-control" placeholder="Enter title"
            value="{{ @$post->title }}" />
        @error('title')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="flex-grow-1">
        <label for="description" class="form-label">
            <b>Description</b>
        </label>
        <textarea class="form-control" id="description" name="content" rows="1"
            placeholder="Write your description here...">{{ @$post->content }}</textarea>
        @error('content')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="type" class="form-label">
            <b>Type</b>
        </label>
        <select name="type" id="type" class="form-control">
            <option value="1" {{ (isset($post) && $post->type == 1) ? 'selected' : '' }}>Request</option>
            <option value="2" {{ (isset($post) && $post->type == 2) ? 'selected' : '' }}>Complaint</option>
            <option value="3" {{ (isset($post) && $post->type == 3) ? 'selected' : '' }}>Improvement</option>
        </select>
        @error('type')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="d-flex justify-content-center mt-3">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>