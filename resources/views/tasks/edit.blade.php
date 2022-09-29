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

                            <textarea id="description" class="hidden block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="description">{{ old('description', $task->description) }}</textarea>
                            <trix-editor input="description"></trix-editor>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>

        <script>
            addEventListener("trix-attachment-add", function (event) {
                if (event.attachment.file) {
                    uploadFileAttachment(event.attachment)
                }
            })
            function uploadFileAttachment(attachment) {
                uploadFile(attachment.file, setProgress, setAttributes)
                function setProgress(progress) {
                    attachment.setUploadProgress(progress)
                }
                function setAttributes(attributes) {
                    attachment.setAttributes(attributes)
                }
            }
            function uploadFile(data, progressCallback, successCallback, errorCallback) {
                var formData = createFormData(data);
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "{{ route('upload') }}", true);
                xhr.setRequestHeader("X-CSRF-Token", '{{ csrf_token() }}');
                xhr.upload.addEventListener("progress", function (event) {
                    var progress = (event.loaded / event.total) * 100;
                    progressCallback(progress);
                });
                xhr.addEventListener("load", function (event) {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        var response = JSON.parse(xhr.response);
                        successCallback({
                            url: response.url,
                            href: response.url
                        })
                    } else {
                        errorCallback(xhr, data.attachment)
                    }
                });
                xhr.send(formData);
            }
            function createFormData(key) {
                var data = new FormData()
                data.append("Content-Type", key.type);
                data.append("file", key);
                return data
            }
        </script>
    @endpush
</x-app-layout>