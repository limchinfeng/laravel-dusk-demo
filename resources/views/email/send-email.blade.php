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

    <h1 class="text-xl text-bold text-white">Email Sending Form</h1>
    <form method="POST" action="{{ route('send-email.send') }}"  class="mt-5">
        @csrf

        <div class="mb-4">
            <label for="recipient" class="block text-white">Recipient:</label>
            <input type="text" name="recipient" id="recipient" value="{{ old('recipient') }}" required class="border border-gray-300 rounded-md px-3 py-2 w-full">
        </div>
        <div class="mb-4">
            <label for="subject" class="block text-white">Subject:</label>
            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required class="border border-gray-300 rounded-md px-3 py-2 w-full">
        </div>
        <div class="mb-4">
            <label for="content" class="block text-white">Content:</label>
            <textarea type="text" name="content" id="content" required class="border border-gray-300 rounded-md px-3 py-2 w-full">{{ old('content') }}</textarea>
        </div>

        <button name="submit" type="submit" class="p-3 px-4 bg-blue-300 hover:bg-blue-400 transition cursor-pointer">Send Email</button>
    </form>


    </div>
@endsection
