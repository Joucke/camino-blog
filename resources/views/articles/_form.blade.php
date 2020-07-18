<div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
    <label for="title" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
        Title
    </label>
    <div class="mt-1 sm:mt-0 sm:col-span-2">
        <div class="max-w-lg rounded-md shadow-sm">
            <input id="title" name="title" placeholder="title of your article" value="{{ old('title', $article->title) }}" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
        </div>
    </div>
</div>

<div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
    <label for="body" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
        Body
    </label>
    <div class="mt-1 sm:mt-0 sm:col-span-2">
        <div class="max-w-lg flex rounded-md shadow-sm">
            <textarea id="body" name="body" rows="15" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">{{ old('body', $article->body) }}</textarea>
        </div>
    </div>
</div>

<div class="mt-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
    <label for="title" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
        Publish(ed) at
    </label>
    <div class="mt-1 sm:mt-0 sm:col-span-2">
        <div class="max-w-lg rounded-md shadow-sm">
            <input type="datetime" id="published_at" name="published_at" placeholder="published_at of your article" value="{{ old('published_at', $article->published_at) }}" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
        </div>
    </div>
</div>
