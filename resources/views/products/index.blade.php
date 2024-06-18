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
        <div class="w-full flex flex-row justify-between">

            <h1 class="text-xl text-bold text-white">Products</h1>

            <p class="p-3 bg-blue-300 hover:bg-blue-400 transition cursor-pointer">
                <a href="{{ route('products.create') }}">Create Product</a>
            </p>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        id
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Description
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($products as $product)
                    <tr id="{{ $product->id}}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $product->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $product->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a id="edit-{{ $product->id }}" href="{{ route('products.edit', $product) }}"
                                class="text-indigo-600 hover:text-indigo-900 px-4 p-2 rounded-lg border-2 border-indigo-600 hover:border-indigo-900 transition">Edit</a>

                            <form method="POST" action="{{ route('products.destroy', $product->id) }}"
                                class="inline-block">
                                @csrf
                                @method('DELETE')

                                <button name="submit" type="submit" id="delete-{{ $product->id }}"
                                    class="bg-red-600 hover:bg-red-900 text-white transition px-4 p-2 ml-2 rounded-lg">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                @if ($products->isEmpty())
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-800 italic">
                            No products found.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>
@endsection
