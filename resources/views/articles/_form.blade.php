<div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
    <label for="title" class="block text-sm font-medium leading-5 sm:mt-px sm:pt-2">
        Titel
    </label>
    <div class="mt-1 sm:mt-0 sm:col-span-2">
        <div class="max-w-lg rounded-md shadow-sm">
            <input id="title" name="title" placeholder="Titel van de blog" value="{{ old('title', $article->title) }}" class="form-input block w-full border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
        </div>
    </div>
</div>

<div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
    <label for="body" class="block text-sm font-medium leading-5 sm:mt-px sm:pt-2">
        Inhoud
    </label>
    <div class="mt-1 sm:mt-0 sm:col-span-2">
        <div class="max-w-lg flex rounded-md shadow-sm">
            <blog-textarea id="body" name="body" rows="15" class="form-textarea block w-full border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800 transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="{{ old('body', $article->body) }}"></blog-textarea>
        </div>
    </div>
</div>

<div class="mt-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
    <label for="published_at" class="block text-sm font-medium leading-5 sm:mt-px sm:pt-2">
        Publiceren op
    </label>
    <div class="mt-1 sm:mt-0 sm:col-span-2">
        <div class="max-w-lg rounded-md shadow-sm">
            <input type="datetime" id="published_at" name="published_at" placeholder="yyyy-mm-dd hh:mm:ss" value="{{ old('published_at', $article->published_at) }}" class="form-input block w-full border-blue-800 focus:outline-none focus:shadow-outline-blue focus:border-blue-800 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
        </div>
    </div>
</div>

<location-select
    class="mt-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5"
    :original-selected="{{ $article->locations->pluck('id') }}">
</location-select>

<tag-select
    class="mt-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5"
    :original-selected="{{ $article->tags->pluck('id') }}">
</tag-select>
