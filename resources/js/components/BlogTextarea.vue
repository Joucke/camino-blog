<template>
    <textarea v-model="text">
    </textarea>
</template>

<script>
export default {
    props: ['value'],
    data () {
        return {
            text: this.value,
        };
    },
    watch: {
        text(value) {
            this.$parent.$emit('text', {value});
        },
    },
    methods: {
        textarea () {
            return true;
        },
    },
}
</script>
