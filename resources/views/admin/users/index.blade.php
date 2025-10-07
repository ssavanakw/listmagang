@extends('layouts.dashboard')

@section('content')
  <div class="w-full mx-auto px-4 pt-6 pb-6 bg-emerald-300">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2">
      👥 Semua Pengguna
    </h2>

    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-200">
      <form method="GET" action="{{ route('admin.users.index') }}">
        <table class="min-w-full table-auto">
          <thead>
            <tr class="bg-gradient-to-r from-indigo-200 to-pink-200 text-gray-800">
              <!-- Nama -->
              <th class="px-4 py-3 text-left font-semibold">
                <a href="?sort={{ request('sort') === 'name_asc' ? 'name_desc' : 'name_asc' }}" class="flex items-center gap-1">
                  🙍 Nama 
                  @if(request('sort') === 'name_asc') ⬆️ @elseif(request('sort') === 'name_desc') ⬇️ @endif
                </a>
              </th>

              <!-- Email -->
              <th class="px-4 py-3 text-left font-semibold">
                <a href="?sort={{ request('sort') === 'email_asc' ? 'email_desc' : 'email_asc' }}" class="flex items-center gap-1">
                  📧 Email
                  @if(request('sort') === 'email_asc') ⬆️ @elseif(request('sort') === 'email_desc') ⬇️ @endif
                </a>
              </th>

              <!-- Role -->
              <th class="px-4 py-3 text-left font-semibold">
                <a href="?sort={{ request('sort') === 'role_asc' ? 'role_desc' : 'role_asc' }}" class="flex items-center gap-1">
                  🎭 Role
                  @if(request('sort') === 'role_asc') ⬆️ @elseif(request('sort') === 'role_desc') ⬇️ @endif
                </a>
              </th>

              <th class="px-4 py-3 text-left font-semibold">
                <a href="?sort={{ request('sort') === 'status_asc' ? 'status_desc' : 'status_asc' }}" class="flex items-center gap-1">
                  📡 Aktivitas
                  @if(request('sort') === 'status_asc') ⬆️ 
                  @elseif(request('sort') === 'status_desc') ⬇️ 
                  @else ⚡
                  @endif
                </a>
              </th>

              <th class="px-4 py-3 text-left font-semibold">⚙️ Aksi</th>
            </tr>

            <!-- Search Row -->
            <tr class="bg-gray-50">
              <th class="px-4 py-2">
                <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama..."
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
              </th>
              <th class="px-4 py-2">
                <input type="text" name="email" value="{{ request('email') }}" placeholder="Cari email..."
                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
              </th>
              <th class="px-4 py-2">
                <select name="role" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                  <option value="">Semua</option>
                  <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
                  <option value="user" {{ request('role')=='user' ? 'selected' : '' }}>User</option>
                  <option value="intern" {{ request('role')=='intern' ? 'selected' : '' }}>Intern</option>
                </select>
              </th>
              <th></th>
              <th class="px-4 py-2">
                <button type="submit" class="px-3 py-1 bg-indigo-200 text-indigo-800 rounded-full text-sm hover:bg-indigo-300">
                  🔍 Filter
                </button>
              </th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
              <tr class="hover:bg-gray-50 transition duration-200">
                <td class="px-4 py-3 font-medium text-gray-700">{{ $user->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                <td class="px-4 py-3">
                  @php
                    $role = strtolower($user->role ?? 'user');
                    $cls = match($role) {
                      'admin' => 'bg-purple-100 text-purple-700',
                      'intern'=> 'bg-green-100 text-green-700',
                      'user'  => 'bg-blue-100 text-blue-700',
                      default => 'bg-gray-100 text-gray-700'
                    };
                    $emoji = match($role) {
                      'admin' => '👑',
                      'intern'=> '🌱',
                      'user'  => '👤',
                      default => '✨'
                    };
                  @endphp
                  <span class="inline-flex items-center px-2 py-1 {{ $cls }} text-sm font-semibold rounded-full">
                    {{ $emoji }} {{ ucfirst($role) }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  @if($user->is_online)
                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full animate-pulse">
                      🟢 Online
                    </span>
                  @else
                    <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-full">
                      🔴 Offline
                    </span>
                  @endif
                </td>
                <td class="px-4 py-3 space-x-2">
                  <a href="{{ route('admin.users.edit', $user->id) }}" 
                    class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full hover:bg-yellow-300 hover:scale-105 transition">
                    ✏️ Edit
                  </a>
                  <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block"
                        onsubmit="return confirm('😱 Yakin mau hapus user ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center gap-1 px-3 py-1 bg-pink-200 text-pink-700 rounded-full hover:bg-pink-300 hover:scale-105 transition">
                      🗑️ Hapus
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">🙁 Tidak ada pengguna ditemukan</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </form>
    </div>
  </div>
@endsection
