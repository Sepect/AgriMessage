<div class="overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            @if(isset($head))
            <thead class="bg-gray-50/75">
                <tr>
                    {{ $head }}
                </tr>
            </thead>
            @endif
            <tbody class="divide-y divide-gray-200 bg-white">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    @if(isset($pagination))
    <div class="border-t border-gray-200 bg-gray-50/50 px-4 py-3 sm:px-6">
        {{ $pagination }}
    </div>
    @endif
</div>
