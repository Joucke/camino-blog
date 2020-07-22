<template>
    <div ref="drop-area" class="max-w-lg flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <p class="mt-1 text-sm text-gray-600">
                Drag & drop of klik om te uploaden
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: [],
    data () {
        return {
            fileInput: null,
            dropArea: null,
        };
    },
    mounted () {
        this.fileInput = this.createFileInput({
            type: 'file',
            accept: 'image/*',
            multiple: true,
            onchange: (e) => {
                this.uploadFiles(e.target.files);
            },
        });
        this.dropArea = this.$refs['drop-area'];
        this.dropArea.addEventListener('click', () => this.fileInput.click());
        this.dropArea.addEventListener('dragenter', this.dont, false);
        this.dropArea.addEventListener('dragleave', this.dont, false);
        this.dropArea.addEventListener('dragover', this.dont, false);
        this.dropArea.addEventListener('drop', this.dont, false);
        this.dropArea.addEventListener('drop', ({dataTransfer}) => {
            this.uploadFiles(dataTransfer.files);
        });
    },
    methods: {
        createFileInput (options) {
            let el = document.createElement('input');
            for (let option in options) {
                el[option] = options[option];
            }
            return el;
        },
        dont (e) {
            e.preventDefault();
            e.stopPropagation();
        },
        selectFiles () {
            this.$refs.files.click();
        },
        uploadFiles (files) {
            if (files.length < 1) {
                return;
            }

            let formData = new FormData();

            for (let file of files) {
                formData.append('photos[]', file);
            }
            let csrf = document.querySelector('meta[name="csrf-token"]').content;

            fetch('/photos', {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': csrf,
                },
                body: formData,
            })
            .then(response => response.json())
            .then(result => {
                this.$parent.$emit('images', {images: result.map(image => image.path)});
                this.fileInput.value = '';
            });
        },
    },
}
</script>
