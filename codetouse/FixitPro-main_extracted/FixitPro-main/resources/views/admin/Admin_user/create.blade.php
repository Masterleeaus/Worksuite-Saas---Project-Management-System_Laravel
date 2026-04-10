@extends(request()->ajax() ? 'admin.layouts.blank' : 'admin.layouts.app')

@section('title', 'Add New Admin')

@section('content')
@if(!request()->ajax())
<div class="max-w-4xl mx-auto mb-10">
@endif

<div class="bg-white {{ request()->ajax() ? '' : 'shadow-2xl rounded-[2.5rem] overflow-hidden border border-gray-100' }}">
    @if(!request()->ajax())
        <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.acl.users') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-2xl text-gray-400 hover:text-blue-600 shadow-sm border border-gray-100 transition-all">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <h2 class="text-xl font-black text-gray-900 tracking-tight">Create Administrator</h2>
                    <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest mt-0.5">Provision new access level</p>
                </div>
            </div>
        </div>
    @endif

        <form action="{{ route('admin.acl.users.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            
            <!-- Premium Profile Image Section -->
            <div class="flex flex-col items-center gap-5 py-6 bg-gradient-to-b from-gray-50/50 to-white rounded-3xl border border-gray-100 shadow-inner">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-[2rem] blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                    <img id="preview" src="https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff&size=200" 
                         class="relative w-36 h-36 rounded-[2rem] object-cover shadow-2xl border-4 border-white transition-transform duration-500 group-hover:scale-[1.02]">
                    <label for="profile_image" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-300 rounded-[2rem] cursor-pointer backdrop-blur-[2px]">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-camera text-white text-3xl"></i>
                            <span class="text-white text-[10px] font-bold uppercase tracking-wider">Upload Photo</span>
                        </div>
                    </label>
                    <input type="file" name="profile_image" id="profile_image" class="hidden" onchange="previewImage(this)">
                </div>
                <div class="text-center">
                    <h3 class="text-sm font-bold text-gray-800">Administrator Avatar</h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mt-1.5 flex items-center justify-center gap-2">
                        <i class="fas fa-info-circle text-blue-400"></i>
                        Optimized for Web (JPG, PNG)
                    </p>
                </div>
                @error('profile_image') <p class="text-xs text-red-600 font-bold bg-red-50 px-3 py-1 rounded-full">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label for="name" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Full Name</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-user text-sm"></i>
                        </span>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full pl-11 pr-5 py-3.5 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700 placeholder:text-gray-300"
                               placeholder="e.g. John Doe">
                    </div>
                    @error('name') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Email Address</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full pl-11 pr-5 py-3.5 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700 placeholder:text-gray-300"
                               placeholder="john@example.com">
                    </div>
                    @error('email') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="phone" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Phone Number</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-phone-alt text-sm"></i>
                        </span>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full pl-11 pr-5 py-3.5 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700 placeholder:text-gray-300"
                               placeholder="+1 (555) 000-0000">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="role_id" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Administrative Role</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors z-10">
                            <i class="fas fa-user-shield text-sm"></i>
                        </span>
                        <select name="role_id" id="role_id" required
                                class="select2-role w-full pl-11 pr-5 py-3.5 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                            <option value="">Select a System Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->ID }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('role_id') <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="address" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Office Address</label>
                <div class="relative group">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                        <i class="fas fa-location-dot text-sm"></i>
                    </span>
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                           class="w-full pl-11 pr-5 py-4 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700 placeholder:text-gray-300"
                           placeholder="Enter branch or office location...">
                    
                    <!-- Advanced Location Metadata -->
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="city" id="city">
                    <input type="hidden" name="province" id="province">
                    <input type="hidden" name="country" id="country">
                    <input type="hidden" name="postal_code" id="postal_code">
                </div>
            </div>

            <div class="space-y-2">
                <label for="bio" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Manager's Bio</label>
                <div class="relative group">
                    <span class="absolute left-4 top-5 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                        <i class="fas fa-quote-left text-sm"></i>
                    </span>
                    <textarea name="bio" id="bio" rows="3"
                              class="w-full pl-11 pr-5 py-3.5 bg-gray-50/5 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700 placeholder:text-gray-300"
                              placeholder="Short professional summary...">{{ old('bio') }}</textarea>
                </div>
            </div>

            <div class="p-6 bg-blue-50/50 rounded-3xl border border-blue-100/50 space-y-5 relative overflow-hidden group">
                <div class="absolute right-0 top-0 opacity-[0.03] -translate-y-4 translate-x-4">
                    <i class="fas fa-shield-halved text-[120px] transition-transform duration-700 group-hover:rotate-12"></i>
                </div>
                <div class="flex items-center gap-3 text-blue-700">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center text-sm shadow-sm">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em]">Security Protocol</p>
                        <p class="text-[10px] text-blue-400 font-bold">Encrypted via Hash::make()</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                    <div class="space-y-2">
                        <label for="password" class="block text-[10px] font-black text-blue-900/40 uppercase tracking-widest ml-1">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-5 py-3.5 bg-white border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700 placeholder:text-gray-300 shadow-sm"
                               placeholder="••••••••">
                        @error('password') <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-[10px] font-black text-blue-900/40 uppercase tracking-widest ml-1">Confirm Access</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-5 py-3.5 bg-white border border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700 placeholder:text-gray-300 shadow-sm"
                               placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between p-6 bg-gray-50/50 rounded-3xl border border-gray-100 group transition-all hover:bg-white hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="relative inline-flex items-center cursor-pointer" x-data="{ active: true }">
                        <input type="checkbox" name="active_status" id="active_status" value="1" checked class="sr-only peer" @change="active = $el.checked">
                        <div class="w-14 h-7 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600 shadow-inner"></div>
                    </div>
                    <div>
                        <label for="active_status" class="block text-sm font-bold text-gray-700">Account Activation</label>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Directly grant access upon save</p>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-100">
                <a href="{{ route('admin.acl.users') }}" 
                   class="w-full sm:w-auto px-8 py-3.5 text-gray-400 font-black uppercase tracking-widest text-[10px] hover:text-gray-600 hover:bg-gray-50 rounded-2xl transition-all text-center">
                    Discard Account
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-2xl font-black shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-3 text-sm uppercase tracking-wider">
                    <i class="fas fa-plus text-xs"></i> Provision Administrator
                </button>
            </div>
        </form>

@if(!request()->ajax())
</div>
@endif
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) { $('#preview').attr('src', e.target.result); }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function initAutocomplete() {
        const input = document.getElementById('address');
        if (!input || typeof google === 'undefined') return;
        
        const autocomplete = new google.maps.places.Autocomplete(input, {
             fields: ["address_components", "geometry", "formatted_address"],
             types: ["address"]
        });
        
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (!place.geometry) return;

            // Fill coordinates
            $('#latitude').val(place.geometry.location.lat());
            $('#longitude').val(place.geometry.location.lng());

            let city = '';
            let state = '';
            let country = '';
            let postal_code = '';

            for (const component of place.address_components) {
                const type = component.types[0];
                if (type === 'locality') city = component.long_name;
                if (type === 'administrative_area_level_1') state = component.long_name;
                if (type === 'country') country = component.long_name;
                if (type === 'postal_code') postal_code = component.long_name;
            }

            $('#city').val(city);
            $('#province').val(state);
            $('#country').val(country);
            $('#postal_code').val(postal_code);
            
            console.log("📍 Location Synced:", {
                city, state, country, postal_code, 
                lat: place.geometry.location.lat(), 
                lng: place.geometry.location.lng()
            });
        });

        // Prevent form submission on enter key in autocomplete
        google.maps.event.addDomListener(input, 'keydown', function(e) {
            if (e.keyCode === 13 && $('.pac-container:visible').length > 0) {
                e.preventDefault();
            }
        });
    }

    // Immediate execution for AJAX loads
    initAutocomplete();

    // Initialize Select2
    $(document).ready(function() {
        $('.select2-role').select2({
            placeholder: "Select a System Role",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#drawer-content').length ? $('#drawer-content') : $(document.body)
        });
    });
</script>
@endsection
