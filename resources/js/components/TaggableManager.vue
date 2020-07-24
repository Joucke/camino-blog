<template>
    <div>
        <slot v-bind:taggable="taggable"></slot>
        <portal to="manage-location-modal" v-if="taggable.url.includes('/locations/')">
            <location-modal
                v-if="showModal"
                :show="showModal"
                @close="showModal = false"
                @update="({location}) => taggable = location"
                :location="taggable">
            </location-modal>
        </portal>
        <portal to="manage-tag-modal" v-if="taggable.url.includes('/tags/')">
            <tag-modal
                v-if="showModal"
                :show="showModal"
                @close="showModal = false"
                @update="({tag}) => taggable = tag"
                :tag="taggable">
            </tag-modal>
        </portal>
    </div>
</template>

<script>
import LocationModal from './LocationModal';
import TagModal from './TagModal';
export default {
    props: ['tag'],
    components: {
        LocationModal,
        TagModal,
    },
    mounted () {
        this.$el.addEventListener('touchstart', this.touching);
        this.$el.addEventListener('mousedown', this.touching);
        this.$el.addEventListener('touchend', this.touchend);
        this.$el.addEventListener('mouseup', this.touchend);
        this.$el.addEventListener('click', this.followLink);
    },
    data () {
        return {
            taggable: this.tag,
            touchTimeout: null,
            showModal: false,
        };
    },
    methods: {
        followLink (e) {
            if (this.showModal) {
                e.preventDefault();
            }
        },
        openModal () {
            this.showModal = true;
        },
        touching (e) {
            e.preventDefault();
            this.touchTimeout = setTimeout(this.openModal, 500);
        },
        touchend (e) {
            if (this.touchTimeout) {
                clearTimeout(this.touchTimeout);
                e.target.click();
            }
            e.preventDefault();
        },
    },
}
</script>
