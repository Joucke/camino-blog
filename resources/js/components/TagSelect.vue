<template>
    <div>
        <div class="flex justify-between items-end">
            <label for="tags" class="block text-sm font-medium leading-5">
                Tag(s)
            </label>
            <button type="button" @click="showModal = true" class="flex w-6 h-6 items-center justify-center rounded font-bold text-lg bg-blue-800 text-yellow-200 py-2 px-2">+</button>
        </div>
        <div class="mt-1 sm:mt-0 sm:col-span-2">
            <div class="max-w-lg rounded-md shadow-sm">
                <select v-model="selectedTags" multiple id="tags" name="tags[]" class="form-multiselect block w-full border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800  transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                    <option v-for="tag in tags" :value="tag.id">{{ tag.title }}</option>
                </select>
            </div>
            <portal to="add-tag-modal">
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
                                <div class="mt-3 sm:mt-5">
                                    <h3 class="text-lg text-center leading-6 font-medium" id="modal-headline">
                                        Tag toevoegen
                                    </h3>
                                    <div class="mt-3">
                                        <label for="tag-title" class="block text-sm font-medium leading-5">Titel</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input type="text" id="tag-title" v-model="newTag.title" placeholder="Titel van de tag" class="form-input block w-full border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <span class="flex w-full rounded-md shadow-sm">
                                    <button type="button" class="flex w-full justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-yellow-200 bg-blue-800 hover:bg-blue-900 focus:outline-none focus:border-blue-800 focus:shadow-outline-blue active:bg-blue-900 transition duration-150 ease-in-out sm:text-sm sm:leading-5" @click="addTag">
                                        Toevoegen
                                    </button>
                                </span>
                            </div>
                        </div>
                    </transition>
                </div>
            </portal>
        </div>
    </div>
</template>

<script>
export default {
    props: ['originalSelected'],
    data () {
        return {
            newTag: {
                title: '',
            },
            showModal: false,
            tags: [],
            selectedTags: this.originalSelected,
        };
    },
    mounted () {
        fetch('/tags', {
            headers: {
                'accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => this.tags = data);
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
        addTag () {
            let csrf = document.querySelector('meta[name="csrf-token"]').content;

            fetch('/tags', {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': csrf,
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify(this.newTag),
            })
                .then(response => response.json())
                .then((data) => {
                    this.tags = data;
                    this.showModal = false;
                });
        },
    },
}
</script>
