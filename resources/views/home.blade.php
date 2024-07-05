<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (request()->session()->get('login'))
    <title>Logs</title>
    @else
    <title>Login</title>
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @if (request()->session()->get('login'))
    <nav class="bg-gray-800 dark:bg-gray-800">
        <div class="container mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                <div>
                    <a class="text-white dark:text-white" href="/">
                        <h1 class="text-xl font-bold">Logs<span class="text-blue-500">Deploy</span></h1>
                    </a>
                </div>
                <div class="flex items-center -mx-2">
                    <a href="/logout" class="text-white dark:text-white">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Log</h1>
            <select name="perpage" id="perpage" class="border border-gray-300 rounded-lg py-2 px-4" onchange="location.href='/?perpage=' + this.value">
                <option value="10" {{ request()->get('perpage') == 10 || !request()->get('perpage') ? 'selected' : '' }} selected>10</option>
                <option value="15" {{ request()->get('perpage') == 15 ? 'selected' : '' }}>15</option>
                <option value="20" {{ request()->get('perpage') == 20 ? 'selected' : '' }}>20</option>
                <option value="25" {{ request()->get('perpage') == 25 ? 'selected' : '' }}>50</option>
                <option value="50" {{ request()->get('perpage') == 50 ? 'selected' : '' }}>100</option>
            </select>            
        </div>
        <table class="min-w-full bg-white dark:bg-gray-800">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-200 dark:bg-gray-700">Repo Name</th>
                    <th class="py-2 px-4 bg-gray-200 dark:bg-gray-700">Log Message</th>
                    <th class="py-2 px-4 bg-gray-200 dark:bg-gray-700">Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dataLog as $log)
                <tr class="bg-gray-100 dark:bg-gray-900">
                    <td class="border px-4 py-2">{{ $log->repo_name }}</td>
                    <td class="border px-4 py-2">{{ $log->message }}</td>
                    <td class="border px-4 py-2">{{ $log->created_at }}</td>
                </tr>
                @empty
                <tr class="bg-gray-100 dark:bg-gray-900">
                    <td class="border px-4 py-2" colspan="3">No data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $dataLog->links() }}
        </div>
    </div>
    @else
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Sign in to your account
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="/login" method="POST">
                        @csrf
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your Username</label>
                            <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="">
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                        </div>
                        
                        <button type="submit" class="block px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue text-center">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>
        </div>
      </section>
    @endif
</body>
</html>
