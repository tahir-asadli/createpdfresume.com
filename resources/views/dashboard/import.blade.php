<x-dashboard-layout>
    @section('title')
        {{ __('Import') }}
    @endsection
    @section('add-new')
        <button onclick="Livewire.dispatch('openModal', { component: 'add-award' })" class="button p-3.5"><x-icons.plus
                class="ml-auto" width="20px" height="20px" /></button>
    @endsection
    <div id="drag-and-drop" class="w-full my-5">
        <div class="flex flex-col justify-center items-center w-full h-[300px] border border-gray-300 rounded-2xl">
            <div id="file-dialog-trigger" class="text-center flex justify-center flex-col items-center max-w-[300px]">
                <x-icons.import width="100" height="100" class="text-gray-300 block mx-auto" />
                <input type="file" name="resume" class="hidden" id="resume-selector">
                <h3 class="text-gray-500 font-semibold">{{ __('Choose a file or drag it here.') }}</h3>
                <p class="italic text-sm text-gray-600 py-2">{{ __('Supported file types: PDF, DOCX, TXT') }}</p>
            </div>
            <div id="import-status" class="import-status hidden">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const resumeSelector = document.getElementById('resume-selector');
            const fileDialogTrigger = document.getElementById('file-dialog-trigger');
            const importStatus = document.getElementById('import-status');
            const upload = (file) => {

                importStatus.classList.remove('hidden');
                importStatus.classList.remove('success');
                importStatus.classList.remove('error');
                updateImportState(`{{ __('Uploding...') }}`, 'none');
                const formData = new FormData();
                formData.append('resume', file);

                fetch('/dashboard/importResume', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                ?.content,
                        },
                        body: formData,
                    })
                    .then((response) => {
                        if (response.ok) {
                            return response.json()
                        }
                        return {
                            status: 'error',
                            message: response.statusText
                        };
                    })
                    .then(data => {
                        if (data) {
                            if (data?.status == 'success') {
                                updateImportState(data?.message ? data.message : 'Uploaded!',
                                    'success');

                            } else {
                                const message = data?.errors?.resume ? data?.errors?.resume.join(
                                    ", ") : data?.message ? data.message : 'Error occured!';
                                updateImportState(message,
                                    'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Upload failed:', error);
                        updateImportState(`{{ __('Upload failed') }}`, 'error');
                    });
            }
            const updateImportState = (message, status = 'success') => {
                importStatus.innerHTML = message;
                if (status == 'error') {
                    importStatus.classList.remove('success');
                    importStatus.classList.add('error');
                } else if (status == 'success') {
                    importStatus.classList.remove('error');
                    importStatus.classList.add('success');
                } else {
                    importStatus.classList.remove('error');
                    importStatus.classList.remove('success');
                }

            }
            const dragAndDrop = document.getElementById('drag-and-drop');

            if (dragAndDrop) {
                dragAndDrop.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dragAndDrop.classList.add('border-indigo-400', 'bg-indigo-50');
                    dragAndDrop.querySelector('.border').classList.add('border-indigo-400');
                });

                dragAndDrop.addEventListener('dragleave', (e) => {
                    if (!dragAndDrop.contains(e.relatedTarget)) {
                        dragAndDrop.classList.remove('border-indigo-400', 'bg-indigo-50');
                        dragAndDrop.querySelector('.border').classList.remove('border-indigo-400');
                    }
                });

                dragAndDrop.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dragAndDrop.classList.remove('border-indigo-400', 'bg-indigo-50');
                    dragAndDrop.querySelector('.border').classList.remove('border-indigo-400');
                    const file = e.dataTransfer.files[0];
                    if (file) {
                        upload(file);
                    }
                });
            }

            if (resumeSelector && fileDialogTrigger && importStatus) {

                fileDialogTrigger.addEventListener('click', () => {
                    if (resumeSelector) {
                        resumeSelector.click();
                    }
                });
                resumeSelector.addEventListener('change', (e) => {
                    if (e.target.files.length) {
                        upload(e.target.files[0]);
                    }
                })
            }
        });
    </script>

</x-dashboard-layout>
