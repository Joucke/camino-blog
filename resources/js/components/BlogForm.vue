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
                    <div v-show="showModal" class="fixed inset-0 transition-opacity" @click="showModal = false">
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
                                <h3 class="text-lg leading-6 font-medium" id="modal-headline">
                                    Onderschrift
                                </h3>
                                <div class="mt-3">
                                    <textarea autofocus ref="captionArea" v-model="caption" rows="3" class="form-textarea block w-full border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800 transition duration-150 ease-in-out sm:text-sm sm:leading-5" ></textarea>
                                </div>
                                <!-- TODO: align left/right, choose width, alt text -->
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6">
                            <span class="flex w-full rounded-md shadow-sm">
                                <button type="button" class="flex w-full justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-yellow-200 bg-blue-800 hover:bg-blue-900 focus:outline-none focus:border-blue-800 focus:shadow-outline-blue active:bg-blue-900 transition duration-150 ease-in-out sm:text-sm sm:leading-5" @click="addPhotoWithCaption">
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
        document.addEventListener('keyup', this.keyListener);
    },
    destroyed () {
        document.removeEventListener('keyup', this.keyListener);
    },
    methods: {
        keyListener (e) {
            if (e.keyCode == 27) {
                this.showModal = false;
            }
        },
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
