<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create User
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">Back to list</a>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('users.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="first_name" class="block font-medium text-sm text-gray-700">First Name <span class="text-red-600">*</span></label>
                            <input type="text" name="first_name" id="first_name" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('first_name', '') }}" required autofocus />
                            @error('first_name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="last_name" class="block font-medium text-sm text-gray-700">Last Name <span class="text-red-600">*</span></label>
                            <input type="text" name="last_name" id="last_name" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('last_name', '') }}" required />
                            @error('last_name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="email" class="block font-medium text-sm text-gray-700">Email <span class="text-red-600">*</span></label>
                            <input type="email" name="email" id="email" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('email', '') }}" required />
                            @error('email')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="mobile" class="block font-medium text-sm text-gray-700">Mobile <span class="text-red-600">*</span></label>
                            <input type="number" name="mobile" id="mobile" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('mobile', '') }}" required />
                            @error('mobile')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="birth_date" class="block font-medium text-sm text-gray-700">Date Of Birth <span class="text-red-600">*</span></label>
                            <input type="date" name="birth_date" id="birth_date" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('birth_date', '') }}" placeholder="dd-mm-yyyy"  required/>
                            @error('birth_date')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="address" class="block font-medium text-sm text-gray-700">Address</label>
                            <textarea name="address" id="address" class="form-input rounded-md shadow-sm mt-1 block w-full" >{{ old('address', '') }}</textarea>
                            @error('address')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="image" class="block font-medium text-sm text-gray-700">Profile Image <span class="text-red-600">*</span></label>
                            <input type="file" name="image" class="img-upload" id="image-upload" required>
                            @error('image')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="password" class="block font-medium text-sm text-gray-700">Password <span class="text-red-600">*</span></label>
                            <input type="password" name="password" id="password" class="form-input rounded-md shadow-sm mt-1 block w-full" required />
                            @error('password')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="confirm-password" class="block font-medium text-sm text-gray-700">Confirm Password <span class="text-red-600">*</span></label>
                            <input type="password" name="confirm-password" id="confirm-password" class="form-input rounded-md shadow-sm mt-1 block w-full" required />
                            @error('confirm-password')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>