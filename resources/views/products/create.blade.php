@extends('layouts.product')

@section('content')
<div class="flex flex-col gap-5 max-w-[1440px] mx-auto p-5 border-2 border-white">
    <div>
        @if (session('status'))
        <div class="bg-amber-500 flex text-center justify-center items-center p-4 w-full text-white">
            {{ session('status') }}
        </div>
        @endif
    </div>

    <a href="{{ route('products.index') }}" class="text-blue-300 border-2 border-blue-300 w-20 p-2 text-center cursor-pointer hover:bg-blue-300 hover:text-black transition">Back</a>
    <h1 class="text-xl text-bold text-white">Create Products</h1>

    <form method="POST" action="{{ route('products.store') }}" class="mt-5">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-white">Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="border border-gray-300 rounded-md px-3 py-2 w-full">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-white">Description:</label>
            <textarea name="description" id="description" required class="border border-gray-300 rounded-md px-3 py-2 w-full">{{ old('description') }}</textarea>
        </div>

        <button name="submit" type="submit" class="p-3 px-4 bg-blue-300 hover:bg-blue-400 transition cursor-pointer">Create</button>
    </form>

</div>
@endsection
