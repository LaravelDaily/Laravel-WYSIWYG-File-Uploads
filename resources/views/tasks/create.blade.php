<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        <div>
                            <x-label for="title" :value="__('Title')" />

                            <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" />
                            @error('title')
                                <span class="text-sm text-red-600 mb-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <x-label for="description" :value="__('Description')" />

                            <textarea id="description" class="hidden block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="description"></textarea>
                            <div id="content">{!! old('description') !!}</div>
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
        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
        <script src="https://unpkg.com/quill-image-uploader@1.2.1/dist/quill.imageUploader.min.js"></script>

        <script>
            Quill.register("modules/imageUploader", ImageUploader);
            const fullToolbarOptions = [
                [{ header: [1, 2, 3, false] }],
                ["bold", "italic"],
                ["clean"],
                ["image"]
            ];

            const quill = new Quill('#content', {
                theme: 'snow',
                modules: {
                    toolbar: fullToolbarOptions,
                    imageUploader: {
                        upload: file => {
                            return new Promise((resolve, reject) => {
                                const formData = new FormData();
                                formData.append("image", file);
                                fetch("{{ route('upload') }}", {
                                    method: "POST",
                                    body: formData,
                                    headers: { "X-CSRF-Token": '{{ csrf_token() }}' }
                                })
                                    .then(response => response.json())
                                    .then(result => {
                                        resolve(result.url);
                                    })
                                    .catch(error => {
                                        reject("Upload failed");
                                        console.error("Error:", error);
                                    });
                            });
                        }
                    }
                },
            });

            quill.on('text-change', function(delta, oldDelta, source) {
                document.getElementById("description").value = quill.root.innerHTML;
            });
        </script>
    @endpush
</x-app-layout>
