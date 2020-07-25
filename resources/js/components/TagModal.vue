<template>
    <div v-show="show" class="z-1000 fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center">
        <transition
            enter-active-class="ease-out duration-300"
            enter-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200"
            leave-class="opacity-100"
            leave-to-class="opacity-0"
            >
            <div v-show="show" class="fixed inset-0 transition-opacity" @click="close">
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
            <form class="block bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-sm sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline" :action="tag.url" method="POST" @submit="updateTag">
                <div>
                    <div class="mt-3 sm:mt-5">
                        <h3 class="text-lg text-center leading-6 font-medium" id="modal-headline">
                            Tag bewerken
                        </h3>
                        <div class="mt-3">
                            <label for="tag-title" class="block text-sm font-medium leading-5">Titel</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input ref="focusInput" type="text" id="tag-title" v-model="newTag.title" placeholder="Titel van de tag" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <span class="flex w-full rounded-md shadow-sm">
                        <button class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Bijwerken
                        </button>
                    </span>
                </div>
            </form>
        </transition>
    </div>
</template>

<script>
export default {
    props: ['tag', 'show'],
    data () {
        return {
            newTag: {
                title: this.tag.title,
                pivot: this.tag.pivot,
            },
        };
    },
    mounted () {
        this.$refs.focusInput.focus().click();
        document.addEventListener('keyup', this.keyListener);
    },
    destroyed () {
        document.removeEventListener('keyup', this.keyListener);
    },
    methods: {
        close () {
            this.$emit('close');
        },
        keyListener (e) {
            if (e.keyCode == 27) {
                this.$emit('close');
            }
        },
        updateTag (e) {
            e.preventDefault();
            let csrf = document.querySelector('meta[name="csrf-token"]').content;

            fetch(this.tag.url, {
                method: 'PATCH',
                headers: {
                  'X-CSRF-TOKEN': csrf,
                  'Content-Type': 'application/json',
                  accept: 'application/json',
                },
                body: JSON.stringify(this.newTag),
            })
                .then(response => response.json())
                .then((data) => {
                    this.$emit('update', {tag: data});
                    this.$emit('close');
                });
        },
    },
}
</script>
