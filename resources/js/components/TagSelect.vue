<template>
    <div class="mt-1 sm:mt-0 sm:col-span-2 flex items-start">
        <div class="max-w-lg rounded-md shadow-sm w-1/2">
            <select v-model="selectedTags" multiple id="tags" name="tags[]" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                <option v-for="tag in tags" :value="tag.id">{{ tag.title }}</option>
            </select>
        </div>
        <button type="button" @click="showModal = true"
             class="ml-2 h-auto inline-flex justify-center w-1/2 py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out"
        >nieuwe tag toevoegen</button>
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
                                <h3 class="text-lg text-center leading-6 font-medium text-gray-900" id="modal-headline">
                                    Tag toevoegen
                                </h3>
                                <div class="mt-3">
                                    <label for="tag-title" class="block text-sm font-medium leading-5 text-gray-700">Titel</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="tag-title" v-model="newTag.title" placeholder="Titel van de tag" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6">
                            <span class="flex w-full rounded-md shadow-sm">
                                <button type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150 sm:text-sm sm:leading-5" @click="addTag">
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
    props: ['originalSelected'],
    data () {
        return {
            newTag: {
                title: '',
            },
            showModal: false,
            tags: [],
            selectedTags: this.originalSelected,
            searching: false,
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
    },
    methods: {
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
