<x-app-layout>
    <x-slot name="title">Новая статья</x-slot>
    <x-slot name="header">
        <p class="eyebrow">Редакция</p>
        <h1 class="section-title">Новая статья</h1>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="card space-y-6 p-7 sm:p-10">
            @csrf
            @include('posts._form')
        </form>
    </div>
</x-app-layout>
