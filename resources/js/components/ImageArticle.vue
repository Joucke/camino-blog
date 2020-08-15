<template>
    <article>
        <slot></slot>
        <portal to="image-slideshow" v-show="isOpen">
            <v-photoswipe
                :isOpen="isOpen"
                :items="items"
                :options="options"
                @close="hidePhotoSwipe"
            ></v-photoswipe>
        </portal>
    </article>
</template>

<script>
import { PhotoSwipe } from 'v-photoswipe'

export default {
    components: {
        'v-photoswipe': PhotoSwipe,
    },
    props: [],
    mounted () {
        this.items = Array.from(this.$el.getElementsByTagName('img')).map((el, i) => {
            el.attributes['data-index'] = i;
            return {
                index: i,
                src: el.src,
                element: el,
                title: el.nextElementSibling.innerText,
                w: el.naturalWidth,
                h: el.naturalHeight,
            };
        });
        this.items.forEach(item => {
            item.element.addEventListener('click', this.showPhotoSwipe);
        });
    },
    data () {
        return {
            items: [],
            isOpen: false,
            active: null,
            options: {
                index: 0,
            },
        };
    },
    methods: {
        showPhotoSwipe (e) {
            let index = e.target.attributes['data-index'];
            this.isOpen = true
            this.$set(this.options, 'index', index)
        },
        hidePhotoSwipe () {
            this.isOpen = false;
        },
    },
}
</script>
