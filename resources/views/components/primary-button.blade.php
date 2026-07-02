<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-thread border border-transparent rounded-md font-sans font-medium text-sm text-white hover:bg-thread-light focus:outline-none focus:ring-2 focus:ring-thread focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
