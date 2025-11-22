
@extends('admin.app')

@section('content')
<!-- Include Bootstrap JS CDN for consistency (though not required for modals anymore) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Local Bus Owners</h3>
            <p class="text-sm text-gray-600 mt-1">Manage all registered local bus owners in the system</p>
        </div>
        <button type="button"
                id="toggleCreateForm"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Local Bus Owner
        </button>
    </div>

    <!-- Flash messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Create Form (Initially Hidden) -->
    <div id="createLocalBusOwnerForm" class="bg-white rounded-lg shadow-md p-6 mb-6 hidden max-w-lg mx-auto">
        <h5 class="text-lg font-semibold text-gray-900 mb-4">Create Local Bus Owner</h5>
        <form action="{{ route('local.bus.owners.create') }}" method="POST">
            @csrf
            <!-- Hidden field -->
            <input type="hidden" name="modal" value="create">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password"
                           class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact (Optional)</label>
                    <input type="text" name="contact" value="{{ old('contact') }}"
                           class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 @error('contact') border-red-500 @enderror">
                    @error('contact') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex justify-end mt-4 space-x-2">
                <button type="button" id="cancelCreateForm" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">Create</button>
            </div>
        </form>
    </div>

    <!-- Edit Form (Initially Hidden, Populated Dynamically) -->
    @forelse ($localBusOwners as $owner)
        <div id="editLocalBusOwnerForm{{ $owner->id }}" class="bg-white rounded-lg shadow-md p-6 mb-6 hidden max-w-lg mx-auto">
            <h5 class="text-lg font-semibold text-gray-900 mb-4">Edit Local Bus Owner</h5>
            <form action="{{ route('local.bus.owners.update', $owner) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Hidden fields -->
                <input type="hidden" name="modal" value="edit">
                <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                <input type="hidden" name="id" value="{{ $owner->id }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $owner->name) }}"
                               class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $owner->email) }}"
                               class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact (Optional)</label>
                        <input type="text" name="contact" value="{{ old('contact', $owner->contact) }}"
                               class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 @error('contact') border-red-500 @enderror">
                        @error('contact') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror" required>
                            <option value="accept" {{ old('status', $owner->status) === 'accept' ? 'selected' : '' }}>Active</option>
                            <option value="cancel" {{ old('status', $owner->status) === 'cancel' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" class="cancelEditForm" data-owner-id="{{ $owner->id }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">Update</button>
                </div>
            </form>
        </div>
    @empty
    @endforelse

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="rounded-full bg-indigo-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Owners</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $localBusOwners->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Owners</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $localBusOwners->where('status', 'accept')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="rounded-full bg-red-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Inactive Owners</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $localBusOwners->where('status', 'cancel')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($localBusOwners as $owner)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $owner->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $owner->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $owner->contact ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $owner->status === 'accept' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($owner->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('local_admin.bus', ['id' => $owner->id]) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-6 6m0-2a4 4 0 01-4-4m5 0a2 2 0 012 2h2a4 4 0 00-4-4v-1a3 3 0 300-3-3H3m6 1a9 9 0 1012-3m-10 10v4m0 0l3-3m-3 3l-3-3"></path>
                                        </svg>
                                        Permissions
                                    </a>
                                    <button
                                        type="button"
                                        class="toggleEditForm text-indigo-600 hover:text-indigo-900 flex items-center"
                                        data-owner-id="{{ $owner->id }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        Edit
                                        </button>
                                    <form action="{{ route('local.bus.owners.destroy', $owner) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 flex items-center"
                                                onclick="return confirm('Are you sure you want to delete this local bus owner?')">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-4 text-gray-500 text-lg">No local bus owners found</p>
                                <p class="text-gray-400">Get started by adding your first local bus owner</p>
                                <button type="button"
                                        id="toggleCreateFormEmpty"
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                    Add Local Bus Owner
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JS: Toggle Create/Edit Forms and Handle Validation Errors -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle Create Form
    const createForm = document.getElementById('createLocalBusOwnerForm');
    const toggleCreateButton = document.getElementById('toggleCreateForm');
    const toggleCreateButtonEmpty = document.getElementById('toggleCreateFormEmpty');
    const cancelCreateButton = document.getElementById('cancelCreateForm');

    if (toggleCreateButton) {
        toggleCreateButton.addEventListener('click', function () {
            // Hide all edit forms
            document.querySelectorAll('[id^="editLocalBusOwnerForm"]').forEach(form => form.classList.add('hidden'));
            // Toggle create form
            createForm.classList.toggle('hidden');
        });
    }

    if (toggleCreateButtonEmpty) {
        toggleCreateButtonEmpty.addEventListener('click', function () {
            // Hide all edit forms
            document.querySelectorAll('[id^="editLocalBusOwnerForm"]').forEach(form => form.classList.add('hidden'));
            // Toggle create form
            createForm.classList.toggle('hidden');
        });
    }

    if (cancelCreateButton) {
        cancelCreateButton.addEventListener('click', function () {
            createForm.classList.add('hidden');
        });
    }

    // Toggle Edit Forms
    const editButtons = document.querySelectorAll('.toggleEditForm');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const ownerId = this.getAttribute('data-owner-id');
            const editForm = document.getElementById('editLocalBusOwnerForm' + ownerId);
            // Hide create form and other edit forms
            createForm.classList.add('hidden');
            document.querySelectorAll('[id^="editLocalBusOwnerForm"]').forEach(form => form.classList.add('hidden'));
            // Show the selected edit form
            editForm.classList.remove('hidden');
        });
    });

    // Cancel Edit Forms
    const cancelEditButtons = document.querySelectorAll('.cancelEditForm');
    cancelEditButtons.forEach(button => {
        button.addEventListener('click', function () {
            const ownerId = this.getAttribute('data-owner-id');
            const editForm = document.getElementById('editLocalBusOwnerForm' + ownerId);
            editForm.classList.add('hidden');
        });
    });

    // Handle validation errors
    const hasErrors = @json($errors->any());
    const modalFromOld = @json(old('modal') ?? session('modal') ?? null);
    const ownerIdFromOld = @json(old('owner_id') ?? session('owner_id') ?? old('id') ?? null);
    const errorKeys = @json($errors->keys());

    if (!hasErrors) return;

    // Show Create form for create-related errors
    if (modalFromOld === 'create' || errorKeys.includes('password') || errorKeys.includes('password_confirmation')) {
        document.querySelectorAll('[id^="editLocalBusOwnerForm"]').forEach(form => form.classList.add('hidden'));
        createForm.classList.remove('hidden');
        return;
    }

    // Show Edit form for edit-related errors
    if (modalFromOld === 'edit' && ownerIdFromOld) {
        createForm.classList.add('hidden');
        document.querySelectorAll('[id^="editLocalBusOwnerForm"]').forEach(form => form.classList.add('hidden'));
        const editForm = document.getElementById('editLocalBusOwnerForm' + ownerIdFromOld);
        if (editForm) editForm.classList.remove('hidden');
        return;
    }

    // Fallback: try owner id for Edit form
    if (ownerIdFromOld) {
        createForm.classList.add('hidden');
        document.querySelectorAll('[id^="editLocalBusOwnerForm"]').forEach(form => form.classList.add('hidden'));
        const editForm = document.getElementById('editLocalBusOwnerForm' + ownerIdFromOld);
        if (editForm) editForm.classList.remove('hidden');
    }
});
</script>
@endsection