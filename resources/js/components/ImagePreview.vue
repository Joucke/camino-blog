<template>
    <div>
        <img
            v-for="image in images"
            :key="image"
            :src="`/images/${image}`"
            @click="selectImage(image)"
        >
    </div>
</template>

<script>
export default {
    props: [],
    data () {
        return {
            images: [],
        };
    },
    mounted () {
        fetch('/photos')
            .then((response) => response.json())
            .then((data) => {
                this.images = data.map(image => image.path)
            })
    },
    methods: {
        selectImage(url) {
            this.$parent.$emit('select', {url: `/images/${url}`});
        },
        imagePreview() {
            return true;
        },
        textarea() {
            return false;
        },
    },
}
</script>
