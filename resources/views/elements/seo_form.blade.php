<div class="row">
    <div class="col-xl-4 col-md-6 col-12">
        <div class="mb-1">
            <x-input-label for="meta_title" class="form-label" :value="__('Meta Title')" />
            <x-text-input id="meta_title" class="form-control" type="text" name="meta_title" :value="old('meta_title', $category->meta_title??null)" />
            <x-input-error :messages="$errors->first('meta_title')" class="mt-2" />
        </div>
    </div>
    <div class="col-xl-4 col-md-6 col-12">
        <div class="mb-1">
            <x-input-label for="seo_keyword" class="form-label" :value="__('Seo Url')" />
            <x-text-input id="seo_keyword" class="form-control" type="text" name="seo_keyword" :value="old('seo_keyword', $category->seo_keyword??null)" />
            <x-input-error :messages="$errors->first('seo_keyword')" class="mt-2" />
        </div>
    </div>
    <div class="col-xl-4 col-md-6 col-12">
        <div class="mb-1">
            <x-input-label for="meta_keyword" class="form-label" :value="__('Meta Keyword')" />
            <x-text-input id="meta_keyword" class="form-control" type="text" name="meta_keyword" :value="old('meta_keyword', $category->meta_keyword??null)" />
            <x-input-error :messages="$errors->first('meta_keyword')" class="mt-2" />
        </div>
    </div>
    <div class="col-xl-4 col-md-6 col-12">
        <div class="mb-1">
            <x-input-label for="meta_description" class="form-label" :value="__('Meta Description')" />
            <x-textarea id="meta_description" class="form-control" name="meta_description">{{old('meta_description', $category->meta_description??null)}}</x-textarea>
            <x-input-error :messages="$errors->first('meta_description')" class="mt-2" />
        </div>
    </div>
    <div class="col-xl-4 col-md-6 col-12">
        <div class="mb-1">
            <x-input-label for="schema_tag" class="form-label" :value="__('Schema Tag')" />
            <x-textarea id="schema_tag" class="form-control" name="schema_tag">{{old('schema_tag', $category->schema_tag??null)}}</x-textarea>
            <x-input-error :messages="$errors->first('schema_tag')" class="mt-2" />
        </div>
    </div>
</div>