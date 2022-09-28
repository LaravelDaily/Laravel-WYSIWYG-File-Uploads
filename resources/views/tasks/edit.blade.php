<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-label for="title" :value="__('Title')" />

                            <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $task->title)" />
                            @error('title')
                                <span class="text-sm text-red-600 mb-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <x-label for="description" :value="__('Description')" />

                            <textarea id="description" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="description">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <span class="text-sm text-red-600 mb-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <x-button class="mt-4">
                            {{ __('Submit') }}
                        </x-button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.2.0/tinymce.min.js"></script>
        <script>
            const image_upload_handler_callback = (blobInfo, progress) => new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '{{ route('upload') }}');
                xhr.setRequestHeader("X-CSRF-Token", '{{ csrf_token() }}');
                xhr.upload.onprogress = (e) => {
                    progress(e.loaded / e.total * 100);
                };
                xhr.onload = () => {
                    if (xhr.status === 403) {
                        reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                        return;
                    }
                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('HTTP Error: ' + xhr.status);
                        return;
                    }
                    const json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.location != 'string') {
                        reject('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    resolve(json.location);
                };
                xhr.onerror = () => {
                    reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                };
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            });

            tinymce.init({
                selector: 'textarea#description',
                menubar: false,
                plugins: 'code table lists image',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | table | image',
                convert_urls: false,
                images_upload_url: '{{ route('upload') }}',
                images_upload_handler: image_upload_handler_callback,
            });
        </script>
    @endpush
</x-app-layout>
