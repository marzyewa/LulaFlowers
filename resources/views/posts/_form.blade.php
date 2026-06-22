@php($editing = isset($post))

@if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="font-semibold">Проверьте заполнение формы:</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <label for="title" class="form-label">Заголовок</label>
    <input id="title" name="title" type="text" value="{{ old('title', $post->title ?? '') }}" class="form-control" required maxlength="255">
</div>

<div>
    <label for="category_id" class="form-label">Категория</label>
    <select id="category_id" name="category_id" class="form-control" required>
        <option value="">Выберите категорию</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label for="content" class="form-label">Текст статьи</label>
    <textarea id="content" name="content" rows="13" class="form-control" required minlength="20">{{ old('content', $post->content ?? '') }}</textarea>
    <p class="mt-2 text-xs text-stone-500">Минимум 20 символов.</p>
</div>

<div>
    <label for="image" class="form-label">Изображение</label>
    <input id="image" name="image" type="file" accept="image/*" class="form-control">
    <p class="mt-2 text-xs text-stone-500">JPG, PNG или WebP, не более 2 МБ.</p>
    @if ($editing && $post->image)
        <img src="{{ asset($post->image) }}" alt="" class="mt-4 h-32 w-48 rounded-xl object-cover">
    @endif
</div>

<div class="flex flex-wrap gap-3 pt-2">
    <button type="submit" class="button-primary">{{ $editing ? 'Сохранить изменения' : 'Опубликовать статью' }}</button>
    <a href="{{ $editing ? route('posts.show', $post) : route('posts.index') }}" class="button-secondary">Отмена</a>
</div>
