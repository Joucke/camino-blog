<template>
    <div>
        <slot />
        <portal to="blog-form-modal">
            <div v-show="showModal" class="z-1000 fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center">
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-class="opacity-100"
                    leave-to-class="opacity-0"
                    >
                    <div v-show="showModal" class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                </transition>

                <transition
                    enter-active-class="ease-out duration-300"
                    enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                    <div class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-sm sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                        <div>
                            <div class="mt-3 text-center sm:mt-5">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                    Onderschrift
                                </h3>
                                <div class="mt-2">
                                    <textarea autofocus ref="captionArea" v-model="caption" rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6">
                            <span class="flex w-full rounded-md shadow-sm">
                                <button type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150 sm:text-sm sm:leading-5" @click="addPhotoWithCaption">
                                    Toevoegen
                                </button>
                            </span>
                        </div>
                    </div>
                </transition>
            </div>
        </portal>
    </div>
</template>

<script>
export default {
    props: [],
    data () {
        return {
            textarea: null,
            showModal: false,
            caption: '',
            imageUrl: '',
        };
    },
    mounted () {
        this.$on('select', this.select);
        this.$on('images', this.setImages);
        this.textarea = this.$children.find(el => el.textarea());
        this.imagePreview = this.$children.find(el => el.imagePreview);
    },
    methods: {
        addPhotoWithCaption() {
            this.textarea.text += `

![een foto](${this.imageUrl})
*${this.caption}*`;
            this.caption = '';
            this.imageUrl = '';
            this.showModal = false;
        },
        select(data) {
            this.imageUrl = data.url;
            this.showModal = true;
            this.$nextTick(() => {
                this.$refs.captionArea.focus();
            });
        },
        setImages(data) {
            this.imagePreview.images = data.images;
        },
    },
}
</script>
