<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Users List
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
               <div class="block mb-8">
                   <a href="{{ route('users.create') }}" class="btn bg-green-500 hover:bg-green-700 font-bold py-2 px-4 rounded">Add User</a>
               </div>
                <!-- Show success message after product data store in a database -->
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p class="text-sm text-green-600">{{ $message }}</p>
                    </div>
                @endif
                <!-- Show error message if product data not stored in a database -->
                @if ($message = Session::get('error'))
                    <div class="">
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    </div>
                @endif
               <div class="flex flex-col">
                  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                     <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        @if (!$users->isEmpty())
                        <table class="min-w-full divide-y divide-gray-200">

                           <thead class="bg-gray-50">
                              <tr>
                              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Name
                              </th>
                              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Information
                              </th>
                              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                 Status
                              </th>
                              <th scope="col" class="relative px-6 py-3">
                                 <span class="sr-only">Actions</span>
                              </th>
                              </tr>
                           </thead>
                           <tbody class="bg-white divide-y divide-gray-200">
                              @foreach($users as $user)
                              <tr>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                       <div class="flex-shrink-0 h-10 w-10">
                                       @if(isset($user->profile_photo_path) && $user->profile_photo_path != NULL )
                                       <img class="h-10 w-10 rounded-full" src="{{ $user->profile_photo_path ? asset('assets/images/users/'.$user->profile_photo_path):asset('assets/images/noimage.jpg') }}" alt="">
                                       @else
                                       <img class="h-10 w-10 rounded-full" src="{{asset('assets/images/noimage.jpg') }}" alt="">
                                       @endif
                                       </div>
                                       <div class="ml-4">
                                       <div class="text-sm font-medium text-gray-900">
                                       {{ $user->first_name }} {{ $user->last_name }}
                                       </div>
                                       <div class="text-sm text-gray-500">
                                          {{ $user->email }}
                                       </div>
                                       <div class="text-sm text-gray-500">
                                          {{ $user->mobile }}
                                       </div>
                                       </div>
                                    </div>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->address }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->birth_date }}</div>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                       Active
                                    </span>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('users.show', $user->id) }}" class="text-blue-600 hover:text-blue-900 mb-2 mr-2">View</a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 mb-2 mr-2">Edit</a>
                                    <form class="inline-block" action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                       <input type="hidden" name="_method" value="DELETE">
                                       <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                       <input type="submit" class="text-red-600 hover:text-red-900 mb-2 mr-2" value="Delete">
                                    </form>
                                 </td>
                                 @endforeach
                              </tr>
                           </tbody>
                        </table>
                        @else
                            <h4 class="text-center">No User's Records Found.</h4>
                         @endif
                        </div>
                     </div>
                  </div>
               </div>
        </div>
    </div>
</x-app-layout>
