<template>
    <form @submit="submit" action="/photos" method="POST" enctype="multipart/form-data">
        <input ref="files" type="file" multiple name="photos[]" accept="image/*">
        <button class="border">Uploaden</button>
    </form>
</template>

<script>
export default {
    props: [],
    data () {
        return {
            //
        };
    },
    methods: {
        submit(e) {
            e.preventDefault();

            let formData = new FormData();
            const files = this.$refs.files;

            for (let i = 0; i < files.files.length; i++) {
                formData.append('photos[]', files.files[i]);
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
                this.$parent.$emit('images', {images: result.map(image => image.path)})
            });
        },
    },
}
</script>
