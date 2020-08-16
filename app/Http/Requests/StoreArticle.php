<?php

namespace App\Http\Requests;

use App\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreArticle extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title)
        ]);
    }

    public function store(): Article
    {
        $article = $this->user()->articles()->create(
            $this->validated()
        );

        return $this->syncTags(
            $this->syncLocations($article)
        );
    }

    protected function syncLocations(Article $article): Article
    {
        if ($this->has('locations')) {
            $locations = $this->input('locations');
            $article->locations()->sync($locations);
        }
        return $article;
    }

    protected function syncTags(Article $article): Article
    {
        if ($this->has('tags')) {
            $tags = $this->input('tags');
            $article->tags()->sync($tags);
        }
        return $article;
    }

    public function update(Article $article): Article
    {
        $article->update($this->validated());

        return $this->syncTags(
            $this->syncLocations($article)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|unique:articles',
            'body' => 'required',
            'published_at' => 'nullable|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        // TODO: move to translation file
        return [
            'required' => ':attribute is verplicht.',
            'unique' => ':attribute moet uniek zijn.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        // TODO: move to translation file
        return [
            'title' => 'Titel',
            'body' => 'Inhoud',
        ];
    }
}
