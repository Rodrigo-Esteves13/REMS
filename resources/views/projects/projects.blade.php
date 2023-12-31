@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.footer')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>REMS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/projects.blade.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
</head>
<body>
<div class="projects-container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @can('create-project')
    <div class="create-project">
        <button id="openModalButton">Add <i class="fas fa-plus"></i></button>
    </div>
    @endcan

    <div class="project-list">
        @foreach ($projects as $project)
            <a href="{{ route('projects.show', ['id' => $project->id]) }}" class="project-item">
                <div class="thumbnail">
                    <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}">
                </div>
                <div class="project-details">
                    <h3 class="project-title">{{ $project->title }}</h3><br>
                    @if (auth()->check() && auth()->user()->isAdmin())
                        <form action="{{ route('projects.edit', ['id' => $project->id]) }}" method="GET">
                            <button type="submit" class="edit-button">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</div>

<div id="projectModal" class="modal">
    <div class="modal-content">
        <form id="createProjectForm" action="{{ route('projects.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required><br>
            <label for="thumbnail" class="custom-file-upload">
            Thumbnail:  <i class="fa-regular fa-folder-open fa-bounce"></i>
            </label>
            <input type="file" name="thumbnail" id="thumbnail" style="display: none;"><br>
            <div class="form-group">
                <label for="description">Project Description</label>
                <input type="hidden" name="description" id="description">
                <trix-editor input="description"></trix-editor>
            </div>
            <button type="submit">Create Project  <i class="fa-solid fa-upload"></i></button>
            <button type="button" id="closeModalButton">Close  <i class="fa-solid fa-xmark"></i></button>
        </form>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.4/axios.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@rails/ujs@7.0.6/lib/assets/compiled/rails-ujs.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const openModalButton = document.getElementById('openModalButton');
    const closeModalButton = document.getElementById('closeModalButton');
    const projectModal = document.getElementById('projectModal');


    if (openModalButton && closeModalButton && projectModal) {
        openModalButton.addEventListener('click', () => {
            projectModal.style.display = 'block';
        });

        closeModalButton.addEventListener('click', () => {
            projectModal.style.display = 'none';
        });
    }

    Trix.config.attachments.preview.caption = { name: false, size: false };
    Trix.config.attachments.preview.file = { name: true, size: true };
    Trix.config.attachments.preview.image = { name: false, size: true };
    Trix.config.attachments.preview.audio = { name: false, size: true };
    Trix.config.attachments.preview.video = { name: false, size: true };

    document.addEventListener('trix-attachment-add', function(event) {
        var attachment = event.attachment;
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var uploadURL = 'projects/upload/image'; // Change this URL as needed
        
        var formData = new FormData();
        formData.append('file', attachment.file);
        formData.append('_token', csrfToken);

        axios.post(uploadURL, formData)
            .then(function(response) {
                attachment.setAttributes({
                    url: response.data.file.url,
                    image_filename: response.data.file.filename // Store the image filename in a custom attribute
                });
            })
            .catch(function(error) {
                console.error('Error uploading image', error);
            });
    });
});
</script>
</body>
</html>
