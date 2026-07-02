<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 border border-denim rounded-md font-sans font-medium text-sm text-denim hover:bg-denim/5 focus:outline-none focus:ring-2 focus:ring-denim focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
