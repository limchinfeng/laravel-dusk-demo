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

    @if ($errors->any())
    <div class="bg-red-600 text-white text-center p-4 w-full">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <a href="{{ route('products.index') }}" class="text-blue-300 border-2 border-blue-300 w-20 p-2 text-center cursor-pointer hover:bg-blue-300 hover:text-black transition">Back</a>
    <h1 class="text-xl text-bold text-white">Edit Product</h1>

    <form method="POST" action="{{ route('products.update', $product->id) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-white">Name:</label>
            <input type="text" name="name" id="name"  class="border border-gray-300 rounded-md px-3 py-2 w-full" value="{{ old('name', $product->name) }}" >
            @if($errors->has('name'))
                <div class="error">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <div>
            <label for="description" class="block text-white">Description:</label>
            <textarea name="description" id="description" class="border border-gray-300 rounded-md px-3 py-2 w-full" >{{ old('description', $product->description) }}</textarea>
            @if($errors->has('description'))
                <div class="error">{{ $errors->first('description') }}</div>
            @endif
        </div>

        <button name="submit" type="submit" class="p-3 px-4 bg-blue-300 hover:bg-blue-400 transition cursor-pointer">Update</button>
    </form>


    </div>
@endsection
