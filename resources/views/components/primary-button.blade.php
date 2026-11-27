<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-esmeralda border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-floresta focus:bg-floresta active:bg-floresta focus:outline-none focus:ring-2 focus:ring-campus focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
