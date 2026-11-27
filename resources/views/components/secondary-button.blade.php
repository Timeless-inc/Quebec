<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-musgo/30 rounded-md font-semibold text-xs text-floresta uppercase tracking-widest shadow-sm hover:bg-menta/50 focus:outline-none focus:ring-2 focus:ring-campus focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
