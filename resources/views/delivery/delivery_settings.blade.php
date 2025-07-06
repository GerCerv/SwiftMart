<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SwiftMart Delivery - Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #4CAF50;
            --light-green: #8BC34A;
            --dark-green: #388E3C;
            --accent-orange: #FF9800;
            --light-bg: #F9F9F9;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .header-bg {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
        }
        
        .btn-primary {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
        }
        
        .btn-outline-primary {
            color: var(--primary-green);
            border-color: var(--primary-green);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-green);
            color: white;
        }
        
        .status-active {
            background-color: #D4EDDA;
            color: #155724;
        }
        
        .status-inactive {
            background-color: #FFF3CD;
            color: #856404;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="header-bg text-white shadow-md">
            <div class="container mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-truck text-2xl"></i>
                        <h1 class="text-xl font-bold">SwiftMart Delivery</h1>
                    </div>

                    <!-- Profile dropdown -->
                    <div class="relative">
                        <button class="flex items-center space-x-2 focus:outline-none" id="profile-dropdown-btn">
                            <img src="{{ $deliveryMan->profile_image ? asset('storage/'.$deliveryMan->profile_image) : asset('images/default-profile.jpg') }}" 
                                 alt="{{ $deliveryMan->name }}" 
                                 class="w-8 h-8 rounded-full object-cover border-2 border-white">
                            
                        </button>
                        
                        <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" id="profile-dropdown">
                            <div class="px-4 py-2 border-b">
                                <p class="font-medium text-gray-800">{{ $deliveryMan->name }}</p>
                                <p class="text-xs text-gray-500">Delivery Partner</p>
                            </div>
                            
                            <div class="px-4 py-2 hover:bg-gray-100">
                                <label class="flex items-center cursor-pointer">
                                    <span class="mr-2 text-gray-700"><i class="fas fa-power-off"></i></span>
                                    <input type="checkbox" class="toggle" {{ $deliveryMan->is_active ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">Active</span>
                                </label>
                            </div>
                            
                            <a href="{{ route('delivery.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-t border-gray-200">
                                <i class="fas fa-home mr-2"></i>Home
                            </a>
                            <!-- Settings Link -->
                            <a href="{{ route('delivery.settings') }}" 
                            class="block px-4 py-2 hover:bg-gray-100 text-gray-700 border-b border-gray-200">
                                <i class="fas fa-cog mr-2"></i>Settings
                            </a>
                            
                            <form action="{{ route('delivery.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Status indicator -->
                <div class="mt-3 flex items-center justify-between bg-white bg-opacity-20 p-2 rounded-lg">
                    <span class="text-sm">Delivery Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $deliveryMan->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $deliveryMan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row gap-6 max-w-7xl mx-auto">
                <!-- Profile Settings Card -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-green-600 to-green-500 px-6 py-4">
                            <h2 class="text-xl font-semibold text-white">Profile Settings</h2>
                            <p class="text-green-100 text-sm mt-1">Update your account information</p>
                        </div>

                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 mx-6 mt-4 rounded">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 text-green-500">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Card Body -->
                        <div class="p-6">
                            <form method="POST" action="{{ route('delivery.settings.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="space-y-5">
                                    <!-- Profile Image -->
                                    <div class="space-y-4">
                                        <div class="relative group">
                                            @if($deliveryMan->profile_image)
                                                <img src="{{ asset('storage/' . $deliveryMan->profile_image) }}" 
                                                    class="w-32 h-32 mx-auto rounded-full object-cover border-4 border-white shadow-lg"
                                                    alt="Profile Image">
                                            @else
                                                <div class="w-32 h-32 mx-auto rounded-full bg-gray-200 flex items-center justify-center border-4 border-white shadow-lg">
                                                    <i class="fas fa-user text-4xl text-gray-400"></i>
                                                </div>
                                            @endif
                                            
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <label class="cursor-pointer bg-black bg-opacity-50 rounded-full p-3">
                                                    <i class="fas fa-camera text-white text-xl"></i>
                                                    <input type="file" id="profile_image" name="profile_image" class="hidden" onchange="previewImage(this)">
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <label for="profile_image" class="inline-flex items-center px-4 py-2 bg-white border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <i class="fas fa-camera mr-2"></i>
                                                Change Photo
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Name Field -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <div class="mt-1">
                                            <input type="text" id="name" name="name" value="{{ old('name', $deliveryMan->name) }}"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('name') border-red-500 @enderror"
                                                required>
                                            @error('name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email Field -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                        <div class="mt-1">
                                            <input type="email" id="email" name="email" value="{{ old('email', $deliveryMan->email) }}"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('email') border-red-500 @enderror"
                                                required>
                                            @error('email')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Phone Field -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <div class="mt-1">
                                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $deliveryMan->phone) }}"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('phone') border-red-500 @enderror"
                                                required>
                                            @error('phone')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Password Field -->
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                        <div class="mt-1">
                                            <input type="password" id="password" name="password"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('password') border-red-500 @enderror"
                                                placeholder="Leave blank to keep current">
                                            @error('password')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                                    </div>

                                    <!-- Confirm Password Field -->
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                        <div class="mt-1">
                                            <input type="password" id="password_confirmation" name="password_confirmation"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                placeholder="Confirm your new password">
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-4">
                                        <button type="submit" class="w-full inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="fas fa-save mr-2"></i> Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delivery History Card -->
                <div class="w-full md:w-2/3">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-green-600 to-green-500 px-6 py-4">
                            <h2 class="text-xl font-semibold text-white">Delivery History</h2>
                            <p class="text-green-100 text-sm mt-1">View your past deliveries</p>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="w-full table-auto">
                                    <h2 class="text-lg font-semibold mb-4">Delivered Items</h2>
                                    <thead class="bg-green-100">
                                        <tr>
                                            <th class="py-3 px-4 text-left">Order ID</th>
                                            <th class="py-3 px-4 text-left">Product Name</th>
                                            <th class="py-3 px-4 text-left">Customer</th>
                                            <th class="py-3 px-4 text-left">Status</th>
                                            <th class="py-3 px-4 text-left">Total</th>
                                            <th class="py-3 px-4 text-left">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($deliveredItems as $item)
                                            <tr>
                                                <td class="px-4 py-2"># {{ $item->order->id }}</td>
                                                <td class="px-4 py-2">{{ $item->product->name }}</td> 
                                                <td class="px-4 py-2">{{ ucfirst($item->order->user->name) }}</td>
                                                <td class="px-4 py-2">{{ $item->status}}</td>
                                                <td class="px-4 py-2">{{ $item->total_price }}</td>
                                                <td class="px-4 py-2">{{ $item->created_at ?? 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-4 py-2 text-center text-gray-500">No delivered items.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    
                                </table>
                                <div class="">
                                        {{ $deliveredItems->links() }}
                                </div>
                                <h2 class="text-lg font-semibold mb-4">Declined Items</h2>
                                <table class="min-w-full border border-gray-300">
                                    <thead class="bg-red-100">
                                        <tr>
                                            <th class="py-3 px-4 text-left">Order ID</th>
                                            <th class="py-3 px-4 text-left">Product Name</th>
                                            <th class="py-3 px-4 text-left">Customer</th>
                                            <th class="py-3 px-4 text-left">Status</th>
                                            <th class="py-3 px-4 text-left">Reason</th>
                                            <th class="py-3 px-4 text-left">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($declinedItems as $item)
                                            <tr>
                                                <td class="px-4 py-2"># {{ $item->order->id }}</td>
                                                <td class="px-4 py-2">{{ $item->product->name }}</td> 
                                                <td class="px-4 py-2">{{ ucfirst($item->order->user->name) }}</td>
                                                <td class="px-4 py-2">{{ ucfirst($item->declinedOrderItem->status)}}</td>
                                                <td class="px-4 py-2">{{ $item->declinedOrderItem->reason ?? 'N/A' }}</td>
                                                <td class="px-4 py-2">{{ $item->created_at ?? 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-4 py-2 text-center text-gray-500">No declined items.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                                <div class="">
                                        {{ $declinedItems->links() }}
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Profile dropdown toggle
        document.getElementById('profile-dropdown-btn')?.addEventListener('click', function() {
            document.getElementById('profile-dropdown').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profile-dropdown');
            const btn = document.getElementById('profile-dropdown-btn');
            
            if (!dropdown.contains(event.target) && !btn.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Image preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const preview = input.closest('.relative').querySelector('img');
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        const placeholder = input.closest('.relative').querySelector('div');
                        if (placeholder) {
                            placeholder.innerHTML = `<img src="${e.target.result}" class="w-32 h-32 mx-auto rounded-full object-cover border-4 border-white shadow-lg" alt="Profile Preview">`;
                        }
                    }
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Toggle active status
        document.querySelector('.toggle')?.addEventListener('change', function() {
            if (confirm('Change your availability status?')) {
                fetch('/delivery/update-active-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ is_active: this.checked })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Error updating status');
                        this.checked = !this.checked;
                    } else {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                    this.checked = !this.checked;
                });
            } else {
                this.checked = !this.checked;
            }
        });
    </script>
</body>
</html>